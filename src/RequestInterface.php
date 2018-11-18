<?php
namespace RHo\Http;

/**
 * HTTP Request
 *
 * @link https://tools.ietf.org/html/rfc7230#section-3
 */
interface RequestInterface
{

    /**
     * Designated initializer
     *
     * @return RequestInterface
     */
    static function init(): RequestInterface;

    /**
     * Get HTTP header
     *
     * @param string $name
     *            The name of the header
     * @return string|NULL The value of header, or NULL if header name not present
     * @link https://tools.ietf.org/html/rfc7230#section-3.2
     */
    function header(string $name): ?string;

    /**
     * Get HTTP Query String
     *
     * @param string $name
     *            The field name of query string
     * @return string|NULL The field value of query string, or NULL if field name is not set
     * @link https://tools.ietf.org/html/rfc7320#section-2.4
     */
    function queryStr(string $name): ?string;

    /**
     * Get HTTP message body
     *
     * @return string The HTTP request body as string
     * @throws \RuntimeException If php://input file cannot be read
     * @link https://tools.ietf.org/html/rfc7230#section-3.3
     */
    function msgBody(): string;

    /**
     * Get HTTP request method
     *
     * @return string The HTTP request method
     * @link https://tools.ietf.org/html/rfc7231#section-4
     */
    function methdod(): string;

    /**
     * Get HTTP request version
     *
     * @return string The HTTP request version
     * @link https://tools.ietf.org/html/rfc7230#section-2.6
     */
    function version(): string;

    /**
     * Get HTTP request target
     *
     * @return string The HTTP request target
     * @link https://tools.ietf.org/html/rfc7230#section-5.3
     */
    function target(): string;
}