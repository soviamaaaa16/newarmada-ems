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


/**
 * @var RouteCollection $routes
 */


// ============================================
// AUTHENTICATED ROUTES (Require Login)
// ============================================

$routes->group('/', ['filter' => 'session'], function ($routes) {

    // ----------------------------------------
    // DASHBOARD / HOME
    // ----------------------------------------
    $routes->get('dashboard', 'Dashboard::index');

    // ----------------------------------------
    // DRIVE - MAIN PAGES (All authenticated users can view)
    // ----------------------------------------
    $routes->get('drive', 'DriveController::index');
    $routes->get('drive/f/(:num)', 'DriveController::index/$1'); // View specific folder
    $routes->post('drive/uploadZip', 'DriveController::uploadZip');

    // ----------------------------------------
    // SEARCH (Basic search for all users)
    // ----------------------------------------
    $routes->get('drive/search', 'DriveController::search');
    $routes->get('drive/search/(:any)', 'DriveController::search/$1');

    // Advanced search (admin/superadmin only)
    $routes->get('drive/search/advanced', 'DriveController::advancedSearch', [
        'filter' => 'permission:search.advanced',
    ]);

    // ----------------------------------------
    // FILE OPERATIONS - VIEW (All users can view/preview)
    // ----------------------------------------
    $routes->get('drive/preview/(:num)', 'DriveController::preview/$1');

    // ----------------------------------------
    // FILE OPERATIONS - UPLOAD (Admin/Superadmin only)
    // ----------------------------------------
    $routes->post('drive/upload', 'DriveController::upload', [
        'filter' => 'permission:files.upload',
    ]);

    $routes->post('drive/upload-multiple', 'DriveController::uploadMultiple', [
        'filter' => 'permission:files.upload',
    ]);

    // ----------------------------------------
    // FILE OPERATIONS - DOWNLOAD (Admin/Superadmin only)
    // ----------------------------------------
    $routes->get('drive/download/(:num)', 'DriveController::download/$1');
    $routes->get('drive/view/(:num)', 'DriveController::view/$1');
    $routes->post('drive/download-multiple', 'DriveController::downloadMultiple', [
        'filter' => 'permission:files.download',
    ]);

    // ----------------------------------------
    // FILE OPERATIONS - EDIT/DELETE (Admin/Superadmin only)
    // ----------------------------------------
    $routes->post('drive/renameFile', 'DriveController::renameFile', [
        'filter' => 'permission:files.delete',
    ]);

    $routes->post('drive/moveFile', 'DriveController::moveFile', [
        'filter' => 'permission:files.delete',
    ]);

    $routes->post('drive/copyFile', 'DriveController::copyFile', [
        'filter' => 'permission:files.delete',
    ]);

    // Soft delete (move to trash)
    $routes->post('drive/moveToTrash/(:num)', 'DriveController::softdeleteFile/$1', [
        'filter' => 'permission:files.delete',
    ]);

    // Hard delete (permanent)
    $routes->delete('drive/file/(:num)', 'DriveController::deleteFile/$1', [
        'filter' => 'permission:files.delete',
    ]);

    // ----------------------------------------
    // FILE OPERATIONS - MANAGE ALL (Admin/Superadmin only)
    // ----------------------------------------
    $routes->get('drive/all-files', 'DriveController::allFiles', [
        'filter' => 'permission:files.manage-all',
    ]);

    $routes->post('drive/transfer-ownership/(:num)', 'DriveController::transferOwnership/$1', [
        'filter' => 'permission:files.manage-all',
    ]);

    // ----------------------------------------
    // FOLDER OPERATIONS - VIEW (All users can view)
    // ----------------------------------------
    $routes->get('drive/getFolderTree', 'DriveController::getFolderTree');

    $routes->get('drive/folder-info/(:num)', 'DriveController::folderInfo/$1', [
        'filter' => 'permission:folders.view',
    ]);

    // ----------------------------------------
    // FOLDER OPERATIONS - CREATE (Admin/Superadmin only)
    // ----------------------------------------
    $routes->post('drive/folder', 'DriveController::createFolder', [
        'filter' => 'permission:folders.create',
    ]);

    // ----------------------------------------
    // FOLDER OPERATIONS - EDIT/DELETE (Admin/Superadmin only)
    // ----------------------------------------
    $routes->post('drive/renameFolder', 'DriveController::renameFolder', [
        'filter' => 'permission:folders.delete',
    ]);

    $routes->post('drive/moveFolder', 'DriveController::moveFolder', [
        'filter' => 'permission:folders.delete',
    ]);

    // Soft delete (move to trash)
    $routes->post('drive/moveToTrashFolder/(:num)', 'DriveController::softdeleteFolder/$1', [
        'filter' => 'permission:folders.delete',
    ]);

    // Hard delete (permanent)
    $routes->delete('drive/folder/(:num)', 'DriveController::deleteFolder/$1', [
        'filter' => 'permission:folders.delete',
    ]);

    // ----------------------------------------
    // FOLDER OPERATIONS - MANAGE ALL (Admin/Superadmin only)
    // ----------------------------------------
    $routes->get('drive/all-folders', 'DriveController::allFolders', [
        'filter' => 'permission:folders.manage-all',
    ]);

    // ----------------------------------------
    // TRASH OPERATIONS (Admin/Superadmin only)
    // ----------------------------------------
    $routes->get('drive/trash', 'TrashController::index', [
        'filter' => 'permission:trash.view',
    ]);

    $routes->get('drive/trash/files', 'TrashController::files', [
        'filter' => 'permission:trash.view',
    ]);

    $routes->get('drive/trash/folders', 'TrashController::folders', [
        'filter' => 'permission:trash.view',
    ]);

    // Restore from trash
    $routes->post('trash/restoreFile/(:num)', 'DriveController::restoreFile/$1', [
        'filter' => 'permission:trash.restore',
    ]);

    $routes->post('trash/restoreFolder/(:num)', 'DriveController::restoreFolder/$1', [
        'filter' => 'permission:trash.restore',
    ]);

    $routes->post('trash/restore-multiple', 'TrashController::restoreMultiple', [
        'filter' => 'permission:trash.restore',
    ]);

    // Empty trash (permanent delete)
    $routes->post('trash/empty', 'TrashController::emptyTrash', [
        'filter' => 'permission:trash.empty',
    ]);

    $routes->delete('trash/file/(:num)', 'TrashController::permanentDeleteFile/$1', [
        'filter' => 'permission:trash.empty',
    ]);

    $routes->delete('trash/folder/(:num)', 'TrashController::permanentDeleteFolder/$1', [
        'filter' => 'permission:trash.empty',
    ]);

    // ----------------------------------------
    // SHARING (Optional - jika ada fitur sharing)
    // ----------------------------------------
    $routes->post('drive/share/(:num)', 'DriveController::shareFile/$1', [
        'filter' => 'permission:files.download',
    ]);

    $routes->get('drive/shared-with-me', 'DriveController::sharedWithMe');

    $routes->get('drive/shared-by-me', 'DriveController::sharedByMe', [
        'filter' => 'permission:files.download',
    ]);

    // ----------------------------------------
    // SETTINGS & PROFILE
    // ----------------------------------------
    $routes->get('profile', 'Profile::index');
    $routes->post('profile/update', 'Profile::update');
    $routes->post('profile/change-password', 'Profile::changePassword');
    $routes->get('settings', 'Settings::index');
});

