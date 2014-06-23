<?php

namespace Cercanias\Tests\Provider;

use Cercanias\Provider\TimetableQuery;
use Cercanias\Route;
use Cercanias\Station;

class TimetableQueryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider routeProvider
     */
    public function testSetRouteId($expectedRouteId, $routeId)
    {
        $query = new TimetableQuery();
        $query->setRoute($routeId);

        $this->assertEquals($expectedRouteId, $query->getRouteId());
    }

    public function routeProvider()
    {
        $route = new Route(456, "Default");
        return array(
            array(1, 1),
            array(456, $route),
        );
    }

    /**
     * @dataProvider stationProvider
     */
    public function testSetDeparture($expectedStationId, $station)
    {
        $query = new TimetableQuery();
        $query->setDeparture($station);

        $this->assertEquals($expectedStationId, $query->getDepartureStationId());
    }

    public function stationProvider()
    {
        $station = new Station(123, "Default station", 1);
        return array(
            array(1, 1),
            array(123, $station),
        );
    }

    /**
     * @dataProvider stationProvider
     */
    public function testSetDestination($expectedStationId, $station)
    {
        $query = new TimetableQuery();
        $query->setDestination($station);

        $this->assertEquals($expectedStationId, $query->getDestinationStationId());
    }

    public function testSetDate()
    {
        $query = new TimetableQuery();
        $this->assertNull($query->getDate());

        $date = new \DateTime("now");
        $query->setDate($date);
        $this->assertEquals($date, $query->getDate());
    }

}
