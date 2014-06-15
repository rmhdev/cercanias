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

}