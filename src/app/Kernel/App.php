<?php
namespace App\Kernel;

use App\Http\Factory;

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
        // TODO: To implement original request class detected from URI and web routes
        $request = Factory::createRequest();
        dump($request);
        $response = Factory::createDefaultResponse();

        try {
            $this->config->get('key');
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
