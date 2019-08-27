<?php

namespace Framework\App;

use Framework\Kernel\KernelInterface;
use Framework\Request\RequestInterface;
use Framework\Router\RouterInterface;
use Framework\TemplateEngine\TemplateEngineInterface;

interface AppInterface
{
    public static function getInstance() : ?App;
    public static function setInstance(App $instance=null) : void;
    public static function config() : Array;
    public static function kernel() : KernelInterface;
    public static function router() : RouterInterface;
    public static function template_engine() : TemplateEngineInterface;
    public function set($key, $value);
    public function get($key);
    public function handle(RequestInterface $request) : void;
}