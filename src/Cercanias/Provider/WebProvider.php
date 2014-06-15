<?php

namespace Cercanias\Provider;

use Cercanias\Exception\InvalidArgumentException;
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

    public function getRoute($id)
    {
        if (is_null($id)) {
            throw new InvalidArgumentException("Route id must be defined");
        }
    }
}
