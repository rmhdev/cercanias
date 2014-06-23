<?php

namespace Cercanias\Tests\Provider;

use Cercanias\Provider\TimetableQuery;

class TimetableQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testSetRouteId()
    {
        $query = new TimetableQuery();
        $query->setRouteId(123);

        $this->assertEquals(123, $query->getRouteId());
    }

    public function testDepartureStationId()
    {
        $query = new TimetableQuery();
        $query->setDepartureStationId(456);

        $this->assertEquals(456, $query->getDepartureStationId());
    }

    public function testDestinationStationId()
    {
        $query = new TimetableQuery();
        $query->setDestinationStationId(789);

        $this->assertEquals(789, $query->getDestinationStationId());
    }


}
