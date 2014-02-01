<?php

namespace Cercanias\Tests\Route;

use Cercanias\Route;
use Cercanias\Station;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getIdProvider
     */
    public function testGetId($id)
    {
        $route = new Route($id, "San Sebastián");

        $this->assertEquals($id, $route->getId());
    }

    public function getIdProvider()
    {
        return array(
            array(61),
            array(10),
        );
    }

    /**
     * @dataProvider getInvalidIdProvider
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     */
    public function testInvalidId($invalidId)
    {
        new Route($invalidId, "San Sebastián");
    }

    public function getInvalidIdProvider()
    {
        return array(
            array(-1),
            array(0),
            array("123"),
        );
    }



    /**
     * @dataProvider getNameProvider
     */
    public function testGetName($name, $expected)
    {
        $route = $this->createRoute($name);
        $this->assertEquals($expected, $route->getName());
    }

    protected function createRoute($name)
    {
        return new Route(123, $name);
    }

    public function getNameProvider()
    {
        return array(
            array("San Sebastián", "San Sebastián"),
            array("Asturias", "Asturias"),
        );
    }

    /**
     * @dataProvider getInvalidNameProvider
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     */
    public function testInvalidName($invalidName)
    {
        $this->createRoute($invalidName);
    }

    public function getInvalidNameProvider()
    {
        return array(
            array(""),
            array(123),
        );
    }

    public function testAddStation()
    {
        $route = $this->createRoute("My route");
        $this->assertEquals(0, $route->countStations());

        $station1 = $this->createDefaultStation(1);
        $route->addStation($station1);
        $this->assertEquals(1, $route->countStations());
    }

    protected function createDefaultStation($id)
    {
        return new Station($id, "Default Station {$id}");
    }

    /**
     * @expectedException \Cercanias\Exception\DuplicateKeyException
     */
    public function testAddRepeatedStation()
    {
        $route = $this->createRoute("My route");
        $station = $this->createDefaultStation(1);
        $route->addStation($station);
        $route->addStation($station);
    }

    public function testGetStationsInEmptyRoute()
    {
        $route = $this->createRoute("My Route");
        $this->assertInstanceOf("ArrayIterator", $route->getStations());
        $this->assertEquals(0, $route->getStations()->count());
    }

    public function testGetStationsInNormalRoute()
    {
        $route = $this->createRoute("My Route");
        $station1 = $this->createDefaultStation(1);
        $route->addStation($station1);
        $station2 = $this->createDefaultStation(2);
        $route->addStation($station2);
        $this->assertEquals(2, $route->getStations()->count());
    }
}
