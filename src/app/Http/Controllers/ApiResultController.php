<?php

namespace App\Http\Controllers;

use App\Http\AbstractApiController;
use App\Http\DefaultResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ApiResultController extends AbstractApiController
{
    public function put(RequestInterface $request, DefaultResponse $response, array $params = []): ResponseInterface
    {
        return $response->withJson([
            'status' => 'ok',
            'message' => 'Welcome to the API'
        ]);
    }
}
