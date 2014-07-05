<?php

namespace Cercanias\Tests\Cercanias;

use Cercanias\Cercanias;
use Cercanias\Provider\RouteQuery;
use Cercanias\Provider\TimetableQuery;

use Cercanias\Entity\Route;
use Cercanias\Provider\RouteParserInterface;
use Cercanias\Provider\RouteQueryInterface;
use Cercanias\Provider\TimetableParserInterface;
use Cercanias\Provider\TimetableQueryInterface;
use Cercanias\Provider\ProviderInterface;

class CercaniasTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @dataProvider getRouteReturnsValidInstanceProvider
     */
    public function testGetRouteReturnsValidInstance($route)
    {
        $provider = $this->getMockProviderReturnsRouteParser();
        $cercanias = new Cercanias($provider);
        $route = $cercanias->getRoute($route);

        $this->assertInstanceOf('\Cercanias\Entity\Route', $route);
    }

    public function getRouteReturnsValidInstanceProvider()
    {
        return array(
            array(12),
        );
    }

    public function testGetRoute()
    {
        $provider = $this->getMockProviderReturnsRouteParser();
        $cercanias = new Cercanias($provider);
        $route = $cercanias->getRoute(1);

        $this->assertEquals(1, $route->getId());
    }

    public function testGetTimetableReturnsValidInstance()
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

    /**
     * @return ProviderInterface
     */
    protected function getMockProviderReturnsRouteParser()
    {
        $mock = $this->getMock('Cercanias\Provider\ProviderInterface');
        $mock->expects($this->once())
            ->method('getRouteParser')
            ->willReturn(new MockRouteParser())
        ;

        return $mock;
    }

    /**
     * @return ProviderInterface
     */
    protected function getMockProviderReturnsTimetableParser()
    {
        $mock = $this->getMock('Cercanias\Provider\ProviderInterface');
        $mock->expects($this->once())
            ->method('getTimetableParser')
            ->willReturn(new MockTimetableParser())
        ;

        return $mock;
    }
}

class MockProvider implements ProviderInterface
{
    public function __construct($name)
    {

    }

    public function getName()
    {

    }

    public function generateRouteUrl(RouteQueryInterface $query)
    {

    }

    public function generateTimetableUrl(TimetableQueryInterface $query)
    {

    }

    public function getRouteParser(RouteQueryInterface $query)
    {

    }

    public function getTimetableParser(TimetableQueryInterface $query)
    {

    }
}

class MockRouteParser implements RouteParserInterface
{
    /**
     * Parse html and create a Route object
     * @return Route
     */
    public function getRoute()
    {

    }

    /**
     * @return int
     */
    public function getRouteId()
    {
        return 1;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return "Test Route";
    }

    /**
     * @return \ArrayIterator
     */
    public function getStations()
    {
        return new \ArrayIterator();
    }
}

class MockTimetableParser implements TimetableParserInterface
{
    public function getDate()
    {
        return new \DateTime("now");
    }

    public function getDepartureName()
    {
        return "Departure station";
    }

    public function getDestinationName()
    {
        return "Destination station";
    }

    public function getTransferName()
    {
        return "Transfer station";
    }

    public function getTrips()
    {
        return new \ArrayIterator();
    }
}
