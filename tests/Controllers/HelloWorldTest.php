<?php

use PHPUnit\Framework\TestCase;

use App\Controller\HelloWorld;
use Framework\Response\Http200Response;

class HelloWorldTest extends TestCase
{
    public function testGet()
    {
        // Arrange
        $request_mock = Mockery::mock('\Framework\Request\HttpRequest');
        // Act
        $retrieved = HelloWorld::get($request_mock);
        // Assert
        $this->assertInstanceOf(Http200Response::class, $retrieved);
    }
}