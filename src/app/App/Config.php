<?php
namespace App;

use Data\DataAccessTrait;

class Config
{
    use DataAccessTrait;

    protected $config = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get($key = null)
    {
        return $this->searchFromData($this->config, $key);
    }
}
