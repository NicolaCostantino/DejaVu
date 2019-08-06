<?php

/**
 * Simple Framework
 *
 * @package  simple-framework
 * @author   Nicola Costantino <nicolacostantino.com>
 */

/*
 * Register Composer's autoloader
 */
require __DIR__.'/../vendor/autoload.php'; // @codeCoverageIgnore

/*
 * Get a bootstrapped instance of the application
 */
$app = require_once __DIR__.'/../app/bootstrap.php'; // @codeCoverageIgnore

// Create the Request
// TODO: Refactor with class method which takes the $_SERVER as optional parameter
$request = new \Framework\Request\HttpRequest($_SERVER); // @codeCoverageIgnore

// Let the App handle the request
$app->handle($request); // @codeCoverageIgnore