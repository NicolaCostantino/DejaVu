<?php

namespace Framework\Router;

use Framework\App\App;
use Framework\Response\ResponseInterface;
use Framework\Response\Http404Response;
use Framework\Request\RequestInterface;
use Framework\Router\RouterInterface;

class Router implements RouterInterface
{
    public $routes = [
        'get' => [],
    ];

    public function bootstrap() : void
    {
        if (isset(App::config()['routes']['files'])) {
            $route_files = App::config()['routes']['files'];
            foreach ($route_files as $key => $file) {
                include_once $file;
            }
        }
    }

    public function get(String $uri, String $controller) : RouteInterface
    {
        $method = 'get';
        // Create the Route
        $route = $this->routeCreator();
        $route->setMethod($method);
        $route->setUri($uri);
        $route->setController($controller);
        // Register the Route
        $this->routes[$method][] = $route;
        // Return the Route
        return $route;
    }

    public function resolve(RequestInterface $request) : ResponseInterface
    {
        // TODO: Handle 500 error (on find and dispatch)
        $route = $this->findRoute($request);
        if (! $route) {
            // Return Response for route not found
            return $this->handleRouteNotFound($request);
        }
        $response = $this->dispatch($request, $route);
        return $response;
    }

    protected function routeCreator() : RouteInterface
    {
        return new Route();
    }

    protected function findRoute(RequestInterface $request) : ?RouteInterface
    {
        $matched_route = NULL;
        $method = $request->getMethod();
        if (array_key_exists($method, $this->routes)) {
            foreach ($this->routes[$method] as $key => $route) {
                if ($route->matchesRequest($request)) {
                    $matched_route = $route;
                    break;
                }
            }
        }
        return $matched_route;
    }

    protected function handleRouteNotFound(RequestInterface $request) : ResponseInterface
    {
        // TODO: improve response
        return new Http404Response('Not Found!');
    }

    protected function dispatch(RequestInterface $request, RouteInterface $route) : ResponseInterface
    {
        $controller = $route->getController();
        $method = $request->getMethod();
        // TODO: Get the payload from route
        // Passing request and arguments to the controller
        $payload = [
            $request,
        ];
        // Call the controller with payload
        $callback_array = $this->resolveController($controller, $method);
        $response = $this->callController($callback_array, $payload);
        return $response;
    }

    protected function resolveController(String $controller_name, String $method) : Array
    {
        $base_namespace = App::config()['controllers']['base_namespace'];
        $full_qualified_controller = $base_namespace.'\\'.$controller_name;
        $callback_array = Array($full_qualified_controller, $method);
        return $callback_array;
    }

    protected function callController(Array $callback_array, Array $payload) : ResponseInterface
    {
        $response = call_user_func_array($callback_array, $payload);  // @codeCoverageIgnore
        return $response;  // @codeCoverageIgnore
    }
}