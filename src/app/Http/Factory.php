<?php
namespace App\Http;

use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Psr7\Stream;
use Slim\Psr7\Uri;

class Factory
{
    /**
     * Create HTTP request object
     *
     * TODO: To implement original request class detected from URI and web routes
     *
     * @return Request
     */
    public static function createRequest()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = new Uri('http', $_SERVER['HTTP_HOST'], null, $_SERVER['REQUEST_URI']);
        $headers = new Headers($_SERVER);
        $body = new Stream(fopen('php://input', 'r'));

        return new Request($method, $uri, $headers, $_COOKIE, $_REQUEST, $body, $_FILES);
    }

    /**
     * Create default HTTP response object
     *
     * @return Response
     */
    public static function createDefaultResponse()
    {
        return new Response();
    }
}
