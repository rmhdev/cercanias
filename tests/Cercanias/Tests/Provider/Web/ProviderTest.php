<?php

namespace Cercanias\Tests\Provider\Web;

use Cercanias\Provider\Web\Provider;
use Cercanias\Route;
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
        $route = new Route(1, "Default route");
        $route->addNewStation(1, "Irun");
        $route->addNewStation(2, "Brincola");
        $date = new \DateTime("2014-02-10");
        $timetable = $provider->getTimetable($route->getStation(1), $route->getStation(2), $date);

        $this->assertEquals(20, $timetable->getTrips()->count());
    }
}