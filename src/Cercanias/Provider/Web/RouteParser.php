<?php

namespace Cercanias\Provider\Web;

use Cercanias\Exception\NotFoundException;
use Cercanias\Route;
use Cercanias\Provider\AbstractRouteParser;
use Cercanias\Provider\RouteParserInterface;

class RouteParser extends AbstractRouteParser implements RouteParserInterface
{

    public function __construct($html)
    {
        $previousState = libxml_use_internal_errors(true);
        $domDocument = new \DOMDocument("1.0", "utf-8");
        $domDocument->loadHTML($html);
        $this->setRoute(
            $this->createRoute(new \DOMXPath($domDocument))
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previousState);
    }

    protected function createRoute(\DOMXPath $path)
    {
        $stations = $this->parseStations($path);
        if (!sizeof($stations)) {
            throw new NotFoundException("no found");
        }
        $route = new Route(
            $this->parseRouteId($path),
            $this->parseRouteName($path)
        );
        foreach ($stations as $stationId => $stationName) {
            $route->addNewStation($stationId, $stationName);
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
            $stationId = (string) $options->item($i)->attributes->getNamedItem("value")->nodeValue;
            if ($stationId !== "?") {
                $stations[$stationId] = $stationName;
            }
        }

        return $stations;
    }

}
