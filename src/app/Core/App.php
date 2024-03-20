<?php

/**
 * App class
 */

namespace App\Core;

use App\Http\Route;
use App\Core\ResponseEmitter;
use Psr\Http\Message\ResponseInterface;

class App
{
    /**
     * Condig instance
     *
     * @var Config
     */
    private $config;

    /**
     * Constructor
     *
     * @param array $config
     */
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

    /**
     * Start the application
     *
     * @return void
     */
    public function run()
    {
        try {
            // TODO: Set config to Route object
            $method = sgv()->server('REQUEST_METHOD');
            $uri = sgv()->server('REQUEST_URI');
            $route = Route::resolve($method, $uri);

            /**
             * @var ResponseInterface $response
             */
            $response = $route->dispatch();
            $responseEmitter = new ResponseEmitter();
            $responseEmitter->emit($response);
        } catch (\Exception $e) {
            // TODO: Log the exception
            // TODO: Render a nice error page
            throw $e;
        }
    }
}
