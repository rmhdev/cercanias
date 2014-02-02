<?php

namespace Cercanias\Tests\RouteParser;

use Cercanias\RouteParser;

class RouteParserTest extends \PHPUnit_Framework_TestCase
{

    public function testGetRoute()
    {
        $html = file_get_contents(__DIR__ . "/../Fixtures/route-sansebastian.html");
        $routeParser = new RouteParser($html);

        $this->assertInstanceOf("Cercanias\Route", $routeParser->getRoute());
    }

}
