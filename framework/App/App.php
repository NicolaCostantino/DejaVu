<?php

namespace Framework\App;

use Framework\App\AppException;
use Framework\App\AppInterface;
use Framework\Kernel\KernelInterface;
use Framework\Request\RequestInterface;
use Framework\Router\RouterInterface;
use Framework\TemplateEngine\TemplateEngineInterface;

class App implements AppInterface
{
    const KEY_KERNEL = 'APP_KERNEL';
    const KEY_CONFIG = 'APP_CONFIG';
    const KEY_ROUTER = 'APP_ROUTER';
    const KEY_TEMPLATE_ENGINE = 'APP_TEMPLATE_ENGINE';

    protected static $instance = NULL;
    protected $registry = NULL;

    public function __construct(Array $config,
        KernelInterface $kernel,
        RouterInterface $router,
        TemplateEngineInterface $template_engine
    ) {
        // Registering the App instance
        static::$instance = $this;

        // Instanciating and populating the registry
        $this->registry = [];
        $this->set(static::KEY_CONFIG, $config);
        // Set the kernel and bootstrap it
        $this->set(static::KEY_KERNEL, $kernel);
        static::kernel()->bootstrap();
        // Set the router and bootstrap it
        $this->set(static::KEY_ROUTER, $router);
        static::router()->bootstrap();
        // Set the template engine and bootstrap it
        $this->set(static::KEY_TEMPLATE_ENGINE, $template_engine);
        static::template_engine()->bootstrap();
    }

    public static function getInstance() : ?App
    {
        return static::$instance;
    }

    public static function setInstance(App $instance=null) : void
    {
        static::$instance = $instance;
    }

    public static function config() : Array
    {
        if (!static::getInstance()) {
            throw new AppException("Application not initialized!");
        }
        return static::getInstance()->get(static::KEY_CONFIG);
    }

    public static function kernel() : KernelInterface
    {
        if (!static::getInstance()) {
            throw new AppException("Application not initialized!");
        }
        return static::getInstance()->get(static::KEY_KERNEL);
    }

    public static function router() : RouterInterface
    {
        if (!static::getInstance()) {
            throw new AppException("Application not initialized!");
        }
        return static::getInstance()->get(static::KEY_ROUTER);
    }

    public static function template_engine() : TemplateEngineInterface
    {
        if (!static::getInstance()) {
            throw new AppException("Application not initialized!");
        }
        return static::getInstance()->get(static::KEY_TEMPLATE_ENGINE);
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