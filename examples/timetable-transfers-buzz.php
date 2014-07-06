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
use Cercanias\Provider\TimetableQuery;
use Cercanias\Provider\HorariosRenfeCom\Provider;
use Cercanias\HttpAdapter\BuzzHttpAdapter;
use Cercanias\Entity\Trip;
use Cercanias\Entity\Train;

$query = new TimetableQuery();
$query
    ->setRoute(Provider::ROUTE_BARCELONA)
    ->setDeparture("79600")     // Arenys de Mar
    ->setDestination("71802");  // Barcelona-Passeig de Gràcia

$httpAdapter  = new BuzzHttpAdapter();
$provider     = new Provider($httpAdapter);
$cercanias    = new Cercanias($provider);
$timetable    = $cercanias->getTimetable($query);

echo "Timetable 'Barcelona': \n";
echo sprintf(" - departure:     '%s'\n", $timetable->getDeparture()->getName());
echo sprintf(" - destination:   '%s'\n", $timetable->getDestination()->getName());
echo sprintf(" - transfer:      '%s'\n", $timetable->getTransferName());
echo sprintf(" - date:          %s\n", $query->getDate()->format("Y-m-d"));

$pattern = "%4s  %6s  %6s  %6s  %4s  %6s\n";
echo sprintf($pattern, "LINE", "DEPART", "ARRIVE", "DEPART", "LINE", "ARRIVE");

foreach ($timetable->getTrips() as $trip) {
    foreach (prepareResults($trip) as $item) {
        echo sprintf(
            $pattern,
            $item["line"],
            $item["departure"],
            $item["arrival"],
            $item["transfer_departure"],
            $item["transfer_line"],
            $item["transfer_arrival"]
        );
    }
}

function prepareResults(Trip $trip)
{
    $result = array(
        "line"                => $trip->getDepartureTrain()->getLine(),
        "departure"           => $trip->getDepartureTrain()->getDepartureTime()->format("H:i"),
        "arrival"             => $trip->getDepartureTrain()->getArrivalTime()->format("H:i"),
        "transfer_departure"  => "",
        "transfer_arrival"    => "",
        "transfer_line"       => "",
    );
    $transfers = array();
    if ($trip->hasTransfer()) {
        $i = 0;
        foreach ($trip->getTransferTrains() as $transfer) {
            /* @var Train $transfer */
            if ($i == 0) {
                $result["transfer_departure"] = $transfer->getDepartureTime()->format("H:i");
                $result["transfer_arrival"]   = $transfer->getArrivalTime()->format("H:i");
                $result["transfer_line"]      = $transfer->getLine();
                $i += 1;
            } else {
                $transfers[] = array(
                    "line"                => "",
                    "departure"           => "",
                    "arrival"             => "",
                    "transfer_departure"  => $transfer->getDepartureTime()->format("H:i"),
                    "transfer_arrival"    => $transfer->getArrivalTime()->format("H:i"),
                    "transfer_line"       => $transfer->getLine(),
                );
            }
        }
    }
    $results = array($result);
    if ($transfers) {
        $results = array_merge($results, $transfers);
    }
    return $results;
}
