<?php

namespace Cercanias\Tests\Provider;

use Cercanias\Provider\WebProvider;
use Cercanias\HttpAdapter\HttpAdapterInterface;

class WebProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $provider = new WebProvider($this->getMockAdapter($this->never()));
        $this->assertEquals("web_provider", $provider->getName());
    }

    /**
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage Could not execute query http://horarios.renfe.com/cer/hjcer300.jsp?NUCLEO=&CP=NO&I=s
     */
    public function testGetRouteForNullId()
    {
        $provider = new WebProvider($this->getMockAdapter($this->never()));
        $provider->getRoute(null);
    }

    /**
     * @param null $expects
     * @return HttpAdapterInterface
     */
    protected function getMockAdapter($expects = null)
    {
        if (null === $expects) {
            $expects = $this->once();
        }
        $mock = $this->getMock('Cercanias\HttpAdapter\HttpAdapterInterface');
        $mock
            ->expects($expects)
            ->method('getContent')
            ->will($this->returnArgument(0));

        return $mock;
    }

    /**
     * @expectedException \Cercanias\Exception\InvalidArgumentException
     * @expectedExceptionMessage Could not execute query http://horarios.renfe.com/cer/hjcer300.jsp?NUCLEO=hi&CP=NO&I=s
     */
    public function testGetRouteForNotNumberId()
    {
        $provider = new WebProvider($this->getMockAdapter($this->never()));
        $provider->getRoute("hi");
    }

    public function testGetRouteSanSebastian()
    {
        $mockAdapter = $this->getMockAdapterReturns(
            file_get_contents(__DIR__ . "/../../Fixtures/" . "route-sansebastian.html")
        );
        $provider = new WebProvider($mockAdapter);
        $route = $provider->getRoute(61);

        $this->assertEquals(61, $route->getId());
        $this->assertEquals("San Sebastián", $route->getName());
        $this->assertEquals(30, $route->countStations());
    }

    /**
     * @param $returnValue
     * @return HttpAdapterInterface
     */
    protected function getMockAdapterReturns($returnValue)
    {
        $mock = $this->getMock('Cercanias\HttpAdapter\HttpAdapterInterface');
        $mock->expects($this->once())
            ->method('getContent')
            ->willReturn($returnValue)
        ;

        return $mock;
    }

}