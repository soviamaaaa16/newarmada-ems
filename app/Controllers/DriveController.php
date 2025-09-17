<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;   // <-- tambahkan ini
use App\Models\FolderModel;
use App\Models\FileModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DriveController extends BaseController
{
    use ResponseTrait;
    protected FolderModel $folders;
    protected FileModel $files;

    public function __construct()
    {
        $this->folders = new FolderModel();
        $this->files = new FileModel();
    }

    private function uid(): int
    {
        session()->set('user_id', 1);
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

        return $this->response->setJSON(['id' => $id, 'name' => $name]);
    }

    public function upload()
    {
        $userId = $this->uid();
        $folderId = $this->request->getPost('folder_id'); // '' saat di root

        // Pastikan folder target (root => buat/ambil root folder id)
        if ($folderId === '' || $folderId === null) {
            $folderId = $this->ensureRootFolder($userId); // <<-- penting utk files.folder_id NOT NULL
        } else {
            $folderId = (int) $folderId;
            $f = $this->folders->where('user_id', $userId)->find($folderId);
            if (!$f) {
                return $this->fail('Folder tidak ditemukan', 404);
            }
        }

        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) {
            return $this->failValidationErrors(['file' => 'File tidak valid']);
        }

        $rules = [
            'file' => [
                'uploaded[file]',
                'max_size[file,20480]', // 20MB
                'ext_in[file,jpg,jpeg,png,gif,webp,pdf,xls,xlsx,csv]',
                'mime_in[file,image/jpg,image/jpeg,image/png,image/gif,image/webp,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv]',
            ],
        ];
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // ======= AMBIL METADATA SEBELUM MOVE() =======
        $clientName = $file->getClientName();
        $clientExt = strtolower(pathinfo($clientName, PATHINFO_EXTENSION));
        $sizeBytes = (int) $file->getSize();

        // Ambil MIME dari temp sebelum dipindah (aman)
        $mimeType = $file->getMimeType() ?: ($file->getClientMimeType() ?: 'application/octet-stream');

        // ======= MOVE KE LOKASI TUJUAN =======
        $targetSub = (string) $folderId; // karena folderId pasti ada sekarang
        $dir = WRITEPATH . 'uploads/drive/' . $userId . '/' . $targetSub . '/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        $randomName = $file->getRandomName();
        $file->move($dir, $randomName);

        // (opsional) Validasi MIME SETELAH pindah berdasarkan file di lokasi baru:
        // $finfo = finfo_open(FILEINFO_MIME_TYPE);
        // $mimeType = finfo_file($finfo, $dir . $randomName) ?: $mimeType;
        // finfo_close($finfo);

        $typeBucket = \App\Models\FileTypeModel::mapExtToType($clientExt);

        $id = $this->files->insert([
            'user_id' => $userId,
            'folder_id' => $folderId, // wajib ada (NOT NULL)
            'name' => $clientName,
            'file_path' => 'uploads/drive/' . $userId . '/' . $targetSub . '/' . $randomName,
            'file_type' => $typeBucket,      // 'image' | 'pdf' | 'excel' | ext fallback
            'size' => $sizeBytes,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => (string) (session('username') ?? null),
        ], true);

        return $this->respond([
            'id' => $id,
            'name' => $clientName,
            'size' => $sizeBytes,
            'mime' => $mimeType,
        ], 200);
    }
    private function ensureRootFolder(int $userId): int
    {
        $root = $this->folders
            ->where('user_id', $userId)
            ->where('parent_id', null)
            ->where('name', 'Root')
            ->first();

        if ($root)
            return (int) $root['id'];

        return (int) $this->folders->insert([
            'user_id' => $userId,
            'parent_id' => null,
            'name' => 'Root',
            'created_at' => date('Y-m-d H:i:s'),
        ], true);
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