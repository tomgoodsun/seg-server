<?php

namespace App\Http\Controllers;

use App\Http\Controller;
use App\Http\DefaultResponse;
use Psr\Http\Message\RequestInterface;

class HomeController extends Controller
{
    public function index(RequestInterface $request, DefaultResponse $response, $params = [])
    {
        return $response->withJson(['message' => 'Hello, world!'], 200);
    }
}
