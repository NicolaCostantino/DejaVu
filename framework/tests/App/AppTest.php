<?php

use PHPUnit\Framework\TestCase;

use Framework\App\App;
use Framework\App\AppException;
use Framework\Kernel\HttpKernel;
use Framework\Request\HttpRequest;

class AppTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected function setUp() : void
    {
        // Instanciate the App
        $this->sut_config = [
            'debug' => false,
            'controllers' => [
                'base_namespace' => 'App\Controller',
            ],
        ];
        $this->sut_kernel = new HttpKernel();
        $this->sut_router = null;
        $this->sut = new App($this->sut_kernel, $this->sut_config);
    }

    public function testGetInstance()
    {
        // Arrange
        // Act
        $sut1 = App::getInstance();
        // Assert
        $this->assertSame($this->sut, $sut1);
    }

    public function testSetInstance()
    {
        // Arrange
        $sut1 = App::getInstance();
        // Act
        App::setInstance(new App($this->sut_kernel, $this->sut_config));
        $sut2 = App::getInstance();
        // Assert
        $this->assertNotSame($sut2, $sut1);
    }

    public function testConfigProxy()
    {
        // Arrange
        // Act
        $config = App::config();
        // Assert
        $this->assertSame($this->sut_config, $config);
    }

    public function testConfigProxyOnNull()
    {
        // Arrange
        App::setInstance();
        // Assert
        $this->expectException(AppException::class);
        // Act
        $config = App::config();
    } // @codeCoverageIgnore

    public function testKernelProxy()
    {
        // Arrange
        // Act
        $kernel = App::kernel();
        // Assert
        $this->assertSame($this->sut_kernel, $kernel);
    }

    public function testKernelProxyOnNull()
    {
        // Arrange
        App::setInstance();
        // Assert
        $this->expectException(AppException::class);
        // Act
        $kernel = App::kernel();
    } // @codeCoverageIgnore

    public function testRouterProxy()
    {
        // Arrange
        // Act
        $router = App::router();
        // Assert
        $this->assertSame($this->sut_router, $router);
    }

    public function testRouterProxyOnNull()
    {
        // Arrange
        App::setInstance();
        // Assert
        $this->expectException(AppException::class);
        // Act
        $router = App::router();
    } // @codeCoverageIgnore

    public function testSetGetCycle()
    {
        // Arrange
        $test_key = 'test_key';
        $expected_value = 'test_value';
        // Act
        $this->sut->set($test_key, $expected_value);
        $result = $this->sut->get($test_key);
        // Assert
        $this->assertSame($expected_value, $result);
    }

    public function testGetUnexistentValue()
    {
        // Arrange
        $test_key = 'test_key';
        // Assert
        $this->expectException(AppException::class);
        // Act
        $result = $this->sut->get($test_key);
    } // @codeCoverageIgnore

    public function testHandle()
    {
        // Arrange
        $request_mock = Mockery::mock('\Framework\Request\RequestInterface');
        $kernel_mock = Mockery::mock('\Framework\Kernel\KernelInterface');
        $response_mock = Mockery::mock('\Framework\Response\ResponseInterface');
        App::setInstance(new App($kernel_mock, $this->sut_config));
        // Assert
        $kernel_mock->shouldReceive('handle')
                    ->once()
                    ->with($request_mock)
                    ->andReturn($response_mock);
        $response_mock->shouldReceive('send')
                    ->once();
        $kernel_mock->shouldReceive('terminate')
                    ->once();
        // Act
        $this->sut->handle($request_mock);
    }
}