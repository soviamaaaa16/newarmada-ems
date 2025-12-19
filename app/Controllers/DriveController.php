<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;   // <-- tambahkan ini
use App\Models\FolderModel;
use App\Models\FileModel;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use ZipArchive;

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

    /** Halaman utama: root = parent_id NULL */
    public function index(?int $folderId = null)
    {
        $userId = $this->uid();
        $currentFolder = null;
        if ($folderId !== null) {
            // $currentFolder = $this->folders->find($folderId);
            $currentFolder = $this->folders->find($folderId);
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
            'folders' => $this->folders->listChildren($parentId),
            'files' => $this->files->listInFolder($parentId),
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
            $f = $this->folders->find($folderId);
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
                'ext_in[file,jpg,jpeg,png,gif,webp,pdf,xls,xlsx,csv,doc,docx,ppt,pptx,zip,rar]', // ekstensi yang diizinkan
                'mime_in[file,image/jpeg,image/png,image/gif,image/webp,application/pdf,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/zip,application/x-rar-compressed]', // MIME types yang sesuai
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

    public function download($id)
    {
        $userId = $this->uid();
        $row = $this->files->find((int) $id);
        if (!$row)
            return $this->response->setStatusCode(404);
        $abs = WRITEPATH . $row['file_path'];
        if (!is_file($abs))
            return $this->response->setStatusCode(404);
        return $this->response->download($abs, null)->setFileName($row['name']);
    }
    // Di Controller Drive
    public function view($id)
    {
        // $userId = $this->uid();
        $row = $this->files->find((int) $id);

        if (!$row) {
            return $this->response->setStatusCode(404);
        }

        $abs = WRITEPATH . ltrim($row['file_path'], '/\\');

        if (!is_file($abs)) {
            return $this->response->setStatusCode(404);
        }

        // Get mime type
        $mimeType = mime_content_type($abs);

        // Set headers untuk direct view (bukan download)
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $row['name'] . '"')
            ->setHeader('Content-Length', filesize($abs))
            ->setHeader('Access-Control-Allow-Origin', '*')  // CORS untuk Office Viewer
            ->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Content-Type')
            ->setBody(file_get_contents($abs));
    }
    // Method baru untuk public view
    // Method untuk public view (tanpa auth untuk Office Viewer)
    public function publicView($id)
    {
        // TIDAK pakai $this->uid() - biar bisa diakses tanpa login

        $row = $this->files->find((int) $id);

        if (!$row) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'File not found']);
        }

        $abs = WRITEPATH . ltrim($row['file_path'], '/\\');

        if (!file_exists($abs) || !is_file($abs)) {
            log_message('error', 'File not found: ' . $abs);
            return $this->response->setStatusCode(404)->setJSON(['error' => 'File not found on disk']);
        }

        $mimeType = mime_content_type($abs);

        // Handle OPTIONS request untuk CORS preflight
        if ($this->request->getMethod() === 'options') {
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS')
                ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
                ->setStatusCode(200);
        }

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $row['name'] . '"')
            ->setHeader('Content-Length', filesize($abs))
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS')
            ->setHeader('Access-Control-Expose-Headers', 'Content-Length, Content-Type')
            ->setHeader('Cache-Control', 'public, max-age=3600')
            ->setBody(file_get_contents($abs));
    }
    public function softdeleteFile($id)
    {
        $userId = $this->uid();
        $row = $this->files->find((int) $id);
        if ($row) {
            $this->files->update((int) $id, [
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);
        }
        return $this->response->setJSON(['ok' => true]);
    }

    public function softdeleteFolder($id)
    {
        $userId = $this->uid();
        $folder = $this->folders->find((int) $id);
        if ($folder) {
            $now = date('Y-m-d H:i:s');
            $this->softDeleteFolderRecursive((int) $id, $userId, $now);
        }
        return $this->response->setJSON(['ok' => true]);
    }

    public function softDeleteFolderRecursive(int $folderId, int $userId, string $deletedAt): void
    {
        // soft delete folder ini
        $this->folders->update($folderId, [
            'deleted_at' => $deletedAt,
        ]);
        // soft delete file di folder ini
        $files = $this->files->where('folder_id', $folderId)->findAll();
        foreach ($files as $f) {
            $this->files->update((int) $f['id'], [
                'deleted_at' => $deletedAt,
            ]);
        }
        // telusuri subfolder
        $subs = $this->folders->where('parent_id', $folderId)->findAll();
        foreach ($subs as $s) {
            $this->softDeleteFolderRecursive((int) $s['id'], $userId, $deletedAt);
        }
    }

    public function restoreFile($id)
    {
        $userId = $this->uid();
        $row = $this->files->find((int) $id);
        if ($row) {
            $this->files->update((int) $id, [
                'deleted_at' => null,
            ]);
        }
        return $this->response->setJSON(['ok' => true]);
    }
    public function restoreFolder($id)
    {
        $userId = $this->uid();
        $folder = $this->folders->find((int) $id);
        if ($folder) {
            $this->restoreFolderRecursive((int) $id, $userId);
        }
        return $this->response->setJSON(['ok' => true]);
    }

    public function restoreFolderRecursive(int $folderId, int $userId): void
    {
        // restore folder ini
        $this->folders->update($folderId, [
            'deleted_at' => null,
        ]);
        // restore file di folder ini
        $files = $this->files->where('folder_id', $folderId)->findAll();
        foreach ($files as $f) {
            $this->files->update((int) $f['id'], [
                'deleted_at' => null,
            ]);
        }
        // telusuri subfolder
        $subs = $this->folders->where('parent_id', $folderId)->findAll();
        foreach ($subs as $s) {
            $this->restoreFolderRecursive((int) $s['id'], $userId);
        }
    }

    public function deleteFile($id)
    {
        $userId = $this->uid();
        $row = $this->files->find((int) $id);
        if ($row) {
            @unlink(WRITEPATH . $row['file_path']);
            $this->files->delete((int) $id); // soft delete tidak dipakai, langsung delete
        }
        return $this->response->setJSON(['ok' => true]);
    }

    public function deleteFolder($id)
    {
        $userId = $this->uid();
        $folder = $this->folders->find((int) $id);
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
        $files = $this->files->where('folder_id', $folderId)->findAll();
        foreach ($files as $f) {
            @unlink(WRITEPATH . $f['file_path']);
        }
        // telusuri subfolder
        $subs = $this->folders->where('parent_id', $folderId)->findAll();
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
        // $file = $this->files->find($fileId);
        $file = $this->files->find($fileId);
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
        // $folder = $this->folders->find($folderId);
        $folder = $this->folders->find($folderId);
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

    // public function getFolderTree($parentId = null)
    // {
    //     helper('filesystem');

    //     $folders = $this->folders
    //         ->where('parent_id', $parentId)
    //         ->orderBy('name', 'ASC')
    //         ->findAll();

    //     foreach ($folders as &$f) {
    //         $f['children'] = $this->folders
    //             ->where('parent_id', $f['id'])
    //             ->orderBy('name', 'ASC')
    //             ->findAll();
    //     }

    //     return $this->response->setJSON($folders);
    // }

    private function buildTree($parentId = null)
    {
        $folders = $this->folders
            ->where('parent_id', $parentId)
            ->where('deleted_at', null)
            ->orderBy('name', 'ASC')
            ->findAll();

        foreach ($folders as &$f) {
            $f['children'] = $this->buildTree($f['id']); // RECURSIVE HERE
        }

        return $folders;
    }

    public function getFolderTree()
    {
        $tree = $this->buildTree(null);
        return $this->response->setJSON($tree);
    }

    /**
     * Upload dan extract ZIP file ke folder struktur
     */
    /**
     * Upload dan extract ZIP file ke folder struktur
     */
    public function uploadZip()
    {
        $userId = $this->uid();
        $parentFolderId = $this->request->getPost('folder_id'); // '' saat di root

        // Validasi file ZIP
        $file = $this->request->getFile('zip_file');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File tidak valid atau tidak ada file yang diupload',
            ])->setStatusCode(422);
        }

        // Validasi ekstensi
        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, ['zip', 'rar'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File harus berformat ZIP/RAR',
            ])->setStatusCode(422);
        }

        // Validasi ukuran (500MB = 524288000 bytes)
        if ($file->getSize() > 524288000) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ukuran file maksimal 50MB',
            ])->setStatusCode(422);
        }

        if (!$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'File tidak valid',
            ])->setStatusCode(422);
        }

        // Pastikan parent folder valid
        if ($parentFolderId === '' || $parentFolderId === null) {
            $parentFolderId = $this->ensureRootFolder($userId);
        } else {
            $parentFolderId = (int) $parentFolderId;
            $parentFolder = $this->folders->find($parentFolderId);
            if (!$parentFolder) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Folder tujuan tidak ditemukan',
                ])->setStatusCode(404);
            }
        }

        try {
            // Simpan ZIP ke temporary location
            $tempDir = WRITEPATH . 'uploads/temp/';
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0775, true);
            }

            $tempZipPath = $tempDir . 'temp_' . time() . '_' . $file->getName();
            $file->move($tempDir, basename($tempZipPath));

            // Extract dan process ZIP
            $result = $this->extractZipToFolders($tempZipPath, $parentFolderId, $userId);

            // Hapus file ZIP temporary
            if (file_exists($tempZipPath)) {
                @unlink($tempZipPath);
            }

            if ($result['success']) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'ZIP berhasil diekstrak',
                    'total_folders' => $result['folder_count'],
                    'total_files' => $result['file_count'],
                    'root_folder_id' => $result['root_folder_id'],
                ]);
            } else {
                return $this->response->setJSON($result)->setStatusCode(500);
            }

        } catch (\Exception $e) {
            log_message('error', 'ZIP Upload Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    /**
     * Extract ZIP dan buat struktur folder + file di database
     */
    private function extractZipToFolders(string $zipPath, int $parentFolderId, int $userId): array
    {
        $zip = new ZipArchive();
        $res = $zip->open($zipPath);

        if ($res !== TRUE) {
            return [
                'success' => false,
                'message' => 'Gagal membuka file ZIP',
            ];
        }

        // Ekstensi file yang diizinkan (sama dengan upload biasa)
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'xls', 'xlsx', 'csv', 'doc', 'docx', 'ppt', 'pptx', 'zip', 'rar'];

        $fileCount = 0;
        $folderCount = 0;
        $folderMap = []; // mapping path -> folder_id

        // Extract langsung ke parent folder yang sedang dibuka
        // TIDAK buat folder baru dengan nama ZIP
        $rootFolderId = $parentFolderId;

        // Scan semua file dalam ZIP
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            $fullPath = $stat['name'];

            // Skip __MACOSX, .DS_Store, dan hidden files
            if (
                strpos($fullPath, '__MACOSX') !== false ||
                strpos($fullPath, '.DS_Store') !== false ||
                basename($fullPath)[0] === '.'
            ) {
                continue;
            }

            // Sanitize path
            $fullPath = $this->sanitizePath($fullPath);

            // Jika path diakhiri dengan /, ini adalah folder
            if (substr($fullPath, -1) === '/') {
                $folderPath = rtrim($fullPath, '/');
                $this->ensureFolderPath($folderPath, $rootFolderId, $userId, $folderMap, $folderCount);
                continue;
            }

            // Ini adalah file
            $pathInfo = pathinfo($fullPath);
            $fileName = $pathInfo['basename'];
            $dirPath = isset($pathInfo['dirname']) && $pathInfo['dirname'] !== '.' ? $pathInfo['dirname'] : '';
            $fileExt = strtolower($pathInfo['extension'] ?? '');

            // Validasi ekstensi
            if (!in_array($fileExt, $allowedExtensions)) {
                log_message('info', "Skipped file (invalid extension): $fullPath");
                continue;
            }

            // Pastikan folder parent ada
            $targetFolderId = $rootFolderId;
            if ($dirPath !== '') {
                $targetFolderId = $this->ensureFolderPath($dirPath, $rootFolderId, $userId, $folderMap, $folderCount);
            }

            // Extract file ke lokasi temporary
            $tempExtractDir = WRITEPATH . 'uploads/temp/extract_' . time() . '/';
            if (!is_dir($tempExtractDir)) {
                mkdir($tempExtractDir, 0775, true);
            }

            $tempFilePath = $tempExtractDir . $fileName;

            // Extract file spesifik
            $fileContent = $zip->getFromIndex($i);
            if ($fileContent === false) {
                log_message('error', "Failed to extract: $fullPath");
                continue;
            }

            file_put_contents($tempFilePath, $fileContent);

            // Pindahkan ke lokasi final
            $finalDir = WRITEPATH . 'uploads/drive/' . $userId . '/' . $targetFolderId . '/';
            if (!is_dir($finalDir)) {
                mkdir($finalDir, 0775, true);
            }

            $randomName = bin2hex(random_bytes(16)) . '.' . $fileExt;
            $finalPath = $finalDir . $randomName;

            if (rename($tempFilePath, $finalPath)) {
                // Simpan ke database
                $fileSize = filesize($finalPath);
                $typeBucket = \App\Models\FileTypeModel::mapExtToType($fileExt);

                $this->files->insert([
                    'user_id' => $userId,
                    'folder_id' => $targetFolderId,
                    'name' => $fileName,
                    'file_path' => 'uploads/drive/' . $userId . '/' . $targetFolderId . '/' . $randomName,
                    'file_type' => $typeBucket,
                    'size' => $fileSize,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => (string) (session('username') ?? null),
                ], true);

                $fileCount++;
            }

            // Cleanup temp file jika masih ada
            if (file_exists($tempFilePath)) {
                @unlink($tempFilePath);
            }
        }

        $zip->close();

        // Cleanup temp extract directory
        $tempExtractDir = WRITEPATH . 'uploads/temp/extract_' . time() . '/';
        if (is_dir($tempExtractDir)) {
            @rmdir($tempExtractDir);
        }

        return [
            'success' => true,
            'root_folder_id' => $rootFolderId,
            'folder_count' => $folderCount,
            'file_count' => $fileCount,
        ];
    }
    /**
     * Ensure folder path exists, create if needed
     */
    private function ensureFolderPath(string $path, int $rootFolderId, int $userId, array &$folderMap, int &$folderCount): int
    {
        // Jika sudah ada di map, return
        if (isset($folderMap[$path])) {
            return $folderMap[$path];
        }

        $parts = explode('/', trim($path, '/'));
        $currentParentId = $rootFolderId;
        $currentPath = '';

        foreach ($parts as $folderName) {
            $currentPath .= ($currentPath ? '/' : '') . $folderName;

            // Cek apakah path ini sudah ada di map
            if (isset($folderMap[$currentPath])) {
                $currentParentId = $folderMap[$currentPath];
                continue;
            }

            // Cek apakah folder dengan nama ini sudah ada di parent
            $existing = $this->folders->where([
                'user_id' => $userId,
                'parent_id' => $currentParentId,
                'name' => $folderName,
            ])->first();

            if ($existing) {
                $folderId = (int) $existing['id'];
            } else {
                // Buat folder baru
                $folderId = $this->folders->insert([
                    'user_id' => $userId,
                    'parent_id' => $currentParentId,
                    'name' => $folderName,
                    'created_at' => date('Y-m-d H:i:s'),
                ], true);
                $folderCount++;
            }

            $folderMap[$currentPath] = $folderId;
            $currentParentId = $folderId;
        }

        return $currentParentId;
    }

    /**
     * Sanitize path untuk keamanan
     */
    private function sanitizePath(string $path): string
    {
        // Remove path traversal attempts
        $path = str_replace(['../', '..\\'], '', $path);

        // Remove null bytes
        $path = str_replace(chr(0), '', $path);

        // Convert backslashes to forward slashes
        $path = str_replace('\\', '/', $path);

        return $path;
    }

    /**
     * Ensure root folder exists for user
     */
    private function ensureRootFolder(int $userId): int
    {
        $root = $this->folders
            ->where('user_id', $userId)
            ->where('parent_id', null)
            ->where('name', 'Root')
            ->first();

        if ($root) {
            return (int) $root['id'];
        }

        return (int) $this->folders->insert([
            'user_id' => $userId,
            'parent_id' => null,
            'name' => 'Root',
            'created_at' => date('Y-m-d H:i:s'),
        ], true);
    }
}