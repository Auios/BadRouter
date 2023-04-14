<?php

use BadRouter\Router;

Router::set_error(404, function() {
  $locals = [
    'route' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
  ];
  Router::render('/404', $locals);
});

Router::get('/', function() {
  Router::redirect('/home');
});

Router::get('/home', function() {
  $locals = [
    'message' => 'Hello world!'
  ];

  Router::render('/home', $locals);
});

Router::get('/dynamic/{id}', function ($id) {
  $locals = [
    "id" => $id
  ];
  Router::render('/dynamic', $locals);
});

Router::get('/login', function() {
  Router::render('/login');
});

Router::get('/admin', function() {
  Router::render('/admin/page');
});
