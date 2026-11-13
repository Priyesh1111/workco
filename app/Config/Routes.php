<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


// Home
$routes->get('/', 'Home::index');

// Authentication
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::attemptLogin');
$routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);

// Dashboard - check is status is logged in by auth filter then dashboard opens
$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);
$routes->get('dashboard/data', 'DashboardController::data', ['filter' => 'auth']);
// costomers
$routes->get('customers', 'CustomersController::index', ['filter' => 'auth']);
$routes->get('customers/new', 'CustomersController::new', ['filter' => 'auth']);
$routes->post('customers', 'CustomersController::create', ['filter' => 'auth']);
$routes->get('customers/edit/(:num)', 'CustomersController::edit/$1', ['filter' => 'auth']);
$routes->post('customers/update/(:num)', 'CustomersController::update/$1', ['filter' => 'auth']);
$routes->post('customers/delete/(:num)', 'CustomersController::delete/$1', ['filter' => 'auth']);

// products
$routes->get('products', 'ProductsController::index', ['filter' => 'auth']);
$routes->get('products/new', 'ProductsController::new', ['filter' => 'auth']);
$routes->post('products', 'ProductsController::create', ['filter' => 'auth']);
$routes->get('products/edit/(:num)', 'ProductsController::edit/$1', ['filter' => 'auth']);
$routes->post('products/update/(:num)', 'ProductsController::update/$1', ['filter' => 'auth']);
$routes->post('products/delete/(:num)', 'ProductsController::delete/$1', ['filter' => 'auth']);

// Invoices
$routes->get('invoices', 'InvoicesController::index', ['filter' => 'auth']);
$routes->get('invoices/new', 'InvoicesController::new', ['filter' => 'auth']);
$routes->post('invoices', 'InvoicesController::create', ['filter' => 'auth']);
$routes->get('invoices/view/(:num)', 'InvoicesController::show/$1', ['filter' => 'auth']);
$routes->get('invoices/pdf/(:num)', 'InvoicesController::pdf/$1', ['filter' => 'auth']);
$routes->post('invoices/status/(:num)', 'InvoicesController::updateStatus/$1', ['filter' => 'auth']);

// Settings
$routes->get('settings', 'SettingsController::index', ['filter' => 'auth']);
$routes->get('settings/create', 'SettingsController::edit/0', ['filter' => 'auth']);
$routes->post('settings/save', 'SettingsController::save', ['filter' => 'auth']);
$routes->get('settings/edit/(:num)', 'SettingsController::edit/$1', ['filter' => 'auth']);
$routes->post('settings/update/(:num)', 'SettingsController::update/$1', ['filter' => 'auth']);