<?php

use BadRouter\Router;

Router::post('/api/login', function() {
  Router::json([
    'success' => true,
  ]);
});
