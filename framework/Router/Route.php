<?php

namespace Framework\Router;

use Framework\App\App;
use Framework\Request\RequestInterface;
use Framework\Router\RouteInterface;

class Route implements RouteInterface
{
    protected $pattern = '';
    protected $method = '';
    protected $controller = '';
    protected $conditions = [];
    protected $parameters = [];
    protected $regex = '';

    public function setPattern(String $pattern) : RouteInterface
    {
        $this->pattern = $pattern;
        return $this;
    }

    public function getPattern() : String
    {
        return $this->pattern;
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

    public function getParameters() : Array
    {
        return $this->parameters;
    }
    
    public function where(Array $conditions) : RouteInterface
    {
        $this->conditions = array_merge($this->conditions, $conditions);
        return $this;
    }

    public function matchesRequest(RequestInterface $request) : Bool
    {
        $this->translateToRegex();
        $match_result = $this->matchRegex($request);
        return !empty($match_result);
    }

    protected function translateToRegex() : Void
    {
        $conditions = $this->conditions;
        $default = App::config()['routes']['default_pattern'];
        $result = preg_replace_callback_array(
            [
                '/\{([a-z]+)\}/' => function ($matches) use ($conditions, $default) {
                    $name = $matches[1];
                    $pattern = array_key_exists($name, $conditions) ? $conditions[$name] : $default;
                    return '(?P<'.$matches[1].'>'.$pattern.')';
                },
                '/\{([a-z]+)\??\}/' => function ($matches) use ($conditions, $default) {
                    $name = $matches[1];
                    $pattern = array_key_exists($name, $conditions) ? $conditions[$name] : $default;
                    return '(?P<'.$matches[1].'>'.$pattern.')?';
                },
            ],
            $this->getPattern()
        );
        $escaped = str_replace('/', '\/', $result);
        $regex = '/'.$escaped.'/';
        $this->regex = $regex;
    }

    protected function matchRegex(RequestInterface $request) : Array
    {
        $matches = [];
        preg_match($this->regex, $request->getUri(), $matches);
        $this->parameters = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);
        return $matches;
    }
}