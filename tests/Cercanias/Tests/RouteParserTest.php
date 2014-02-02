<?php

namespace Cercanias\Tests\RouteParser;

use Cercanias\RouteParser;

class RouteParserTest extends \PHPUnit_Framework_TestCase
{

    public function testGetRoute()
    {
        $routeParser = $this->getRouteParser("route-sansebastian.html");

        $this->assertInstanceOf("Cercanias\Route", $routeParser->getRoute());
    }

    protected function getRouteParser($filename)
    {
        $html = file_get_contents(__DIR__ . "/../Fixtures/" . $filename);

        return new RouteParser($html);
    }

    public function testGetRouteSanSebastian()
    {
        $routeParser = $this->getRouteParser("route-sansebastian.html");

        $route = $routeParser->getRoute();
        $this->assertEquals(61, $route->getId());
        $this->assertEquals("San Sebastián", $route->getName());
        $this->assertEquals(30, $route->countStations());

        $this->assertTrue($route->hasStation(11409));
        $station = $route->getStation(11409);
        $this->assertEquals("Alegia de Oria", $station->getName());
    }

}
