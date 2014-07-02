<?php

namespace Cercanias\Tests\Cercanias;

use Cercanias\Cercanias;

class CercaniasTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @expectedException Cercanias\Exception\InvalidArgumentException
     */
    public function testCreateWithProviderNull()
    {
        $cercanias = new Cercanias();
    }
}
