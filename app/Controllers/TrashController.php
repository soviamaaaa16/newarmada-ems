<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FileModel;
use App\Models\FolderModel;
use CodeIgniter\HTTP\ResponseInterface;

class TrashController extends BaseController
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
        session()->set('user_id', 1);
        return (int) (session('user_id') ?? 1);
    }
    public function index(?int $folderId = null)
    {
        $userId = $this->uid();

        $currentFolder = null;
        if ($folderId !== null) {
            $currentFolder = $this->folders->where('user_id', $userId)->where('deleted_at is NOT NULL')->find($folderId);
            if (!$currentFolder) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Folder tidak ditemukan');
            }
        }

        $parentId = $folderId ?? null;

        $data = [
            'currentFolder' => $currentFolder,
            'breadcrumbs' => $folderId ? $this->folders->breadcrumb($folderId) : [],
            'folders' => $this->folders->listChildrenInTrash($userId, $parentId),
            'files' => $this->files->listInTrash($userId, $parentId),
            'searchQuery' => null,
            'isSearch' => false,
        ];
        return view('trash', $data);
    }
}
