<?php

namespace Cercanias\Tests\Trip;

use Cercanias\Trip;

class TripTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDepartureTime()
    {
        $dateTime = new \DateTime("now");
        $trip = new Trip($dateTime);

        $this->assertEquals($dateTime, $trip->getDepartureTime());

        $dateTime2 = new \DateTime("tomorrow");
        $trip2 = new Trip($dateTime2);

        $this->assertEquals($dateTime2, $trip2->getDepartureTime());
    }

    public function testGetArrivalTimeWhenNotDefined()
    {
        $dateTime = new \DateTime("now");
        $trip = new Trip($dateTime);

        $this->assertEquals($dateTime, $trip->getArrivalTime());
    }

    public function testGetArrivalTime()
    {
        $tripTime = new \DateTime("+1 day 5 hours");
        $arrivalTime = new \DateTime("+1 day 6 hours");
        $trip = new Trip($tripTime, $arrivalTime);

        $this->assertEquals($arrivalTime, $trip->getArrivalTime());
    }

    public function testGetDurationWithoutDefiningArrivalTime()
    {
        $tripTime = new \DateTime("now");
        $interval = new \DateInterval("PT0H");
        $trip = new Trip($tripTime);

        $this->assertEquals($interval, $trip->getDuration());
    }

    public function testGetDuration()
    {
        $tripTime = new \DateTime("+1 day 5 hours");
        $arrivalTime = new \DateTime("+1 day 6 hours");
        $trip = new Trip($tripTime, $arrivalTime);
        $duration = new \DateInterval("PT1H");

        $this->assertEquals($duration, $trip->getDuration());
    }
}
