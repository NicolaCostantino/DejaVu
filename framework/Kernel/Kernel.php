<?php

namespace Framework\Kernel;

use Framework\App\App;
use Framework\Kernel\KernelInterface;
use Framework\Request\RequestInterface;
use Framework\Response\ResponseInterface;
use Framework\Response\Http200Response;
use Framework\Router\RouteInterface;

class HttpKernel implements KernelInterface
{
    public function handle(RequestInterface $request) : ResponseInterface
    {
        // TODO: fixed test response
        $controller_name = 'HelloWorld';
        $method = 'get';
        $payload = [$request, 'This is a Test!'];
        
        // Dispatch to the controller
        $response = $this->dispatch($controller_name, $method, $payload);

        // Return the response
        return $response;
    }

    protected function dispatch($controller, $method, $payload)
    {
        $base_controller_namespace = App::config()['controllers']['base_namespace'];
        $full_qualified_controller = $base_controller_namespace.'\\'.$controller;
        $response = call_user_func_array(
            Array($full_qualified_controller, $method),
            $payload
        );
        return $response;
    }

    public function terminate()
    {
        // Post-Response actions
    }
}