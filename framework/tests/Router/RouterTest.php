<?php

use PHPUnit\Framework\TestCase;

use Framework\App\App;
use Framework\Response\Http404Response;
use Framework\Kernel\HttpKernel;
use Framework\Request\HttpRequest;
use Framework\Router\Router;
use Framework\Router\Route;
use Framework\TemplateEngine\TwigTemplateEngine;

class RouterTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected function setUp() : void
    {
        // Mocks
        $this->route_mock = Mockery::mock('\Framework\Router\Route')
                                   ->makePartial();
        $this->request_mock = Mockery::mock('\Framework\Request\HttpRequest')
                                     ->makePartial();
        // Instances and configurations
        $this->sut_config = [
            'debug' => false,
            'controllers' => [
                'base_namespace' => 'App\Controller',
            ],
            'routes' => [
                'default_pattern' => '[A-Za-z0-9]+',
                'default_parameter_value' => NULL,
            ],
        ];
        $this->sut_kernel = new HttpKernel();
        // Instanciate the Router
        $this->sut_router = new Router();
        $this->sut_template_engine = new TwigTemplateEngine();
        $this->refl_sut = new ReflectionObject($this->sut_router);
        $this->sut_app = new App(
            $this->sut_config,
            $this->sut_kernel,
            $this->sut_router,
            $this->sut_template_engine
        );
        App::setInstance($this->sut_app);
    }

    public function testBootstrap()
    {
        // Arrange
        // Instanciate the App without configuration for routes loading
        $router_mock = Mockery::mock('\Framework\Router\Router')
                              ->makePartial()
                              ->shouldAllowMockingProtectedMethods();
        $refl_sut = new ReflectionObject($router_mock);
        $sut_property = $refl_sut->getProperty('routes');
        $sut_property->setAccessible(true);
        $previous_value = $sut_property->getValue($this->sut_router);
        $router_mock->shouldReceive('bootstrap')
                    ->once();
        // Act
        $this->sut_app = new App(
            $this->sut_config,
            $this->sut_kernel,
            $router_mock,
            $this->sut_template_engine
        );
        App::setInstance($this->sut_app);
        // Assert
        $current_value = $sut_property->getValue($this->sut_router);
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
        $route_mock->shouldReceive('setPattern')
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
        $retrieved = $this->sut_router->get($uri, $controller);
        $internal = $sut_property->getValue($this->sut_router)['get'][0];
        // Assert
        $this->assertSame($uri, $retrieved->getPattern());
        $this->assertSame($uri, $internal->getPattern());
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
            $this->sut_router,
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
        $retrieved = $this->sut_router->get($uri, $controller);
        // Act
        $found_route = $sut_property->invokeArgs(
            $this->sut_router,
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
            $this->sut_router,
            array($request)
        );
        // Assert
        $this->assertInstanceOf(Http404Response::class, $retrieved);
    }

    public function testHandleRouteNotFoundWithTemplate()
    {
        // Arrange
        $this->sut_config = array_merge(
            $this->sut_config,
            [
                'template_engine' => [
                    'template_path' => __DIR__.'/../../../app/templates',
                    'options' => [],
                    'template_404' => '404.html',
                ],
            ]
        );
        $this->sut_app = new App(
            $this->sut_config,
            $this->sut_kernel,
            $this->sut_router,
            $this->sut_template_engine
        );
        App::setInstance($this->sut_app);
        $request = Mockery::mock('\Framework\Request\HttpRequest')
                          ->makePartial();
        $sut_property = $this->refl_sut->getMethod('handleRouteNotFound');
        $sut_property->setAccessible(true);
        // Act
        $retrieved = $sut_property->invokeArgs(
            $this->sut_router,
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
        $route_mock->shouldReceive('getParameters')
                   ->once();
        $router_mock->shouldReceive('resolveController')
                    ->once();
        $router_mock->shouldReceive('preparePayload')
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
        $controller_name = 'TestController';
        $method = 'get';
        $base_namespace = App::config()['controllers']['base_namespace'];
        $full_qualified_controller = $base_namespace.'\\'.$controller_name;
        $sut_property = $this->refl_sut->getMethod('resolveController');
        $sut_property->setAccessible(true);
        $expected = array($full_qualified_controller, $method);
        // Act
        $retrieved = $sut_property->invokeArgs(
            $this->sut_router,
            array($controller_name, $method)
        );
        // Assert
        $this->assertSame($expected, $retrieved);
    }

    public function testPreparePayload1()
    {
        // Arrange
        $this->route_mock->shouldReceive('getParameters')
                         ->once()
                         ->andReturn(
                             [
                                 'foo' => 'foo_value',
                                 'bar' => 'bar_value',
                                 'baz' => 'baz_value',
                             ]
                         );
        $callback_array = [
            'Framework\Test\Router\Helpers\HelperController',
            'testMethod1',
        ];
        $sut_method = $this->refl_sut->getMethod('preparePayload');
        $sut_method->setAccessible(true);
        $expected = [
            'request' => $this->request_mock,
            'bar' => 'bar_value',
        ];
        // Act
        $retrieved = $sut_method->invokeArgs(
            $this->sut_router,
            array($this->request_mock, $this->route_mock, $callback_array)
        );
        // Assert
        $this->assertSame($expected, $retrieved);
    }

    public function testPreparePayload2()
    {
        // Arrange
        $this->route_mock->shouldReceive('getParameters')
                         ->once()
                         ->andReturn(
                             [
                                 'foo' => 'foo_value',
                                 'baz' => 'baz_value',
                             ]
                         );
        $callback_array = [
            'Framework\Test\Router\Helpers\HelperController',
            'testMethod1',
        ];
        $sut_method = $this->refl_sut->getMethod('preparePayload');
        $sut_method->setAccessible(true);
        $expected = [
            'request' => $this->request_mock,
            'bar' => NULL,
        ];
        // Act
        $retrieved = $sut_method->invokeArgs(
            $this->sut_router,
            array($this->request_mock, $this->route_mock, $callback_array)
        );
        // Assert
        $this->assertSame($expected, $retrieved);
    }

    public function testCallController()
    {
        // Arrange
        $callback_array = [
            'Framework\Test\Router\Helpers\HelperController',
            'testMethod1',
        ];
        $payload = [
            'request' => $this->request_mock,
            'bar' => NULL,
        ];
        $sut_method = $this->refl_sut->getMethod('callController');
        $sut_method->setAccessible(true);
        $expected = 'testMethod1';
        // Act
        $retrieved = $sut_method->invokeArgs(
            $this->sut_router,
            array($callback_array, $payload)
        );
        // Assert
        $this->assertSame($expected, $retrieved->getContent());
    }

    public function testCallControllerParameters()
    {
        // Arrange
        $callback_array = [
            'Framework\Test\Router\Helpers\HelperController',
            'testMethod2',
        ];
        $payload = [
            'request' => $this->request_mock,
            'foo' => NULL,
            'bar' => 'test',
        ];
        $sut_method = $this->refl_sut->getMethod('callController');
        $sut_method->setAccessible(true);
        $expected = [
            $payload['request'],
            $payload['foo'],
            $payload['bar'],
        ];
        // Act
        $retrieved = $sut_method->invokeArgs(
            $this->sut_router,
            array($callback_array, $payload)
        );
        // Assert
        $this->assertSame($expected, $retrieved->getContent());
    }
}