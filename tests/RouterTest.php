<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BadRouter\Router;
use BadRouter\Request;

class RouterTest extends TestCase {
    public array $request_page = [
        'REQUEST_URI' => '/page',
        'REQUEST_METHOD' => 'GET',
    ];

    public array $request_404 = [
        'REQUEST_URI' => '/page_does_not_exist',
        'REQUEST_METHOD' => 'GET',
    ];

    public function test_404(): void {
        Router::reset();

        $request = new Request($this->request_404);

        ob_start();
        Router::enable_headers(false);
        Router::run($request);
        $output = trim(ob_get_contents());
        ob_end_clean();

        $this->assertSame('404', $output);
    }

    public function test_get_page_route(): void {
        Router::reset();

        $request = new Request($this->request_page);

        // Setup a route
        Router::get('/page', function() {
            echo('Hello world!');
        });

        ob_start();
        Router::enable_headers(false);
        Router::run($request);
        $output = trim(ob_get_contents());
        ob_end_clean();

        $this->assertSame('Hello world!', $output);
    }

    public function test__post_page_route(): void {
        Router::reset();

        $request = new Request($this->request_page);

        // Setup a route
        Router::post('/page', function() {
            echo('Hello world!');
        });

        ob_start();
        Router::enable_headers(false);
        Router::run($request);
        $output = trim(ob_get_contents());
        ob_end_clean();

        $this->assertSame('404', $output);
    }
}
