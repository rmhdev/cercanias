<?php

namespace Cercanias\Tests\Provider\Web;

use Cercanias\Provider\TimetableQuery;
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
     * @expectedExceptionMessage Could not execute query http://horarios.renfe.com/cer/hjcer300.jsp?NUCLEO=&CP=NO&I=s
     */
    public function testGetRouteForNullId()
    {
        $provider = new Provider($this->getMockAdapter($this->never()));
        $provider->getRoute(null);
    }

    /**
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage Could not execute query http://horarios.renfe.com/cer/hjcer300.jsp?NUCLEO=hi&CP=NO&I=s
     */
    public function testGetRouteForNotNumberId()
    {
        $provider = new Provider($this->getMockAdapter($this->never()));
        $provider->getRoute("hi");
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
}
