<?php

namespace Cercanias\Tests\Cercanias;

use Cercanias\Cercanias;
use Cercanias\Entity\Route;
use Cercanias\Provider\ProviderInterface;
use Cercanias\Provider\RouteParserInterface;
use Cercanias\Provider\RouteQueryInterface;
use Cercanias\Provider\TimetableQueryInterface;
use Cercanias\Provider\RouteQuery;
use Cercanias\HttpAdapter\HttpAdapterInterface;

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
        $provider = new MockProvider("default");
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
}

class MockProvider implements ProviderInterface
{
    public function __construct($name)
    {

    }

    public function getName()
    {

    }

    public function getRoute($routeId)
    {

    }

    public function getTimetable(TimetableQueryInterface $query)
    {

    }

    public function generateRouteUrl(RouteQueryInterface $query)
    {

    }

    public function generateTimetableUrl(TimetableQueryInterface $query)
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
        // TODO: Implement getRoute() method.
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
