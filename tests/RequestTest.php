<?php
declare(strict_types = 1);
namespace RHo\HttpTest\Request;

use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{

    private $req;

    private function msgBodyFile(): string
    {
        switch ($this->getName()) {
            case 'testMsgBodyInputReadError':
                return 'unknown.json';
            default:
                return __DIR__ . '/body.json';
        }
    }

    private function headers(): array
    {
        switch ($this->getName()) {
            case 'testEmptyMethod':
            case 'testEmptyVersion':
            case 'testEmptyTarget':
                return [];
            default:
                return [
                    'HTTP_ACCEPT' => '*/*',
                    'CONTENT_TYPE' => 'text/plain;charset=UTF-8',
                    'CONTENT_LENGTH' => '123',
                    'REQUEST_TIME_FLOAT' => 1541672550.85,
                    'REQUEST_TIME' => 1541672550
                ];
        }
    }

    private function requestLine(): string
    {
        switch ($this->getName()) {
            case 'testEmptyQueryString':
                return 'GET /index.php HTTP/1.1';
            default:
                return 'POST /index.html?foo=bar HTTP/1.1';
        }
    }

    protected function setUp()
    {
        $this->req = new \RHo\Http\Request($this->requestLine(), $this->headers(), $this->msgBodyFile());
    }

    public function testRequestLine(): void
    {
        $this->assertSame("POST", $this->req->methdod());
        $this->assertSame('HTTP/1.1', $this->req->version());
        $this->assertSame('/index.html?foo=bar', $this->req->target());
    }

    public function testQueryString(): void
    {
        $this->assertSame("bar", $this->req->queryStr("foo"));
        $this->assertNull($this->req->queryStr("bar"));
    }

    public function testEmptyQueryString(): void
    {
        $this->assertNull($this->req->queryStr("foo"));
        $this->assertNull($this->req->queryStr("bar"));
    }

    public function testHeaders(): void
    {
        $this->assertSame("*/*", $this->req->header('Accept'));
        $this->assertNull($this->req->header('Authorization'));
        $this->assertSame('text/plain;charset=UTF-8', $this->req->header('Content-Type'));
        $this->assertSame('123', $this->req->header('Content-Length'));
    }

    public function testMsgBodySuccess(): void
    {
        $this->assertSame('{"name": "Róbert"}', $this->req->msgBody());
    }

    public function testMsgBodyInputReadError(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("file_get_contents(unknown.json): failed to open stream: No such file or directory");
        $this->req->msgBody();
    }

    public function testInitWithGlobals(): void
    {
        $req = \RHo\Http\Request::init();
        $this->assertSame("POST", $req->methdod());
        $this->assertSame('HTTP/1.1', $req->version());
        $this->assertSame('/index.php?foo=bar&baz=1', $req->target());
        $this->assertSame("bar", $req->queryStr("foo"));
        $this->assertNull($req->queryStr("bar"));
        $this->assertSame("*/*", $req->header('Accept'));
        $this->assertNull($req->header('Authorization'));
        $this->assertSame('text/plain;charset=UTF-8', $req->header('Content-Type'));
        $this->assertSame('123', $req->header('Content-Length'));
        $this->assertSame('', $req->msgBody());
    }
}