<?php
require_once('../src/Router.php');
require_once('./middleware.php');
require_once('./api.php');
require_once('./views.php');

use BadRouter\Router;

// Set 404 page
Router::set_error(404, function() {
  $locals = [
    'route' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
  ];
  Router::render('/404', $locals, null);
});

// Configure directories
Router::set_base_path('/example');
Router::set_public('/public');
Router::set_views(__DIR__ . '/views');

// Run
Router::run();
