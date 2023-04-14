<?php
require_once('../src/Router.php');
require_once('middleware.php');
require_once('api.php');
require_once('views.php');

use BadRouter\Router;

// Configure directories
Router::set_base_path('/example');
Router::set_public('/public');
Router::set_views(__DIR__ . '/views');

// Run
session_start();
Router::run();
