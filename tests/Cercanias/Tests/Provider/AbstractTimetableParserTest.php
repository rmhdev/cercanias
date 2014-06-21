<?php

namespace Cercanias\Tests\Provider;

abstract class AbstractTimetableParser extends \PHPUnit_Framework_TestCase
{
    protected function getContentHtml($filename)
    {
        return file_get_contents(__DIR__ . "/../../Fixtures/" . $filename);
    }
}
