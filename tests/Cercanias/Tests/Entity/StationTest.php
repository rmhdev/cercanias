<?php

namespace Cercanias\Tests\Entity;

use Cercanias\Entity\Station;

class StationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getIdProvider
     */
    public function testGetId($id)
    {
        $station = new Station($id, "Irún", 61);

        $this->assertEquals($id, $station->getId());
    }

    public function getIdProvider()
    {
        return array(
            array(61),
            array(10),
            array("0123")
        );
    }

    /**
     * @dataProvider getInvalidIdProvider
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     */
    public function testInvalidId($invalidId)
    {
        new Station($invalidId, "Irún", 61);
    }

    public function getInvalidIdProvider()
    {
        return array(
            array(null),
            array(""),
        );
    }

    /**
     * @dataProvider getNameProvider
     */
    public function testGetName($name, $expected)
    {
        $city = new Station(1, $name, 61);

        $this->assertEquals($expected, $city->getName());
    }

    public function getNameProvider()
    {
        return array(
            array("Irún", "Irún"),
            array("Brinkola", "Brinkola"),
            array("Alegia de Oria                          ", "Alegia de Oria"),
        );
    }

    /**
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     */
    public function testNameShouldNotBeAnEmptyString()
    {
        new Station(1, "", 61);
    }

    public function testGetRouteId()
    {
        $station = new Station(1, "Irun", 61);
        $this->assertEquals(61, $station->getRouteId());
    }

    /**
     * @dataProvider getInvalidIdProvider
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     */
    public function testInvalidRouteId($routeId)
    {
        new Station(1, "Irun", $routeId);
    }
}
