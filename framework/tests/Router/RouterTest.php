<?php

use PHPUnit\Framework\TestCase;

use Framework\Router\Router;
use Framework\Router\Route;

class RouterTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected function setUp() : void
    {
        // Instanciate the Router
        $this->router_sut = new Router();
    }

    public function testRouteCreator()
    {
        // Arrange
        $sut_method = 'routeCreator';
        $class = new ReflectionClass('\Framework\Router\Router');
        $method = $class->getMethod($sut_method);
        $method->setAccessible(true);
        // Act
        $retrieved = $method->invokeArgs(new Router(), array());
        // Assert
        $this->assertInstanceOf(Route::class, $retrieved);
    }

    public function testGetMethodCalls()
    {
        // Arrange
        $uri = '/foo/bar/baz';
        $controller = 'TestController';
        $route_mock = Mockery::mock('\Framework\Router\Route');
        $router_mock = Mockery::mock('\Framework\Router\Router')
                              ->makePartial()
                              ->shouldAllowMockingProtectedMethods();
        $router_mock->shouldReceive('routeCreator')
                    ->andReturn($route_mock);
        // Assert
        $route_mock->shouldReceive('setUri')
                   ->once();
        $route_mock->shouldReceive('setMethod')
                   ->once();
        $route_mock->shouldReceive('setController')
                   ->once();
        // Act
        $sut = $router_mock->get($uri, $controller);
    }

    public function testGetRegistersRoute()
    {
        // Arrange
        $uri = '/foo/bar/baz';
        $controller = 'TestController';
        $method = 'get';
        $sut = new Router();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty('routes');
        $sut_property->setAccessible(true);
        // Act
        $retrieved = $sut->get($uri, $controller);
        $internal = $sut_property->getValue($sut)['get'][0];
        // Assert
        $this->assertSame($uri, $retrieved->getUri());
        $this->assertSame($uri, $internal->getUri());
        $this->assertSame($method, $retrieved->getMethod());
        $this->assertSame($method, $internal->getMethod());
        $this->assertSame($controller, $retrieved->getController());
        $this->assertSame($controller, $internal->getController());
    }
}