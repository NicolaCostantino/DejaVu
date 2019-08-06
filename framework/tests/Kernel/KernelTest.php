<?php

use PHPUnit\Framework\TestCase;

use Framework\Kernel\HttpKernel;
use Framework\Request\HttpRequest;
use Framework\Response\Http200Response;

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
        $server_info = [];
        $expected_value = 'GET';
        $server_info['REQUEST_METHOD'] = $expected_value;
        $request = new HttpRequest($server_info);
        // Act
        $response = $this->sut->handle($request);
        // Assert
        $this->assertInstanceOf(Http200Response::class, $response);
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