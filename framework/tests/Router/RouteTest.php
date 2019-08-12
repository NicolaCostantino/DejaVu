<?php

use PHPUnit\Framework\TestCase;

use Framework\Router\Route;

class RouteTest extends TestCase
{
    public function testSetUri()
    {
        // Arrange
        $uri = '/foo/bar/baz';
        $property = 'uri';
        $sut = new Route();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        // Act
        $sut->setUri($uri);
        $retrieved = $sut_property->getValue($sut);
        // Assert
        $this->assertSame($uri, $retrieved);
    }

    public function testGetUri()
    {
        // Arrange
        $uri = '/foo/bar/baz';
        $property = 'uri';
        $sut = new Route();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        $sut_property->setValue($sut, $uri);
        // Act
        $retrieved = $sut->getUri($uri);
        // Assert
        $this->assertSame($uri, $retrieved);
    }

    public function testSetMethod()
    {
        // Arrange
        $method = 'GET';
        $expected = 'get';
        $property = 'method';
        $sut = new Route();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        // Act
        $sut->setMethod($method);
        $retrieved = $sut_property->getValue($sut);
        // Assert
        $this->assertSame($expected, $retrieved);
    }

    public function testGetMethod()
    {
        // Arrange
        $method = 'get';
        $property = 'method';
        $sut = new Route();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        $sut_property->setValue($sut, $method);
        // Act
        $retrieved = $sut->getMethod($method);
        // Assert
        $this->assertSame($method, $retrieved);
    }

    public function testSetController()
    {
        // Arrange
        $controller = 'SampleController';
        $property = 'controller';
        $sut = new Route();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        // Act
        $sut->setController($controller);
        $retrieved = $sut_property->getValue($sut);
        // Assert
        $this->assertSame($controller, $retrieved);
    }

    public function testGetController()
    {
        // Arrange
        $controller = 'SampleController';
        $property = 'controller';
        $sut = new Route();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty($property);
        $sut_property->setAccessible(true);
        $sut_property->setValue($sut, $controller);
        // Act
        $retrieved = $sut->getController($controller);
        // Assert
        $this->assertSame($controller, $retrieved);
    }
}