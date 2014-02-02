<?php

namespace Cercanias;

use Cercanias\Exception\DuplicateKeyException;
use Cercanias\Exception\InvalidArgumentException;

class Route
{

    protected
        $id,
        $name,
        $stations;

    public function __construct($id, $name)
    {
        if ($this->isInvalidId($id)) {
            throw new InvalidArgumentException("Invalid Id");
        }
        if ($this->isInvalidName($name)) {
            throw new InvalidArgumentException("Invalid name");
        }
        $this->id = $id;
        $this->name = $name;
        $this->stations = array();
    }

    protected function isInvalidId($id)
    {
        return (!is_integer($id) || $id <= 0);
    }

    protected function isInvalidName($name)
    {
        return (!is_string($name) || strlen($name) === 0);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function countStations()
    {
        return sizeof($this->stations);
    }

    public function addStation(Station $station)
    {
        if (!$this->hasStation($station->getId())) {
            throw new DuplicateKeyException("Station id already exists");
        }
        $this->stations[$station->getId()] = $station;
    }

    public function getStations()
    {
        return new \ArrayIterator($this->stations);
    }

    public function hasStation($stationId)
    {
        return isset($this->stations[$stationId]);
    }
}
