<?php

namespace Cercanias\Provider;

use Cercanias\HttpAdapter\HttpAdapterInterface;

abstract class AbstractProvider
{
    protected $httpAdapter;

    /**
     * @param HttpAdapterInterface $httpAdapter
     */
    public function __construct(HttpAdapterInterface $httpAdapter)
    {
        $this->httpAdapter = $httpAdapter;
    }

    /**
     * @return HttpAdapterInterface
     */
    protected function getHttpAdapter()
    {
        return $this->httpAdapter;
    }
}