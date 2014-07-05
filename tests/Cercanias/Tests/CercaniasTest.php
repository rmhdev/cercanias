<?php

namespace Cercanias\Tests\Cercanias;

use Cercanias\Cercanias;
use Cercanias\Provider\ProviderInterface;
use Cercanias\Provider\RouteQueryInterface;
use Cercanias\Provider\TimetableQueryInterface;
use Cercanias\Provider\RouteQuery;

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
