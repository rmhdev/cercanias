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
        return new Train(1, new \DateTime("2014-01-20 12:00:00"), new \DateTime("2014-01-20 12:55:00"));
    }

    public function testHasTransfer()
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



}
