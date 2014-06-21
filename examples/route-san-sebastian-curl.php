<?php

require_once __DIR__ . '/..' . '/vendor/autoload.php';

use Cercanias\Provider\Web\Provider;
use Cercanias\HttpAdapter\CurlHttpAdapter;
use Cercanias\Station;

$httpAdapter = new CurlHttpAdapter();
$provider = new Provider($httpAdapter);

$route = $provider->getRoute(Provider::ROUTE_SAN_SEBASTIAN);

echo "Route 'San Sebastián': \n";

foreach ($route->getStations() as $station) {
    /* @var Station $station */
    echo sprintf(" - [%5d] %s\n", $station->getId(), $station->getName());
}

echo sprintf("results: %d\n", $route->getStations()->count());
