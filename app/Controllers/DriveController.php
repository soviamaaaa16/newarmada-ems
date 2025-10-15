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

        // Jika ada search query, tampilkan hasil pencarian
        // Normal view tanpa search
        $data = [
            'currentFolder' => $currentFolder,
            'breadcrumbs' => $folderId ? $this->folders->breadcrumb($folderId) : [],
            'folders' => $this->folders->listChildren($userId, $parentId),
            'files' => $this->files->listInFolder($userId, $parentId),
            'searchQuery' => null,
            'isSearch' => false,
        ];

        return view('drive', $data);
    }

    public function search($searchQuery = '')
    {
        $userId = $this->uid();
        $searchQuery = trim(urldecode($searchQuery));

        // Validasi input
        if (strlen($searchQuery) < 2) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Query minimal 2 karakter',
                'query' => $searchQuery,
                'folders' => [],
                'files' => [],
            ])->setStatusCode(400);
        }

        try {
            // Cari di folders
            $folders = $this->folders->search($userId, $searchQuery);

            // Cari di files
            $files = $this->files->search($userId, $searchQuery);

            // Format response
            $response = [
                'success' => true,
                'query' => $searchQuery,
                'folders' => $folders ?: [],
                'files' => $files ?: [],
                'total' => (count($folders ?: []) + count($files ?: [])),
            ];

            return $this->response->setJSON($response);
        } catch (\Exception $e) {
            log_message('error', 'Search error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari',
                'query' => $searchQuery,
                'folders' => [],
                'files' => [],
            ])->setStatusCode(500);
        }
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
            return redirect()->back()->withInput()->with('errors', ['message' => 'Nama folder sudah ada di lokasi ini']);
        }

        $id = $this->folders->insert([
            'user_id' => $userId,
            'parent_id' => ($parentId === '' ? null : (int) $parentId),
            'name' => $name,
            'created_at' => date('Y-m-d H:i:s'),
        ], true);

        // return $this->response->setJSON(['id' => $id, 'name' => $name]);
        return redirect()->back()->withInput()->with('success', ['message' => 'success to create folder.']);
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

        // return $this->respond([
        //     'id' => $id,
        //     'name' => $clientName,
        //     'size' => $sizeBytes,
        //     'mime' => $mimeType,
        // ], 200);

        return redirect()->back()->withInput()->with('success', ['message' => 'success to save data.']);
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


    public function renameFile()
    {
        $userId = $this->uid();
        $fileId = (int) $this->request->getPost('id');
        $newName = trim((string) $this->request->getPost('name'));

        // Validasi input
        if (!$fileId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID file tidak valid',
            ])->setStatusCode(400);
        }

        if ($newName === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama file tidak boleh kosong',
            ])->setStatusCode(422);
        }

        // Cari file
        $file = $this->files->where('user_id', $userId)->find($fileId);
        if (!$file) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File tidak ditemukan',
            ])->setStatusCode(404);
        }

        // Ambil ekstensi dari nama file asli
        $oldName = $file['name'];
        $fileExtension = pathinfo($oldName, PATHINFO_EXTENSION);

        // Jika nama baru belum ada ekstensi, tambahkan ekstensi asli
        if (!preg_match('/\.' . preg_quote($fileExtension) . '$/i', $newName)) {
            $newName = $newName . '.' . $fileExtension;
        }

        try {
            // Update nama file di database
            $this->files->update($fileId, [
                'name' => $newName,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            // Update nama file fisik di server
            $oldFilePath = WRITEPATH . $file['file_path'];
            if (file_exists($oldFilePath)) {
                // Ambil direktori dari file path lama
                $fileDir = dirname($oldFilePath);
                $newFilePath = $fileDir . '/' . $newName;

                // Rename file fisik
                if (!rename($oldFilePath, $newFilePath)) {
                    throw new \Exception('Gagal mengubah nama file fisik');
                }

                // Update file_path di database
                $newFilePathRelative = str_replace(WRITEPATH, '', $newFilePath);
                $this->files->update($fileId, [
                    'file_path' => $newFilePathRelative,
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Nama file berhasil diubah',
                'newName' => $newName,
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error rename file: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function renameFolder()
    {
        $userId = $this->uid();
        $folderId = (int) $this->request->getPost('id');
        $newName = trim((string) $this->request->getPost('name'));

        // Validasi input
        if (!$folderId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID folder tidak valid',
            ])->setStatusCode(400);
        }

        if ($newName === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama folder tidak boleh kosong',
            ])->setStatusCode(422);
        }

        // Cari folder
        $folder = $this->folders->where('user_id', $userId)->find($folderId);
        if (!$folder) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Folder tidak ditemukan',
            ])->setStatusCode(404);
        }

        // Cek apakah nama folder sudah ada di lokasi yang sama
        $exists = $this->folders->where([
            'user_id' => $userId,
            'parent_id' => $folder['parent_id'],
            'name' => $newName,
            'id !=' => $folderId,
        ])->first();

        if ($exists) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama folder sudah ada di lokasi ini',
            ])->setStatusCode(422);
        }

        try {
            // Update nama folder di database
            $this->folders->update($folderId, [
                'name' => $newName,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Nama folder berhasil diubah',
                'newName' => $newName,
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error rename folder: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}