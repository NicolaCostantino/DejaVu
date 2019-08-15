<?php

namespace Framework\Router;

use Framework\App\BootstrappableServiceInterface;
use Framework\Response\ResponseInterface;
use Framework\Request\RequestInterface;
use Framework\Router\RouteInterface;
use Framework\Router\RouterInterface;

interface RouterInterface extends BootstrappableServiceInterface
{
    public function get(String $uri, String $controller) : RouteInterface;
    public function resolve(RequestInterface $request) : ResponseInterface;
}