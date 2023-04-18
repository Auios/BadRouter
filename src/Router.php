<?php

namespace BadRouter;

use Closure;

require('Request.php');

class Router {
  private static array  $routes = [];
  private static array  $errors = [];
  private static array  $middlewares = [];
  private static string $public_dir = 'public';
  private static string $views_dir = 'views';
  private static string $base_path = '';

  // Content types
  private static array $validContentTypes = [
    'html' => 'text/html',
    'json' => 'application/json',
    'xml' => 'application/xml',
  ];
  private static string $currentContentType = 'text/html';

  public static function set_content_type(string $type):void {
    if(isset(self::$validContentTypes[$type])) {
      header('Content-Type: ' . self::$validContentTypes[$type]);
    }
    else {
      // TODO: Throw an error
    }
  }

  public static function set_public(string $dir):void {
    self::$public_dir = $dir;
  }

  public static function set_views(string $dir):void {
    self::$views_dir = $dir;
  }

  public static function set_base_path(string $path):void {
    if($path == '/') $path = '';
    self::$base_path = $path;
  }

  public static function set_error(int $code, Closure $callback):void {
    self::$errors[$code] = $callback;
  }

  public static function get(string $route, Closure $callback):void {
    self::$routes['GET'][$route] = $callback;
  }

  public static function post(string $route, Closure $callback):void {
    self::$routes['POST'][$route] = $callback;
  }

  public static function put(string $route, Closure $callback):void {
    self::$routes['PUT'][$route] = $callback;
  }

  public static function delete(string $route, Closure $callback):void {
    self::$routes['DELETE'][$route] = $callback;
  }

  public static function redirect(string $path):void {
    header('Location: ' . BASE_PATH . $path);
    exit;
  }

  public static function render(string $view, array $locals = [], ?string $layout = '/layout'):void {
    extract($locals);
    ob_start();
    include(VIEWS_PATH . $view . '.php');
    $content = ob_get_clean();

    if($layout == null) {
      $output = $content;
    } else {
      ob_start();
      include(VIEWS_PATH . $layout . '.php');
      $output = ob_get_clean();
    }

    echo($output);
  }

  public static function json(array $data):void {
    self::set_content_type('json');
    echo(json_encode($data));
  }

  public static function use(Closure $middleware):void {
    self::$middlewares[] = $middleware;
  }

  public static function run():void {
    // Defines
    define('BASE_PATH', self::$base_path);
    define('PUBLIC_PATH', BASE_PATH . self::$public_dir);
    define('VIEWS_PATH', self::$views_dir);

    // Set content type
    header('Content-Type: ' . self::$currentContentType);

    $request = new Request($_SERVER);
    $request->route = str_replace(BASE_PATH, '', $request->route);

    // If route is empty, set it to "/"
    if(strlen($request->route) === 0) $request->route = '/';

    $routeFound = false;

    if(isset(self::$routes[$request->method])) {
      foreach (self::$routes[$request->method] as $route => $callback) {
        // Replace route parameters with regex pattern
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $route);

        // Check if the URL path matches the route pattern
        if(preg_match('~^' . $pattern . '$~', $request->route, $matches)) {
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

    if(!$routeFound) {
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
