<?php

namespace Cercanias\Tests\Trip;

use Cercanias\Trip;

class TripTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getLineProvider
     */
    public function testGetLine($line, $expected)
    {
        $departure = new \DateTime("now");
        $trip = new Trip($line, $departure);

        $this->assertEquals($expected, $trip->getLine());
    }

    public function getLineProvider()
    {
        return array(
            array("c1", "c1"),
            array("C1", "c1"),
        );
    }

    public function testGetDepartureTime()
    {
        $departure = new \DateTime("now");
        $trip = new Trip("C1", $departure);

        $this->assertEquals($departure, $trip->getDepartureTime());

        $departure2 = new \DateTime("tomorrow");
        $trip2 = new Trip("C1", $departure2);

        $this->assertEquals($departure2, $trip2->getDepartureTime());
    }

    public function testGetArrivalTimeWhenNotDefined()
    {
        $departure = new \DateTime("now");
        $trip = new Trip("C1", $departure);

        $this->assertEquals($departure, $trip->getArrivalTime());
    }

    public function testGetArrivalTime()
    {
        $departureTime = new \DateTime("+1 day 5 hours");
        $arrivalTime = new \DateTime("+1 day 6 hours");
        $trip = new Trip("C1", $departureTime, $arrivalTime);

        $this->assertEquals($arrivalTime, $trip->getArrivalTime());
    }

    public function testGetDurationWithoutDefiningArrivalTime()
    {
        $departureTime = new \DateTime("now");
        $interval = new \DateInterval("PT0H");
        $trip = new Trip("C1", $departureTime);

        $this->assertEquals($interval, $trip->getDuration());
    }

    public function testGetDuration()
    {
        $departureTime = new \DateTime("+1 day 5 hours");
        $arrivalTime = new \DateTime("+1 day 6 hours");
        $trip = new Trip("C1", $departureTime, $arrivalTime);
        $duration = new \DateInterval("PT1H");

        $this->assertEquals($duration, $trip->getDuration());
    }

    /**
     * @expectedException \Cercanias\Exception\OutOfBoundsException
     */
    public function testArrivalTimeOutOfBounds()
    {
        $departureTime = new \DateTime("+1 day 6 hours");
        $arrivalTime = new \DateTime("+1 day 3 hours");
        new Trip("C1", $departureTime, $arrivalTime);
    }
}
