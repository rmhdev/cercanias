<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider\HorariosRenfeCom;

use Cercanias\Entity\Station;
use Cercanias\Exception\NotFoundException;
use Cercanias\Provider\AbstractRouteParser;
use Cercanias\Provider\RouteParserInterface;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class RouteParser extends AbstractRouteParser implements RouteParserInterface
{
    /**
     * {@inheritDoc}
     */
    public function __construct($html)
    {
        parent::__construct($html);
        $previousState = libxml_use_internal_errors(true);
        $domDocument = new \DOMDocument("1.0", "utf-8");
        $domDocument->loadHTML($html);
        $this->parseValues(new \DOMXPath($domDocument));
        libxml_clear_errors();
        libxml_use_internal_errors($previousState);
    }

    protected function parseValues(\DOMXPath $path)
    {
        $this->setRouteId($this->parseRouteId($path));
        $this->setRouteName($this->parseRouteName($path));
        $stations = $this->parseStations($path);
        if (!sizeof($stations)) {
            throw new NotFoundException("No stations found in Route");
        }
        foreach ($stations as $stationId => $stationName) {
            $this->addStation(
                new Station($stationId, $stationName, $this->getRouteId())
            );
        }
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
