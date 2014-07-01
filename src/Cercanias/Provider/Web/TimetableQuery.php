<?php

namespace Cercanias\Provider\Web;

use Cercanias\Provider\AbstractTimetableQuery;

class TimetableQuery extends AbstractTimetableQuery
{

    const BASE_URL = "http://horarios.renfe.com/cer/hjcer310.jsp";

    /**
     * {@inheritDoc}
     */
    public function generateUrl()
    {
        $params = array();
        foreach ($this->prepareUrlParameters() as $name => $value) {
            $params[] = sprintf("%s=%s", $name, $value);
        }

        return self::BASE_URL . "?" . implode("&", $params);
    }

    private function prepareUrlParameters()
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
