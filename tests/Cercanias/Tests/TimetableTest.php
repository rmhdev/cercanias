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
        $trip = new Trip("C1", new \DateTime("now -1 hour"), new \DateTime("now"));
        $timetable->addTrip($trip);

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
                    $previousDepartureTime->getTimestamp() <= $trip->getDepartureTime()->getTimestamp(),
                    "Trip " . $trip->getDepartureTime()->format("Y-m-d H:i:s") . " is after " .
                    "Trip " . $previousDepartureTime->format("Y-m-d H:i:s") . "."
                );
            }
            $previousDepartureTime = $trip->getDepartureTime();
        }
    }

    protected function createTimetableAddingUnorderedTrips()
    {
        $timetable = $this->createTimetable();
        $tripA = new Trip("C1", new \DateTime("2014-01-20 11:00:00"), new \DateTime("2014-01-20 12:00:00"));
        $tripB = new Trip("C1", new \DateTime("2014-01-20 11:15:00"), new \DateTime("2014-01-20 12:15:00"));
        $tripC = new Trip("C1", new \DateTime("2014-01-20 11:30:00"), new \DateTime("2014-01-20 12:30:00"));
        $timetable->addTrip($tripA);
        $timetable->addTrip($tripC);
        $timetable->addTrip($tripB);

        return $timetable;
    }
}
