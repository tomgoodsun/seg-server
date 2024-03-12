<?php
namespace App\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractController
{
    private RequestInterface $request;
    private ResponseInterface $response;

    public function __construct(RequestInterface $request, ResponseInterface $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Dispatch an action
     *
     * @param string $action
     * @param array $params
     * @return mixed|ResponseInterface
     */
    public function dispatch(string $action, array $params = []): mixed
    {
        $this->before($this->request, $this->response);
        $ret = call_user_func_array([$this, $action], [$this->request, $this->response, ...$params]);
        $this->after($this->request, $this->response);
        return $ret;
    }

    protected function before(RequestInterface $request, ResponseInterface $response): void
    {
        // do nothing, override if needed
    }

    protected function after(RequestInterface $request, ResponseInterface $response): void
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
