<?php

namespace Cercanias\Tests\Route;

use Cercanias\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    public function testGetId()
    {
        $route = new Route(61, "San Sebastián");

        $this->assertEquals(61, $route->getId());
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
