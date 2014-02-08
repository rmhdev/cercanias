<?php

namespace Cercanias\Tests\Timetable;

use Cercanias\Station;
use Cercanias\Timetable;

class TimetableTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDeparture()
    {
        $departure = new Station(1, "My station");
        $destination = new Station(2, "My destination");
        $timetable = new Timetable($departure, $destination);

        $this->assertEquals($departure, $timetable->getDeparture());
    }

    public function testGetDestination()
    {
        $departure = new Station(1, "My departure");
        $destination = new Station(2, "My destination");
        $timetable = new Timetable($departure, $destination);

        $this->assertEquals($destination, $timetable->getDestination());
    }

    public function testGetTrips()
    {
        $departure = new Station(1, "My departure");
        $destination = new Station(2, "My destination");
        $timetable = new Timetable($departure, $destination);
        $empty = new \ArrayIterator();

        $this->assertEquals($empty, $timetable->getTrips());
    }

}
