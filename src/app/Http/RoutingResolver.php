<?php

namespace App\Http;

class RoutingResolver
{
    /**
     * @var string
     */
    private $path = '';

    /**
     * @var string
     */
    private $pattern = '';

    /**
     * @var array
     */
    private $paramNames = [];

    /**
     * @var array
     */
    private $matches = [];

    /**
     * Constructor
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->createPattern();
    }

    /**
     * Create route resolving pattern
     *
     * @return array
     */
    protected function createPattern(): void
    {
        $paramNames = [];
        $parts = explode('/', $this->path);
        array_walk($parts, function (&$part) use (&$paramNames) {
            if (preg_match('/^{([a-zA-Z_][a-zA-Z0-9_-]*)}$/', $part)) {
                // Remove '{' at first and '}' at last
                $paramNames[] = preg_replace('/(^\{|\}$)/', '', $part);
                $part = '([a-zA-Z0-9_-]*)';
            }
        });
        $pattern = implode('\/', $parts);
        $this->pattern = '/^' . $pattern . '$/';
        $this->paramNames = $paramNames;
    }

    /**
     * Set URI-including query string to $_GET
     *
     * @return RoutingResolver
     */
    public function setQueryParams(): RoutingResolver
    {
        foreach ($this->paramNames as $index => $name) {
            // At this point, Request object is not created yet
            // URI-including query string is for $_GET parameter
            $_GET[$name] = $this->matches[$index + 1];
        }
        return $this;
    }

    /**
     * Match the path with the pattern
     *
     * @param string $path
     * @return bool
     */
    public function match(string $path)
    {
        if (!preg_match($this->pattern, $path, $this->matches)) {
            return false;
        }
        if (count($this->matches) !== count($this->paramNames) + 1) {
            return false;
        }
        return true;
    }
}
