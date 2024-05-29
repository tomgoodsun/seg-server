<?php

namespace App\Http;

class ErrorHandler
{
    /**
     * Error handlers
     *
     * @var callable[]
     */
    protected $handlers = [];

    /**
     * This class is a singleton
     *
     * @return ErrorHandler
     */
    public static function getInstance(): ErrorHandler
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Set error handler by path pattern
     *
     * @param string $pathPattern
     * @param callable $handler
     * @return void
     */
    public function setHandlerByPathPattern(string $pathPattern, callable $handler)
    {
        $this->handlers[$pathPattern] = $handler;
        return $this;
    }

    /**
     * Resolve error handler by path
     *
     * @param string $path
     * @return callable|null
     */
    public function resolve(string $path): ?callable
    {
        foreach ($this->handlers as $pathPattern => $handler) {
            $pattern = '#^' . $pathPattern . '#';
            if (preg_match($pattern, $path)) {
                return $handler;
            }
        }
        return null;
    }

    /**
     * Handle an error
     *
     * @param \Throwable $e
     * @param string $path
     * @return void
     */
    public function handle(\Throwable $e, string $path)
    {
        $handler = $this->resolve($path);
        if ($handler === null) {
            throw new \Exception("Error handler for path {$path} not found");
        }
        $handler($e);
    }
}
