<?php

namespace Cercanias\Tests\Provider;

use Cercanias\HttpAdapter\HttpAdapterInterface;

abstract class AbstractProviderTest extends \PHPUnit_Framework_TestCase
{
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
     * @param string $filename
     * @return HttpAdapterInterface
     */
    protected function getMockAdapterReturnsFixtureContent($filename)
    {
        $mock = $this->getMock('Cercanias\HttpAdapter\HttpAdapterInterface');
        $mock->expects($this->once())
            ->method('getContent')
            ->willReturn(file_get_contents(__DIR__ . "/../../Fixtures/" . $filename))
        ;

        return $mock;
    }
}