// ============================================
// ADMIN PANEL ROUTES
// (Require admin.access permission)
// ============================================
$routes->get('drive/public-view/(:num)', 'DriveController::publicView/$1'); // Tanpa auth

$routes->group('admin', ['filter' => 'permission:admin.access'], function ($routes) {

    // ----------------------------------------
    // ADMIN DASHBOARD
    // ----------------------------------------
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Statistics & Reports
    $routes->get('statistics', 'Admin\Dashboard::statistics');
    $routes->get('reports', 'Admin\Dashboard::reports');

    // ----------------------------------------
    // USER MANAGEMENT
    // ----------------------------------------

    // View users (require: users.view)
    $routes->get('users', 'Admin\UsersController::index', [
        'filter' => 'permission:users.view',
    ]);

    $routes->get('users/search', 'Admin\UsersController::search', [
        'filter' => 'permission:users.view',
    ]);

    $routes->get('users/show/(:num)', 'Admin\UsersController::show/$1', [
        'filter' => 'permission:users.view',
    ]);

    $routes->get('users/activity/(:num)', 'Admin\UsersController::activity/$1', [
        'filter' => 'permission:users.view',
    ]);

    // Create users (require: users.create)
    $routes->get('users/create', 'Admin\UsersController::create', [
        'filter' => 'permission:users.create',
    ]);

    $routes->post('users/store', 'Admin\UsersController::store', [
        'filter' => 'permission:users.create',
    ]);

    // Edit users (require: users.edit)
    $routes->get('users/edit/(:num)', 'Admin\UsersController::edit/$1', [
        'filter' => 'permission:users.edit',
    ]);

    $routes->post('users/update/(:num)', 'Admin\UsersController::update/$1', [
        'filter' => 'permission:users.edit',
    ]);

    $routes->post('users/change-role/(:num)', 'Admin\UsersController::changeRole/$1', [
        'filter' => 'permission:users.edit',
    ]);

    // Delete users (require: users.delete) - Superadmin only
    $routes->delete('users/delete/(:num)', 'Admin\UsersController::delete/$1', [
        'filter' => 'permission:users.delete',
    ]);

    // Ban/Unban users (require: users.ban)
    $routes->post('users/ban/(:num)', 'Admin\UsersController::ban/$1', [
        'filter' => 'permission:users.ban',
    ]);

    $routes->post('users/unban/(:num)', 'Admin\UsersController::unban/$1', [
        'filter' => 'permission:users.ban',
    ]);

    // Manage permissions (require: users.manage-permissions) - Superadmin only
    $routes->get('users/permissions/(:num)', 'Admin\UsersController::permissions/$1', [
        'filter' => 'permission:users.manage-permissions',
    ]);

    $routes->post('users/updatePermissions/(:num)', 'Admin\UsersController::updatePermissions/$1', [
        'filter' => 'permission:users.manage-permissions',
    ]);

    $routes->post('users/syncPermissions/(:num)', 'Admin\UsersController::syncPermissions/$1', [
        'filter' => 'permission:users.manage-permissions',
    ]);

    // ----------------------------------------
    // GROUPS & PERMISSIONS MANAGEMENT
    // ----------------------------------------

    $routes->get('groups', 'Admin\GroupsController::index', [
        'filter' => 'permission:users.manage-permissions',
    ]);

    $routes->get('groups/create', 'Admin\GroupsController::create', [
        'filter' => 'permission:users.manage-permissions',
    ]);

    $routes->post('groups/store', 'Admin\GroupsController::store', [
        'filter' => 'permission:users.manage-permissions',
    ]);

    $routes->get('groups/edit/(:segment)', 'Admin\GroupsController::edit/$1', [
        'filter' => 'permission:users.manage-permissions',
    ]);

    $routes->post('groups/update/(:segment)', 'Admin\GroupsController::update/$1', [
        'filter' => 'permission:users.manage-permissions',
    ]);

    // ----------------------------------------
    // FILE MANAGEMENT (Admin Panel)
    // ----------------------------------------

    $routes->get('files', 'Admin\FilesController::index', [
        'filter' => 'permission:files.manage-all',
    ]);

    $routes->get('files/search', 'Admin\FilesController::search', [
        'filter' => 'permission:files.manage-all',
    ]);

    $routes->get('files/statistics', 'Admin\FilesController::statistics', [
        'filter' => 'permission:files.manage-all',
    ]);

    $routes->delete('files/bulk-delete', 'Admin\FilesController::bulkDelete', [
        'filter' => 'permission:files.manage-all',
    ]);

    // ----------------------------------------
    // FOLDER MANAGEMENT (Admin Panel)
    // ----------------------------------------

    $routes->get('folders', 'Admin\FoldersController::index', [
        'filter' => 'permission:folders.manage-all',
    ]);

    $routes->get('folders/statistics', 'Admin\FoldersController::statistics', [
        'filter' => 'permission:folders.manage-all',
    ]);

    // ----------------------------------------
    // TRASH MANAGEMENT (Admin Panel)
    // ----------------------------------------

    $routes->get('trash', 'Admin\TrashController::index', [
        'filter' => 'permission:trash.view',
    ]);

    $routes->get('trash/statistics', 'Admin\TrashController::statistics', [
        'filter' => 'permission:trash.view',
    ]);

    $routes->post('trash/restore-all', 'Admin\TrashController::restoreAll', [
        'filter' => 'permission:trash.restore',
    ]);

    $routes->post('trash/empty-all', 'Admin\TrashController::emptyAll', [
        'filter' => 'permission:trash.empty',
    ]);

    // ----------------------------------------
    // SYSTEM SETTINGS (Superadmin only)
    // ----------------------------------------

    $routes->get('settings', 'Admin\SettingsController::index', [
        'filter' => 'permission:admin.settings',
    ]);

    $routes->post('settings/update', 'Admin\SettingsController::update', [
        'filter' => 'permission:admin.settings',
    ]);

    $routes->get('settings/storage', 'Admin\SettingsController::storage', [
        'filter' => 'permission:admin.settings',
    ]);

    $routes->get('settings/security', 'Admin\SettingsController::security', [
        'filter' => 'permission:admin.settings',
    ]);

    $routes->post('settings/clear-cache', 'Admin\SettingsController::clearCache', [
        'filter' => 'permission:admin.settings',
    ]);

    $routes->get('settings/logs', 'Admin\SettingsController::logs', [
        'filter' => 'permission:admin.settings',
    ]);

    // ----------------------------------------
    // ACTIVITY LOGS
    // ----------------------------------------

    $routes->get('logs', 'Admin\LogsController::index', [
        'filter' => 'permission:admin.access',
    ]);

    $routes->get('logs/user/(:num)', 'Admin\LogsController::userLogs/$1', [
        'filter' => 'permission:admin.access',
    ]);

    $routes->post('logs/clear', 'Admin\LogsController::clear', [
        'filter' => 'permission:admin.settings',
    ]);
});

