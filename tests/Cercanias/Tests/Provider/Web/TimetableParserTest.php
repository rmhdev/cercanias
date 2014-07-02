<?php

namespace Cercanias\Tests\Provider\Web;

use Cercanias\Tests\Provider\AbstractTimetableParserTest;
use Cercanias\Provider\Web\TimetableQuery;
use Cercanias\Provider\Web\TimetableParser;
use Cercanias\Entity\Train;
use Cercanias\Entity\Trip;

class TimetableParserTest extends AbstractTimetableParserTest
{

    public function testGetTimetable()
    {
        $parser = $this->createTimetableParserSanSebastian();

        $this->assertInstanceOf('Cercanias\Entity\Timetable', $parser->getTimetable());
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
        $query = new TimetableQuery();
        $query
            ->setRoute(61)
            ->setDeparture(123)
            ->setDestination(456);

        return new TimetableParser(
            $query,
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
        $trip = $timetable->nextTrip(new \DateTime("2014-02-10 05:50"));
        $this->assertEquals($expectedTrip, $trip);
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
        $query = new TimetableQuery();
        $query
            ->setRoute(1)
            ->setDeparture(123)
            ->setDestination(456);

        return new TimetableParser(
            $query,
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
        $timetable = $parser->getTimetable();
        $this->assertEquals(34, $timetable->getTrips()->count());

        $train = new Train("c1", new \DateTime("2014-06-22 22:58"), new \DateTime("2014-06-22 23:10"));
        $transferTrain = new Train("c3", new \DateTime("2014-06-22 23:37"), new \DateTime("2014-06-23 00:35"));
        $expectedTrip = new Trip($train, $transferTrain);
        $trip = $timetable->nextTrip(new \DateTime("2014-06-22 22:30"));
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
        $trip = $timetable->nextTrip(new \DateTime("2014-06-15 21:00"));
        $this->assertEquals($expectedTrip, $trip);
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
        $timetable = $parser->getTimetable();
        $this->assertEquals($expectedName, $timetable->getTransferName());
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
