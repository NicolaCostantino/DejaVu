<?php

namespace Framework\Router;

use Framework\Request\RequestInterface;
use Framework\Router\RouteInterface;

class Route implements RouteInterface
{
    protected $uri = '';
    protected $method = '';
    protected $controller = '';

    public function setUri(String $uri) : RouteInterface
    {
        $this->uri = $uri;
        return $this;
    }

    public function getUri() : String
    {
        return $this->uri;
    }

    public function setMethod(String $method) : RouteInterface
    {
        $this->method = strtolower($method);
        return $this;
    }

    public function getMethod() : String
    {
        return $this->method;
    }

    public function setController(String $controller) : RouteInterface
    {
        $this->controller = $controller;
        return $this;
    }

    public function getController() : String
    {
        return $this->controller;
    }

    public function matchesRequest(RequestInterface $request) : Bool
    {
        // TODO: Improve with checks
        return $request->getUri() == $this->getUri();
    }
}