<?php

namespace Cercanias\Tests\Route;

use Cercanias\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getNameProvider
     */
    public function testGetName($name, $expected)
    {
        $route = new Route($name);
        $this->assertEquals($expected, $route->getName());
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
        new Route($invalidName);
    }

    public function getInvalidNameProvider()
    {
        return array(
            array(""),
            array(123),
        );
    }
}
