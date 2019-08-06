<?php

namespace Framework\Kernel;

use Framework\Request\RequestInterface;
use Framework\Response\ResponseInterface;

interface KernelInterface
{
    public function handle(RequestInterface $request) : ResponseInterface;
    public function terminate();
}