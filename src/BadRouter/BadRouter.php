<?php
class BadRouter {
  private static $routes = array();
  private static $validContentTypes = [
    'html' => 'text/html',
    'json' => 'application/json',
    'xml' => 'application/xml',
  ];
  private static $currentContentType = "text/html";

  public static function get($route, $callback) {
    self::$routes['GET'][$route] = $callback;
  }

  public static function post($route, $callback) {
    self::$routes['POST'][$route] = $callback;
  }

  public static function set_content_type($type) {
    if (isset(self::$validContentTypes[$type])) {
      header('Content-Type: ' . self::$validContentTypes[$type]);
    }
  }

  public static function redirect($path) {
    header('Location: ' . $path);
    exit;
  }

  public static function render($view, $params = array(), $layout = "layout") {
    extract($params);
    ob_start();
    include 'views/' . $view . '.php';
    $content = ob_get_clean();

    ob_start();
    include 'views/' . $layout . '.php';
    $output = ob_get_clean();
    $output = str_replace('src="/', 'src="/public/', $output);
    echo $output;
  }

  public static function run() {
    header('Content-Type: ' . self::$currentContentType);
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if (isset(self::$routes[$method][$path])) {
      $callback = self::$routes[$method][$path];
      $callback();
    } else {
      self::set_content_type("html");
      http_response_code(404);
      echo "404 Not Found";
    }
  }
}
