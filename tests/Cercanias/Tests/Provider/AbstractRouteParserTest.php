<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Provider;

use PHPUnit\Framework\TestCase;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
abstract class AbstractRouteParserTest extends TestCase
{
    protected function getContentHtml($filename)
    {
        return file_get_contents(__DIR__ . "/../../Fixtures/" . $filename);
    }
}
