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

        $stations = $this->getSanSebastianStations();
        $this->assertEquals(sizeof($stations), $route->countStations());

        foreach ($stations as $stationId => $stationName) {
            $this->assertTrue($route->hasStation($stationId));
            $station = $route->getStation($stationId);
            $this->assertEquals($stationName, $station->getName());
        }
    }

    protected function getSanSebastianStations()
    {
        return array(
            "11409" => "Alegia de Oria",
            "11505" => "Andoain",
            "11504" => "Andoain-Centro",
            "11502" => "Anoeta",
            "11513" => "Ategorrieta",
            "11404" => "Beasain",
            "11503" => "Billabona-Zizurkil",
            "11305" => "Brincola",
            "11511" => "Donostia-San Sebastian",
            "11512" => "Gros",
            "11508" => "Hernani",
            "11507" => "Hernani-Centro",
            "11514" => "Herrera",
            "11408" => "Ikaztegieta",
            "11522" => "Intxaurrondo",
            "11600" => "Irun",
            "11406" => "Itsasondo",
            "11306" => "Legazpi",
            "11407" => "Legorreta",
            "11516" => "Lezo-Renteria",
            "11510" => "Loiola",
            "11509" => "Martutene",
            "11405" => "Ordizia",
            "11402" => "Ormaiztegui",
            "11515" => "Pasaia",
            "11500" => "Tolosa",
            "11501" => "Tolosa Centro",
            "11506" => "Urnieta",
            "11518" => "Ventas",
            "11400" => "Zumarraga",
        );
    }

}
