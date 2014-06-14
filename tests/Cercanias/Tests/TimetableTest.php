<?php

namespace Cercanias\Tests\Timetable;

use Cercanias\Station;
use Cercanias\Timetable;
use Cercanias\Train;

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

        $this->assertEquals($empty, $timetable->getTrains());
    }

    public function testAddTrip()
    {
        $timetable = $this->createTimetable();
        $train = new Train("C1", new \DateTime("now -1 hour"), new \DateTime("now"));
        $timetable->addTrip($train);

        $this->assertEquals(1, $timetable->getTrains()->count());
    }

    public function testGetTripsShouldReturnOrderedList()
    {
        $timetable = $this->createTimetableAddingUnorderedTrips();
        /* @var Train $train */
        /* @var \DateTime $previousDepartureTime */
        $previousDepartureTime = NULL;
        foreach ($timetable->getTrains() as $train) {
            if (!is_null($previousDepartureTime)) {
                $this->assertTrue(
                    $previousDepartureTime->getTimestamp() <= $train->getDepartureTime()->getTimestamp(),
                    "Train " . $train->getDepartureTime()->format("Y-m-d H:i:s") . " is after " .
                    "Train " . $previousDepartureTime->format("Y-m-d H:i:s") . "."
                );
            }
            $previousDepartureTime = $train->getDepartureTime();
        }
    }

    protected function createTimetableAddingUnorderedTrips()
    {
        $timetable = $this->createTimetable();
        $trainA = new Train("C1", new \DateTime("2014-01-20 11:00:00"), new \DateTime("2014-01-20 12:00:00"));
        $trainB = new Train("C1", new \DateTime("2014-01-20 11:15:00"), new \DateTime("2014-01-20 12:15:00"));
        $trainC = new Train("C1", new \DateTime("2014-01-20 11:30:00"), new \DateTime("2014-01-20 12:30:00"));
        $timetable->addTrip($trainA);
        $timetable->addTrip($trainC);
        $timetable->addTrip($trainB);

        return $timetable;
    }

    public function testNextDeparture()
    {
        $timetable = $this->createTimetable();
        $trainA = new Train("C1", new \DateTime("2014-01-20 11:00:00"), new \DateTime("2014-01-20 12:00:00"));
        $trainB = new Train("C1", new \DateTime("2014-01-20 11:15:00"), new \DateTime("2014-01-20 12:15:00"));
        $timetable->addTrip($trainA);
        $timetable->addTrip($trainB);

        $this->assertEquals($trainA, $timetable->nextDeparture(new \DateTime("2014-01-20 10:55:00")));
        $this->assertEquals($trainB, $timetable->nextDeparture(new \DateTime("2014-01-20 11:00:00")));
        $this->assertEquals($trainB, $timetable->nextDeparture(new \DateTime("2014-01-20 11:10:00")));
        $this->assertNull($timetable->nextDeparture(new \DateTime("2014-01-20 12:00:00")));
    }
}
