<?php

namespace Cercanias\Provider\Web;

use Cercanias\Provider\AbstractTimetableQuery;

class TimetableQuery extends AbstractTimetableQuery
{
    protected function getBaseUrl()
    {
        return "http://horarios.renfe.com/cer/hjcer310.jsp";
    }

    protected function getUrlParameters()
    {
        return array(
            "nucleo"    => $this->getRouteId(),
            "i"         => "s",
            "cp"        => "NO",
            "o"         => $this->getDepartureStationId(),
            "d"         => $this->getDestinationStationId(),
            "df"        => $this->getDate()->format("Ymd"),
            "ho"        => "00",
            "hd"        => "26",
            "TXTInfo"   => ""
        );
    }
}
