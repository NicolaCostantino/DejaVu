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
    public function bootstrap() : void
    {
        // Bootstrap the kernel
    }

    public function handle(RequestInterface $request) : ResponseInterface
    {
        // Dispatch to the router
        $response = App::router()->resolve($request);

        // Return the response
        return $response;
    }

    public function terminate()
    {
        // Post-Response actions
    }
}