# BadRouter

A minimalist router inspired by Ruby's Sinatra router.

# Install
1. Run `composer require auios/badrouter` in your project directory to install the package and its dependencies.
2. Require the autoloader. At the beginning of your PHP file, require the Composer autoloader by adding this line of code:
```php
require_once('./vendor/autoload.php');
```
3. Use the `Router` static class.
```php
Router::get('/user/{id}', function ($id) {
  $locals = [
    "user_id" => $id
  ];
  Router::render('/user', $locals);
});
```
4. Configure the view and public directories.
```php
Router::set_public('/public');
Router::set_views(__DIR__ . '/views');
```
5. After the router is configured, run it.
```php
Router::run();
```

# Usage example
```php
<?php
require_once('./vendor/autoload.php');

use BadRouter\Router;

Router::use(function() {
  // Middleware
});

// Setup routes
Router::get('/', function() {
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

// Restricted /admin route
function restricted() {
  if (!isset($_SESSION['user'])) {
    Router::redirect('/login');
    return false;
  }
}

Router::get('/admin', function() {
  restricted();
  Router::render('/admin/page');
});

// POST
Router::post('/api/login', function() {
  echo json_encode([
    'success' => true,
  ]);
});

// Set 404 page
Router::set_error(404, function() {
  $locals = [
    'route' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
  ];
  Router::render('/404', $locals, null);
});

// Configure directories
Router::set_public('/public');
Router::set_views(__DIR__ . '/views');

// Run
Router::run();
```
