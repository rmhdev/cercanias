<?php

namespace Cercanias\Tests\City;

use Cercanias\City;

class CityTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getNameProvider
     */
    public function testGetName($name, $expected)
    {
        $city = new City($name);

        $this->assertEquals($expected, $city->getName());
    }

    public function getNameProvider()
    {
        return array(
            array("Irún", "Irún"),
            array("Brinkola", "Brinkola"),
        );
    }

}
