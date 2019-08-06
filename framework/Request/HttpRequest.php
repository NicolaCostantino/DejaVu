<?php

namespace Framework\Request;

use Framework\App\App;
use Framework\Request\RequestInterface;

class HttpRequest implements RequestInterface
{
    protected $server_info = [];
    protected $method = '';
    protected $uri = '';

    public function __construct(Array $server_info)
    {
        $this->server_info = $server_info;
        $this->uri = $server_info['REQUEST_URI'] ?? NULL;
        $this->method = strtolower($server_info['REQUEST_METHOD'] ?? NULL);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }
}