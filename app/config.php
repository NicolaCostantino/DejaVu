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
    ],

    /*
     * Controllers
     */
    'controllers' => [
        // Used for controller loading
        'base_namespace' => 'App\Controller',
    ],
];