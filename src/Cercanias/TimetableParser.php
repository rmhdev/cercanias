<?php

namespace Cercanias;

class TimetableParser
{
    public function getTimetable()
    {
        $departure = new Station(1, "Departure Station");
        $arrival = new Station(2, "Arrival Station");

        return new Timetable($departure, $arrival);
    }
}