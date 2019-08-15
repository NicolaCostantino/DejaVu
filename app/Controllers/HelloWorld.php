<?php

namespace App\Controller;

use Framework\Controller\Controller as BaseController;
use Framework\Request\RequestInterface;
use Framework\Response\ResponseInterface;
use Framework\Response\Http200Response;

class HelloWorld extends BaseController
{
    public static function get(RequestInterface $request, $parameter='Default') : ResponseInterface
    {
        $controller_context = 'Hello, World!';
        $content = $controller_context.' '.$parameter;
        
        $response = new Http200Response($content);
        // Return the response
        return $response;
    }
}