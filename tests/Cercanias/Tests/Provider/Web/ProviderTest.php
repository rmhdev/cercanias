<?php

namespace Cercanias\Tests\Provider\Web;

use Cercanias\Provider\Web\RouteQuery;
use Cercanias\Provider\Web\TimetableQuery;
use Cercanias\Provider\Web\Provider;
use Cercanias\Tests\Provider\AbstractProviderTest;

class ProviderTest extends AbstractProviderTest
{

    public function testGetName()
    {
        $provider = new Provider($this->getMockAdapter($this->never()));
        $this->assertEquals("web_provider", $provider->getName());
    }

    /**
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid routeId
     */
    public function testGetRouteForNullId()
    {
        $provider = new Provider($this->getMockAdapter($this->never()));
        $provider->getRoute(null);
    }

    /**
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid routeId
     */
    public function testGetRouteForNotNumberId()
    {
        $provider = new Provider($this->getMockAdapter($this->never()));
        $provider->getRoute("hi");
    }

    /**
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage RouteQuery is not valid
     */
    public function testGetRouteForNotValidQuery()
    {
        $provider = new Provider($this->getMockAdapter($this->never()));
        $query = new RouteQuery();
        $provider->getRoute($query);
    }


    public function testGetRouteForQuery()
    {
        $mockAdapter = $this->getMockAdapterReturnsFixtureContent("route-sansebastian.html");
        $provider = new Provider($mockAdapter);
        $query = new RouteQuery();
        $query->setRoute(Provider::ROUTE_SAN_SEBASTIAN);
        $route = $provider->getRoute($query);

        $this->assertEquals(Provider::ROUTE_SAN_SEBASTIAN, $route->getId());
    }

    public function testGetRouteSanSebastian()
    {
        $mockAdapter = $this->getMockAdapterReturnsFixtureContent("route-sansebastian.html");
        $provider = new Provider($mockAdapter);
        $route = $provider->getRoute(Provider::ROUTE_SAN_SEBASTIAN);

        $this->assertEquals(Provider::ROUTE_SAN_SEBASTIAN, $route->getId());
        $this->assertEquals("San Sebastián", $route->getName());
        $this->assertEquals(30, $route->countStations());
    }

    public function testGetTimetableSanSebastian()
    {
        $provider = new Provider($this->getMockAdapterReturnsFixtureContent("timetable-sansebastian.html"));
        $query = new TimetableQuery();
        $query
            ->setRoute(1)
            ->setDeparture(123)
            ->setDestination(456)
            ->setDate(new \DateTime("2014-02-10"))
        ;
        $timetable = $provider->getTimetable($query);

        $this->assertEquals(20, $timetable->getTrips()->count());
    }

    /**
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage TimetableQuery is not valid
     */
    public function testGetTimetableWithNotValidQuery()
    {
        $provider = new Provider($this->getMockAdapter($this->never()));
        $query = new TimetableQuery();
        $query->setRoute(1);
        $provider->getTimetable($query);
    }
}
