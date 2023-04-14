<?php

namespace BadRouter;

class Router {
  private static $routes = [];
  private static $errors = [];
  private static $middlewares = [];
  private static $public_dir = 'public';
  private static $views_dir = 'views';
  private static $base_path = '/';

  // Content types
  private static $validContentTypes = [
    'html' => 'text/html',
    'json' => 'application/json',
    'xml' => 'application/xml',
  ];
  private static $currentContentType = 'text/html';

  public static function set_content_type($type) {
    if (isset(self::$validContentTypes[$type])) {
      header('Content-Type: ' . self::$validContentTypes[$type]);
    }
    else {
      // TODO: Throw an error
    }
  }

  public static function set_public($dir) {
    self::$public_dir = $dir;
  }

  public static function set_views($dir) {
    self::$views_dir = $dir;
  }

  public static function set_base_path($path = '/') {
    if(strlen($path) == 0) $path = '/';
    self::$base_path = $path;
  }

  public static function set_error($code, $callback) {
    self::$errors[$code] = $callback;
  }

  public static function get($route, $callback) {
    self::$routes['GET'][$route] = $callback;
  }

  public static function post($route, $callback) {
    self::$routes['POST'][$route] = $callback;
  }

  public static function put($route, $callback) {
    self::$routes['PUT'][$route] = $callback;
  }

  public static function delete($route, $callback) {
    self::$routes['DELETE'][$route] = $callback;
  }

  public static function redirect($path) {
    header('Location: ' . self::$base_path . $path);
    exit;
  }

  public static function render($view, $locals = [], $layout = '/layout') {
    extract($locals);
    ob_start();
    include(self::$views_dir . $view . '.php');
    $content = ob_get_clean();

    if($layout == null) {
      $output = $content;
    } else {
      ob_start();
      include(self::$views_dir . $layout . '.php');
      $output = ob_get_clean();
    }

    echo($output);
  }

  public static function json($data) {
    self::set_content_type('json');
    echo(json_encode($data));
  }

  public static function use($middleware) {
    self::$middlewares[] = $middleware;
  }

  public static function run() {
    // Defines
    define('BASE_PATH', self::$base_path);
    define('PUBLIC_PATH', BASE_PATH . self::$public_dir);
    define('VIEWS_PATH', self::$views_dir);

    // Set content type
    header('Content-Type: ' . self::$currentContentType);
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $path = str_replace(BASE_PATH, '', $path);

    $request = [
      'route' => $path,
      'method' => $_SERVER['REQUEST_METHOD'],
      'address' => $_SERVER['REMOTE_ADDR'],
      'port' => $_SERVER['REMOTE_PORT'],
      'platform' => $_SERVER['HTTP_SEC_CH_UA_PLATFORM'],
      'user_agent' => $_SERVER['HTTP_USER_AGENT'],
      'accept' => $_SERVER['HTTP_ACCEPT'],
      'encoding' => $_SERVER['HTTP_ACCEPT_ENCODING'],
      'language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
      'referer' => $_SERVER['HTTP_REFERER'],
      'time' => $_SERVER['REQUEST_TIME_FLOAT'],
    ];

    // If route is empty, set it to "/"
    if(strlen($path) === 0) $path = '/';

    $routeFound = false;

    if (isset(self::$routes[$method])) {
      foreach (self::$routes[$method] as $route => $callback) {
        // Replace route parameters with regex pattern
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $route);

        // Check if the URL path matches the route pattern
        if (preg_match('~^' . $pattern . '$~', $path, $matches)) {
          $routeFound = true;

          foreach (self::$middlewares as $middleware) {
            $middleware($request);
          }

          // Filter out numeric keys from the matches array
          $params = array_filter($matches, function ($key) {
              return !is_numeric($key);
          }, ARRAY_FILTER_USE_KEY);

          // Call the callback function with route parameters as arguments
          call_user_func_array($callback, $params);
          break;
        }
      }
    }

    if (!$routeFound) {
      self::set_content_type('html');
      http_response_code(404);
      if(isset(self::$errors[404])) {
        $cb = self::$errors[404];
        $cb();
      } else {
        echo('404 Not Found');
      }
    }
  }
}
