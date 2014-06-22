<?php

namespace Cercanias\Tests\Provider\Web;

use Cercanias\Station;
use Cercanias\Tests\Provider\AbstractTimetableParser;
use Cercanias\Timetable;
use Cercanias\Provider\Web\TimetableParser;
use Cercanias\Train;
use Cercanias\Trip;

class TimetableParserTest extends AbstractTimetableParser
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
                new Station(123, "Brincola", 61),
                new Station(456, "Irun", 61)
            ),
            $this->getContentHtml("timetable-sansebastian.html")
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
                new Station(123, "Departure station", 61),
                new Station(456, "Arrival station", 61)
            ),
            $this->getContentHtml($filename)
        );
    }

    public function testGetTimetableWithSimpleTransfer()
    {
        $parser = $this->createTimetableParser("timetable-transfer-simple.html");
        $timetable = $parser->getTimetable();
        $this->assertEquals(34, $timetable->getTrips()->count());

        $train = new Train("c1", new \DateTime("2014-06-22 22:58"), new \DateTime("2014-06-22 23:10"));
        $transferTrain = new Train("c3", new \DateTime("2014-06-22 23:37"), new \DateTime("2014-06-23 00:35"));
        $expectedTrip = new Trip($train, $transferTrain);
        $trip = $timetable->nextDeparture(new \DateTime("2014-06-22 22:30"));
        $this->assertEquals($expectedTrip, $trip);
    }

    public function testGetTimetableWithMultipleTransfers()
    {
        $parser = $this->createTimetableParser("timetable-transfer-complete.html");
        $timetable = $parser->getTimetable();
        $this->assertEquals(33, $timetable->getTrips()->count());

        $train = new Train("r1", new \DateTime("2014-06-15 21:03"), new \DateTime("2014-06-15 21:49"));
        $transferTrains = array(
            new Train("r2", new \DateTime("2014-06-15 21:56"), new \DateTime("2014-06-15 22:01")),
            new Train("r2", new \DateTime("2014-06-15 22:09"), new \DateTime("2014-06-15 22:14"))
        );
        $expectedTrip = new Trip($train, $transferTrains);
        $trip = $timetable->nextDeparture(new \DateTime("2014-06-15 21:00"));
        $this->assertEquals($expectedTrip, $trip);
    }

    public function testStationNames()
    {
        $parser = $this->createTimetableParserSanSebastian();
        $this->assertEquals("Brincola", $parser->getDepartureStationName());
        $this->assertEquals("Irun", $parser->getArrivalStationName());
        $this->assertEmpty($parser->getTransferStationName());
    }

    public function testStationNamesWithSpecialCharacters()
    {
        $this->markTestSkipped("Some problems with utf-8");
        $parser = $this->createTimetableParser("timetable-transfer-complete.html");

        $this->assertEquals("Arenys de Mar", $parser->getDepartureStationName());
        $this->assertEquals("Barcelona-Passeig de Gràcia", $parser->getArrivalStationName());
        $this->assertEquals("Barcelona-El Clot-Aragó", $parser->getTransferStationName());
    }

}

