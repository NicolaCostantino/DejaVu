<?php

use PHPUnit\Framework\TestCase;

use Framework\App\App;
use Framework\Kernel\HttpKernel;
use Framework\Request\HttpRequest;
use Framework\Response\Http200Response;
use Framework\TemplateEngine\TwigTemplateEngine;

class KernelTest extends TestCase
{
    protected function setUp(): void
    {
        // Instanciate the App
        $this->sut = new HttpKernel();
    }

    public function testHandle()
    {
        // Arrange
        // Instanciate the App
        $this->sut_config = [
            'debug' => false,
        ];
        $this->sut_template_engine = new TwigTemplateEngine();
        $router_mock = Mockery::mock('\Framework\Router\Router')
                              ->makePartial();
        $request_mock = Mockery::mock('\Framework\Request\HttpRequest');
        $response_mock = Mockery::mock('\Framework\Response\Http200Response');
        $this->sut_app = new App(
            $this->sut_config,
            $this->sut,
            $router_mock,
            $this->sut_template_engine
        );
        App::setInstance($this->sut_app);
        // Assert
        $router_mock->shouldReceive('resolve')
                    ->once()
                    ->andReturn($response_mock);
        // Act
        $retrieved = $this->sut->handle($request_mock);
        // Assert
        $this->assertSame($response_mock, $retrieved);
    }

    public function testTerminate()
    {
        // Arrange
        // Act
        $result = $this->sut->terminate();
        // Assert
        $this->assertSame($result, null);
    }
}