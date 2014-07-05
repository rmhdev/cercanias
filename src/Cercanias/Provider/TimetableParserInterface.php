<?php

namespace Cercanias\Provider;

interface TimetableParserInterface
{
    /**
     * @return \DateTime
     */
    public function getDate();

    /**
     * @return string
     */
    public function getDepartureName();

    /**
     * @return string
     */
    public function getDestinationName();

    /**
     * @return string
     */
    public function getTransferName();

    /**
     * @return \ArrayIterator
     */
    public function getTrips();
}
