<?php

namespace App\Http;

class Route
{
    /**
     * Registered routes
     *
     * @var Route[][]
     */
    protected static $routes = [];

    /**
     * Registered routes by names
     *
     * @var Route[]
     */
    protected static $routesByNames = [];

    /**
     * Route path
     *
     * @var string
     */
    private $path;

    /**
     * Route method
     *
     * @var string
     */
    private $method;

    /**
     * Route handler
     *
     * @var string
     */
    private $handler;

    /**
     * Route name
     *
     * @var string
     */
    private $name;

    /**
     * Route constructor
     *
     * @param string $path
     * @param string $method
     * @param string $handler
     * @param string $name
     */
    public function __construct(string $path, string $method, string $handler, string $name = null)
    {
        $this->path = $path;
        $this->method = $method;
        $this->handler = $handler;
        $this->name = $name;
    }

    /**
     * Get route path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Get route method
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Get route handler
     *
     * @return string
     */
    public function getHandler(): string
    {
        return $this->handler;
    }

    /**
     * Get route name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Route
     */
    public function setName(string $name): Route
    {
        if ($name && array_key_exists($name, static::$routesByNames)) {
            throw new \Exception("Route with name '{$name}' already exists");
        }
        $this->name = $name;
        return $this;
    }

    /**
     * Create request
     *
     * @return Request
     */
    public function createRequest()
    {
        return Factory::createRequest();
    }

    /**
     * Create response
     *
     * @return Response
     */
    public function createResponse()
    {
        return Factory::createDefaultResponse();
    }

    /**
     * Register route
     *
     * @param string $path
     * @param string $method
     * @param string $handler
     * @param string|null $name
     * @return Route
     */
    protected static function register(string $path, string $method, string $handler, string $name = null): Route
    {
        if (!array_key_exists($method, static::$routes)) {
            static::$routes[$method] = [];
        }
        if (array_key_exists($path, static::$routes[$method])) {
            throw new \Exception("Route with path '{$path}' already exists");
        }
        static::$routes[$method][$path] = new self($path, $method, $handler, $name);
        static::$routesByNames[$name] = static::$routes[$method][$path];
        return static::$routes[$method][$path]->setName($name);
    }

    /**
     * Register GET method route
     *
     * @param string $path
     * @param string $handler
     * @param string|null $name
     * @return Route
     */
    public static function get(string $path, string $handler, string $name = null): Route
    {
        return static::register($path, 'get', $handler, $name);
    }

    /**
     * Register POST method route
     *
     * @param string $path
     * @param string $handler
     * @param string|null $name
     * @return Request
     */
    public static function post(string $path, string $handler, string $name = null): Route
    {
        return static::register($path, 'post', $handler, $name);
    }

    /**
     * Resgister PUT method route
     *
     * @param string $path
     * @param string $handler
     * @param string|null $name
     * @return Request
     */
    public static function put(string $path, string $handler, string $name = null): Route
    {
        return static::register($path, 'put', $handler, $name);
    }

    /**
     * Register PATCH method route
     *
     * @param string $path
     * @param string $handler
     * @param string|null $name
     * @return Request
     */
    public static function patch(string $path, string $handler, string $name = null): Route
    {
        return static::register($path, 'patch', $handler, $name);
    }

    /**
     * Register DELETE method route
     *
     * @param string $path
     * @param string $handler
     * @param string|null $name
     * @return Request
     */
    public static function delete(string $path, string $handler, string $name = null): Route
    {
        return static::register($path, 'delete', $handler, $name);
    }

    /**
     * Find route
     *
     * @param string $method
     * @param string $path
     * @return Route
     */
    public static function findRoute(string $method, string $path): Route
    {
        if (!array_key_exists($method, static::$routes)) {
            throw new \Exception("Method '{$method}' not allowed");
        }
        if (!array_key_exists($path, static::$routes[$method])) {
            throw new \Exception("Route '{$path}' not found");
        }
        return static::$routes[$method][$path];
    }
}
