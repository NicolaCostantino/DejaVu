<?php

namespace Framework\Response;

interface ResponseInterface
{
    public function getStatusCode() : String;
    public function setStatusCode(String $status_code) : Void;
    public function getHeaders() : Array;
    public function setHeaders(Array $headers) : Void;
    public function getContent();
    public function setContent($headers) : Void;
    public function send() : Void;
}