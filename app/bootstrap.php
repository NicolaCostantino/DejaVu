<?php

use Framework\App\App;
use Framework\Kernel\HttpKernel;
use Framework\Router\Router;


// Instanciate DotEnv
$dotenv = Dotenv\Dotenv::create(__DIR__.'/..');
$dotenv->load();

// DEBUG-only: Instanciate Whoops
if (getenv('APP_DEBUG')) {
    $whoops = new \Whoops\Run;
    $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

// Load configurations
$config = require_once __DIR__.'/config.php';

// Create dependencies for the application
$kernel = new HttpKernel();
$router = new Router();

// Create a new instance of the application
$app = new App($config, $kernel, $router);

// Return the instance to the caller script
return $app;