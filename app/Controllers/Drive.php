<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Drive extends BaseController
{
    public function index()
    {
        return view('drive');
    }

    public function createFolder()
    {
        // Simpan ke storage / DB (dummy)
        $name = $this->request->getPost('folder_name');
        // Simulasi buat folder
        return redirect()->back()->with('message', "Folder '$name' berhasil dibuat.");
    }

    public function upload()
    {
        $file = $this->request->getFile('file');
        if ($file->isValid() && !$file->hasMoved()) {
            $file->move(WRITEPATH . 'uploads');
        }
        return redirect()->back()->with('message', 'File berhasil diupload.');
    }
}
