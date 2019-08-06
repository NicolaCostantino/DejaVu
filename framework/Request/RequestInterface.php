<?php

namespace Framework\Request;

interface RequestInterface
{
    public function getMethod();
    public function getUri();
}