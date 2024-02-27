<?php
namespace App;

class App
{
    /**
     * Condig instance
     *
     * @var  Config
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function run()
    {
        try {
            $this->config->get('key');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}   