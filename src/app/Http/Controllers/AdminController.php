<?php

namespace App\Http\Controllers;

use App\Http\AbstractPageController;
use App\Http\DefaultResponse;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AdminController extends AbstractPageController
{
    public function index(RequestInterface $request, DefaultResponse $response, array $params = []): ResponseInterface
    {
        return $this->render('admin/index.twig', [
            'title' => 'Admin',
            'content' => 'Welcome to the admin area'
        ]);
    }
}
