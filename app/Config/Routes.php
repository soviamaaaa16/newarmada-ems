<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/ems/drive', 'Drive::index');
$routes->post('ems/folder/create', 'Drive::createFolder');
$routes->post('ems/file/upload', 'Drive::upload');
