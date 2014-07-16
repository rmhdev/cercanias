<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Cercanias;

use Cercanias\Cercanias;
use Cercanias\Provider\RouteQuery;
use Cercanias\Provider\TimetableQuery;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class CercaniasTest extends AbstractCercaniasTest
{
    /**
     * @dataProvider getInvalidRouteProvider
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid routeId
     */
    public function testGetRouteForNullId($route)
    {
        $provider = new MockProvider("default");
        $cercanias = new Cercanias($provider);
        $cercanias->getRoute($route);
    }

    public function getInvalidRouteProvider()
    {
        return array(
            array(null),
            array(""),
        );
    }

    public function testGetRoute()
    {
        $provider = $this->getMockProviderReturnsRouteParser();
        $cercanias = new Cercanias($provider);
        $route = $cercanias->getRoute(1);

        $this->assertInstanceOf('\Cercanias\Entity\Route', $route);
        $this->assertEquals(1, $route->getId());
    }

    public function testGetRouteWithQuery()
    {
        $provider = $this->getMockProviderReturnsRouteParser();
        $cercanias = new Cercanias($provider);
        $query = new RouteQuery();
        $query->setRoute(1);
        $route = $cercanias->getRoute($query);

        $this->assertInstanceOf('\Cercanias\Entity\Route', $route);
        $this->assertEquals(1, $route->getId());
    }

    public function testGetTimetable()
    {
        $query = new TimetableQuery();
        $query
            ->setRoute(1)
            ->setDeparture("123")
            ->setDestination("456");
        $provider = $this->getMockProviderReturnsTimetableParser();
        $cercanias = new Cercanias($provider);

        $this->assertInstanceOf('\Cercanias\Entity\Timetable', $cercanias->getTimetable($query));
    }
}
