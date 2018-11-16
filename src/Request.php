<?php
declare(strict_types = 1);
namespace RHo\Http;

class Request implements RequestInterface
{

    /** @var array */
    private $requestLine;

    /** @var array */
    private $headers;

    /** @var string */
    private $msgBodyFile;

    /** @var array */
    private $queryStr;

    public static function init(): RequestInterface
    {
        $requestLine = $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'] . ' ' . $_SERVER['SERVER_PROTOCOL'];
        $headers = $_SERVER;
        $msgBodyFile = 'php://input';
        return new self($requestLine, $headers, $msgBodyFile);
    }

    public function __construct(string $requestLine, array $headers, string $msgBodyFile = 'php://input')
    {
        $this->requestLine = explode(' ', $requestLine);
        $this->headers = $headers;
        $this->msgBodyFile = $msgBodyFile;
    }

    public function header(string $name): ?string
    {
        if ($name === 'Content-Type')
            $name = 'CONTENT_TYPE';
        elseif ($name === 'Content-Length')
            $name = 'CONTENT_LENGTH';
        else
            $name = 'HTTP_' . str_replace('-', '_', strtoupper($name));
        return $this->headers[$name] ?? null;
    }

    public function queryStr(string $name): ?string
    {
        if ($this->queryStr === NULL)
            $this->initQueryStr();
        return $this->queryStr[$name] ?? NULL;
    }

    public function msgBody(): string
    {
        // The at sign (@) error-control operator would work here too. However, in this case
        // running phpunit tests in Eclipse IDE fails with error due to its own
        // error handling mechanism. Executing phpunit from terminal always works.
        set_error_handler(function ($errNo, $errStr, $errFile, $errLine) {
            throw new \RuntimeException("$errStr\n$errFile:$errLine", $errNo);
        });
        $str = file_get_contents($this->msgBodyFile);
        restore_error_handler();
        return $str;
    }

    public function methdod(): string
    {
        return $this->requestLine[0];
    }

    public function target(): string
    {
        return $this->requestLine[1];
    }

    public function version(): string
    {
        return $this->requestLine[2];
    }

    private function initQueryStr(): void
    {
        $queryStr = parse_url($this->target(), PHP_URL_QUERY);
        if (is_string($queryStr))
            parse_str($queryStr, $this->queryStr);
        else
            $this->queryStr = [];
    }
}
