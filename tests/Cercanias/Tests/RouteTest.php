<?php

namespace Cercanias\Tests\Route;

use Cercanias\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSlug()
    {
        $route = new Route("sansebastian");
        $this->assertEquals("sansebastian", $route->getSlug());

        $route2 = new Route("asturias");
        $this->assertEquals("asturias", $route2->getSlug());
    }
}
