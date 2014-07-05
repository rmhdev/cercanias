<?php

namespace Cercanias\Tests\Cercanias;

use Cercanias\Entity\Route;
use Cercanias\Provider\RouteParserInterface;
use Cercanias\Provider\RouteQueryInterface;
use Cercanias\Provider\TimetableParserInterface;
use Cercanias\Provider\TimetableQueryInterface;
use Cercanias\Provider\ProviderInterface;

abstract class AbstractCercaniasTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return ProviderInterface
     */
    protected function getMockProviderReturnsRouteParser()
    {
        $mock = $this->getMock('Cercanias\Provider\ProviderInterface');
        $mock->expects($this->once())
            ->method('getRouteParser')
            ->willReturn(new MockRouteParser())
        ;

        return $mock;
    }

    /**
     * @return ProviderInterface
     */
    protected function getMockProviderReturnsTimetableParser()
    {
        $mock = $this->getMock('Cercanias\Provider\ProviderInterface');
        $mock->expects($this->once())
            ->method('getTimetableParser')
            ->willReturn(new MockTimetableParser())
        ;

        return $mock;
    }
}

class MockProviderddd implements ProviderInterface
{
    public function __construct($name)
    {

    }

    public function getName()
    {

    }

    public function generateRouteUrl(RouteQueryInterface $query)
    {

    }

    public function generateTimetableUrl(TimetableQueryInterface $query)
    {

    }

    public function getRouteParser(RouteQueryInterface $query)
    {

    }

    public function getTimetableParser(TimetableQueryInterface $query)
    {

    }
}

class MockRouteParserddd implements RouteParserInterface
{
    /**
     * Parse html and create a Route object
     * @return Route
     */
    public function getRoute()
    {
        // TODO: Implement getRoute() method.
    }

    /**
     * @return int
     */
    public function getRouteId()
    {
        return 1;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return "Test Route";
    }

    /**
     * @return \ArrayIterator
     */
    public function getStations()
    {
        return new \ArrayIterator();
    }
}

class MockTimetableParserddd implements TimetableParserInterface
{
    public function getDate()
    {
        return new \DateTime("now");
    }

    public function getDepartureName()
    {
        return "Departure station";
    }

    public function getDestinationName()
    {
        return "Destination station";
    }

    public function getTransferName()
    {
        return "Transfer station";
    }

    public function getTrips()
    {
        return new \ArrayIterator();
    }
}
