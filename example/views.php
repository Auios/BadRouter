<?php

use BadRouter\Router;

Router::get('/', function() {
  Router::redirect('/home');
});

Router::get('/home', function() {
  $locals = [
    'message' => 'Hello world!'
  ];

  Router::render('/home', $locals);
});

Router::get('/login', function() {
  Router::render('/login', [], null);
});

Router::get('/about', function() {
  $locals = [
    'message' => 'We are awesome!',
  ];

  Router::render('/about', $locals);
});

Router::get('/message', function() {
  Router::set_content_type('json');
  echo json_encode(array('message' => 'About Us'));
});

Router::get('/redirectMe', function() {
  Router::redirect('/about');
});

Router::get('/user/{id}', function ($id) {
  $locals = [
    "id" => $id
  ];
  Router::render('/user', $locals);
});

Router::get('/admin', function() {
  restricted();
  Router::render('/admin/page');
});
