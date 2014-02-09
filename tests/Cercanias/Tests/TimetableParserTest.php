<?php

namespace Cercanias\Tests\TimetableParser;

use Cercanias\Station;
use Cercanias\Timetable;
use Cercanias\TimetableParser;

class TimetableParserTest extends \PHPUnit_Framework_TestCase
{

    public function testGetTimetable()
    {
        $parser = $this->createTimetableParserSanSebastian();

        $this->assertInstanceOf('\Cercanias\Timetable', $parser->getTimetable());
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
    }

}
