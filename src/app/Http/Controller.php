<?php

namespace App\Http;

use Slim\Psr7\Request;
use Slim\Psr7\Response;

abstract class Controller
{
    private Request $request;
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Dispatch an action
     *
     * @param string $action
     * @param array $params
     * @return mixed
     */
    public function dispatch(string $action, array $params = [])
    {
        $this->before($this->request, $this->response);
        $ret = call_user_func_array([$this, $action], [$this->request, $this->response, ...$params]);
        $this->after($this->request, $this->response);
        return $ret;
    }

    protected function before(Request $request, Response $response)
    {
        // do nothing, override if needed
    }

    protected function after(Request $request, Response $response)
    {
        // do nothing, override if needed
    }

    /**
     * Render a view
     *
     * @param string $view
     * @param array $data
     * @return string
     */
    protected function view(string $view, array $data = []): string
    {
        extract($data);
        ob_start();
        require __DIR__ . "/../../resources/views/{$view}.php";
        return ob_get_clean();
    }
}
