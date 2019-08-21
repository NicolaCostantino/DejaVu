<?php

namespace Framework\Test\Router\Helpers;

use Framework\Controller\Controller as BaseController;
use Framework\Request\RequestInterface;
use Framework\Response\Http200Response;
use Framework\Response\ResponseInterface;

class HelperController extends BaseController
{
    public function testMethod1(RequestInterface $request, $bar) : ResponseInterface
    {
        $response = new Http200Response('testMethod1');
        // Return the response
        return $response;
    }

    public function testMethod2(RequestInterface $request, $foo, $bar) : ResponseInterface
    {
        $response = new Http200Response(func_get_args());
        // Return the response
        return $response;
    }
}