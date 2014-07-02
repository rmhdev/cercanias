<?php

namespace Cercanias\Tests\Provider\Web;

use Cercanias\Entity\Route;
use Cercanias\Provider\Web\RouteQuery;

class RouteQueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider routeProvider
     */
    public function testSetRouteId($expectedRouteId, $routeId)
    {
        $query = new RouteQuery();
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

    public function testIsValid()
    {
        $query = new RouteQuery();
        $this->assertFalse($query->isValid());

        $query->setRoute(123);
        $this->assertTrue($query->isValid());
    }

    /**
     * @dataProvider getGenerateUrlProvider
     */
    public function testGenerateUrl($expectedUrl, $route)
    {
        $query = new RouteQuery();
        $query->setRoute($route);

        $this->assertEquals($expectedUrl, $query->generateUrl());
    }

    public function getGenerateUrlProvider()
    {
        $url = "http://horarios.renfe.com";
        $url .= "/cer/hjcer300.jsp?NUCLEO=%s&CP=NO&I=s";
        $route = new Route(60, "Default route");

        return array(
            array(sprintf($url, 123), 123),
            array(sprintf($url, 60), $route)
        );
    }
}
