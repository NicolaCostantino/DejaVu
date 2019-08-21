<?php

namespace Framework\Response;

use Framework\Response\ResponseInterface;

class HttpResponse implements ResponseInterface
{
    protected $headers = [];
    protected $status_code = '';
    protected $content = '';

    public function __construct($content='')
    {
        $this->setContent($content);
    }

    public function getStatusCode() : String
    {
        return $this->status_code;
    }

    public function setStatusCode(String $status_code) : Void
    {
        $this->status_code = $status_code;
    }

    public function getHeaders() : Array
    {
        return $this->headers;
    }

    public function setHeaders(Array $headers) : Void
    {
        $this->headers = $headers;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content) : Void
    {
        $this->content = $content;
    }

    public function send() : Void
    {
        // Send headers
        $this->sendHeaders();
        // Send content
        $this->sendContent();
    }
    
    protected function sendHeaders() : Void
    {
        // Send headers with status code, if not already sent
        // Testing: trivial, built around globals
        if (!headers_sent()) {  // @codeCoverageIgnore
            foreach ($this->headers as $key => $value) {  // @codeCoverageIgnore
                header($value, true, $this->status_code);  // @codeCoverageIgnore
            }
        }
    } // @codeCoverageIgnore

    protected function sendContent() : Void
    {
        // Testing: trivial, built around globals
        echo $this->content; // @codeCoverageIgnore
    } // @codeCoverageIgnore
}