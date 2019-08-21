<?php

use PHPUnit\Framework\TestCase;

use Framework\App\App;
use Framework\Kernel\HttpKernel;
use Framework\Router\Router;
use Framework\Router\Route;

class RouteTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected function setUp() : void
    {
        // Mocks
        $this->route_mock = Mockery::mock('\Framework\Router\Route')
                                   ->shouldAllowMockingProtectedMethods()
                                   ->makePartial();
        $this->request_mock = Mockery::mock('\Framework\Request\HttpRequest')
                                     ->shouldAllowMockingProtectedMethods()
                                     ->makePartial();
        // Instances and configurations
        $this->sut_config = [
            'debug' => false,
            'controllers' => [
                'base_namespace' => 'App\Controller',
            ],
            'routes' => [
                'default_pattern' => '[A-Za-z0-9]+',
            ],
        ];
        $this->sut_kernel = new HttpKernel();
        $this->sut_router = new Router();
        // Instanciate the Router
        $this->sut = new Route();
        $this->refl_sut = new ReflectionObject($this->sut);
        // Reflection Properties
        $this->sut_pattern = $this->refl_sut->getProperty('pattern');
        $this->sut_pattern->setAccessible(true);
        $this->sut_conditions = $this->refl_sut->getProperty('conditions');
        $this->sut_conditions->setAccessible(true);
        $this->sut_regex = $this->refl_sut->getProperty('regex');
        $this->sut_regex->setAccessible(true);
        $this->sut_app = App::setInstance(
            new App(
                $this->sut_config, $this->sut_kernel, $this->sut_router
            )
        );
    }

    public function testSetPattern()
    {
        // Arrange
        $pattern = '/foo/bar/baz';
        $property = 'pattern';
        $sut_property = $this->refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        // Act
        $this->sut->setPattern($pattern);
        $retrieved = $sut_property->getValue($this->sut);
        // Assert
        $this->assertSame($pattern, $retrieved);
    }

    public function testGetPattern()
    {
        // Arrange
        $pattern = '/foo/bar/baz';
        $property = 'pattern';
        $sut_property = $this->refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        $sut_property->setValue($this->sut, $pattern);
        // Act
        $retrieved = $this->sut->getPattern($pattern);
        // Assert
        $this->assertSame($pattern, $retrieved);
    }

    public function testSetMethod()
    {
        // Arrange
        $method = 'GET';
        $expected = 'get';
        $property = 'method';
        $sut_property = $this->refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        // Act
        $this->sut->setMethod($method);
        $retrieved = $sut_property->getValue($this->sut);
        // Assert
        $this->assertSame($expected, $retrieved);
    }

    public function testGetMethod()
    {
        // Arrange
        $method = 'get';
        $property = 'method';
        $sut_property = $this->refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        $sut_property->setValue($this->sut, $method);
        // Act
        $retrieved = $this->sut->getMethod($method);
        // Assert
        $this->assertSame($method, $retrieved);
    }

    public function testSetController()
    {
        // Arrange
        $controller = 'SampleController';
        $property = 'controller';
        $sut_property = $this->refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        // Act
        $this->sut->setController($controller);
        $retrieved = $sut_property->getValue($this->sut);
        // Assert
        $this->assertSame($controller, $retrieved);
    }

    public function testGetController()
    {
        // Arrange
        $controller = 'SampleController';
        $property = 'controller';
        $sut_property = $this->refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        $sut_property->setValue($this->sut, $controller);
        // Act
        $retrieved = $this->sut->getController($controller);
        // Assert
        $this->assertSame($controller, $retrieved);
    }

    public function testGetParameters()
    {
        // Arrange
        $parameters = [
            'foo' => 'bar',
        ];
        $property = 'parameters';
        $sut_property = $this->refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        $sut_property->setValue($this->sut, $parameters);
        // Act
        $retrieved = $this->sut->getParameters($parameters);
        // Assert
        $this->assertSame($parameters, $retrieved);
    }

    public function testWhereOnEmpty()
    {
        // Arrange
        $property = 'conditions';
        $conditions = [
            'key' => 'value',
        ];
        $sut_property = $this->refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        // Act
        $this->sut->where($conditions);
        $retrieved = $sut_property->getValue($this->sut);
        // Assert
        $this->assertSame($conditions, $retrieved);
    }

    public function testWhereOnNotEmpty()
    {
        // Arrange
        $property = 'conditions';
        $previous_conditions = [
            'key_1' => 'value_1',
            'key_2' => 'value_2',
        ];
        $new_conditions = [
            'key_2' => 'value_2_1',
            'key_3' => 'value_3',
        ];
        $expected_conditions = [
            'key_1' => 'value_1',
            'key_2' => 'value_2_1',
            'key_3' => 'value_3',
        ];
        $sut_property = $this->refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        $sut_property->setValue($this->sut, $previous_conditions);
        // Act
        $this->sut->where($new_conditions);
        $retrieved = $sut_property->getValue($this->sut);
        // Assert
        $this->assertSame($expected_conditions, $retrieved);
    }

    public function testMatchesRequest()
    {
        // Arrange
        // Assert
        $this->route_mock->shouldReceive('translateToRegex')
                         ->once();
        $this->route_mock->shouldReceive('matchRegex')
                         ->once();
        // Act
        $this->route_mock->matchesRequest($this->request_mock);
    }

    public function testTranslateToRegex1()
    {
        // Arrange
        $pattern = '^/foo/bar/baz$';
        $conditions = [
            'bar' => '[\d]+',
            'baz' => '[\w]+',
            'foo' => '[\w\d]+',
        ];
        $expected = '/^\/foo\/bar\/baz$/';
        $this->sut_pattern->setValue($this->sut, $pattern);
        $this->sut_conditions->setValue($this->sut, $conditions);
        $sut_method = $this->refl_sut->getMethod('translateToRegex');
        $sut_method->setAccessible(true);
        // Act
        $sut_method->invokeArgs(
            $this->sut, array()
        );
        // Assert
        $retrieved = $this->sut_regex->getValue($this->sut);
        $this->assertSame($expected, $retrieved);
    }

    public function testTranslateToRegex2()
    {
        // Arrange
        $pattern = '^/foo/{bar}/{baz?}$';
        $conditions = [
            'bar' => '[\d]+',
            'baz' => '[\w]+',
            'foo' => '[\w\d]+',
        ];
        $expected = '/^\/foo\/(?P<bar>[\d]+)\/(?P<baz>[\w]+)?$/';
        $this->sut_pattern->setValue($this->sut, $pattern);
        $this->sut_conditions->setValue($this->sut, $conditions);
        $sut_method = $this->refl_sut->getMethod('translateToRegex');
        $sut_method->setAccessible(true);
        // Act
        $sut_method->invokeArgs(
            $this->sut, array()
        );
        // Assert
        $retrieved = $this->sut_regex->getValue($this->sut);
        $this->assertSame($expected, $retrieved);
    }

    public function testTranslateToRegex3()
    {
        // Arrange
        $pattern = '^/foo/{bar}/{baz?}$';
        $default_ptn = $this->sut_config['routes']['default_pattern'];
        $conditions = [
            'foo' => '[\w\d]+',
        ];
        $expected = "/^\/foo\/(?P<bar>{$default_ptn})\/(?P<baz>{$default_ptn})?$/";
        $this->sut_pattern->setValue($this->sut, $pattern);
        $this->sut_conditions->setValue($this->sut, $conditions);
        $sut_method = $this->refl_sut->getMethod('translateToRegex');
        $sut_method->setAccessible(true);
        // Act
        $sut_method->invokeArgs(
            $this->sut, array()
        );
        // Assert
        $retrieved = $this->sut_regex->getValue($this->sut);
        $this->assertSame($expected, $retrieved);
    }

    public function testMatchRegex1()
    {
        // Arrange
        $property = 'regex';
        $mocked_regex = '/^\/foo\/(?P<bar>[\w]+)\/(?P<baz>[\d]+)?$/';
        $mocked_uri = '/foo/bar/1';
        $expected = [
            'bar' => 'bar',
            'baz' => '1',
        ];
        $refl_sut_mock = new ReflectionObject($this->route_mock);
        $sut_property = $refl_sut_mock->getProperty($property);
        $sut_property->setAccessible(true);
        $sut_property->setValue($this->route_mock, $mocked_regex);
        $this->request_mock->shouldReceive('getUri')
                           ->andReturn($mocked_uri);
        $sut_method = $refl_sut_mock->getMethod('matchRegex');
        $sut_method->setAccessible(true);
        // Act
        $retrieved = $sut_method->invokeArgs(
            $this->route_mock, array($this->request_mock)
        );
        // Assert
        $this->assertTrue(!array_diff($expected, $retrieved));
    }

    public function testMatchRegex2()
    {
        // Arrange
        $property = 'regex';
        $mocked_regex = '/^\/foo\/bar\/baz$/';
        $mocked_uri = '/foo/bar/baz';
        $expected = [
            $mocked_uri,
        ];
        $refl_sut_mock = new ReflectionObject($this->route_mock);
        $sut_property = $refl_sut_mock->getProperty($property);
        $sut_property->setAccessible(true);
        $sut_property->setValue($this->route_mock, $mocked_regex);
        $this->request_mock->shouldReceive('getUri')
                           ->andReturn($mocked_uri);
        $sut_method = $refl_sut_mock->getMethod('matchRegex');
        $sut_method->setAccessible(true);
        // Act
        $retrieved = $sut_method->invokeArgs(
            $this->route_mock, array($this->request_mock)
        );
        // Assert
        $this->assertSame($expected, $retrieved);
    }

    public function testMatchRegex3()
    {
        // Arrange
        $property = 'regex';
        $mocked_regex = '/^\/foo\/bar\/baz$/';
        $mocked_uri = '/foo/bar/baz/';
        $expected = [
        ];
        $refl_sut_mock = new ReflectionObject($this->route_mock);
        $sut_property = $refl_sut_mock->getProperty($property);
        $sut_property->setAccessible(true);
        $sut_property->setValue($this->route_mock, $mocked_regex);
        $this->request_mock->shouldReceive('getUri')
                           ->andReturn($mocked_uri);
        $sut_method = $refl_sut_mock->getMethod('matchRegex');
        $sut_method->setAccessible(true);
        // Act
        $retrieved = $sut_method->invokeArgs(
            $this->route_mock, array($this->request_mock)
        );
        // Assert
        $this->assertSame($expected, $retrieved);
    }
}