// ============================================
// API ROUTES (Optional - untuk AJAX/Mobile)
// ============================================

$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($routes) {

    // Authentication
    $routes->post('login', 'Auth::login');
    $routes->post('register', 'Auth::register');
    $routes->post('logout', 'Auth::logout', ['filter' => 'session']);

    // Files API
    $routes->group('files', ['filter' => 'session'], function ($routes) {
        $routes->get('/', 'FilesApi::index');
        $routes->get('(:num)', 'FilesApi::show/$1');
        $routes->post('upload', 'FilesApi::upload', ['filter' => 'permission:files.upload']);
        $routes->delete('(:num)', 'FilesApi::delete/$1', ['filter' => 'permission:files.delete']);
    });

    // Folders API
    $routes->group('folders', ['filter' => 'session'], function ($routes) {
        $routes->get('/', 'FoldersApi::index');
        $routes->get('(:num)', 'FoldersApi::show/$1');
        $routes->post('/', 'FoldersApi::create', ['filter' => 'permission:folders.create']);
        $routes->delete('(:num)', 'FoldersApi::delete/$1', ['filter' => 'permission:folders.delete']);
    });
});

// ============================================
// ERROR ROUTES
// ============================================

$routes->set404Override(function () {
    return view('errors/html/error_404');
});

