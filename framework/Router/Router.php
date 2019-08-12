<?php

namespace Framework\Router;

use Framework\Router\RouterInterface;

class Router implements RouterInterface
{
    public $routes = [
        'get' => [],
    ];

    protected function routeCreator() : ?RouteInterface
    {
        return new Route();
    }

    public function get(String $uri, String $controller) : ?RouteInterface
    {
        // Create the Route
        $route = $this->routeCreator();
        $route->setMethod("get");
        $route->setUri($uri);
        $route->setController($controller);
        // Register the Route
        $this->routes['get'][] = $route;
        // Return the Route
        return $route;
    }
}