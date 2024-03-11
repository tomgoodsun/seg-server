<?php

namespace App\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

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
     * Controller object
     *
     * @var Controller
     */
    private $controller;

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
        if (array_key_exists($name, static::$routesByNames)) {
            throw new \Exception("Route with name '{$name}' already exists");
        }
        static::$routesByNames[$name] = $this;
        return $this;
    }

    /**
     * Dispatch route
     *
     * @return ResponseInterface
     */
    public function dispatch(): ResponseInterface
    {
        $handler = explode('@', $this->handler);
        if (count($handler) !== 2) {
            throw new \Exception("Invalid route handler '{$this->handler}'");
        }
        /** @var Controller $controller */
        $controller = new $handler[0]($this->createRequest(), $this->createResponse());
        $method = $handler[1];
        return $controller->dispatch($method);
    }

    /**
     * Create request
     *
     * @return RequestInterface
     */
    public function createRequest(): RequestInterface
    {
        return Factory::createRequest();
    }

    /**
     * Create response
     *
     * @return ResponseInterface
     */
    public function createResponse(): ResponseInterface
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
        if (null !== $name) {
            static::$routes[$method][$path]->setName($name);
        }
        return static::$routes[$method][$path];
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
     * @return Route
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
     * @return Route
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
     * @return Route
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
     * @return Route
     */
    public static function delete(string $path, string $handler, string $name = null): Route
    {
        return static::register($path, 'delete', $handler, $name);
    }

    /**
     * Create route resolving pattern
     *
     * TODO: This is much better define Resolver class
     *
     * @param string $path
     * @return array
     */
    final public static function createPattern(string $path): array
    {
        $paramNames = [];
        $parts = explode('/', $path);
        array_walk($parts, function (&$part) use (&$paramNames) {
            if (preg_match('/^{([a-zA-Z_][a-zA-Z0-9_-]*)}$/', $part)) {
                // Remove '{' at first and '}' at last
                $paramNames[] = preg_replace('/(^\{|\}$)/', '', $part);
                $part = '([a-zA-Z0-9_-]*)';
            }
        });
        return [implode('\/', $parts), $paramNames];
    }

    /**
     * Find route
     *
     * TODO: This is much better define Resolver class
     *
     * @param string $method
     * @param string $uri
     * @return Route
     */
    final public static function resolve(string $method, string $path): Route
    {
        $method = strtolower($method);
        if (!array_key_exists($method, static::$routes)) {
            throw new NotFoundException("Method '{$method}' not allowed");
        }
        if (array_key_exists($path, static::$routes[$method])) {
            $route = static::$routes[$method][$path];
        } else {
            // No static route found, try to resolve by pattern
            $route = self::resolveByPattern($method, $path);
            if (null === $route) {
                throw new NotFoundException("Route '{$path}' not found");
            }
        }
        return $route;
    }

    /**
     * Resolve route by pattern, set URI-including query parameters are set into $_GET
     * (simple pattern matching)
     *
     * TODO: This is much better define Resolver class
     *
     * @param string $method
     * @param string $path
     * @return Route|null
     */
    final public static function resolveByPattern(string $method, string $path): Route|null
    {
        foreach (static::$routes[$method] as $routePath => $route) {
            // Avoid too many nested if/for statements
            $matches = [];
            list($pattern, $paramNames) = static::createPattern($routePath);
            if (!preg_match('/^' . $pattern . '$/', $path, $matches)) {
                continue;
            }
            if (count($matches) !== count($paramNames) + 1) {
                continue;
            }
            foreach ($paramNames as $index => $name) {
                // At this point, Request object is not created yet
                // URI-including query string is for $_GET parameter
                $_GET[$name] = $matches[$index + 1];
            }
            return $route;
        }
        return null;
    }
}
