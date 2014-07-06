<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Provider;

use Cercanias\HttpAdapter\HttpAdapterInterface;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
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
