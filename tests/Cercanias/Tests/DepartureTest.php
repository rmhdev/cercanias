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

        $dateTime2 = new \DateTime("tomorrow +5 hours");
        $departure2 = new Departure($dateTime2);

        $this->assertEquals($dateTime2, $departure2->getDepartureTime());
    }
}
