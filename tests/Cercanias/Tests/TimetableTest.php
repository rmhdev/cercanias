<?php

namespace Cercanias\Tests\Timetable;

use Cercanias\Station;
use Cercanias\Timetable;
use Cercanias\Trip;

class TimetableTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDeparture()
    {
        $timetable = $this->createTimetable();

        $this->assertEquals($this->createDepartureStation(), $timetable->getDeparture());
    }

    public function testGetDestination()
    {
        $timetable = $this->createTimetable();

        $this->assertEquals($this->createDestinationStation(), $timetable->getDestination());
    }

    public function testGetTrips()
    {
        $timetable = $this->createTimetable();
        $empty = new \ArrayIterator();

        $this->assertEquals($empty, $timetable->getTrips());
    }

    protected function createTimetable()
    {
        return new Timetable(
            $this->createDepartureStation(),
            $this->createDestinationStation()
        );
    }

    protected function createDepartureStation()
    {
        return new Station(1, "My departure");
    }

    protected function createDestinationStation()
    {
        return new Station(2, "My destination");
    }

    public function testAddTrip()
    {
        $timetable = $this->createTimetable();
        $trip = new Trip("C1", new \DateTime("now -1 hour"), new \DateTime("now"));
        $timetable->addTrip($trip);

        $this->assertEquals(1, $timetable->getTrips()->count());
    }
}
