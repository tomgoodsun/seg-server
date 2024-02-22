<?php

namespace Data;

trait DataAccessTrait
{
    private $data = [];

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Undocumented function
     *
     * @param string|int $key
     * @return mixed
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->data)) {
            throw new \Exception('Key not found');
        }
        return $this->data[$key];
    }
}