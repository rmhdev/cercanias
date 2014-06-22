<?php

namespace Cercanias\Tests\Provider;

abstract class AbstractTimetableParser extends \PHPUnit_Framework_TestCase
{
    protected function getContentHtml($filename)
    {
        return mb_convert_encoding(
            file_get_contents(__DIR__ . "/../../Fixtures/" . $filename),
            'HTML-ENTITIES',
            "UTF-8"
        );
    }
}
