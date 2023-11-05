<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/all-vendors', 'Home::allVendors');
$routes->get('/all-products', 'Home::allProducts');
$routes->get('/sale-history', 'Home::saleHistory');
$routes->post('/buy-products', 'Home::buyProducts');
$routes->post('/return-product', 'Home::returnProduct');
$routes->post('/reset', 'Home::reset');
