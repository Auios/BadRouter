<?php

namespace BadRouter;

use Closure;
use JetBrains\PhpStorm\NoReturn;

require_once('Request.php');
require_once('Mime.php');

class Router {
    private static array $routes = [];
    private static array $errors = [];
    private static array $middlewares = [];
    private static string $views_dir = 'views';
    private static string $currentContentType = 'text/html';
    private static bool $headers_enabled = true;

    private static ?Request $request;

    public static function reset(): void {
        self::$routes = [];
        self::$errors = [];
        self::$middlewares = [];
        self::$views_dir = 'views';
        self::$currentContentType = 'text/html';
        self::$headers_enabled = true;
        self::$request = null;
    }

    public static function set_content_type(string $type): void {
        self::$currentContentType = Mime::Get($type);
        if(self::$headers_enabled) {
            header('Content-Type: ' . self::$currentContentType);
        }
    }

    public static function set_views(string $dir): void {
        self::$views_dir = $dir;
    }

    public static function set_error(int $code, Closure $callback): void {
        self::$errors[$code] = $callback;
    }

    public static function enable_headers(bool $enabled = true): void {
        self::$headers_enabled = $enabled;
    }

    public static function get(string $route, Closure $callback): void {
        self::$routes['GET'][$route] = $callback;
    }

    public static function post(string $route, Closure $callback): void {
        self::$routes['POST'][$route] = $callback;
    }

    public static function put(string $route, Closure $callback): void {
        self::$routes['PUT'][$route] = $callback;
    }

    public static function delete(string $route, Closure $callback): void {
        self::$routes['DELETE'][$route] = $callback;
    }

    public static function redirect(string $route): never {
        if(self::$headers_enabled) {
            header('Location: ' . $route);
        }
        exit(0);
    }

    public static function execute_route(string $method, string $route): void {
        if(isset(self::$routes[$method])) {
            if(isset(self::$routes[$method][$route])) {
                self::$routes[$method][$route]();
            }
        }
    }

    public static function render(string $view, array $locals = [], ?string $layout = '/layout'): void {
        extract($locals);
        ob_start();
        include($_SERVER['DOCUMENT_ROOT'] . '/' . self::$views_dir . $view . '.php');
        $content = ob_get_clean();

        if($layout == null) {
            $output = $content;
        }
        else {
            ob_start();
            include($_SERVER['DOCUMENT_ROOT'] . '/' . self::$views_dir . $layout . '.php');
            $output = ob_get_clean();
        }

        echo $output;
    }

    public static function json(mixed $data): void {
        self::set_content_type('json');
        echo json_encode($data);
    }

    public static function use(Closure $middleware): void {
        self::$middlewares[] = $middleware;
    }

    public static function run(Request $request): void {
        // If route is empty, set it to "/"
        if(strlen($request->route) === 0) {
            $request->route = '/';
        }

        self::$request = $request;

        $routeFound = false;

        if(isset(self::$routes[self::$request->method])) {
            foreach(self::$routes[self::$request->method] as $route => $callback) {
                // Replace route parameters with regex pattern
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[^/]+)', $route);

                // Check if the URL path matches the route pattern
                if(preg_match('~^' . $pattern . '$~', self::$request->route, $matches)) {
                    $routeFound = true;

                    foreach(self::$middlewares as $middleware) {
                        $middleware(self::$request);
                    }

                    // Filter out numeric keys from the matches array
                    $params = array_filter($matches, function($key) {
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
                $route_callback = self::$errors[404];
                $route_callback();
            }
            else {
                echo '404';
            }
        }
    }
}

?>
