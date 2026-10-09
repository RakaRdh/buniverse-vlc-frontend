<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Public Pages
$routes->get('/', 'Home::index');
$routes->get('/about_us', 'Home::aboutus');
$routes->get('/programs', 'Home::programs');
$routes->get('/programs/programs_detail', 'Home::programsdetail');
$routes->get('/programs/detail/(:any)', 'Home::programsdetail/$1');
$routes->match(['get', 'post'], 'programs/enroll/(:num)', 'Home::enroll/$1');

// Auth & Member
$routes->get('login', 'Auth::login');
$routes->get('register', 'Auth::register');
$routes->post('auth/login', 'Auth::attemptLogin');
$routes->post('auth/register', 'Auth::attemptRegister');
$routes->get('logout', 'Auth::logout');

// Member Profile
$routes->get('profile', 'Profile::index');
$routes->post('profile/update', 'Profile::update');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
