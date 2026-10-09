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
$routes->get('auth/verify', 'Auth::verify');
$routes->post('auth/resend-verification', 'Auth::resendVerification');
$routes->get('logout', 'Auth::logout');

// Member Profile
$routes->get('profile', 'Profile::index');
$routes->post('profile/update', 'Profile::update');

// Media Reverse-Proxy & Disk Caching (Pola IDS)
$routes->get('uploads/(:any)', 'MediaController::serve/$1');

// Cache Invalidation Webhook (Triggered by CMS)
$routes->match(['get', 'post'], 'api/clear-cache', 'Home::clearCache');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
