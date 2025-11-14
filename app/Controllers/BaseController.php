<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }

    /**
     * Get current user ID from Shield authentication
     * Fallback ke session jika belum login (untuk development)
     * 
     * @return int User ID
     */
    protected function uid(): int
    {
        // Cek apakah user sudah login via Shield
        if (auth()->loggedIn()) {
            $user = auth()->user();
            // Simpan ke session juga untuk kompatibilitas
            session()->set('user_id', $user->id);
            return (int) $user->id;
        }

        // Fallback: cek session (untuk backward compatibility)
        $sessionUserId = session('user_id');
        if ($sessionUserId) {
            return (int) $sessionUserId;
        }

        // Development mode: set default user_id = 1
        // HAPUS INI di production!
        // if (ENVIRONMENT === 'development') {
        //     session()->set('user_id', 1);
        //     return 1;
        // }

        // Production: redirect ke login jika tidak ada user
        return redirect()->to('/login')->send();
    }

    /**
     * Alternative: Strict version - paksa login
     * Uncomment method ini jika mau paksa user harus login
     */
    /*
    protected function uid(): int
    {
        if (!auth()->loggedIn()) {
            redirect()->to('/login')->send();
            exit;
        }

        $user = auth()->user();
        return (int) $user->id;
    }
    */

    /**
     * Helper method untuk cek apakah user sudah login
     * 
     * @return bool
     */
    protected function isLoggedIn(): bool
    {
        return auth()->loggedIn();
    }

    /**
     * Helper method untuk ambil data user lengkap
     * 
     * @return object|null
     */
    protected function getCurrentUser()
    {
        if (auth()->loggedIn()) {
            return auth()->user();
        }
        return null;
    }

    /**
     * Helper method untuk cek user group/role
     * 
     * @param string $group
     * @return bool
     */
    protected function inGroup(string $group): bool
    {
        if (!auth()->loggedIn()) {
            return false;
        }
        return auth()->user()->inGroup($group);
    }

    /**
     * Helper method untuk cek permission
     * 
     * @param string $permission
     * @return bool
     */
    protected function hasPermission(string $permission): bool
    {
        if (!auth()->loggedIn()) {
            return false;
        }
        return auth()->user()->can($permission);
    }
}
