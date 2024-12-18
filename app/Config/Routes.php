<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

$routes = Services::routes();

if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('login');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// Set login as the default landing page
$routes->get('/', 'Auth::login');

// Auth Routes
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');

// Admin Routes (protected by auth filter)
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('inventory', 'Admin\Inventory::index');
    $routes->get('products', 'Admin\Products::index');
    $routes->get('products/create', 'Admin\Products::create');
    $routes->post('products', 'Admin\Products::store');
    $routes->get('products/edit/(:num)', 'Admin\Products::edit/$1');
    $routes->post('products/update/(:num)', 'Admin\Products::update/$1');
    $routes->get('products/delete/(:num)', 'Admin\Products::delete/$1');
    
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/add', 'Admin\Users::add');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/save', 'Admin\Users::save');
    $routes->post('users/delete/(:num)', 'Admin\Users::delete/$1');
    $routes->post('users/getUsers', 'Admin\Users::getUsers');
    
    // Product Routes
    $routes->get('products', 'Admin\Products::index');
    $routes->get('products/add', 'Admin\Products::add');
    $routes->get('products/edit/(:num)', 'Admin\Products::edit/$1');
    $routes->post('products/save', 'Admin\Products::save');
    $routes->post('products/delete/(:num)', 'Admin\Products::delete/$1');
    $routes->post('admin/products/getProducts', 'Admin\Products::getProducts');

    // Inventory Routes
    $routes->group('inventory', function($routes) {
        $routes->get('/', 'Admin\Inventory::index');
        $routes->post('getInventory', 'Admin\Inventory::getInventory');
        $routes->post('save', 'Admin\Inventory::save');
        $routes->get('edit/(:num)', 'Admin\Inventory::edit/$1');
        $routes->post('update', 'Admin\Inventory::update');
        $routes->post('delete/(:num)', 'Admin\Inventory::delete/$1');
    });

    $routes->post('products/list', 'Admin\ProductController::list');
    $routes->post('products/delete/(:num)', 'Admin\ProductController::delete/$1');
});

// User Routes (protected by auth filter)
$routes->group('user', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'User\Dashboard::index');
    $routes->get('inventory', 'User\Inventory::index');
    $routes->post('inventory/getInventory', 'User\Inventory::getInventory');
    $routes->post('inventory/save', 'User\Inventory::save');
    $routes->get('inventory/edit/(:num)', 'User\Inventory::edit/$1');
    $routes->post('inventory/update', 'User\Inventory::update');
    $routes->post('inventory/delete/(:num)', 'User\Inventory::delete/$1');
    $routes->post('inventory/getProductId', 'User\Inventory::getProductId');
    $routes->post('inventory/getSizes', 'User\Inventory::getSizes');
});

// Catch-all route to redirect unauthorized access to login
$routes->get('(:any)', 'Auth::login');

return $routes;
