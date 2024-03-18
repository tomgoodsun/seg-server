<?php

namespace App\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;

class Factory
{
    /**
     * Create default HTTP request object
     *
     * TODO: To implement original request class detected from URI and web routes
     *
     * @return RequestInterface
     */
    public static function createDefaultRequest(): RequestInterface
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = new Uri('http', $_SERVER['HTTP_HOST'], null, $_SERVER['REQUEST_URI']);
        $headers = new Headers($_SERVER);
        $body = new Stream(fopen('php://input', 'r'));
        return new DefaultRequest($method, $uri, $headers, $_COOKIE, $_REQUEST, $body, $_FILES);
    }

    /**
     * Create default HTTP response object
     *
     * @return ResponseInterface
     */
    public static function createDefaultResponse(): ResponseInterface
    {
        $response = new Response();
        $streamFactory = new StreamFactory();
        return new DefaultResponse($response, $streamFactory);
    }
}
