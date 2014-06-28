<?php

namespace Cercanias\Tests\HttpAdapter;

use Cercanias\HttpAdapter\CurlHttpAdapter;

class CurlHttpAdapterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var CurlHttpAdapter
     */
    protected $curl;

    public function setUp()
    {
        if (!function_exists('curl_init')) {
            $this->markTestSkipped('cURL has to be enabled');
        }

        $this->curl = new CurlHttpAdapter();
    }

    public function testGetNullContent()
    {
        $this->assertNull($this->curl->getContent(null));
    }

    public function testGetFalseContent()
    {
        $this->assertNull($this->curl->getContent(false));
    }

    public function testGetName()
    {
        $this->assertEquals("curl", $this->curl->getName());
    }
}
