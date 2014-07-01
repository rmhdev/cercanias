<?php

namespace Cercanias\Provider\Web;

use Cercanias\Provider\AbstractRouteQuery;

class RouteQuery extends AbstractRouteQuery
{
    protected function getBaseUrl()
    {
        return "http://horarios.renfe.com/cer/hjcer300.jsp";
    }

    protected function getUrlParameters()
    {
        return array(
            "NUCLEO"    => $this->getRouteId(),
            "CP"        => "NO",
            "I"         => "s"
        );
    }
}
