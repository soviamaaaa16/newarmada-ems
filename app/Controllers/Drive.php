<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FolderModel;
use App\Models\FileModel;
use App\Models\FileTypeModel;
use CodeIgniter\HTTP\ResponseInterface;

class Drive extends BaseController
{
    protected FolderModel $folders;
    protected FileModel $files;

    public function __construct()
    {
        $this->folders = new FolderModel();
        $this->files = new FileModel();
    }

    private function uid(): int
    {
        return (int) (session('user_id') ?? 1);
    }

    /** Halaman utama: root = parent_id NULL */
    public function index(?int $folderId = null)
    {
        $userId = $this->uid();

        // Jika folderId diberikan, pastikan milik user
        $currentFolder = null;
        if ($folderId !== null) {
            $currentFolder = $this->folders->where('user_id', $userId)->find($folderId);
            if (!$currentFolder) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Folder tidak ditemukan');
            }
        }

        $parentId = $folderId ?? null;

        $data = [
            'currentFolder' => $currentFolder,
            'breadcrumbs' => $folderId ? $this->folders->breadcrumb($folderId) : [],
            'folders' => $this->folders->listChildren($userId, $parentId),
            'files' => $this->files->listInFolder($userId, $parentId),
        ];

        return view('drive', $data);
    }

    public function createFolder()
    {
        $userId = $this->uid();
        $parentId = $this->request->getPost('parent_id');
        $name = trim((string) $this->request->getPost('name'));

        if ($name === '') {
            return $this->response->setStatusCode(422)->setJSON(['message' => 'Nama folder wajib diisi']);
        }

        // Cegah duplikasi nama di level yang sama untuk user yang sama
        $exists = $this->folders->where([
            'user_id' => $userId,
            'parent_id' => ($parentId === '' ? null : (int) $parentId),
            'name' => $name,
        ])->first();

        if ($exists) {
            return $this->response->setStatusCode(409)->setJSON(['message' => 'Nama folder sudah ada di lokasi ini']);
        }

        $id = $this->folders->insert([
            'user_id' => $userId,
            'parent_id' => ($parentId === '' ? null : (int) $parentId),
            'name' => $name,
            'created_at' => date('Y-m-d H:i:s'),
        ], true);
        return redirect()->back()->withInput()->with('success', ['message' => 'success to create folder.']);
        // return $this->response->setJSON(['id' => $id, 'name' => $name]);
    }

    public function upload()
    {
        $userId = $this->uid();
        $folderId = $this->request->getPost('folder_id'); // boleh null/'' untuk root

        // validasi folder milik user (kecuali root)
        if ($folderId !== '' && $folderId !== null) {
            $f = $this->folders->where('user_id', $userId)->find((int) $folderId);
            if (!$f)
                return $this->fail('Folder tidak ditemukan', 404);
        }

        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) {
            return $this->failValidationErrors('File tidak valid');
        }

        // Batasan sesuai kebutuhanmu
        $rules = [
            'file' => [
                'uploaded[file]',
                'max_size[file,20480]',
                'ext_in[file,jpg,jpeg,png,gif,webp,pdf,xls,xlsx,csv]',
                'mime_in[file,image/jpg,image/jpeg,image/png,image/gif,image/webp,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv]',
            ],
        ];
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Simpan fisik di writable/uploads/drive/{user_id}/{folder_id|null}/
        $dir = WRITEPATH . 'uploads/drive/' . $userId . '/' . (($folderId === '' || $folderId === null) ? 'root' : (int) $folderId) . '/';
        if (!is_dir($dir))
            @mkdir($dir, 0775, true);

        $randomName = $file->getRandomName();
        $file->move($dir, $randomName);

        $ext = strtolower($file->getExtension() ?: pathinfo($file->getClientName(), PATHINFO_EXTENSION));
        $type = \App\Models\FileTypeModel::mapExtToType($ext);

        $id = $this->files->insert([
            'user_id' => $userId,
            'folder_id' => ($folderId === '' || $folderId === null) ? null : (int) $folderId,
            'name' => $file->getClientName(),       // nama asli
            'file_path' => 'uploads/drive/' . $userId . '/' . (($folderId === '' || $folderId === null) ? 'root' : (int) $folderId) . '/' . $randomName,
            'file_type' => $type,                        // VARCHAR(50) sesuai tabel
            'size' => (int) $file->getSize(),
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => (string) (session('username') ?? null),
        ], true);

        // return $this->response->setJSON([
        //     'id' => $id,
        //     'name' => $file->getClientName(),
        //     'size' => (int) $file->getSize()
        // ]);
        return redirect()->back()->withInput()->with('success', ['message' => 'success to save data.']);
    }

    public function download($id)
    {
        $userId = $this->uid();
        $row = $this->files->where('user_id', $userId)->find((int) $id);
        if (!$row)
            return $this->response->setStatusCode(404);
        $abs = WRITEPATH . $row['file_path'];
        if (!is_file($abs))
            return $this->response->setStatusCode(404);
        return $this->response->download($abs, null)->setFileName($row['name']);
    }
    public function preview($id)
    {
        $file = $this->files->find($id);

        if (!$file) {
            return $this->response->setStatusCode(404)->setBody('File not found');
        }

        $filePath = WRITEPATH . $file['file_path'];

        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404)->setBody('File not found on server');
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        // Set content type sesuai file extension
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
        ];

        $contentType = $mimeTypes[$ext] ?? 'application/octet-stream';

        return $this->response
            ->setHeader('Content-Type', $contentType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $file['name'] . '"')
            ->setHeader('Cache-Control', 'public, max-age=3600')
            ->setBody(file_get_contents($filePath));
    }
    public function deleteFile($id)
    {
        $userId = $this->uid();
        $row = $this->files->where('user_id', $userId)->find((int) $id);
        if ($row) {
            @unlink(WRITEPATH . $row['file_path']);
            $this->files->delete((int) $id); // soft delete tidak dipakai, langsung delete
        }
        return $this->response->setJSON(['ok' => true]);
    }

    public function deleteFolder($id)
    {
        $userId = $this->uid();
        $folder = $this->folders->where('user_id', $userId)->find((int) $id);
        if (!$folder)
            return $this->fail('Folder tidak ditemukan', 404);

        // Karena FK ON DELETE CASCADE (files.folder_id -> folders.id dan folders.parent_id -> folders.id),
        // cukup hapus folder induknya — anak & files akan terhapus otomatis.
        // Namun, file fisiknya tetap harus dihapus manual.
        $this->deletePhysicalFilesRecursive((int) $id, $userId);

        $this->folders->delete((int) $id);

        return $this->response->setJSON(['ok' => true]);
    }

    private function deletePhysicalFilesRecursive(int $folderId, int $userId): void
    {
        // hapus file di folder ini
        $files = $this->files->where('user_id', $userId)->where('folder_id', $folderId)->findAll();
        foreach ($files as $f) {
            @unlink(WRITEPATH . $f['file_path']);
        }
        // telusuri subfolder
        $subs = $this->folders->where('user_id', $userId)->where('parent_id', $folderId)->findAll();
        foreach ($subs as $s) {
            $this->deletePhysicalFilesRecursive((int) $s['id'], $userId);
        }
        // Hapus direktori fisik (opsional)
        $dir = WRITEPATH . 'uploads/drive/' . $userId . '/' . $folderId . '/';
        if (is_dir($dir))
            @rmdir($dir);
    }
}