<?php

namespace Cercanias\Tests\Entity;

use Cercanias\Entity\Train;
use Cercanias\Entity\Trip;

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
        $departureTransferDateTime = clone $train->getArrivalTime();
        $departureTransferDateTime = $departureTransferDateTime->add(new \DateInterval("PT10M"));
        $arrivalTransferDateTime = clone $departureTransferDateTime;
        $arrivalTransferDateTime = $arrivalTransferDateTime->add(new \DateInterval("PT55M"));

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

    /**
     * @expectedException \Cercanias\Exception\OutOfBoundsException
     */
    public function testTransferTrainOutOfBounds()
    {
        $departureTrain = $this->createSimpleTrain();
        $transferDepartureTime = clone $departureTrain->getArrivalTime();
        $transferDepartureTime = $transferDepartureTime->sub(new \DateInterval("PT10M"));
        $transferArrivalTime = clone $transferDepartureTime;
        $transferArrivalTime = $transferArrivalTime->add(new \DateInterval("PT55M"));
        $transferTrain = new Train($departureTrain->getLine(), $transferDepartureTime, $transferArrivalTime);
        $trip = new Trip($departureTrain, $transferTrain);
    }
}
