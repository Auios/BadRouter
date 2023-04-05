<?php

namespace BadRouter;

class Router {
  private static $routes = [];
  private static $errors = [];
  private static $middlewares = [];
  private static $public_dir = 'public';
  private static $views_dir = 'views';

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
  }

  public static function set_public($public_dir) {
    self::$public_dir = $public_dir;
  }

  public static function set_views($views_dir) {
    self::$views_dir = $views_dir;
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
    header('Location: ' . $path);
    exit;
  }

  public static function render($view, $locals = [], $layout = '/layout') {
    extract($locals);
    ob_start();
    include self::$views_dir . $view . '.php';
    $content = ob_get_clean();

    if($layout == null) {
      $output = $content;
    } else {
      ob_start();
      include self::$views_dir . $layout . '.php';
      $output = ob_get_clean();
    }

    echo $output;
  }

  public static function use($middleware) {
    self::$middlewares[] = $middleware;
  }

  public static function run() {
    define("PUBLIC_DIR", self::$public_dir);
    // define("VIEWS_DIR", self::$views_dir);
    header('Content-Type: ' . self::$currentContentType);
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $routeFound = false;

    if (isset(self::$routes[$method])) {
      foreach (self::$routes[$method] as $route => $callback) {
        // Replace route parameters with regex pattern
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $route);

        // Check if the URL path matches the route pattern
        if (preg_match('~^' . $pattern . '$~', $path, $matches)) {
          $routeFound = true;

          foreach (self::$middlewares as $middleware) {
            $middleware();
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
        echo '404 Not Found';
      }
    }
  }
}
