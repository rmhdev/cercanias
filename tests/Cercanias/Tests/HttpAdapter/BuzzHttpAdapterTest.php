<?php

namespace Cercanias\Tests\HttpAdapter;

use Cercanias\HttpAdapter\BuzzHttpAdapter;
use Buzz\Browser;

/**
 * Class BuzzHttpAdapterTest
 * @package Cercanias\Tests\HttpAdapter
 */
class BuzzHttpAdapterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var BuzzHttpAdapter
     */
    private $buzz;

    public function setUp()
    {
        if (!class_exists('Buzz\Browser')) {
            $this->markTestSkipped("Buzz library has to be installed");
        }

        $this->buzz = new BuzzHttpAdapter();
    }

    public function testGetName()
    {
        $this->assertEquals("buzz", $this->buzz->getName());
    }

    public function testGetNullContent()
    {
        $this->assertNull($this->buzz->getContent(null));
    }

    public function testGetFalseContent()
    {
        $this->assertNull($this->buzz->getContent(false));
    }

    public function testGetContentWithBuzzBrowser()
    {
        $content = "Morem ipsum dolor sit amet";
        $browser = $this->getBrowserMock($content);
        $buzz = new BuzzHttpAdapter($browser);

        $this->assertEquals($content, $buzz->getContent('http://example.com'));
    }

    /**
     * @param $content
     * @return Browser
     */
    protected function getBrowserMock($content)
    {
        $mock = $this->getMock('Buzz\Browser');
        $mock
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue(
                $this->getResponseMock($content)
            ));

        return $mock;
    }

    protected function getResponseMock($content)
    {
        $mock = $this->getMock('Buzz\Message\Response');
        $mock
            ->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue($content));
        ;

        return $mock;
    }

}