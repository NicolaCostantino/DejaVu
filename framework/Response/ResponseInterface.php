<?php

namespace Framework\Response;

interface ResponseInterface
{
    public function setStatusCode(String $status_code) : void;
    public function setHeaders(Array $headers) : void;
    public function setContent($headers) : void;
    public function send() : void;
}