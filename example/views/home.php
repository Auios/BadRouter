<div class="feature">
  <h1>About BadRouter</h1>
  <p>
    BadRouter is a light, simple, and easy to use router for PHP.<br>
  </p>
  <p>
    To get started, download BadRouter from Packagist.<br>
    <a href="https://packagist.org/packages/badluck/badrouter">https://packagist.org/packages/badluck/badrouter</a>
    <pre><code><i>composer require badluck/badrouter</i></code></pre>
  </p>
  <p>
    BadRouter was inspired by Ruby Sinatra's design.<br>
    For context visit Sinatra: <a href="https://sinatrarb.com/intro.html">https://sinatrarb.com/intro.html</a>
  </p>
</div>

<div class="feature-list">
  <div class="feature">
    <h1>Example</h1>
    <pre>
  <code id="code_example" class="language-php">&lt;?php
use BadRouter\Router;

// Setup views
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

// Set error views
Router::set_error(404, function() {
  $locals = [
    'route' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
  ];
  Router::render('/404', $locals);
});

// Configure directories
Router::set_base_path('/example');
Router::set_public('/public');
Router::set_views(__DIR__ . '/views');

// Run
session_start();
Router::run();</code>
    </pre>
  </div>

  <div class="feature">
    <h1>API</h1>
    <p>Create APIs that return JSON.</p>
    <pre>
<code class="language-php">&lt;?php

use BadRouter\Router;

// Login using a password
Router::post('/api/login', function() {
  $result = false;

  if($_POST['password'] == 'rosebud') {
    $result = true;
    $_SESSION['is_admin'] = true;
  }

  Router::json([
    'success' => $result,
  ]);
});

// Logout
Router::post('/api/logout', function() {
  session_destroy();
  Router::json([
    'success' => true
  ]);
});</code>
    </pre>
  </div>

  <div class="feature">
    <h1>Accessing Variables from Views</h1>
    <pre>
<code class="language-php">Router::get('/home', function() {
  $locals = [
    'message' => 'Hello world!'
  ];

  Router::render('/home', $locals);
});</code>
    </pre>
    <pre>
<code class="language-html">&lt;p&gt;&dollar;message = "&lt;?&equals; &dollar;message ?&gt;"&lt;/p&gt;</code>
  </pre>
  <p>$message = "<?= $message ?>"</p>
</div>

  <div class="feature">
    <h1>Static Files</h1>
    <p>
      Static files in .../public/... can be accessed.<br>
      The "PUBLIC_PATH" is a global variable defined when Router::run() is called.
    </p>
    <pre>
<code class="language-html">&lt;img src="&lt;?= PUBLIC_PATH ?&gt;/images/dog.jpg" alt="dog"/&gt;</code>
    </pre>
    <img src="<?= PUBLIC_PATH ?>/images/dog.jpg" alt="dog"/>
  </div>

  <div class="feature">
    <h1>Middleware</h1>
    <p>
      Middleware functions can be chained together to handle logic before a route is rendered.
    </p>
    <pre>
<code class="language-php">&lt;?php

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
Router::use(function($request) {
  Logger::log($request);
});</code>
    </pre>
  </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/styles/dark.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.7.0/highlight.min.js"></script>
<script>
  hljs.highlightAll();
</script>
