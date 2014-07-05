<?php

namespace Cercanias\Tests\RouteParser\Web;

use Cercanias\Provider\HorariosRenfeCom\RouteParser;
use Cercanias\Tests\Provider\AbstractRouteParserTest;

class RouteParserTest extends AbstractRouteParserTest
{
    public function testParseSanSebastian()
    {
        $routeParser = $this->getRouteParser("route-sansebastian.html");

        //$route = $routeParser->getRoute();
        $this->assertEquals(61, $routeParser->getRouteId());
        $this->assertEquals("San Sebastián", $routeParser->getRouteName());

        $stations = $this->getSanSebastianStations();
        $this->assertEquals(sizeof($stations), $routeParser->getStations()->count());

        $keys = array();
        $names = array();
        foreach ($routeParser->getStations() as $station) {
            /* @var \Cercanias\Entity\Station $station */
            $keys[] = $station->getId();
            $names[] = $station->getName();
        }

        $this->assertEquals(array_keys($this->getSanSebastianStations()), $keys);
        $this->assertEquals(array_values($this->getSanSebastianStations()), $names);
    }

    protected function getRouteParser($filename)
    {
        return new RouteParser($this->getContentHtml($filename));
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

    public function testParseMadrid()
    {
        $routeParser = $this->getRouteParser("route-madrid.html");

        $this->assertEquals(10, $routeParser->getRouteId());
        $this->assertEquals("Madrid", $routeParser->getRouteName());
        $this->assertEquals(92, $routeParser->getStations()->count());
    }

    /**
     * @expectedException \Cercanias\Exception\NotFoundException
     * @expectedExceptionMessage No stations found in Route
     */
    public function testParseNoResults()
    {
        $this->getRouteParser("route-no-results.html");
    }
}
