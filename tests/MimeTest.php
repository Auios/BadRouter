<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use BadRouter\Mime;

class MimeTest extends TestCase {

    public function test_get(): void {
        $this->assertSame('application/json', Mime::Get('json'));
    }

    public function test_get_invalid(): void {
        $this->assertSame('text/plain', Mime::Get('invalid'));
    }
}
