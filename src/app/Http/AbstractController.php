<?php

namespace App\Http;

abstract class AbstractController
{
    protected DefaultRequest $request;
    protected DefaultResponse $response;

    public function __construct(DefaultRequest $request, DefaultResponse $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Dispatch an action
     *
     * @param string $action
     * @param array $params
     * @return mixed|DefaultResponse
     */
    public function dispatch(string $action, array $params = []): mixed
    {
        $this->response = $this->before($this->request, $this->response);
        $this->response = call_user_func_array(
            [$this, $action],
            [$this->request, $this->response, ...$params]
        );
        $this->response = $this->after($this->request, $this->response);
        return $this->response;
    }

    protected function before(DefaultRequest $request, DefaultResponse $response): DefaultResponse
    {
        return $response;
    }

    protected function after(DefaultRequest $request, DefaultResponse $response): DefaultResponse
    {
        return $response;
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
