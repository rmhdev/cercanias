<?php

namespace Cercanias;

use Cercanias\Exception\InvalidArgumentException;

class Route
{

    protected
        $id,
        $name,
        $stations = array();

    public function __construct($id, $name)
    {
        if ($this->isInvalidId($id)) {
            throw new InvalidArgumentException("Invalid Id");
        }
        $this->id = $id;
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
        return (!is_string($name) || strlen($name) <= 0);
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
        $this->stations[] = $station;
    }
}