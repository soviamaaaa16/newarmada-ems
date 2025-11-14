<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
// $routes->get('/ems/drive', 'Drive::index');
// $routes->post('ems/folder/create', 'Drive::createFolder');
// $routes->post('ems/file/upload', 'Drive::upload');
// Auth routes (login, register, dll)
// service('auth')->routes($routes);
// $routes->get('login', 'Auth\LoginController::index', ['as' => 'login']);
// $routes->post('login', 'Auth\LoginController::login');
// $routes->get('logout', 'Auth\LoginController::logout', ['as' => 'logout']);

// $routes->get('register', 'Auth\RegisterController::index', ['as' => 'register']);
// $routes->post('register', 'Auth\RegisterController::register');

// $routes->get('/', 'LoginController::index');                     // root (parent_id NULL)
// $routes->get('/dashboard', 'DriveController::index');                     // root (parent_id NULL)
// $routes->get('/drive/f/(:num)', 'DriveController::index/$1');         // buka folder tertentu
// $routes->get('drive/search/(:any)', 'DriveController::search/$1');
// $routes->post('drive/renameFile', 'DriveController::renameFile');
// $routes->post('drive/renameFolder', 'DriveController::renameFolder');

// $routes->post('drive/folder', 'DriveController::createFolder');      // buat folder
// $routes->post('drive/upload', 'DriveController::upload');            // upload file
// $routes->get('drive/preview/(:num)', 'DriveController::preview/$1');
// $routes->get('drive/download/(:num)', 'DriveController::download/$1');
// $routes->delete('drive/file/(:num)', 'DriveController::deleteFile/$1');
// $routes->delete('drive/folder/(:num)', 'DriveController::deleteFolder/$1');
// $routes->post('drive/moveToTrash/(:num)', 'DriveController::softdeleteFile/$1');
// $routes->post('drive/moveToTrashFolder/(:num)', 'DriveController::softdeleteFolder/$1');
// $routes->post('trash/restoreFile/(:num)', 'DriveController::restoreFile/$1');
// $routes->post('trash/restoreFolder/(:num)', 'DriveController::restoreFolder/$1');
// $routes->get('drive/trash', 'TrashController::index');


// Custom Auth Routes
$routes->get('login', 'LoginController::index', ['as' => 'login']);
$routes->post('login', 'LoginController::login');
$routes->get('logout', 'LoginController::logout', ['as' => 'logout']);

$routes->get('register', 'RegisterController::index', ['as' => 'register']);
$routes->post('register', 'RegisterController::register');


// ============================================
// PUBLIC ROUTES (No Authentication Required)
// ============================================

// Root redirect ke login kalau belum login
$routes->get('/', 'LoginController::index');


// ============================================
// PROTECTED ROUTES (Require Authentication)
// ============================================

$routes->group('/', ['filter' => 'session'], function ($routes) {

    // Dashboard / Drive Home
    $routes->get('drive', 'DriveController::index');

    // Drive Routes
    $routes->get('drive/f/(:num)', 'DriveController::index/$1');         // buka folder tertentu
    $routes->get('drive/search/(:any)', 'DriveController::search/$1');    // search

    // File Operations
    $routes->post('drive/upload', 'DriveController::upload');             // upload file
    $routes->get('drive/preview/(:num)', 'DriveController::preview/$1');  // preview
    $routes->get('drive/download/(:num)', 'DriveController::download/$1'); // download
    $routes->delete('drive/file/(:num)', 'DriveController::deleteFile/$1'); // hard delete
    $routes->post('drive/moveToTrash/(:num)', 'DriveController::softdeleteFile/$1'); // soft delete

    // Rename Operations
    $routes->post('drive/renameFile', 'DriveController::renameFile');
    $routes->post('drive/renameFolder', 'DriveController::renameFolder');

    // Folder Operations
    $routes->post('drive/folder', 'DriveController::createFolder');       // buat folder
    $routes->delete('drive/folder/(:num)', 'DriveController::deleteFolder/$1'); // hard delete
    $routes->post('drive/moveToTrashFolder/(:num)', 'DriveController::softdeleteFolder/$1'); // soft delete

    // Trash Operations
    $routes->get('drive/trash', 'TrashController::index');                // lihat trash
    $routes->post('trash/restoreFile/(:num)', 'DriveController::restoreFile/$1');
    $routes->post('trash/restoreFolder/(:num)', 'DriveController::restoreFolder/$1');
});


$routes->group('admin', ['filter' => 'group:admin'], function ($routes) {
    // User Management
    $routes->get('users', 'Admin\UsersController::index');
    $routes->get('users/create', 'Admin\UsersController::create');
    $routes->post('users/store', 'Admin\UsersController::store');
    $routes->get('users/show/(:num)', 'Admin\UsersController::show/$1');
    $routes->get('users/edit/(:num)', 'Admin\UsersController::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\UsersController::update/$1');
    $routes->delete('users/delete/(:num)', 'Admin\UsersController::delete/$1');
    $routes->post('users/ban/(:num)', 'Admin\UsersController::ban/$1');
    $routes->post('users/unban/(:num)', 'Admin\UsersController::unban/$1');
    $routes->get('users/permissions/(:num)', 'Admin\UsersController::permissions/$1');
    $routes->post('users/updatePermissions/(:num)', 'Admin\UsersController::updatePermissions/$1');
    $routes->get('users/search', 'Admin\UsersController::search');
});


