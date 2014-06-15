<?php

namespace Cercanias\Provider;

use Cercanias\HttpAdapter\HttpAdapterInterface;

class WebProvider
{
    protected $httpAdapter;

    public function __construct(HttpAdapterInterface $httpAdapter)
    {
        $this->httpAdapter = $httpAdapter;
    }

    public function getName()
    {
        return 'web_provider';
    }
}
