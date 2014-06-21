<?php

namespace Cercanias\Tests\Timetable;

use Cercanias\Station;
use Cercanias\Timetable;
use Cercanias\Train;
use Cercanias\Trip;

class TimetableTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDeparture()
    {
        $timetable = $this->createTimetable();

        $this->assertEquals($this->createDepartureStation(), $timetable->getDeparture());
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
        return new Station(1, "My departure", 61);
    }

    protected function createDestinationStation()
    {
        return new Station(2, "My destination", 61);
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

    public function testAddTrip()
    {
        $timetable = $this->createTimetable();
        $train = new Train("C1", new \DateTime("now -1 hour"), new \DateTime("now"));
        $timetable->addTrip(new Trip($train));

        $this->assertEquals(1, $timetable->getTrips()->count());
    }

    public function testGetTripsShouldReturnOrderedList()
    {
        $timetable = $this->createTimetableAddingUnorderedTrips();
        /* @var Trip $trip */
        /* @var \DateTime $previousDepartureTime */
        $previousDepartureTime = NULL;
        foreach ($timetable->getTrips() as $trip) {
            if (!is_null($previousDepartureTime)) {
                $this->assertTrue(
                    $previousDepartureTime <= $trip->getDepartureTime(),
                    "Train " . $trip->getDepartureTime()->format("Y-m-d H:i:s") . " is after " .
                    "Train " . $previousDepartureTime->format("Y-m-d H:i:s") . "."
                );
            }
            $previousDepartureTime = $trip->getDepartureTime();
        }
    }

    protected function createTimetableAddingUnorderedTrips()
    {
        $timetable = $this->createTimetable();
        $trainA = new Train("C1", new \DateTime("2014-01-20 11:00:00"), new \DateTime("2014-01-20 12:00:00"));
        $trainB = new Train("C1", new \DateTime("2014-01-20 11:15:00"), new \DateTime("2014-01-20 12:15:00"));
        $trainC = new Train("C1", new \DateTime("2014-01-20 11:30:00"), new \DateTime("2014-01-20 12:30:00"));
        $timetable->addTrip(new Trip($trainA));
        $timetable->addTrip(new Trip($trainC));
        $timetable->addTrip(new Trip($trainB));

        return $timetable;
    }

    public function testNextDeparture()
    {
        $timetable = $this->createTimetable();
        $trainA = new Train("C1", new \DateTime("2014-01-20 11:00:00"), new \DateTime("2014-01-20 12:00:00"));
        $trainB = new Train("C1", new \DateTime("2014-01-20 11:15:00"), new \DateTime("2014-01-20 12:15:00"));
        $tripA = new Trip($trainA);
        $tripB = new Trip($trainB);
        $timetable->addTrip($tripA);
        $timetable->addTrip($tripB);

        $this->assertEquals($tripA, $timetable->nextDeparture(new \DateTime("2014-01-20 10:55:00")));
        $this->assertEquals($tripB, $timetable->nextDeparture(new \DateTime("2014-01-20 11:00:00")));
        $this->assertEquals($tripB, $timetable->nextDeparture(new \DateTime("2014-01-20 11:10:00")));
        $this->assertNull($timetable->nextDeparture(new \DateTime("2014-01-20 12:00:00")));
    }
}
