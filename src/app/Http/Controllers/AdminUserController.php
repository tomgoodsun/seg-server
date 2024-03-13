<?php

namespace App\Http\Controllers;

use App\Http\AbstractController;
use App\Http\DefaultResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AdminUserController extends AbstractController
{
    public function get(RequestInterface $request, DefaultResponse $response, array $params = []): ResponseInterface
    {
        return $response->withJson([
            'status' => 'ok',
            'message' => 'Welcome to the API'
        ]);
    }

    public function getWithUserId(RequestInterface $request, DefaultResponse $response, array $params = []): ResponseInterface
    {
        return $response->withJson([
            'status' => 'ok',
            'message' => 'Welcome to the API'
        ]);
    }
}
