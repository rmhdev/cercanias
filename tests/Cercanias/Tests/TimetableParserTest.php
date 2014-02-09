<?php

namespace Cercanias\Tests\TimetableParser;

use Cercanias\Station;
use Cercanias\Timetable;
use Cercanias\TimetableParser;

class TimetableParserTest extends \PHPUnit_Framework_TestCase
{

    public function testGetTimetable()
    {
        $timetable = new Timetable(
            new Station(1, "Departure Station"),
            new Station(2, "Arrival Station")
        );
        $parser = new TimetableParser($timetable, "html");

        $this->assertInstanceOf('\Cercanias\Timetable', $parser->getTimetable());
    }

    public function testGetTimetableCheckBasicData()
    {
        $parser = new TimetableParser(
            new Timetable(
                new Station(123, "Brincola"),
                new Station(456, "Irun")
            ),
            file_get_contents(__DIR__ . "/../Fixtures/timetable-sansebastian.html")
        );
        $timetable = $parser->getTimetable();

        $this->assertEquals("Brincola", $timetable->getDeparture()->getName());
        $this->assertEquals("Irun", $timetable->getDestination()->getName());
    }

}
