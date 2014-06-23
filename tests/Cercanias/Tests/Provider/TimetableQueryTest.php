<?php

namespace Cercanias\Tests\Provider;

use Cercanias\Provider\TimetableQuery;
use Cercanias\Route;

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
            array("123", "123"),
            array(456, $route),
        );
    }

    public function testSetDepartureStationId()
    {
        $query = new TimetableQuery();
        $query->setDepartureStationId(456);

        $this->assertEquals(456, $query->getDepartureStationId());
    }

    public function testSetDestinationStationId()
    {
        $query = new TimetableQuery();
        $query->setDestinationStationId(789);

        $this->assertEquals(789, $query->getDestinationStationId());
    }

    public function testSetDate()
    {
        $query = new TimetableQuery();
        $date = new \DateTime("now");
        $query->setDate($date);

        $this->assertEquals($date, $query->getDate());
    }


}
