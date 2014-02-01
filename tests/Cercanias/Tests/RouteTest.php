<?php

namespace Cercanias\Tests\Route;

use Cercanias\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider getSlugProvider
     */
    public function testGetSlug($slug, $expected)
    {
        $route = new Route($slug);
        $this->assertEquals($expected, $route->getSlug());
    }

    public function getSlugProvider()
    {
        return array(
            array("sansebastian", "sansebastian"),
            array("asturias", "asturias"),
        );
    }
}
