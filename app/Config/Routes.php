<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');
$routes->get('about', 'About::index');
$routes->get('services', 'Services::index');
$routes->get('portfolio', 'Portfolio::index');
$routes->get('project/(:segment)', 'Portfolio::show/$1');
$routes->get('quote', 'Quote::index');
$routes->post('quote', 'Quote::submit');
$routes->get('contact', 'Contact::index');
$routes->post('contact', 'Contact::submit');
$routes->get('sitemap.xml', 'Sitemap::index');
$routes->get('page/(:segment)', 'Page::show/$1');
$routes->get('company-profile', 'CompanyProfile::index');

$routes->get('/database-test', 'DatabaseTest::index');

// Redirect old .php URLs gracefully
$routes->get('index.php', fn() => redirect()->to('/'));
$routes->get('about.php', fn() => redirect()->to('about'));
$routes->get('services.php', fn() => redirect()->to('services'));
$routes->get('portfolio.php', fn() => redirect()->to('portfolio'));
$routes->get('contact.php', fn() => redirect()->to('contact'));
$routes->get('quote.php', fn() => redirect()->to('quote'));
$routes->get('training', fn() => redirect()->to('services'));

// ================= ADMIN =================
$routes->group('control', function ($routes) {
    // Public (login)
    $routes->get('login', 'Admin\Auth::login');
    $routes->post('login', 'Admin\Auth::attemptLogin');
    $routes->get('logout', 'Admin\Auth::logout');

    // Protected
    $routes->group('', ['filter' => 'adminauth'], function ($routes) {
        $routes->get('/', 'Admin\Dashboard::index');
        $routes->get('dashboard', 'Admin\Dashboard::index');
        $routes->get('change-password', 'Admin\Auth::changePassword');
        $routes->post('change-password', 'Admin\Auth::savePassword');
        $routes->get('messages', 'Admin\Messages::index');
        $routes->get('messages/view/(:num)', 'Admin\Messages::view/$1');
        $routes->post('messages/toggle-read', 'Admin\Messages::toggleRead');
        $routes->post('messages/delete', 'Admin\Messages::delete');

        // Quotes
        $routes->get('quotes', 'Admin\Quotes::index');
        $routes->get('quotes/view/(:num)', 'Admin\Quotes::view/$1');
        $routes->post('quotes/status', 'Admin\Quotes::updateStatus');
        $routes->post('quotes/delete', 'Admin\Quotes::delete');

        // Projects
        $routes->get('projects', 'Admin\Projects::index');
        $routes->get('projects/create', 'Admin\Projects::create');
        $routes->post('projects/store', 'Admin\Projects::store');
        $routes->get('projects/edit/(:num)', 'Admin\Projects::edit/$1');
        $routes->post('projects/update/(:num)', 'Admin\Projects::update/$1');
        $routes->post('projects/delete', 'Admin\Projects::delete');

        // Services
        $routes->get('services', 'Admin\Services::index');
        $routes->post('services/store', 'Admin\Services::store');
        $routes->post('services/update/(:num)', 'Admin\Services::update/$1');
        $routes->post('services/delete', 'Admin\Services::delete');

        // Training
        $routes->get('training', 'Admin\Training::index');
        $routes->post('training/store', 'Admin\Training::store');
        $routes->post('training/update/(:num)', 'Admin\Training::update/$1');
        $routes->post('training/delete', 'Admin\Training::delete');

        // Settings
        $routes->get('settings', 'Admin\Settings::index');
        $routes->post('settings/save', 'Admin\Settings::save');

            // Pages (CMS)
        $routes->get('pages',                 'Admin\Pages::index');
        $routes->get('pages/create',          'Admin\Pages::create');
        $routes->post('pages',                'Admin\Pages::store');
        $routes->get('pages/(:num)/edit',     'Admin\Pages::edit/$1');
        $routes->post('pages/(:num)/update',  'Admin\Pages::update/$1');
        $routes->post('pages/(:num)/delete',  'Admin\Pages::delete/$1');
    });   
});

