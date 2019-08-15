<?php

namespace Framework\Router;

use Framework\Request\RequestInterface;

interface RouteInterface
{
    public function setUri(String $uri) : RouteInterface;
    public function getUri() : String;
    public function setMethod(String $method) : RouteInterface;
    public function getMethod() : String;
    public function setController(String $controller) : RouteInterface;
    public function getController() : String;
    public function matchesRequest(RequestInterface $request) : Bool;
}