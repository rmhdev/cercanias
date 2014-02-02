<?php

namespace Cercanias;

class RouteParser
{

    protected
        $route;

    public function __construct($html)
    {
        libxml_use_internal_errors(true);
        $domDocument = new \DOMDocument("1.0", "utf-8");
        $domDocument->loadHTML($html);
        $path = new \DOMXPath($domDocument);
        $this->route = $this->createRoute($path);
    }

    protected function createRoute(\DOMXPath $path)
    {
        $id = $this->parseRouteId($path);
        $name = $this->parseRouteName($path);
        $route = new Route($id, $name);
        $stations = $this->parseStations($path);
        foreach ($stations as $stationId => $stationName) {
            $route->addStation(new Station($stationId, $stationName));
        }

        return $route;
    }

    protected function parseRouteId(\DOMXPath $path)
    {
        $inputs = $path->query('//input[@name="nucleo"]');
        $value = $inputs->item(0)->attributes->getNamedItem("value")->textContent;

        return (int) $value;
    }

    protected function parseRouteName(\DOMXPath $path)
    {
        $titles = $path->query('//title');
        $title = $titles->item(0)->textContent;
        $titleParts = explode("  ", $title);

        return utf8_decode($titleParts[1]);
    }


    protected function parseStations(\DOMXPath $path)
    {
        $stations = array();
        $options = $path->query('//select[@name="o"]/option');
        for ($i = 0; $i < $options->length; $i += 1) {
            $stationName = $options->item($i)->textContent;
            $stationId = $options->item($i)->attributes->getNamedItem("value")->nodeValue;
            if ($stationId !== "?") {
                $stations[$stationId] = $stationName;
            }
        }

        return $stations;
    }

    protected function prepareFakeRoute()
    {
        for ($i = 1; $i <= 29; $i += 1) {
            $this->route->addStation(new Station($i, "Default Station {$i}"));
        }
        $this->route->addStation(new Station(11409, "Default Station 11409"));
    }

    public function getRoute()
    {
        return $this->route;
    }
}
