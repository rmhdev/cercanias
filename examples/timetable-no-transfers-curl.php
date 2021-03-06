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
use Cercanias\Provider\TimetableQuery;
use Cercanias\Cercanias;
use Cercanias\Entity\Trip;

$query = new TimetableQuery();
$query
    ->setRoute(Provider::ROUTE_SAN_SEBASTIAN)
    ->setDeparture("11305")     // from Brincola
    ->setDestination("11600")   // to Irun
    ->setDate(new \DateTime("now"));

$httpAdapter  = new CurlHttpAdapter();
$provider     = new Provider($httpAdapter);
$cercanias    = new Cercanias($provider);
$timetable    = $cercanias->getTimetable($query);

echo "Timetable 'San Sebastián': \n";
echo sprintf(" - departure:     '%s'\n", $timetable->getDeparture()->getName());
echo sprintf(" - destination:   '%s'\n", $timetable->getDestination()->getName());
echo sprintf(" - date:          %s\n", $query->getDate()->format("Y-m-d"));

$pattern = "%4s  %6s  %6s  %4s\n";
echo sprintf($pattern, "LINE", "DEPART", "ARRIVE", "TIME");
foreach ($timetable->getTrips() as $trip) {
    /* @var Trip $trip*/
    echo sprintf(
        $pattern,
        $trip->getDepartureTrain()->getLine(),
        $trip->getDepartureTrain()->getDepartureTime()->format("H:i"),
        $trip->getDepartureTrain()->getArrivalTime()->format("H:i"),
        $trip->getDepartureTrain()->getDuration()->format("%h:%i")
    );
}
