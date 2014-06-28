<?php

require __DIR__ . '/..' . '/vendor/autoload.php';

use Cercanias\Provider\Web\Provider;
use Cercanias\HttpAdapter\BuzzHttpAdapter;
use Cercanias\Station;

$httpAdapter  = new BuzzHttpAdapter();
$provider     = new Provider($httpAdapter);
$route        = $provider->getRoute(Provider::ROUTE_BARCELONA);

echo "Route 'Barcelona': \n";

foreach ($route->getStations() as $station) {
    /* @var Station $station */
    echo sprintf(" - [%5s] %s\n", $station->getId(), $station->getName());
}

echo sprintf("results: %d\n", $route->getStations()->count());
