<?php

namespace Framework\Router;

use Framework\Router\RouteInterface;

interface RouterInterface
{
    public function get(String $uri, String $controller) : ?RouteInterface;
    public function resolve(RequestInterface $request) : ?RouteInterface;
}
