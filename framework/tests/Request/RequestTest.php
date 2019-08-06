<?php

use PHPUnit\Framework\TestCase;

use Framework\Request\HttpRequest;

class RequestTest extends TestCase
{
    public function testGetMethod()
    {
        // Arrange
        $server_info = [];
        $provided_value = 'GET';
        $expected_value = strtolower($provided_value);
        $server_info['REQUEST_METHOD'] = $provided_value;
        $sut = new HttpRequest($server_info);
        // Act
        $result = $sut->getMethod();
        // Assert
        $this->assertSame($expected_value, $result);
    }

    public function testGetUri()
    {
        // Arrange
        $server_info = [];
        $expected_value = '/foo/bar/baz';
        $server_info['REQUEST_URI'] = $expected_value;
        $sut = new HttpRequest($server_info);
        // Act
        $result = $sut->getUri();
        // Assert
        $this->assertSame($expected_value, $result);
    }
}