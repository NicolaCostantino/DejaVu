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
        $route->setPattern($uri);
        $route->setController($controller);
        // Register the Route
        $this->routes[$method][] = $route;
        // Return the Route
        return $route;
    }

    public function resolve(RequestInterface $request) : ResponseInterface
    {
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
        $content = '404 - Not found';
        if (isset(App::config()['template_engine']['template_404'])) {
            $template = App::config()['template_engine']['template_404'];
            $context = compact('request');
            $content = App::template_engine()->render($template, $context);
        }
        return new Http404Response($content);
    }

    protected function dispatch(RequestInterface $request, RouteInterface $route) : ResponseInterface
    {
        $controller = $route->getController();
        $method = $request->getMethod();
        $parameters = $route->getParameters();
        // Resolve the controller
        $callback_array = $this->resolveController($controller, $method);
        // Passing request and arguments to the controller
        $payload = $this->preparePayload($request, $route, $callback_array);
        // Call the controller with payload
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

    protected function preparePayload(RequestInterface $request, RouteInterface $route, Array $callback_array) : Array
    {
        // Get extracted parameters
        $parameters = $route->getParameters();
        // Passing request and arguments to the controller
        $available_values = array_merge(['request' => $request,], $parameters);
        // (Advanced) Clean and sanitize parameter values
        // Order and fill the parameters as required by the controller
        $reflection_method = new \ReflectionMethod(
            $callback_array[0], $callback_array[1]
        );
        $payload = [];
        foreach ($reflection_method->getParameters() as $parameter) {
            if (isset($available_values[$parameter->name])) {
                $payload[$parameter->name] = $available_values[$parameter->name];
            } else {
                // Default for missing values
                $default_value = App::config()['routes']['default_parameter_value'];
                $payload[$parameter->name] = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : $default_value;
            }
        }
        return $payload;
    }

    protected function callController(Array $callback_array, Array $payload) : ResponseInterface
    {
        $controller_instance = new $callback_array[0];
        $callback_array[0] = $controller_instance;
        $response = call_user_func_array($callback_array, $payload);
        return $response;
    }
}