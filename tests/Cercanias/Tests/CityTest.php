<?php

namespace Cercanias\Tests\City;

use Cercanias\City;

class CityTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getIdProvider
     */
    public function testGetId($id)
    {
        $city = new City($id, "Irún");

        $this->assertEquals($id, $city->getId());
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
        new City($invalidId, "Irún");
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
        $city = new City(1, $name);

        $this->assertEquals($expected, $city->getName());
    }

    public function getNameProvider()
    {
        return array(
            array("Irún", "Irún"),
            array("Brinkola", "Brinkola"),
        );
    }

    /**
     * @dataProvider getInvalidNameProvider
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     */
    public function testNameShouldNotBeAnEmptyString($name)
    {
        new City(1, $name);
    }

    public function getInvalidNameProvider()
    {
        return array(
            array(""),
            array(123),
        );
    }

}
