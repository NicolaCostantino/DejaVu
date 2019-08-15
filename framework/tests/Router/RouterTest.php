<?php

use PHPUnit\Framework\TestCase;

use Framework\App\App;
use Framework\Response\Http404Response;
use Framework\Kernel\HttpKernel;
use Framework\Request\HttpRequest;
use Framework\Router\Router;
use Framework\Router\Route;

class RouterTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected function setUp() : void
    {
        // Instanciate the Router
        $this->router_sut = new Router();
        $this->refl_sut = new ReflectionObject($this->router_sut);
    }

    public function testBootstrap()
    {
        // Arrange
        // Instanciate the App without configuration for routes loading
        $this->sut_config = [
            'debug' => false,
            'controllers' => [
                'base_namespace' => 'App\Controller',
            ],
        ];
        $this->sut_kernel = new HttpKernel();
        $router_mock = Mockery::mock('\Framework\Router\Router')
                              ->makePartial()
                              ->shouldAllowMockingProtectedMethods();
        $refl_sut = new ReflectionObject($router_mock);
        $sut_property = $refl_sut->getProperty('routes');
        $sut_property->setAccessible(true);
        $previous_value = $sut_property->getValue($this->router_sut);
        $router_mock->shouldReceive('bootstrap')
                    ->once();
        // Act
        $this->sut_app = App::setInstance(
            new App(
                $this->sut_config, $this->sut_kernel, $router_mock
            )
        );
        // Assert
        $current_value = $sut_property->getValue($this->router_sut);
        $this->assertSame($previous_value, $current_value);
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
        $sut_property = $this->refl_sut->getProperty('routes');
        $sut_property->setAccessible(true);
        // Act
        $retrieved = $this->router_sut->get($uri, $controller);
        $internal = $sut_property->getValue($this->router_sut)['get'][0];
        // Assert
        $this->assertSame($uri, $retrieved->getUri());
        $this->assertSame($uri, $internal->getUri());
        $this->assertSame($method, $retrieved->getMethod());
        $this->assertSame($method, $internal->getMethod());
        $this->assertSame($controller, $retrieved->getController());
        $this->assertSame($controller, $internal->getController());
    }

    public function testResolveWithNoRoute()
    {
        // Arrange
        $request_mock = Mockery::mock('\Framework\Request\HttpRequest')
                               ->makePartial();
        $router_mock = Mockery::mock('\Framework\Router\Router')
                              ->makePartial()
                              ->shouldAllowMockingProtectedMethods();
        $refl_sut = new ReflectionObject($router_mock);
        $sut_property = $refl_sut->getMethod('resolve');
        $sut_property->setAccessible(true);
        // Assert
        $router_mock->shouldReceive('findRoute')
                    ->with($request_mock)
                    ->once();
        $router_mock->shouldReceive('handleRouteNotFound')
                    ->with($request_mock)
                    ->once();
        // Act
        $retrieved = $sut_property->invokeArgs(
            $router_mock, array($request_mock)
        );
    }

    public function testResolveWithRoute()
    {
        // Arrange
        $route_mock = Mockery::mock('\Framework\Router\Route')
                             ->makePartial();
        $request_mock = Mockery::mock('\Framework\Request\HttpRequest')
                               ->makePartial();
        $router_mock = Mockery::mock('\Framework\Router\Router')
                              ->makePartial()
                              ->shouldAllowMockingProtectedMethods();
        $refl_sut = new ReflectionObject($router_mock);
        $sut_property = $refl_sut->getMethod('resolve');
        $sut_property->setAccessible(true);
        // Assert
        $router_mock->shouldReceive('findRoute')
                    ->with($request_mock)
                    ->once()
                    ->andReturn($route_mock);
        $router_mock->shouldReceive('dispatch')
                    ->with($request_mock, $route_mock)
                    ->once();
        // Act
        $retrieved = $sut_property->invokeArgs(
            $router_mock, array($request_mock)
        );
    }

    public function testRouteCreator()
    {
        // Arrange
        $sut_property = $this->refl_sut->getMethod('routeCreator');
        $sut_property->setAccessible(true);
        // Act
        $retrieved = $sut_property->invokeArgs(
            $this->router_sut,
            array()
        );
        // Assert
        $this->assertInstanceOf(Route::class, $retrieved);
    }

    public function testFindRouteWithStaticRoute()
    {
        // Arrange
        // Create the Request
        $uri = '/foo/bar/baz';
        $method = 'GET';
        $server_info = [];
        $server_info['REQUEST_METHOD'] = $method;
        $server_info['REQUEST_URI'] = $uri;
        $request = new HttpRequest($server_info);
        // Get the reflection method
        $sut_property = $this->refl_sut->getMethod('findRoute');
        $sut_property->setAccessible(true);
        // Register the route
        $controller = 'TestController';
        $retrieved = $this->router_sut->get($uri, $controller);
        // Act
        $found_route = $sut_property->invokeArgs(
            $this->router_sut,
            array($request)
        );
        // Assert
        $this->assertSame($retrieved, $found_route);
    }

    public function testHandleRouteNotFound()
    {
        // Arrange
        $request = Mockery::mock('\Framework\Request\HttpRequest');
        $sut_property = $this->refl_sut->getMethod('handleRouteNotFound');
        $sut_property->setAccessible(true);
        // Act
        $retrieved = $sut_property->invokeArgs(
            $this->router_sut,
            array($request)
        );
        // Assert
        $this->assertInstanceOf(Http404Response::class, $retrieved);
    }

    public function testDispatch()
    {
        // Arrange
        $method = 'get';
        $route_mock = Mockery::mock('\Framework\Router\Route')
                             ->makePartial();
        $request_mock = Mockery::mock('\Framework\Request\HttpRequest')
                               ->makePartial();
        $router_mock = Mockery::mock('\Framework\Router\Router')
                              ->makePartial()
                              ->shouldAllowMockingProtectedMethods();
        $refl_sut = new ReflectionObject($router_mock);
        $sut_property = $refl_sut->getMethod('dispatch');
        $sut_property->setAccessible(true);
        // Assert
        $route_mock->shouldReceive('getController')
                   ->once();
        $request_mock->shouldReceive('getMethod')
                     ->once()
                     ->andReturn($method);
        $router_mock->shouldReceive('resolveController')
                    ->once();
        $router_mock->shouldReceive('callController')
                    ->once();
        // Act
        $retrieved = $sut_property->invokeArgs(
            $router_mock, array($request_mock, $route_mock)
        );
    }

    public function testResolveController()
    {
        // Arrange
        // Instanciate the App
        $this->sut_config = [
            'debug' => false,
            'controllers' => [
                'base_namespace' => 'App\Controller',
            ],
        ];
        $this->sut_kernel = new HttpKernel();
        $this->sut_router = new Router();
        $this->sut_app = App::setInstance(
            new App(
                $this->sut_config, $this->sut_kernel, $this->sut_router
            )
        );
        $controller_name = 'TestController';
        $method = 'get';
        $base_namespace = App::config()['controllers']['base_namespace'];
        $full_qualified_controller = $base_namespace.'\\'.$controller_name;
        $sut_property = $this->refl_sut->getMethod('resolveController');
        $sut_property->setAccessible(true);
        $expected = array($full_qualified_controller, $method);
        // Act
        $retrieved = $sut_property->invokeArgs(
            $this->router_sut,
            array($controller_name, $method)
        );
        // Assert
        $this->assertSame($expected, $retrieved);
    }
}