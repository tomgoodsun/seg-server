<?php

namespace App\Http;

use Slim\Psr7\Request;

class DefaultRequest extends Request
{
    public $props = [];

    public $bearerToken;

    public function __set($name, $value): void
    {
        if ('bearerToken' === $name) {
            $this->bearerToken = $value;
            return;
        }
        $this->props[$name] = $value;
    }

    public function getBearerToken(): string
    {
        return $this->bearerToken;
    }

    public function getParam(string $name, $default = null)
    {
        return $this->getServerParams()[$name] ?? $default;
    }
}
