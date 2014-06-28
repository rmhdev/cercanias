<?php

namespace Cercanias\Provider;

use Cercanias\Timetable;

interface TimetableParserInterface
{
    /**
     * @return \DateTime
     */
    public function getDate();

    /**
     * @return Timetable
     */
    public function getTimetable();

    /**
     * @return string
     */
    public function getTransferStationName();

    /**
     * @return string
     */
    public function getDepartureStationName();

    /**
     * @return string
     */
    public function getArrivalStationName();
}
