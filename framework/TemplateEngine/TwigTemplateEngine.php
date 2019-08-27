<?php

namespace Framework\TemplateEngine;

use Framework\App\App;

class TwigTemplateEngine implements TemplateEngineInterface
{
    protected $engine = NULL;
    protected $loader = NULL;

    public function bootstrap() : void
    {
        if (isset(App::config()['template_engine'])) {
            // Configurations
            $templates_path = App::config()['template_engine']['template_path'];
            $template_engine_options = App::config()['template_engine']['options'];
            // Twig instance creation
            $this->loader = new \Twig\Loader\FilesystemLoader($templates_path);
            $this->engine = new \Twig\Environment(
                $this->loader,
                $template_engine_options
            );
        }
    }

    public function render(String $template, Array $context=[])
    {
        return $this->engine->render($template, $context);
    }
}