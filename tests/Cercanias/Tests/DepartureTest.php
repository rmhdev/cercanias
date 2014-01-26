<?php

namespace Cercanias\Tests\Departure;

use Cercanias\Departure;

class DepartureTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDepartureTime()
    {
        $dateTime = new \DateTime("now");
        $departure = new Departure($dateTime);

        $this->assertEquals($dateTime, $departure->getDepartureTime());
    }
}
