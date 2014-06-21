<?php

require_once __DIR__ . '/..' . '/vendor/autoload.php';

use Cercanias\HttpAdapter\CurlHttpAdapter;
use Cercanias\Provider\Web\Provider;
use Cercanias\Station;
use Cercanias\Trip;

$httpAdapter = new CurlHttpAdapter();
$provider = new Provider($httpAdapter);

$route = $provider->getRoute(Provider::ROUTE_SAN_SEBASTIAN);
$stations = $route->getStations();
$firstStation = $stations->current();
$stations->seek($stations->count() - 1);
$lastStation = $stations->current();
/* @var Station $firstStation */
/* @var Station $lastStation */
$date = new DateTime("now");

$timetable = $provider->getTimetable($firstStation, $lastStation, $date);

echo "Timetable 'San Sebastián': \n";
echo sprintf(" - departure:  '%s'\n", $firstStation->getName());
echo sprintf(" - arrival:    '%s'\n", $lastStation->getName());
echo sprintf(" - date:       %s\n", $date->format("Y-m-d"));

$pattern = "%4s  %6s  %6s  %4s\n";
echo sprintf($pattern, "LINE", "DEPART", "ARRIVE", "TIME");
foreach ($timetable->getTrips() as $trip) {
    /* @var Trip $trip*/
    echo sprintf($pattern,
        $trip->getDepartureTrain()->getLine(),
        $trip->getDepartureTrain()->getDepartureTime()->format("H:i"),
        $trip->getDepartureTrain()->getArrivalTime()->format("H:i"),
        $trip->getDepartureTrain()->getDuration()->format("%h:%i")
    );
}
