<?php

use PHPUnit\Framework\TestCase;

use Framework\App\App;
use Framework\Kernel\HttpKernel;
use Framework\Request\HttpRequest;
use Framework\Response\Http200Response;
use Framework\Router\Router;
use Framework\TemplateEngine\TwigTemplateEngine;

class TwigTemplateEngineTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        // Instanciate the App
        $this->sut_config = [
            'debug' => false,
            'template_engine' => [
                // Path to templates
                'template_path' => __DIR__.'/../../../app/templates',
                // Template engine-specific options
                'options' => [],
            ],
        ];
        $this->sut_kernel = new HttpKernel();
        $this->sut_router = new Router();
        $this->sut = new TwigTemplateEngine();
        // Instanciate the mocks
        $this->kernel_mock = Mockery::mock('\Framework\Kernel\HttpKernel')
                                    ->makePartial();
        $this->router_mock = Mockery::mock('\Framework\Router\Router')
                                    ->makePartial();
        // Instanciate the reflection objects
        $this->refl_sut = new ReflectionObject($this->sut);
    }

    public function testBootstrap()
    {
        // Arrange
        // Instanciate the App without configuration for routes loading
        $sut_property_engine = $this->refl_sut->getProperty('engine');
        $sut_property_engine->setAccessible(true);
        $sut_property_loader = $this->refl_sut->getProperty('loader');
        $sut_property_loader->setAccessible(true);
        $this->kernel_mock->shouldReceive('bootstrap')
                          ->once();
        $this->router_mock->shouldReceive('bootstrap')
                          ->once();
        // Act
        $this->sut_app = new App(
            $this->sut_config,
            $this->kernel_mock,
            $this->router_mock,
            $this->sut
        );
        App::setInstance($this->sut_app);
        // Assert
        $current_engine = $sut_property_engine->getValue($this->sut);
        $current_loader = $sut_property_loader->getValue($this->sut);
        $this->assertNotNull($current_engine);
        $this->assertNotNull($current_loader);
    }

    public function testRender()
    {
        // Arrange
        // Instanciate the App without configuration for routes loading
        $template = 'test';
        $context = [
            'foo' => 'bar',
        ];
        $sut_property_engine = $this->refl_sut->getProperty('engine');
        $sut_property_engine->setAccessible(true);
        $current_engine = $sut_property_engine->getValue($this->sut);
        $engine_mock = Mockery::mock('\Twig\Environment')
                              ->makePartial();
        $sut_property_engine->setValue($this->sut, $engine_mock);
        // Assert
        $engine_mock->shouldReceive('render')
                    ->once();
        // Act
        // $sut_method = $this->refl_sut->getMethod('render');
        // $sut_method->invokeArgs(
        //     $this->sut, array($template, $context)
        // );
        $this->sut->render($template, $context);
    }
}