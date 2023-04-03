<?php
require('../src/BadRouter/BadRouter.php');

BadRouter::use(function ($path, $params) {
  if (!isset($_SESSION['user'])) {
    BadRouter::redirect('/login');
    return false;
  }
}, [
  '/admin/*'
]);

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

BadRouter::get('/redirect', function() {
  BadRouter::redirect('/about');
});

BadRouter::get('/user/{id}', function ($id) {
  $locals = [
    "id" => $id
  ];
  BadRouter::render('/user', $locals);
});

BadRouter::get('/admin', function () {
  BadRouter::render('/admin/page');
});

BadRouter::set_public('/public');
BadRouter::set_views(__DIR__ . '/views');
BadRouter::run();
