<?php

/**
 * This file is part of the cercanias package.
 *
 * (c) Roberto Martin <rmh.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cercanias\Tests\Provider\HorariosRenfeCom;

use Cercanias\Tests\Provider\AbstractTimetableParserTest;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class TimetableParserSpecialTest extends AbstractTimetableParserTest
{
    public function notestStationNamesWithTemporaryTransfers()
    {
        $parser = $this->createTimetableParser("HorariosRenfeCom/timetable-sansebastian-2018-01-14.html");

        $this->assertEquals("Brincola", $parser->getDepartureName());
        $this->assertEquals("Irun", $parser->getDestinationName());

    }
}
