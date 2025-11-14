<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class LoginController extends BaseController
{
    /**
     * Tampilkan form login
     */
    public function index()
    {
        if (auth()->loggedIn()) {
            if (auth()->user()->inGroup('admin')) {
                return redirect()->to('/admin');
            } elseif (auth()->user()->inGroup('user')) {
                return redirect()->to('/drive');
            }

            return redirect()->to('/drive');
        }
        return view('auth/login');
    }

    /**
     * Process login
     */
    public function login()
    {
        $credentials = [
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        // Validasi input
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email dan password wajib diisi');
        }

        // Remember me
        $remember = (bool) $this->request->getPost('remember');

        // Attempt login
        $result = auth()->attempt($credentials, $remember);

        if (!$result->isOK()) {
            return redirect()->back()
                ->withInput()
                ->with('error', $result->reason());
        }

        // Login berhasil
        if (auth()->user()->inGroup('superadmin')) {
            return redirect()->to('/admin')->with('message', 'Login berhasil!');

        } elseif (auth()->user()->inGroup('admin')) {
            return redirect()->to('/admin/users')->with('message', 'Login berhasil!');

        } elseif (auth()->user()->inGroup('user')) {
            return redirect()->to('/drive')->with('message', 'Login berhasil!');

        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        auth()->logout();

        return redirect()->to('/')
            ->with('message', 'Anda telah logout');
    }
}
