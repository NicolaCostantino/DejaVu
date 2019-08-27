<?php

namespace Framework\TemplateEngine;

use Framework\App\BootstrappableServiceInterface;

interface TemplateEngineInterface extends BootstrappableServiceInterface
{
    public function render(String $template, Array $context=[]);
}