<?php

namespace Framework\Controller;

use Framework\App\App;
use Framework\Response\ResponseInterface;
use Framework\Response\Http200Response;

class Controller implements ControllerInterface
{
    protected function renderToResponse(String $template, Array $context=[]) : ResponseInterface
    {
        $content = App::template_engine()->render($template, $context);
        $response = new Http200Response($content);
        return $response;
    }
}