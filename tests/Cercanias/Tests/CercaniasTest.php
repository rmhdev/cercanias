<?php

namespace Cercanias\Tests\Cercanias;

use Cercanias\Cercanias;
use Cercanias\Provider\RouteQuery;
use Cercanias\Provider\TimetableQuery;

class CercaniasTest extends AbstractCercaniasTest
{
    /**
     * @dataProvider getInvalidRouteProvider
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid routeId
     */
    public function testGetRouteForNullId($route)
    {
        $provider = new MockProvider("default");
        $cercanias = new Cercanias($provider);
        $cercanias->getRoute($route);
    }

    public function getInvalidRouteProvider()
    {
        return array(
            array(null),
            array(""),
            array(new RouteQuery()),
        );
    }

    public function testGetRoute()
    {
        $provider = $this->getMockProviderReturnsRouteParser();
        $cercanias = new Cercanias($provider);
        $route = $cercanias->getRoute(1);

        $this->assertInstanceOf('\Cercanias\Entity\Route', $route);
        $this->assertEquals(1, $route->getId());
    }

    public function testGetTimetable()
    {
        $query = new TimetableQuery();
        $query
            ->setRoute(1)
            ->setDeparture("123")
            ->setDestination("456");
        $provider = $this->getMockProviderReturnsTimetableParser();
        $cercanias = new Cercanias($provider);

        $this->assertInstanceOf('\Cercanias\Entity\Timetable', $cercanias->getTimetable($query));
    }
}
