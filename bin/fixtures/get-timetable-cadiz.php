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
    ->setRoute(Provider::ROUTE_CADIZ)
    ->setDeparture("51405")     // from "Cádiz"
    ->setDestination("51205")   // to "Aeropuerto de Jerez"
    ->setDate(new \DateTime("now"));

$httpAdapter  = new CurlHttpAdapter();
$provider = new Provider($httpAdapter);

echo $httpAdapter->getContent($provider->generateTimetableUrl($query));
