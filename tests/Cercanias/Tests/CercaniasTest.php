<?php

namespace Cercanias\Tests\Cercanias;

use Cercanias\Cercanias;
use Cercanias\Provider\ProviderInterface;
use Cercanias\Provider\TimetableQueryInterface;

class CercaniasTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage Invalid routeId
     */
    public function testGetRouteForNullId()
    {
        $provider = new MockProvider("default");
        $cercanias = new Cercanias($provider);
        $cercanias->getRoute(null);
    }
}

class MockProvider implements ProviderInterface
{
    public function __construct($name)
    {

    }

    public function getName()
    {

    }

    public function getRoute($routeId)
    {

    }

    public function getTimetable(TimetableQueryInterface $query)
    {

    }
}
