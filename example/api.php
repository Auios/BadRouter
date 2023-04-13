<?php

use BadRouter\Router;

Router::post('/api/login', function() {
  echo json_encode([
    'success' => true,
  ]);
});
