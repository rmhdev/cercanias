<?php

namespace Cercanias\Tests\Timetable;

use Cercanias\Station;
use Cercanias\Timetable;

class TimetableTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDeparture()
    {
        $departure = new Station(1, "My station");
        $timetable = new Timetable($departure);

        $this->assertEquals($departure, $timetable->getDeparture());
    }
}