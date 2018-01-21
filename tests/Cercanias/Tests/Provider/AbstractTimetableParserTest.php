<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Provider;

use Cercanias\Provider\HorariosRenfeCom\TimetableParser;
use PHPUnit\Framework\TestCase;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
abstract class AbstractTimetableParserTest extends TestCase
{
    protected function getContentHtml($filename)
    {
        return mb_convert_encoding(
            file_get_contents(__DIR__ . "/../../Fixtures/" . $filename),
            'HTML-ENTITIES',
            "UTF-8"
        );
    }

    protected function createTimetableParser($filename)
    {
        return new TimetableParser(
            $this->getContentHtml($filename)
        );
    }
}
