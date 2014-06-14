<?php

namespace Cercanias\Tests\Trip;

use Cercanias\Train;
use Cercanias\Trip;

class TripTest extends \PHPUnit_Framework_TestCase
{

    public function testGetDepartureTrain()
    {
        $train = new Train(1, new \DateTime("2014-01-20 12:00:00"), new \DateTime("2014-01-20 12:55:00"));
        $trip = new Trip($train);
        $this->assertEquals($train, $trip->getDepartureTrain());
    }

}
