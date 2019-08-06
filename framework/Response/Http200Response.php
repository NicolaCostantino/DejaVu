<?php

namespace Framework\Response;

use Framework\Response\HttpResponse;

class Http200Response extends HttpResponse
{
    protected $headers = [
        "Content-Type: text/html; charset=utf-8",
    ];
    protected $status_code = '200';
    protected $content = '';

    public function __construct($content='')
    {
        $this->setContent($content);
    }
}