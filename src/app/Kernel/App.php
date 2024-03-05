<?php
namespace App\Kernel;

use App\Http\Route;
use Psr\Http\Message\ResponseInterface;

class App
{
    /**
     * Condig instance
     *
     * @var  Config
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function run()
    {
        try {
            // TODO: Set config to Route object
            // TODO: Avoid using $_SERVER global variable as much as possible
            $method = $_SERVER['REQUEST_METHOD'];
            $uri = $_SERVER['REQUEST_URI'];
            $route = Route::resolve($method, $uri);

            /** @var ResponseInterface $response */
            $response = $route->dispatch();
            echo $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
