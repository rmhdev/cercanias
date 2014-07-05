<?php

namespace Cercanias\Tests\Provider\Web;

use Cercanias\Entity\Route;
use Cercanias\Provider\RouteQuery;
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

//    /**
//     * @expectedException \Cercanias\Exception\InvalidArgumentException
//     * @expectedExceptionMessage Invalid routeId
//     */
//    public function testGetRouteForNullId()
//    {
//        $provider = new Provider($this->getMockAdapter($this->never()));
//        $provider->getRoute(null);
//    }
//
//    /**
//     * @expectedException \Cercanias\Exception\InvalidArgumentException
//     * @expectedExceptionMessage Invalid routeId
//     */
//    public function testGetRouteForNotNumberId()
//    {
//        $provider = new Provider($this->getMockAdapter($this->never()));
//        $provider->getRoute("hi");
//    }
//
//    /**
//     * @expectedException \Cercanias\Exception\InvalidArgumentException
//     * @expectedExceptionMessage RouteQuery is not valid
//     */
//    public function testGetRouteForNotValidQuery()
//    {
//        $provider = new Provider($this->getMockAdapter($this->never()));
//        $query = new RouteQuery();
//        $provider->getRoute($query);
//    }
//
//
//    public function testGetRouteForQuery()
//    {
//        $mockAdapter = $this->getMockAdapterReturnsFixtureContent("route-sansebastian.html");
//        $provider = new Provider($mockAdapter);
//        $query = new RouteQuery();
//        $query->setRoute(Provider::ROUTE_SAN_SEBASTIAN);
//        $route = $provider->getRoute($query);
//
//        $this->assertEquals(Provider::ROUTE_SAN_SEBASTIAN, $route->getId());
//    }
//
//    public function testGetRouteSanSebastian()
//    {
//        $mockAdapter = $this->getMockAdapterReturnsFixtureContent("route-sansebastian.html");
//        $provider = new Provider($mockAdapter);
//        $route = $provider->getRoute(Provider::ROUTE_SAN_SEBASTIAN);
//
//        $this->assertEquals(Provider::ROUTE_SAN_SEBASTIAN, $route->getId());
//        $this->assertEquals("San Sebastián", $route->getName());
//        $this->assertEquals(30, $route->countStations());
//    }

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
        $parser = $provider->getTimetableParser($query);

        $this->assertEquals(20, $parser->getTrips()->count());
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

    /**
     * @dataProvider getGenerateRouteUrlProvider
     */
    public function testGenerateRouteUrl($expectedUrl, $route)
    {
        $query = new RouteQuery();
        $query->setRoute($route);
        $provider = new Provider($this->getMockAdapter($this->never()));

        $this->assertEquals($expectedUrl, $provider->generateRouteUrl($query));
    }

    public function getGenerateRouteUrlProvider()
    {
        $url = "http://horarios.renfe.com";
        $url .= "/cer/hjcer300.jsp?NUCLEO=%s&CP=NO&I=s";
        $route = new Route(60, "Default route");

        return array(
            array(sprintf($url, 123), 123),
            array(sprintf($url, 60), $route)
        );
    }

    /**
     * @dataProvider getGenerateTimetableUrlProvider
     */
    public function testGenerateTimetableUrl($expectedUrl, $values)
    {
        $query = new TimetableQuery();
        $query
            ->setRoute($values["route"])
            ->setDeparture($values["from"])
            ->setDestination($values["to"])
        ;
        if ($values["date"]) {
            $query->setDate($values["date"]);
        }
        $provider = new Provider($this->getMockAdapter($this->never()));

        $this->assertEquals($expectedUrl, $provider->generateTimetableUrl($query));
    }

    public function getGenerateTimetableUrlProvider()
    {
        $url = "http://horarios.renfe.com/cer/hjcer310.jsp";
        $url .= "?nucleo=%s&i=s&cp=NO&o=%s&d=%s&df=%s&ho=00&hd=26&TXTInfo=";
        $today = new \DateTime("now");

        return array(
            array(
                sprintf($url, "123", "808", "909", "20140701"),
                array("route" => 123, "from" => "808", "to" => "909", "date" => new \DateTime("2014-07-01"))
            ),
            array(
                sprintf($url, "123", "abc", "def", $today->format("Ymd")),
                array("route" => 123, "from" => "abc", "to" => "def", "date" => null)
            )
        );
    }

    /**
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage RouteQuery is not valid
     */
    public function testGetRouteParserForNonValidQuery()
    {
        $provider = new Provider($this->getMockAdapter($this->never()));
        $query = new RouteQuery();
        $provider->getRouteParser($query);
    }

    public function testGetRouteParser()
    {
        $mockAdapter = $this->getMockAdapterReturnsFixtureContent("route-sansebastian.html");
        $provider = new Provider($mockAdapter);
        $query = new RouteQuery();
        $query->setRoute(Provider::ROUTE_SAN_SEBASTIAN);
        $routeParser = $provider->getRouteParser($query);

        $this->assertInstanceOf('\Cercanias\Provider\Web\RouteParser', $routeParser);
        $this->assertEquals(61, $routeParser->getRouteId());
        $this->assertEquals("San Sebastián", $routeParser->getRouteName());
        $this->assertEquals(30, $routeParser->getStations()->count());
    }
}
