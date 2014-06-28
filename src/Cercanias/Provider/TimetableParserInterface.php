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
    public function getTransferName();

    /**
     * @return string
     */
    public function getDepartureName();

    /**
     * @return string
     */
    public function getArrivalName();
}
