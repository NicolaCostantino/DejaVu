<?php

namespace Framework\App;

use Framework\Kernel\KernelInterface;
use Framework\Request\RequestInterface;
use Framework\Router\RouterInterface;

interface AppInterface
{
    public static function getInstance() : ?App;
    public static function setInstance(App $instance=null) : void;
    public static function config() : Array;
    public static function kernel() : KernelInterface;
    public static function router() : RouterInterface;
    public function set($key, $value);
    public function get($key);
    public function handle(RequestInterface $request) : void;
}