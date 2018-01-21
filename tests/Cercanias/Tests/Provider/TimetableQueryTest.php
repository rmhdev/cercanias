<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Provider\Web;

use Cercanias\Provider\TimetableQuery;
use Cercanias\Entity\Route;
use Cercanias\Entity\Station;
use PHPUnit\Framework\TestCase;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class TimetableQueryTest extends TestCase
{
    /**
     * @dataProvider routeProvider
     */
    public function testSetRouteId($expectedRouteId, $routeId)
    {
        $query = new TimetableQuery();
        $query->setRoute($routeId);

        $this->assertEquals($expectedRouteId, $query->getRouteId());
    }

    public function routeProvider()
    {
        $route = new Route(456, "Default");
        return array(
            array(1, 1),
            array(456, $route),
        );
    }

    /**
     * @dataProvider stationProvider
     */
    public function testSetDeparture($expectedStationId, $station)
    {
        $query = new TimetableQuery();
        $query->setDeparture($station);

        $this->assertEquals($expectedStationId, $query->getDepartureId());
    }

    public function stationProvider()
    {
        $station = new Station(123, "Default station", 1);
        return array(
            array(1, 1),
            array(123, $station),
        );
    }

    /**
     * @dataProvider stationProvider
     */
    public function testSetDestination($expectedStationId, $station)
    {
        $query = new TimetableQuery();
        $query->setDestination($station);

        $this->assertEquals($expectedStationId, $query->getDestinationId());
    }

    public function testSetDate()
    {
        $query = new TimetableQuery();
        $date = new \DateTime("now");
        $query->setDate($date);

        $this->assertEquals($date, $query->getDate());
    }

    public function testDefaultGetDate()
    {
        $now = new \DateTime("now");
        $query = new TimetableQuery();

        $this->assertEquals($now->format("Y-m-d"), $query->getDate()->format("Y-m-d"));
    }

    public function testIsValid()
    {
        $query = new TimetableQuery();
        $this->assertFalse($query->isValid());

        $query->setRoute(1);
        $this->assertFalse($query->isValid());

        $query->setDeparture(123);
        $this->assertFalse($query->isValid());

        $query->setDestination(456);
        $this->assertTrue($query->isValid());

        $query->setDate(new \DateTime("now"));
        $this->assertTrue($query->isValid());
    }

    public function testIsValidWithConcatenatedSetters()
    {
        $query = new TimetableQuery();
        $query
            ->setRoute(1)
            ->setDeparture(123)
            ->setDestination(456)
            ->setDate(new \DateTime("now"));

        $this->assertTrue($query->isValid());
    }
}
