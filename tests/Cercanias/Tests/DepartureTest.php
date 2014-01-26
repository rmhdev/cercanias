<?php

namespace Cercanias\Tests\Departure;

use Cercanias\Departure;

class DepartureTest extends \PHPUnit_Framework_TestCase
{
    public function testGetDepartureTime()
    {
        $dateTime = new \DateTime("now");
        $departure = new Departure($dateTime);

        $this->assertEquals($dateTime, $departure->getDepartureTime());

        $dateTime2 = new \DateTime("tomorrow");
        $departure2 = new Departure($dateTime2);

        $this->assertEquals($dateTime2, $departure2->getDepartureTime());
    }

    public function testGetArrivalTimeWhenNotDefined()
    {
        $dateTime = new \DateTime("now");
        $departure = new Departure($dateTime);

        $this->assertEquals($dateTime, $departure->getArrivalTime());
    }

    public function testGetArrivalTime()
    {
        $departureTime = new \DateTime("+1 day 5 hours");
        $arrivalTime = new \DateTime("+1 day 6 hours");
        $departure = new Departure($departureTime, $arrivalTime);

        $this->assertEquals($arrivalTime, $departure->getArrivalTime());
    }

    public function testGetDurationWithoutDefiningArrivalTime()
    {
        $departureTime = new \DateTime("now");
        $interval = new \DateInterval("PT0H");
        $departure = new Departure($departureTime);

        $this->assertEquals($interval, $departure->getDuration());
    }

    public function testGetDuration()
    {
        $departureTime = new \DateTime("+1 day 5 hours");
        $arrivalTime = new \DateTime("+1 day 6 hours");
        $departure = new Departure($departureTime, $arrivalTime);
        $duration = new \DateInterval("PT1H");

        $this->assertEquals($duration, $departure->getDuration());
    }
}
