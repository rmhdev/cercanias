<?php

namespace Cercanias;

class TimetableParser
{

    protected $timetable;

    public function __construct(Timetable $timetable, $html)
    {
        $this->timetable = $timetable;
    }

    public function getTimetable()
    {
        return $this->timetable;
    }

}
