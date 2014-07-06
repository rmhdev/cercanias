<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\HttpAdapter;

use Cercanias\HttpAdapter\CurlHttpAdapter;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
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
