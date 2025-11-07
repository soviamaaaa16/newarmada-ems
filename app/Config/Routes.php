<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/', 'Home::index');
// $routes->get('/ems/drive', 'Drive::index');
// $routes->post('ems/folder/create', 'Drive::createFolder');
// $routes->post('ems/file/upload', 'Drive::upload');

$routes->get('/', 'DriveController::index');                     // root (parent_id NULL)
$routes->get('/drive/f/(:num)', 'DriveController::index/$1');         // buka folder tertentu
$routes->get('drive/search/(:any)', 'DriveController::search/$1');
$routes->post('drive/renameFile', 'DriveController::renameFile');
$routes->post('drive/renameFolder', 'DriveController::renameFolder');


$routes->post('drive/folder', 'DriveController::createFolder');      // buat folder
$routes->post('drive/upload', 'DriveController::upload');            // upload file
$routes->get('drive/preview/(:num)', 'DriveController::preview/$1');
$routes->get('drive/download/(:num)', 'DriveController::download/$1');
$routes->delete('drive/file/(:num)', 'DriveController::deleteFile/$1');
$routes->delete('drive/folder/(:num)', 'DriveController::deleteFolder/$1');
$routes->post('drive/moveToTrash/(:num)', 'DriveController::softdeleteFile/$1');
$routes->post('drive/restoreFromTrash/(:num)', 'DriveController::restoreFromTrash/$1');
$routes->get('drive/trash', 'TrashController::index');

