<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BadRouter\Request;

class RequestTest extends TestCase {
    public array $request_with_route = [
        'REQUEST_URI' => '/page',
        'REQUEST_METHOD' => 'GET',
        'REMOTE_ADDR' => 'localhost',
        'REMOTE_PORT' => 1234,
        'HTTP_SEC_CH_UA_PLATFORM' => 'Windows',
        'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36',
        'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
        'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, br',
        'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.9,la;q=0.8',
        'HTTP_REFERER' => '/other_page',
        'REQUEST_TIME_FLOAT' => 1693323233.596446,
    ];

    public array $request_without_route = [
        'REQUEST_URI' => null,
        'REQUEST_METHOD' => 'GET',
    ];

    public function test_constructor(): void {
        $request = new Request($this->request_with_route);

        $this->assertSame('/page', $request->route);
        $this->assertSame('GET', $request->method);
        $this->assertSame('localhost', $request->address);
        $this->assertSame(1234, $request->port);
        $this->assertSame('Windows', $request->platform);
        $this->assertSame('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36', $request->user_agent);
        $this->assertSame('text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7', $request->accept);
        $this->assertSame('gzip, deflate, br', $request->encoding);
        $this->assertSame('en-US,en;q=0.9,la;q=0.8', $request->language);
        $this->assertSame('/other_page', $request->referrer);
        $this->assertSame(1693323233.596446, $request->time);
    }

    public function test_empty_route(): void {
        $request = new Request($this->request_without_route);

        $this->assertSame('/', $request->route);
    }
}
