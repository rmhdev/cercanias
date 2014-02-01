<?php

namespace Cercanias\Tests\City;

use Cercanias\City;

class CityTest extends \PHPUnit_Framework_TestCase
{

    public function testGetName()
    {
        $city = new City("Irún");

        $this->assertEquals("Irún", $city->getName());

        $city2 = new City("Brinkola");

        $this->assertEquals("Brinkola", $city2->getName());
    }

}
