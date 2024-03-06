<?php

namespace App\Http\Controllers;

use App\Http\Controller;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AdminController extends Controller
{
    public function index(RequestInterface $request, ResponseInterface $response, $params = [])
    {
        $response->getBody()->write('Hello, world!');
        return $response;
    }
}
