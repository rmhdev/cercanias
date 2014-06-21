<?php

namespace Cercanias\Provider;

use Cercanias\Route;

interface RouteParserInterface
{
    /**
     * Parse html and create a Route object
     * @return Route
     */
    public function getRoute();

}
