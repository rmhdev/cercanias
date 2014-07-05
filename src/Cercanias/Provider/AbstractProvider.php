<?php

namespace Cercanias\Provider;

use Cercanias\HttpAdapter\HttpAdapterInterface;

abstract class AbstractProvider
{
    const ROUTE_ASTURIAS = 20;
    const ROUTE_BARCELONA = 50;
    const ROUTE_BILBAO = 60;
    const ROUTE_CADIZ = 31;
    const ROUTE_MADRID = 10;
    const ROUTE_MALAGA = 32;
    const ROUTE_MURCIA_ALICANTE = 41;
    const ROUTE_SAN_SEBASTIAN = 61;
    const ROUTE_SANTANDER = 62;
    const ROUTE_SEVILLA = 30;
    const ROUTE_VALENCIA = 40;
    const ROUTE_ZARAGOZA = 70;

    private $httpAdapter;

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
