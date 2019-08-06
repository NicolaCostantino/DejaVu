<?php

namespace Framework\App;

use Framework\App\AppException;
use Framework\App\AppInterface;
use Framework\Kernel\KernelInterface;
use Framework\Request\RequestInterface;

class App implements AppInterface
{
    const KEY_KERNEL = 'APP_KERNEL';
    const KEY_CONFIG = 'APP_CONFIG';
    const KEY_ROUTER = 'APP_ROUTER';

    protected static $instance = NULL;
    protected $registry = NULL;

    public function __construct(KernelInterface $kernel, Array $config)
    {
        static::$instance = $this;

        // Instanciating and populating the registry
        $this->registry = [];
        $this->set(static::KEY_KERNEL, $kernel);
        $this->set(static::KEY_CONFIG, $config);
        $this->set(static::KEY_ROUTER, null);
    }

    public static function getInstance() : ?App
    {
        return static::$instance;
    }

    public static function setInstance(App $instance=null)
    {
        static::$instance = $instance;
    }

    public static function config()
    {
        if (!static::getInstance()) {
            throw new AppException("Application not initialized!");
        }
        return static::getInstance()->get(static::KEY_CONFIG);
    }

    public static function kernel()
    {
        if (!static::getInstance()) {
            throw new AppException("Application not initialized!");
        }
        return static::getInstance()->get(static::KEY_KERNEL);
    }

    public static function router()
    {
        if (!static::getInstance()) {
            throw new AppException("Application not initialized!");
        }
        return static::getInstance()->get(static::KEY_ROUTER);
    }

    public function set($key, $value)
    {
        $this->registry[$key] = $value;
    }

    public function get($key)
    {
        if (! array_key_exists($key, $this->registry)) {
            throw new AppException("{$key}: not found");
        }
        return $this->registry[$key];
    }

    public function handle(RequestInterface $request) : void
    {
        $kernel = static::kernel();
        // Handle the request
        $response = $kernel->handle($request);
        // Sending the response
        $response->send();
        // Post-Response actions
        $kernel->terminate();
    }
}