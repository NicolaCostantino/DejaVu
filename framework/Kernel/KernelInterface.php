<?php

namespace Framework\Kernel;

use Framework\App\BootstrappableServiceInterface;
use Framework\Request\RequestInterface;
use Framework\Response\ResponseInterface;

interface KernelInterface extends BootstrappableServiceInterface
{
    public function handle(RequestInterface $request) : ResponseInterface;
    public function terminate();
}