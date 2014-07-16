<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Provider\Web;

use Cercanias\Entity\Route;
use Cercanias\Provider\RouteQuery;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class RouteQueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider routeProvider
     */
    public function testSetRouteId($expectedRouteId, $routeId)
    {
        $query = new RouteQuery();
        $query->setRoute($routeId);

        $this->assertEquals($expectedRouteId, $query->getRouteId());
    }

    public function routeProvider()
    {
        $route = new Route("456", "Default");
        return array(
            array("1", "1"),
            array("0123", "0123"),
            array("456", $route),
        );
    }

    public function testIsValid()
    {
        $query = new RouteQuery();
        $this->assertFalse($query->isValid());

        $query->setRoute(123);
        $this->assertTrue($query->isValid());

        $query->setRoute("123");
        $this->assertTrue($query->isValid());
    }
}
