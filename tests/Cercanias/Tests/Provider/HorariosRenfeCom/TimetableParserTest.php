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
        $date = $parser->getDate();

        $this->assertInstanceOf('\DateTime', $date);
        $this->assertEquals(new \DateTimeZone("Europe/Madrid"), $date->getTimezone());
    }

    protected function createTimetableParserSanSebastian()
    {
        return $this->createTimetableParser("HorariosRenfeCom/timetable-sansebastian.html");
    }

    public function testGetTrips()
    {
        $parser = $this->createTimetableParserSanSebastian();
        $this->assertGreaterThan(0, $parser->getTrips()->count());
    }

    /**
     * @expectedException \Cercanias\Exception\NotFoundException
     */
    public function testGetTimetableWithNoResults()
    {
        $this->createTimetableParser("HorariosRenfeCom/timetable-no-results.html");
    }

    /**
     * @expectedException \Cercanias\Exception\ServiceUnavailableException
     * @expectedExceptionMessage Servicio temporalmente no disponible.
     */
    public function testGetTimetableServiceUnavailable()
    {
        $this->createTimetableParser("HorariosRenfeCom/service-unavailable.html");
    }

    public function testItReturnsADateObjectThatCanNotBeModified()
    {
        $parser = $this->createTimetableParserSanSebastian();
        $expectedDate = $parser->getDate();
        $parser->getDate()->modify("+1 day");

        $this->assertEquals($expectedDate, $parser->getDate());
    }

    public function testItParsesDatesThatArriveAtDestinationAfterMidnight()
    {
        $parser = $this->createTimetableParser("HorariosRenfeCom/timetable-madrid.html");
        $this->assertGreaterThan(0, $parser->getTrips()->count());

        $trips = $parser->getTrips();
        $trips->seek($trips->count() - 1);//last trip
        /* @var Trip $lastTrip */
        $lastTrip = $trips->current();
        $this->assertTrue($lastTrip->hasTransfer());
        $queryDate = $parser->getDate();

        /* @var Train $lastTrain */
        $trains = $lastTrip->getTransferTrains();
        $trains->seek($lastTrip->getTransferTrains()->count() - 1);
        $lastTrain = $trains->current();
        $this->assertEquals(
            $queryDate->modify("+1 day")->format("Y-m-d"),
            $lastTrain->getArrivalTime()->format("Y-m-d"),
            "Last train arrives at destination after midnight"
        );
    }

    public function notestGetTimetableWithMultipleTransfers()
    {
        $parser = $this->createTimetableParser("HorariosRenfeCom/timetable-barcelona.html");
        $this->assertGreaterThan(0, $parser->getTrips()->count());

        $hasMultiTransfers = false;
        foreach ($parser->getTrips() as $trip) {
            /* @var Trip $trip */
            if (1 < $trip->getTransferTrains()->count()) {
                $hasMultiTransfers = true;
                break;
            }
        }
        $this->assertTrue($hasMultiTransfers);
    }

    public function testStationNames()
    {
        $parser = $this->createTimetableParserSanSebastian();
        $this->assertEquals("Brincola", $parser->getDepartureName());
        $this->assertEquals("Irun", $parser->getDestinationName());
    }

    public function testStationNamesWithSpecialCharacters()
    {
        $parser = $this->createTimetableParser("HorariosRenfeCom/timetable-barcelona.html");

        $this->assertEquals("Barcelona-Passeig de Gracia", $parser->getDepartureName());
        $this->assertEquals("Arenys de Mar", $parser->getDestinationName());
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
            array("Lezo-Renteria", "HorariosRenfeCom/timetable-sansebastian.html"),
            array("Chamartin", "HorariosRenfeCom/timetable-madrid.html"),
            array("Barcelona-El Clot-Aragó", "HorariosRenfeCom/timetable-barcelona.html"),
        );
    }
}
