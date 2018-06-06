<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

require __DIR__ . '/..' . '/vendor/autoload.php';

use Cercanias\HttpAdapter\CurlHttpAdapter;
use Cercanias\Provider\HorariosRenfeCom\Provider;
use Cercanias\Cercanias;

$httpAdapter  = new CurlHttpAdapter();          // 1. HttpAdapter
$provider     = new Provider($httpAdapter);     // 2. Provider
$cercanias    = new Cercanias($provider);       // 3. Cercanias

$route = $cercanias->getRoute(Provider::ROUTE_SAN_SEBASTIAN);

echo "ROUTE: san sebastian\n";
$i = 0;
foreach ($route->getStations() as $station) {
    echo sprintf(' %2d. [%2d] %s' . "\n", $i +=1, $station->getId(), $station->getName());
}
