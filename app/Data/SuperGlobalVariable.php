<?php
namespace Data;

class SuperGlobalVariable
{
    use DataAccessTrait;

    protected $server;
    protected $get;
    protected $post;
    protected $files;
    protected $cookie;
    //protected $session;
    protected $request;
    protected $env;

    /**
     * This class is a singleton class
     *
     * @return SuperGlobalVariable
     */
    public static function getInstance()
    {
        $instance = null;
        if (null === $instance) {
            $instance = new static();
        }
        return $instance;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        // Session must be managed with session object
        $this->server = $_SERVER;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->cookie = $_COOKIE;
        //$this->session = $_SESSION;
        $this->request = $_REQUEST;
        $this->env = $_ENV;
    }

    public function server($key = null)
    {
        return $this->searchFromData($this->server, $key);
    }

    public function get($key = null)
    {
        return $this->searchFromData($this->get, $key);
    }

    public function post($key = null)
    {
        return $this->searchFromData($this->post, $key);
    }
    
    public function files($key = null)
    {
        return $this->searchFromData($this->files, $key);
    }

    public function cookie($key = null)
    {
        return $this->searchFromData($this->cookie, $key);
    }

    //public function session($key = null)
    //{
    //    return $this->searchFromData($this->session, $key);
    //}

    public function request($key = null)
    {
        return $this->searchFromData($this->request, $key);
    }

    public function env($key = null)
    {
        return $this->searchFromData($this->env, $key);
    }
}
