<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider\HorariosRenfeCom;

use Cercanias\Provider\HorariosRenfeCom\Parser\TimetableAssertParser;
use Cercanias\Provider\HorariosRenfeCom\Parser\TimetableBasicInfoParser;
use Cercanias\Provider\HorariosRenfeCom\Parser\TimetableTripsParser;
use Cercanias\Provider\TimetableParserInterface;
use Cercanias\Provider\AbstractTimetableParser;
use Cercanias\Entity\Train;
use Cercanias\Entity\Trip;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
final class TimetableParser extends AbstractTimetableParser implements TimetableParserInterface
{
    protected function processHTML($html)
    {
        $assert = new TimetableAssertParser($html);
        $basic = new TimetableBasicInfoParser($html);

        $this->setDate($this->createDateFromFormat("Y-m-d", $basic->date()));
        $this->setDepartureName($basic->departureStationName());
        $this->setDestinationName($basic->arrivalStationName());

        $tripParser = new TimetableTripsParser($html);
        $this->setTransferName($tripParser->transferStationName());

        foreach ($tripParser->trips() as $tripData) {
            $departure = $this->createDateFromTime($tripData["departure"]);
            $this->setFirstDateTimeIfFirst($departure);
            $train = new Train(
                $tripData["line"],
                $departure,
                $this->createDateFromTime($tripData["arrival"])
            );
            $transfers = [];
            foreach ($tripData["transfers"] as $transferData) {
                $transfers[] = new Train(
                    $transferData["line"],
                    $this->createDateFromTime($transferData["departure"]),
                    $this->createDateFromTime($transferData["arrival"])
                );
            }
            $this->addTrip(new Trip($train, $transfers));
        }
    }

    private function createDateFromTime($time)
    {
        if (!$this->getDate()) {
            throw new \UnexpectedValueException("There is not date defined");
        }
        $date = new \DateTime($this->getDate()->format("Y-m-d") . " " . $time);
        if ($this->isHourInNextDay($date->format("H"), $date->format("i"))) {
            $date = $date->modify("+1 day");
        }

        return $date;
    }
}
