<?php

return [
    /*
     * Globals
     */
    'debug' => isset($_ENV['APP_DEBUG']) ? $_ENV['APP_DEBUG'] : false,

    /*
     * Routes
     */
    'routes' => [
        // Used for routes loading
        'files' => [
            __DIR__.'/routes.php',
        ],
        // Default patterns for routes
        'default_pattern' => '[A-Za-z0-9]+',
        // Default value for missing parameters
        'default_parameter_value' => NULL,
    ],

    /*
     * Controllers
     */
    'controllers' => [
        // Used for controller loading
        'base_namespace' => 'App\Controller',
    ],

    /*
     * Template Engine
     */
    'template_engine' => [
        // Path to templates
        'template_path' => __DIR__.'/templates',
        // Template engine-specific options
        'options' => [],
        // Template engine-specific options
        'template_404' => '404.html',
    ]
];