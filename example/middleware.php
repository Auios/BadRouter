<?php

use BadRouter\Router;

// Require admin for specific routes
Router::use(function($request) {
  $restricted_routes = [
    '/admin',
    '/admin/configuration',
  ];

  // Check if route is restricted
  if(in_array($request['route'], $restricted_routes)) {
    // Check if user is logged in
    if($_SESSION['is_admin'] != true) {
      Router::redirect('/login');
    }
  }
});

// Log routes
// Router::use(function($request) {
//   Logger::log($request);
// });
