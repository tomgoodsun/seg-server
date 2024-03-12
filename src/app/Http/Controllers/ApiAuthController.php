<?php

namespace App\Http\Controllers;

use App\Http\AbstractApiController;
use App\Http\DefaultResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ApiAuthController extends AbstractApiController
{
    //protected function before(RequestInterface $request, ResponseInterface $response): void
    //{
    //}

    public function get(RequestInterface $request, DefaultResponse $response, array $params = [])
    {
        return $response->withJson([
            'status' => 'ok',
            'message' => 'Welcome to the API'
        ]);
    }
}
