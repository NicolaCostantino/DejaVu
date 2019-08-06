<?php

namespace Framework\Response;

use Framework\Response\ResponseInterface;

class HttpResponse implements ResponseInterface
{
    protected $headers = [];
    protected $status_code = '';
    protected $content = '';

    public function setStatusCode(String $status_code) : void
    {
        $this->status_code = $status_code;
    }
    
    public function setHeaders(Array $headers) : void
    {
        $this->headers = $headers;
    }
    
    public function setContent($content) : void
    {
        $this->content = $content;
    }

    public function send() : void
    {
        // Send headers
        $this->sendHeaders();
        // Send content
        $this->sendContent();
    }
    
    protected function sendHeaders() : void
    {
        // Send headers with status code, if not already sent
        // Testing: trivial, built around globals
        if (!headers_sent()) {  // @codeCoverageIgnore
            foreach ($this->headers as $key => $value) {  // @codeCoverageIgnore
                header($value, true, $this->status_code);  // @codeCoverageIgnore
            }
        }
    } // @codeCoverageIgnore

    protected function sendContent() : void
    {
        // Testing: trivial, built around globals
        echo $this->content; // @codeCoverageIgnore
    } // @codeCoverageIgnore
}