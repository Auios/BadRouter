<?php
require('../src/BadRouter/BadRouter.php');

BadRouter::use(function() {
  // Middleware
});

// Setup routes
BadRouter::get('/', function() {
  $locals = [
    'message' => 'Hello world!'
  ];

  BadRouter::render('/home', $locals);
});

BadRouter::get('/login', function() {
  BadRouter::render('/login', [], null);
});

BadRouter::get('/about', function() {
  $locals = [
    'message' => 'We are awesome!',
  ];

  BadRouter::render('/about', $locals);
});

BadRouter::get('/message', function() {
  BadRouter::set_content_type('json');
  echo json_encode(array('message' => 'About Us'));
});

BadRouter::get('/redirectMe', function() {
  BadRouter::redirect('/about');
});

BadRouter::get('/user/{id}', function ($id) {
  $locals = [
    "id" => $id
  ];
  BadRouter::render('/user', $locals);
});

// Restricted /admin route
function restricted() {
  if (!isset($_SESSION['user'])) {
    BadRouter::redirect('/login');
    return false;
  }
}

BadRouter::get('/admin', function() {
  restricted();
  BadRouter::render('/admin/page');
});

// POST
BadRouter::post('/api/login', function() {
  echo json_encode([
    'success' => true,
  ]);
});

// Set 404 page
BadRouter::set_error(404, function() {
  $locals = [
    'route' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
  ];
  BadRouter::render('/404', $locals, null);
});

// Configure directories
BadRouter::set_public('/public');
BadRouter::set_views(__DIR__ . '/views');

// Run
BadRouter::run();
