<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider\HorariosRenfeCom\Parser;

use Cercanias\Exception\NotFoundException;
use Cercanias\Exception\ServiceUnavailableException;

class TimetableAssertParser
{
    private $hasTrips;

    public function __construct($html)
    {
        $previousState = libxml_use_internal_errors(true);
        $domDocument = new \DOMDocument("1.0", "utf-8");
        $domDocument->loadHTML($html);
        $path = new \DOMXPath($domDocument);
        $this->parse($path);
        libxml_clear_errors();
        libxml_use_internal_errors($previousState);
    }

    protected function parse(\DOMXPath $path)
    {
        $this->hasTrips = false;
        if ($path->query('//table')->length <= 0) {
            $unavailable = $path->query('//div[@class="lista_cuadradorosa posicion_cuadrado"]');
            if ($unavailable->length) {
                throw new ServiceUnavailableException(trim($unavailable->item(0)->textContent));
            }
            throw new NotFoundException("HTML has no timetable results");
        }
        $this->hasTrips = true;
    }

    public function hasTrips()
    {
        return $this->hasTrips;
    }
}
