<?php

use PHPUnit\Framework\TestCase;

use Framework\App\App;
use Framework\Controller\Controller;

class ControllerTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        // Instanciate the App
        $this->sut_config = [];
        // Instanciate the mocks
        $this->kernel_mock = Mockery::mock('\Framework\Kernel\HttpKernel')
                                    ->makePartial();
        $this->router_mock = Mockery::mock('\Framework\Router\Router')
                                    ->makePartial();
        $this->template_engine = Mockery::mock('\Framework\TemplateEngine\TwigTemplateEngine')
                                        ->makePartial();
        $this->sut = new Controller();
        $this->refl_sut = new ReflectionObject($this->sut);
    }

    public function testRenderToResponse()
    {
        // Arrange
        $template = 'test';
        $context = [
            'foo' => 'bar',
        ];
        // Assert
        $this->kernel_mock->shouldReceive('bootstrap')
                          ->once();
        $this->router_mock->shouldReceive('bootstrap')
                          ->once();
        $this->template_engine->shouldReceive('bootstrap')
                              ->once();
        $this->template_engine->shouldReceive('render')
                              ->once();
        $sut = new Controller();
        $sut_method = $this->refl_sut->getMethod('renderToResponse');
        $sut_method->setAccessible(true);
        $this->sut_app = new App(
            $this->sut_config,
            $this->kernel_mock,
            $this->router_mock,
            $this->template_engine
        );
        App::setInstance($this->sut_app);
        // Act
        $sut_method->invokeArgs(
            $this->sut, array($template, $context)
        );
    }
}