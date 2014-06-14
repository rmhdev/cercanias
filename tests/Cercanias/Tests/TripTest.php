<?php

namespace Cercanias\Tests\Trip;

use Cercanias\Train;
use Cercanias\Trip;

class TripTest extends \PHPUnit_Framework_TestCase
{

    public function testGetDepartureTrain()
    {
        $train = $this->createSimpleTrain();
        $trip = new Trip($train);
        $this->assertEquals($train, $trip->getDepartureTrain());
    }

    protected function createSimpleTrain()
    {
        return new Train("c1", new \DateTime("2014-01-20 12:00:00"), new \DateTime("2014-01-20 12:55:00"));
    }

    public function testSimpleTripHasNoTransfer()
    {
        $trip = new Trip($this->createSimpleTrain());
        $this->assertFalse($trip->hasTransfer());
    }

    public function testGetDepartureTime()
    {
        $train = $this->createSimpleTrain();
        $trip = new Trip($train);
        $this->assertEquals($train->getDepartureTime(), $trip->getDepartureTime());
    }

    public function testCompareWith()
    {
        $trip = new Trip($this->createSimpleTrain());
        $trip2 = new Trip($this->createSimpleTrain());
        $this->assertEquals(0, $trip->compareWith($trip2));
    }

    public function testGetTransferTrainsInTripWithNoTransfers()
    {
        $trip = new Trip($this->createSimpleTrain());
        $this->assertEquals(new \ArrayIterator(), $trip->getTransferTrains());
    }

    public function testGetTransferTrainsInTripWithOneTransfer()
    {
        $train = $this->createSimpleTrain();
        $date = $train->getArrivalTime();
        $departureTransferDateTime = $date->add(new \DateInterval("P10M"));
        $arrivalTransferDateTime = $departureTransferDateTime->add(new \DateInterval("P55M"));
        $expectedTransferTrain = new Train($train->getLine(), $departureTransferDateTime, $arrivalTransferDateTime);
        $trip = new Trip($train, $expectedTransferTrain);
        $this->assertEquals(1, $trip->getTransferTrains()->count());
        $this->assertEquals($expectedTransferTrain, $trip->getTransferTrains()->offsetGet(0));
    }



}
