<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Cercanias;

use Cercanias\Entity\Route;
use Cercanias\Provider\RouteParserInterface;
use Cercanias\Provider\RouteQueryInterface;
use Cercanias\Provider\TimetableParserInterface;
use Cercanias\Provider\TimetableQueryInterface;
use Cercanias\Provider\ProviderInterface;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
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

class MockProvider implements ProviderInterface
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

class MockRouteParser implements RouteParserInterface
{
    /**
     * Parse html and create a Route object
     * @return Route
     */
    public function getRoute()
    {

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

class MockTimetableParser implements TimetableParserInterface
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
