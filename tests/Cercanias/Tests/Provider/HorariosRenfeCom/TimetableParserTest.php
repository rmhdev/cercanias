<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Provider\Web;

use Cercanias\Tests\Provider\AbstractTimetableParserTest;
use Cercanias\Provider\HorariosRenfeCom\TimetableParser;
use Cercanias\Entity\Train;
use Cercanias\Entity\Trip;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class TimetableParserTest extends AbstractTimetableParserTest
{
    public function testGetDate()
    {
        $parser = $this->createTimetableParserSanSebastian();
        // +01:00, Date without DST (february):
        $expected = new \DateTime("2014-02-10T00:00:00+01:00");
        $date = $parser->getDate();

        $this->assertEquals($expected, $date);
        $this->assertEquals(new \DateTimeZone("Europe/Madrid"), $date->getTimezone());
    }

    protected function createTimetableParserSanSebastian()
    {
        return $this->createTimetableParser("timetable-sansebastian.html");
    }

    public function testGetTrips()
    {
        $parser = $this->createTimetableParserSanSebastian();
        $this->assertEquals(20, $parser->getTrips()->count());
    }

    /**
     * @expectedException \Cercanias\Exception\NotFoundException
     */
    public function testGetTimetableWithNoResults()
    {
        $this->createTimetableParser("timetable-no-results.html");
    }

    protected function createTimetableParser($filename)
    {
        return new TimetableParser(
            $this->getContentHtml($filename)
        );
    }

    /**
     * @expectedException \Cercanias\Exception\ServiceUnavailableException
     * @expectedExceptionMessage Servicio temporalmente no disponible.
     */
    public function testGetTimetableServiceUnavailable()
    {
        $this->createTimetableParser("service-unavailable.html");
    }

    public function testGetTimetableWithSimpleTransfer()
    {
        $parser = $this->createTimetableParser("timetable-transfer-simple.html");
        $this->assertEquals(34, $parser->getTrips()->count());

        $trips = $parser->getTrips();
        $trips->seek(34 - 1);
        $lastTrip = $trips->current();
        $train = new Train("c1", new \DateTime("2014-06-22T22:58+02:00"), new \DateTime("2014-06-22T23:10+02:00"));
        $transferTrain = new Train(
            "c3",
            new \DateTime("2014-06-22T23:37+02:00"),
            new \DateTime("2014-06-23T00:35+02:00")
        );
        $expectedTrip = new Trip($train, $transferTrain);

        $this->assertEquals($expectedTrip, $lastTrip, "Last trip arrives after midnight");
    }

    public function testGetTimetableWithMultipleTransfers()
    {
        $parser = $this->createTimetableParser("timetable-transfer-complete.html");
        $this->assertEquals(33, $parser->getTrips()->count());

        $trips = $parser->getTrips();
        $trips->seek(31 - 1);
        $trip = $trips->current();
        $train = new Train("r1", new \DateTime("2014-06-15T21:03+02:00"), new \DateTime("2014-06-15T21:49+02:00"));
        $transferTrains = array(
            new Train("r2", new \DateTime("2014-06-15T21:56+02:00"), new \DateTime("2014-06-15T22:01+02:00")),
            new Train("r2", new \DateTime("2014-06-15T22:09+02:00"), new \DateTime("2014-06-15T22:14+02:00"))
        );
        $expectedTrip = new Trip($train, $transferTrains);
        $this->assertEquals($expectedTrip, $trip, "Trips #30 has two transfers");
    }

    public function testStationNames()
    {
        $parser = $this->createTimetableParserSanSebastian();
        $this->assertEquals("Brincola", $parser->getDepartureName());
        $this->assertEquals("Irun", $parser->getDestinationName());
        $this->assertEmpty($parser->getTransferName());
    }

    public function testStationNamesWithSpecialCharacters()
    {
        $parser = $this->createTimetableParser("timetable-transfer-complete.html");

        $this->assertEquals("Arenys de Mar", $parser->getDepartureName());
        $this->assertEquals("Barcelona-Passeig de Gràcia", $parser->getDestinationName());
        $this->assertEquals("Barcelona-El Clot-Aragó", $parser->getTransferName());
    }

    /**
     * @dataProvider getTransferNameProvider
     */
    public function testTransferName($expectedName, $filename)
    {
        $parser = $this->createTimetableParser($filename);

        $this->assertEquals($expectedName, $parser->getTransferName());
    }

    public function getTransferNameProvider()
    {
        return array(
            array("", "timetable-sansebastian.html"),
            array("Chamartin", "timetable-transfer-simple.html"),
            array("Barcelona-El Clot-Aragó", "timetable-transfer-complete.html"),
        );
    }
}
