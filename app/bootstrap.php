<?php

use Framework\App\App;
use Framework\Kernel\HttpKernel;


// Instanciate DotEnv
$dotenv = Dotenv\Dotenv::create(__DIR__.'/..');
$dotenv->load();

// DEBUG-only: Instanciate Whoops
if (getenv('APP_DEBUG')) {
    $whoops = new \Whoops\Run;
    $whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

$config = require_once __DIR__.'/config.php';

// Create dependencies for the application
$kernel = new HttpKernel();

// Create a new instance of the application
$app = new App($kernel, $config);

// Return the instance to the caller script
return $app;