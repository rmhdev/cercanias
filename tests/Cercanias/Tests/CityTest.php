<?php

namespace Cercanias\Tests\City;

use Cercanias\City;

class CityTest extends \PHPUnit_Framework_TestCase
{

    public function testGetName()
    {
        $city = new City("Irún");

        $this->assertEquals("Irún", $city->getName());
    }

}
