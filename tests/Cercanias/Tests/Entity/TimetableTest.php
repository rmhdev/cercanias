<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Entity;

use Cercanias\Entity\Station;
use Cercanias\Entity\Timetable;
use Cercanias\Entity\Train;
use Cercanias\Entity\Trip;
use PHPUnit\Framework\TestCase;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class TimetableTest extends TestCase
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
        return new Station(1, "My departure", "61");
    }

    protected function createDestinationStation()
    {
        return new Station(2, "My destination", "61");
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
        $previousDepartureTime = null;
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

    public function testNextTrip()
    {
        $timetable = $this->createTimetable();
        $trainA = new Train("C1", new \DateTime("2014-01-20 11:00:00"), new \DateTime("2014-01-20 12:00:00"));
        $trainB = new Train("C1", new \DateTime("2014-01-20 11:15:00"), new \DateTime("2014-01-20 12:15:00"));
        $tripA = new Trip($trainA);
        $tripB = new Trip($trainB);
        $timetable->addTrip($tripA);
        $timetable->addTrip($tripB);

        $this->assertEquals($tripA, $timetable->nextTrip(new \DateTime("2014-01-20 10:55:00")));
        $this->assertEquals($tripB, $timetable->nextTrip(new \DateTime("2014-01-20 11:00:00")));
        $this->assertEquals($tripB, $timetable->nextTrip(new \DateTime("2014-01-20 11:10:00")));
        $this->assertNull($timetable->nextTrip(new \DateTime("2014-01-20 12:00:00")));
    }

    public function testNextTrips()
    {
        $timetable = $this->createTimetableForNextTrips();

        $this->assertEquals(2, $timetable->nextTrips(new \DateTime("2014-06-28 10:55:00"))->count());
        $this->assertEquals(1, $timetable->nextTrips(new \DateTime("2014-06-28 11:00:00"))->count());
        $this->assertEquals(1, $timetable->nextTrips(new \DateTime("2014-06-28 11:05:00"))->count());
        $this->assertEquals(0, $timetable->nextTrips(new \DateTime("2014-06-28 11:16:00"))->count());
    }

    protected function createTimetableForNextTrips()
    {
        $timetable = $this->createTimetable();
        $trainA = new Train("C1", new \DateTime("2014-06-28 11:00:00"), new \DateTime("2014-06-28 12:00:00"));
        $trainB = new Train("C1", new \DateTime("2014-06-28 11:15:00"), new \DateTime("2014-06-28 12:15:00"));
        $tripA = new Trip($trainA);
        $tripB = new Trip($trainB);
        $timetable->addTrip($tripA);
        $timetable->addTrip($tripB);

        return $timetable;
    }

    public function testNextTripsWithLimit()
    {
        $timetable = $this->createTimetableForNextTrips();

        $dateTime = new \DateTime("2014-06-28 10:55:00");
        $this->assertEquals(1, $timetable->nextTrips($dateTime, 1)->count());
        $this->assertEquals(2, $timetable->nextTrips($dateTime, 2)->count());
        $this->assertEquals(2, $timetable->nextTrips($dateTime, 0)->count());
        $this->assertEquals(2, $timetable->nextTrips($dateTime, 5)->count());
    }

    /**
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage Stations must have the same RouteId
     */
    public function testStationsMustHaveSameRouteId()
    {
        $departure = new Station(1, "Irun", "61");
        $arrival = new Station(2, "Brincola", "20");
        new Timetable($departure, $arrival);
    }

    public function testHasTransferForSimpleTimetable()
    {
        $timetable = $this->createTimetableForNextTrips();
        $this->assertFalse($timetable->hasTransfer());
    }

    public function testHasTransferForComplexTimetable()
    {
        $timetable = $this->createTimetable();
        $trainB = new Train("C1", new \DateTime("2014-06-28 11:15:00"), new \DateTime("2014-06-28 12:15:00"));
        $transferB1 = new Train("C2", new \DateTime("2014-06-28 12:20:00"), new \DateTime("2014-06-28 12:45:00"));
        $transferB2 = new Train("C2", new \DateTime("2014-06-28 12:23:00"), new \DateTime("2014-06-28 12:48:00"));
        $tripB = new Trip($trainB, array($transferB1, $transferB2));
        $timetable->addTrip($tripB);

        $this->assertTrue($timetable->hasTransfer());
    }

    /**
     * @dataProvider getTransferProvider
     */
    public function testGetTransferName($expectedName, $transfer)
    {
        $departure = new Station(1, "Irun", 61);
        $destination = new Station(2, "Brincola", 61);
        $timetable = new Timetable($departure, $destination, $transfer);
        $this->assertEquals($expectedName, $timetable->getTransferName());
    }

    public function getTransferProvider()
    {
        $station = new Station("909", "My station", 61);

        return array(
            array("Transfer Station", "Transfer Station"),
            array("", ""),
            array("", null),
            array("", false),
            array("My station", $station),
        );
    }

    /**
     * @dataProvider getIncorrectTransferProvider
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage Unknown type of transfer
     */
    public function testIncorrectTransfer($transfer)
    {
        $departure = new Station(1, "Irun", 61);
        $destination = new Station(2, "Brincola", 61);
        $timetable = new Timetable($departure, $destination, $transfer);
    }

    public function getIncorrectTransferProvider()
    {
        return array(
            array(new Train("c1", new \DateTime("now -10 minutes"), new \DateTime("now")))
        );
    }

    /**
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage Transfer station is from different route
     */
    public function testAddTransferStationFromDifferentRoute()
    {
        $departure = new Station(1, "From", 61);
        $destination = new Station(2, "To", 61);
        $transfer = new Station(3, "Transfer", 20);
        $timetable = new Timetable($departure, $destination, $transfer);
    }

    public function testGetDate()
    {
        $departure = new Station(1, "From", 61);
        $destination = new Station(2, "To", 61);
        $timetable = new Timetable($departure, $destination);
        $this->assertNull($timetable->getDate());

        $timetable->addTrip(
            new Trip(
                new Train(
                    "c1",
                    new \DateTime("2014-07-15 12:00:00"),
                    new \DateTime("2014-07-15 12:45:00")
                )
            )
        );
        $expectedDate = new \DateTime("2014-07-15 00:00:00");
        $this->assertEquals($expectedDate, $timetable->getDate());
    }
}
