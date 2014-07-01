<?php

namespace Cercanias\Tests\Provider\Web;

use Cercanias\Route;
use Cercanias\Provider\Web\RouteQuery;

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
        $route = new Route(456, "Default");
        return array(
            array(1, 1),
            array(456, $route),
        );
    }
}
