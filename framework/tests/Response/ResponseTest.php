<?php

use PHPUnit\Framework\TestCase;

use Framework\Response\HttpResponse;

class HttpResponseTest extends TestCase
{
    use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testConstructor()
    {
        // Arrange
        $expected = 'TestContent';
        // Act
        $sut = new HttpResponse($expected);
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty('content');
        $sut_property->setAccessible(true);
        $retrieved = $sut_property->getValue($sut);
        // Assert
        $this->assertSame($expected, $retrieved);
    }

    public function testGetStatusCode()
    {
        // Arrange
        $status_code = '200';
        $sut = new HttpResponse();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty('status_code');
        $sut_property->setAccessible(true);
        $sut_property->setValue($sut, $status_code);
        // Act
        $retrieved = $sut->getStatusCode();
        // Assert
        $this->assertSame($status_code, $retrieved);

    }

    public function testSetStatusCode()
    {
        // Arrange
        $status_code = '200';
        $sut = new HttpResponse();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty('status_code');
        $sut_property->setAccessible(true);
        // Act
        $sut->setStatusCode($status_code);
        $retrieved = $sut_property->getValue($sut);
        // Assert
        $this->assertSame($status_code, $retrieved);
    }

    public function testGetHeaders()
    {
        // Arrange
        $headers = [
            'test_header',
        ];
        $sut = new HttpResponse();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty('headers');
        $sut_property->setAccessible(true);
        $sut_property->setValue($sut, $headers);
        // Act
        $retrieved = $sut->getHeaders();
        // Assert
        $this->assertSame($headers, $retrieved);

    }

    public function testSetHeaders()
    {
        // Arrange
        $headers = ['test_header'];
        $sut = new HttpResponse();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty('headers');
        $sut_property->setAccessible(true);
        // Act
        $sut->setHeaders($headers);
        $retrieved = $sut_property->getValue($sut);
        // Assert
        $this->assertSame($headers, $retrieved);
    }

    public function testGetContent()
    {
        // Arrange
        $content = 'test_content';
        $sut = new HttpResponse();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty('content');
        $sut_property->setAccessible(true);
        $sut_property->setValue($sut, $content);
        // Act
        $retrieved = $sut->getContent();
        // Assert
        $this->assertSame($content, $retrieved);

    }

    public function testSetContent()
    {
        // Arrange
        $content = 'test_content';
        $sut = new HttpResponse();
        $refl_sut = new ReflectionObject($sut);
        $sut_property = $refl_sut->getProperty('content');
        $sut_property->setAccessible(true);
        // Act
        $sut->setContent($content);
        $retrieved = $sut_property->getValue($sut);
        // Assert
        $this->assertSame($content, $retrieved);
    }

    public function testSend()
    {
        // Arrange
        $response_mock = Mockery::mock('\Framework\Response\HttpResponse')
                                ->makePartial()
                                ->shouldAllowMockingProtectedMethods();
        // Assert
        $response_mock->shouldReceive('sendHeaders')
                      ->once();
        $response_mock->shouldReceive('sendContent')
                      ->once();
        // Act
        $response_mock->send();
    }
}