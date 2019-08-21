<?php

namespace Framework\Router;

use Framework\Request\RequestInterface;

interface RouteInterface
{
    public function setPattern(String $uri) : RouteInterface;
    public function getPattern() : String;
    public function setMethod(String $method) : RouteInterface;
    public function getMethod() : String;
    public function setController(String $controller) : RouteInterface;
    public function getController() : String;
    public function getParameters() : Array;
    public function where(Array $conditions) : RouteInterface;
    public function matchesRequest(RequestInterface $request) : Bool;
}