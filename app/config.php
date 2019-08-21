<?php

return [
    /*
     * Globals
     */
    'debug' => getenv('APP_DEBUG', false),

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
];