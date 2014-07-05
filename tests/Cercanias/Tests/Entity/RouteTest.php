<?php

namespace Cercanias\Tests\Entity;

use Cercanias\Entity\Route;
use Cercanias\Entity\Station;

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
            array("Madrid     ", "Madrid"),
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
            array("   "),
        );
    }

    public function testAddStation()
    {
        $route = $this->createRoute("My route");
        $this->assertEquals(0, $route->countStations());

        $route->addStation(new Station(1, "Default", $route->getId()));
        $this->assertEquals(1, $route->countStations());
    }

    protected function createDefaultStation($id)
    {
        return new Station($id, "Default Station {$id}", 61);
    }

    /**
     * @expectedException \Cercanias\Exception\DuplicateKeyException
     */
    public function testAddRepeatedStation()
    {
        $route = $this->createRoute("My route");
        $station = new Station(1, "Default 1", $route->getId());
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
        $route->addStation(new Station(1, "Default 1", $route->getId()));
        $route->addStation(new Station(2, "Default 2", $route->getId()));
        $this->assertEquals(2, $route->getStations()->count());
    }

    public function testHasStation()
    {
        $route = $this->createRoute("My Route");
        $route->addStation(new Station(1, "Default 1", $route->getId()));

        $this->assertTrue($route->hasStation(1));
        $this->assertFalse($route->hasStation(2));
    }

    public function testGetStation()
    {
        $route = $this->createRoute("My Route");
        $station1 = new Station(1, "Default 1", $route->getId());
        $route->addStation($station1);

        $this->assertEquals($station1, $route->getStation(1));
    }

    /**
     * @expectedException \Cercanias\Exception\NotFoundException
     */
    public function testGetUnknownStation()
    {
        $route = $this->createRoute("My Route");
        $route->addStation(new Station(1, "Default 1", $route->getId()));
        $route->getStation(2);
    }
}
