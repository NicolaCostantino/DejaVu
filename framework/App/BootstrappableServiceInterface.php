<?php

namespace Framework\App;

interface BootstrappableServiceInterface
{
    public function bootstrap() : void;
}