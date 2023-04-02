<?php
require("../src/BadRouter/BadRouter.php");

BadRouter::get('/', function() {
  $locals = [
    "message" => "Hello world!"
  ];

  BadRouter::render('home', $locals);
});

BadRouter::get('/about', function() {
  $locals = [
    "message" => "We are awesome!",
  ];

  BadRouter::render('about', $locals);
});

BadRouter::get('/message', function() {
  BadRouter::set_content_type('json');
  echo json_encode(array('message' => 'About Us'));
});

BadRouter::get('/redirect', function() {
  BadRouter::redirect('/about');
});

BadRouter::run();
