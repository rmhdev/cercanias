<?php

namespace Cercanias\Entity;

use Cercanias\Exception\DuplicateKeyException;
use Cercanias\Exception\InvalidArgumentException;
use Cercanias\Exception\NotFoundException;

class Route
{
    protected $id;
    protected $name;
    protected $stations;

    /**
     * @param int $id
     * @param string $name
     */
    public function __construct($id, $name)
    {
        $this->setId($id);
        $this->setName($name);
        $this->stations = array();
    }

    protected function setId($id)
    {
        if ($this->isInvalidId($id)) {
            throw new InvalidArgumentException("Invalid routeId");
        }
        $this->id = $id;
    }

    protected function setName($name)
    {
        $name = trim($name);
        if ($this->isInvalidName($name)) {
            throw new InvalidArgumentException("Invalid name");
        }
        $this->name = $name;
    }

    protected function isInvalidId($id)
    {
        return (!is_integer($id) || $id <= 0);
    }

    protected function isInvalidName($name)
    {
        return (!is_string($name) || strlen($name) === 0);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function countStations()
    {
        return sizeof($this->stations);
    }

    /**
     * @param Station $station
     * @throws \Cercanias\Exception\DuplicateKeyException
     */
    public function addStation(Station $station)
    {
        if ($this->hasStation($station->getId())) {
            throw new DuplicateKeyException("Station id already exists");
        }
        $this->stations[$station->getId()] = $station;
    }

    /**
     * @return \ArrayIterator
     */
    public function getStations()
    {
        return new \ArrayIterator($this->stations);
    }

    /**
     * @param $stationId
     * @return bool
     */
    public function hasStation($stationId)
    {
        return isset($this->stations[$stationId]);
    }

    /**
     * @param $stationId
     * @return Station
     * @throws \Cercanias\Exception\NotFoundException
     */
    public function getStation($stationId)
    {
        if (!$this->hasStation($stationId)) {
            throw new NotFoundException("Station {$stationId} not found in Route");
        }

        return $this->stations[$stationId];
    }
}
