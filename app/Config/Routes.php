<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'MobileController::index');
$routes->get('/client', 'MobileController::client');
$routes->get('/client/add', 'MobileController::add_compte');
$routes->post('/client/add', 'MobileController::add_compte');
$routes->get('/client/operation', 'MobileController::operation');
$routes->post('/client/operation', 'MobileController::operation');

$routes->get('admin/login', 'Operateur::login');
$routes->post('admin/login', 'Operateur::loginPost');
$routes->get('admin/logout', 'Operateur::logout');
$routes->get('admin', 'Operateur::prefixes');
$routes->get('operateur/prefixes',            'Operateur::prefixes');
$routes->post('operateur/prefixes/ajouter',    'Operateur::ajouterPrefixe');
$routes->get('operateur/prefixes/basculer/(:num)', 'Operateur::basculerPrefixe/$1');

$routes->get('operateur/operations',           'Operateur::operations');
$routes->post('operateur/operations/ajouter',  'Operateur::ajouterTranche');

$routes->get('operateur/gains',                'Operateur::gains');

$routes->get('operateur/clients',              'Operateur::clients');

