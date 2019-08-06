<?php

use PHPUnit\Framework\TestCase;

use Framework\App\App;

class BootstrapTest extends TestCase
{
    public function testBootstrapModule()
    {
        // Arrange
        // Act
        $sut = require_once __DIR__.'/../app/bootstrap.php';
        // Assert
        $this->assertSame($sut, App::getInstance());
    }
}