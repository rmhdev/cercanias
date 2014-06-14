<?php

namespace Cercanias\Tests\TimetableParser;

use Cercanias\Station;
use Cercanias\Timetable;
use Cercanias\TimetableParser;
use Cercanias\Train;
use Cercanias\Trip;

class TimetableParserTest extends \PHPUnit_Framework_TestCase
{

    public function testGetTimetable()
    {
        $parser = $this->createTimetableParserSanSebastian();

        $this->assertInstanceOf('\Cercanias\Timetable', $parser->getTimetable());
    }

    public function testGetDate()
    {
        $parser = $this->createTimetableParserSanSebastian();
        $expected = new \DateTime("2014-02-10 00:00:00");

        $this->assertEquals($expected, $parser->getDate());
    }

    public function testGetTimetableCheckBasicData()
    {
        $parser = $this->createTimetableParserSanSebastian();
        $timetable = $parser->getTimetable();

        $this->assertEquals("Brincola", $timetable->getDeparture()->getName());
        $this->assertEquals("Irun", $timetable->getDestination()->getName());
    }

    protected function createTimetableParserSanSebastian()
    {
        return new TimetableParser(
            new Timetable(
                new Station(123, "Brincola"),
                new Station(456, "Irun")
            ),
            file_get_contents(__DIR__ . "/../Fixtures/timetable-sansebastian.html")
        );
    }

    public function testGetTimetableCheckTrips()
    {
        $parser = $this->createTimetableParserSanSebastian();
        $timetable = $parser->getTimetable();
        $this->assertEquals(20, $timetable->getTrips()->count());

        $train = new Train("c1", new \DateTime("2014-02-10 05:53"), new \DateTime("2014-02-10 07:23"));
        $expectedTrip = new Trip($train);
        $trip = $timetable->nextDeparture(new \DateTime("2014-02-10 05:50"));
        $this->assertEquals($expectedTrip, $trip);
    }

    public function testGetTimetableWithNoResults()
    {
        $parser = $this->createTimetableParser("timetable-no-results.html");
        $expected = new \DateTime("2014-02-15 00:00:00");
        $this->assertEquals($expected, $parser->getDate());

        $timetable = $parser->getTimetable();
        $this->assertEquals(0, $timetable->getTrips()->count());
    }

    protected function createTimetableParser($filename)
    {
        return new TimetableParser(
            new Timetable(
                new Station(123, "Departure station"),
                new Station(456, "Arrival station")
            ),
            file_get_contents(__DIR__ . "/../Fixtures/" . $filename)
        );
    }

    public function testGetTimetableWithSimpleTransfer()
    {
        $parser = $this->createTimetableParser("timetable-transfer-simple.html");
        $timetable = $parser->getTimetable();
        $this->assertEquals(34, $timetable->getTrips()->count());

        $train = new Train("c1", new \DateTime("2014-02-15 22:58"), new \DateTime("2014-02-15 23:10"));
        $transferTrain = new Train("c3", new \DateTime("2014-02-15 23:37"), new \DateTime("2014-02-16 00:35"));
        $expectedTrip = new Trip($train, $transferTrain);
        $trip = $timetable->nextDeparture(new \DateTime("2014-02-15 22:30"));
        $this->assertEquals($expectedTrip, $trip);
    }

}

