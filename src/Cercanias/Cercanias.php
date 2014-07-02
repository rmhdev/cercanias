<?php

namespace Cercanias;

use Cercanias\Exception\InvalidArgumentException;
use Cercanias\Provider\ProviderInterface;

class Cercanias
{

    private $provider;

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function getRoute($routeId)
    {
        throw new InvalidArgumentException("Invalid routeId");
    }
}
