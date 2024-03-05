<?php

namespace App\Http\Controllers;

use App\Http\Controller;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class AdminController extends Controller
{
    public function index(Request $request, Response $response, $params = [])
    {
        $response->getBody()->write('Hello, world!');
        return $response;
    }
}
