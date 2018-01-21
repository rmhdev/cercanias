<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

require __DIR__ . '/../..' . '/vendor/autoload.php';

use Cercanias\HttpAdapter\CurlHttpAdapter;
use Cercanias\Provider\HorariosRenfeCom\Provider;
use Cercanias\Provider\TimetableQuery;

$query = new TimetableQuery();
$query
    ->setRoute(Provider::ROUTE_SEVILLA)
    ->setDeparture("40113")     // from "Cazalla-Constantina"
    ->setDestination("51200")   // to "Utrera"
    ->setDate(new \DateTime("now"));

$httpAdapter  = new CurlHttpAdapter();
$provider = new Provider($httpAdapter);

echo $httpAdapter->getContent($provider->generateTimetableUrl($query));
