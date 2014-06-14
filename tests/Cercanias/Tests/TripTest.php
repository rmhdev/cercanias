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
        $expectedTransferTrain = $this->createTransferTrain($train);
        $trip = new Trip($train, $expectedTransferTrain);
        $this->assertEquals(1, $trip->getTransferTrains()->count());
        $this->assertEquals($expectedTransferTrain, $trip->getTransferTrains()->offsetGet(0));
    }

    protected function createTransferTrain(Train $train)
    {
        $date = $train->getArrivalTime();
        $departureTransferDateTime = $date->add(new \DateInterval("P10M"));
        $arrivalTransferDateTime = $departureTransferDateTime->add(new \DateInterval("P55M"));

        return new Train($train->getLine(), $departureTransferDateTime, $arrivalTransferDateTime);
    }

    public function testGetTransferTrainsInTripWithMultipleTransfers()
    {
        $departureTrain = $this->createSimpleTrain();
        $transfer1 = $this->createTransferTrain($departureTrain);
        $transfer2 = $this->createTransferTrain($transfer1);
        $transfers = array($transfer1, $transfer2);
        $trip = new Trip($departureTrain, $transfers);
        $this->assertEquals(2, $trip->getTransferTrains()->count());
    }



}
