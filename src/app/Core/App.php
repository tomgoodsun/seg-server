<?php
namespace App\Core;

use App\Http\Route;
use App\Core\ResponseEmitter;
use App\Database\PdoSql;
use Psr\Http\Message\ResponseInterface;

class App
{
    /**
     * Condig instance
     *
     * @var  Config
     */
    private $config;

    /**
     * Databse instance
     *
     * @var PdoSql
     */
    private $database;

    /**
     * Constructor
     *
     * @param array $config
     */
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

    /**
     * Get database instance, if not exists create new one
     *
     * @return PdoSql
     */
    public function getDatabase()
    {
        if ($this->database === null) {
            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s',
                $this->config->get('DB_DRIVER'),
                $this->config->get('DB_HOST'),
                $this->config->get('DB_PORT'),
                $this->config->get('DB_SCHEMA')
            );
            $this->database = new PdoSql(
                $dsn,
                $this->config->get('DB_USER'),
                $this->config->get('DB_PASSWORD'),
                []
            );
        }
        return $this->database;
    }

    /**
     * Start the application
     *
     * @return void
     */
    public function run()
    {
        try {
            // TODO: Set config to Route object
            // TODO: Avoid using $_SERVER global variable as much as possible
            $method = $_SERVER['REQUEST_METHOD'];
            $uri = $_SERVER['REQUEST_URI'];
            $route = Route::resolve($method, $uri);

            /** @var ResponseInterface $response */
            $response = $route->dispatch();
            $responseEmitter = new ResponseEmitter();
            $responseEmitter->emit($response);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
}
