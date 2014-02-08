<?php

namespace Cercanias\Tests\TimetableParser;

use Cercanias\TimetableParser;

class TimetableParserTest extends \PHPUnit_Framework_TestCase
{

    public function testGetTimetable()
    {
        $parser = new TimetableParser("html");

        $this->assertInstanceOf('\Cercanias\Timetable', $parser->getTimetable());
    }

}
