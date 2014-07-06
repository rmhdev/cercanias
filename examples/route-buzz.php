<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

require __DIR__ . '/..' . '/vendor/autoload.php';

use Cercanias\Cercanias;
use Cercanias\Provider\HorariosRenfeCom\Provider;
use Cercanias\HttpAdapter\BuzzHttpAdapter;
use Cercanias\Entity\Station;

$httpAdapter  = new BuzzHttpAdapter();
$provider     = new Provider($httpAdapter);
$cercanias    = new Cercanias($provider);
$route        = $cercanias->getRoute(Provider::ROUTE_BARCELONA);

echo "Route 'Barcelona': \n";

foreach ($route->getStations() as $station) {
    /* @var Station $station */
    echo sprintf(" - [%5s] %s\n", $station->getId(), $station->getName());
}

echo sprintf("results: %d\n", $route->getStations()->count());
