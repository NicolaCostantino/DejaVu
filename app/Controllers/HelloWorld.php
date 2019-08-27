<?php

namespace App\Controller;

use Framework\Controller\Controller as BaseController;
use Framework\Request\RequestInterface;
use Framework\Response\ResponseInterface;
use Framework\Response\Http200Response;

class HelloWorld extends BaseController
{
    public function get(RequestInterface $request, $bar, $folk, $baz='Default') : ResponseInterface
    {
        $context = [
            'controller_context' => 'Hello, from controller!',
            'request' => $request,
            'extra' => [
                'bar' => $bar,
                'folk' => $folk,
                'baz' => $baz,
            ],
        ];

        // Return the response
        return $this->renderToResponse('hello_world.html', $context);
    }
}