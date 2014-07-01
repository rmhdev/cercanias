<?php

namespace Cercanias\Provider;

abstract class AbstractQuery implements QueryInterface
{
    public function generateUrl()
    {
        $params = array();
        foreach ($this->getUrlParameters() as $name => $value) {
            $params[] = sprintf("%s=%s", $name, $value);
        }

        return $this->getBaseUrl() . "?" . implode("&", $params);
    }

    /**
     * @return string
     */
    abstract protected function getBaseUrl();

    /**
     * @return array
     */
    abstract protected function getUrlParameters();
}
