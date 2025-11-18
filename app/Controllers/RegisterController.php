<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class RegisterController extends BaseController
{
    /**
     * Tampilkan form register
     */
    public function index()
    {
        // Kalau sudah login, redirect ke dashboard
        if (auth()->loggedIn()) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register');
    }

    /**
     * Process registration
     */
    public function register()
    {
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[auth_identities.secret]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Buat user baru
        $users = model('UserModel');

        $user = new User([
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ]);

        $users->save($user);

        // Ambil user yang baru dibuat
        $newUser = $users->findById($users->getInsertID());

        // Tambahkan ke group default (user)
        $newUser->addGroup('user');

        // Auto login setelah register
        auth()->login($newUser);

        return redirect()->to('/dashboard')
            ->with('message', 'Registrasi berhasil! Selamat datang.');
    }
}
