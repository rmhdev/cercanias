<?php

namespace Cercanias\Provider\Web;

use Cercanias\Provider\AbstractRouteQuery;

class RouteQuery extends AbstractRouteQuery
{

    const BASE_URL = "http://horarios.renfe.com/cer/hjcer300.jsp";

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
            "NUCLEO"    => $this->getRouteId(),
            "CP"        => "NO",
            "I"         => "s"
        );
    }
}
