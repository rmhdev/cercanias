<?php

namespace Cercanias\Tests\Route;

use Cercanias\Route;

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
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     */
    public function testInvalidId()
    {
        new Route(-1, "San Sebastián");
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
}
