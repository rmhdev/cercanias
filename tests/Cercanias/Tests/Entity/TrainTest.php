<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Entity;

use Cercanias\Entity\Train;
use PHPUnit\Framework\TestCase;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class TrainTest extends TestCase
{
    /**
     * @dataProvider getLineProvider
     */
    public function testGetLine($line, $expected)
    {
        $departure = new \DateTime("now");
        $train = new Train($line, $departure);

        $this->assertEquals($expected, $train->getLine());
    }

    public function getLineProvider()
    {
        return array(
            array("c1", "c1"),
            array("C1", "c1"),
            array("C1     ", "c1"),
        );
    }

    public function testGetDepartureTime()
    {
        $departure = new \DateTime("now");
        $train = new Train("C1", $departure);

        $this->assertEquals($departure, $train->getDepartureTime());

        $departure2 = new \DateTime("tomorrow");
        $train2 = new Train("C1", $departure2);

        $this->assertEquals($departure2, $train2->getDepartureTime());
    }

    public function testGetArrivalTimeWhenNotDefined()
    {
        $departure = new \DateTime("now");
        $train = new Train("C1", $departure);

        $this->assertEquals($departure, $train->getArrivalTime());
    }

    public function testGetArrivalTime()
    {
        $departureTime = new \DateTime("+1 day 5 hours");
        $arrivalTime = new \DateTime("+1 day 6 hours");
        $train = new Train("C1", $departureTime, $arrivalTime);

        $this->assertEquals($arrivalTime, $train->getArrivalTime());
    }

    public function testGetDurationWithoutDefiningArrivalTime()
    {
        $departureTime = new \DateTime("now");
        $interval = new \DateInterval("PT0H");
        $train = new Train("C1", $departureTime);

        $this->assertEquals($interval, $train->getDuration());
    }

    public function testGetDuration()
    {
        $base = (new \DateTime("today +1 day"))->setTime(12, 0, 0);
        $departureTime = clone $base;
        $departureTime = $departureTime->modify("+5 hours");
        $arrivalTime = clone $base;
        $arrivalTime = $arrivalTime->modify("+6 hours");
        $train = new Train("C1", $departureTime, $arrivalTime);
        $duration = new \DateInterval("PT1H");

        $this->assertEquals($duration, $train->getDuration());
    }

    /**
     * @expectedException \Cercanias\Exception\OutOfBoundsException
     */
    public function testArrivalTimeOutOfBounds()
    {
        $departureTime = new \DateTime("+1 day 6 hours");
        $arrivalTime = new \DateTime("+1 day 3 hours");
        new Train("C1", $departureTime, $arrivalTime);
    }

    public function testCompareWithALaterTrain()
    {
        $train = new Train(
            "C1",
            new \DateTime("2014-01-10 11:00:00"),
            new \DateTime("2014-01-10 11:30:00")
        );
        $trainLater = new Train(
            "C1",
            new \DateTime("2014-01-10 12:00:00"),
            new \DateTime("2014-01-10 12:30:00")
        );

        $this->assertEquals(-1, $train->compareWith($trainLater));
    }

    public function testCompareWithAPreviousTrain()
    {
        $train = new Train(
            "C1",
            new \DateTime("2014-01-10 11:00:00"),
            new \DateTime("2014-01-10 11:30:00")
        );
        $trainBefore = new Train(
            "C1",
            new \DateTime("2014-01-10 10:00:00"),
            new \DateTime("2014-01-10 10:30:00")
        );

        $this->assertEquals(1, $train->compareWith($trainBefore));
    }

    public function testCompareWithASameDepartureTimeAndLaterArrival()
    {
        $train = new Train(
            "C1",
            new \DateTime("2014-01-10 11:00:00"),
            new \DateTime("2014-01-10 11:30:00")
        );
        $trainBefore = new Train(
            "C1",
            new \DateTime("2014-01-10 11:00:00"),
            new \DateTime("2014-01-10 11:45:00")
        );

        $this->assertEquals(-1, $train->compareWith($trainBefore));
    }

    public function testCompareWithASameDepartureTimeAndBeforeArrival()
    {
        $train = new Train(
            "C1",
            new \DateTime("2014-01-10 11:00:00"),
            new \DateTime("2014-01-10 11:30:00")
        );
        $trainBefore = new Train(
            "C1",
            new \DateTime("2014-01-10 11:00:00"),
            new \DateTime("2014-01-10 11:25:00")
        );

        $this->assertEquals(1, $train->compareWith($trainBefore));
    }

    public function testCompareWithSameDepartureAndArrivalTimes()
    {
        $train = new Train(
            "C1",
            new \DateTime("2014-01-10 11:00:00"),
            new \DateTime("2014-01-10 11:30:00")
        );

        $this->assertEquals(0, $train->compareWith($train));
    }
}
