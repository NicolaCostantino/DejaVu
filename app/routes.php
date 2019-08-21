<?php

use Framework\App\App;

// Define routes here
App::router()->get('/foo/bar/baz', 'HelloWorld');

App::router()->get('^/helloworld$', 'HelloWorld');

App::router()->get('^/foo/{bar}/{baf}/{folk}/{baz?}/{bat?}$', 'HelloWorld')
            ->where(
                [
                    'bar' => '[\d]+',
                    'baz' => '[\w]+',
                    'foo' => '[\w\d]+',
                ]
            );