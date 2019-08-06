<?php

namespace App\Controller;

use Framework\Controller\Controller as BaseController;
use Framework\Request\RequestInterface;
use Framework\Response\ResponseInterface;
use Framework\Response\Http200Response;

class HelloWorld extends BaseController
{
    public static function get(RequestInterface $request, $parameter) : ResponseInterface
    {
        $response = new Http200Response();

        // TODO: Mocked for test
        $controller_context = 'Hello, World!';
        $content = $controller_context.' '.$parameter;
        
        $response->setContent($content);
        // Return the response
        return $response;
    }
}