<?php

namespace Framework\App;
use Framework\Request\RequestInterface;

interface AppInterface
{
    public static function getInstance() : ?App;
    public static function setInstance(App $instance=null);
    public function set($key, $value);
    public function get($key);
    public function handle(RequestInterface $request) : void;
}