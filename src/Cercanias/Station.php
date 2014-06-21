<?php

namespace Cercanias;

use Cercanias\Exception\InvalidArgumentException;

class Station
{
    protected $id;
    protected $name;
    protected $routeId;

    public function __construct($id, $name, $routeId)
    {
        $this->setId($id);
        $this->setName($name);
        $this->setRouteId($routeId);
    }

    protected function setId($id)
    {
        if ($this->isInvalidId($id)) {
            throw new InvalidArgumentException("Invalid Id");
        }
        $this->id = $id;
    }

    protected function isInvalidId($id)
    {
        return (!is_integer($id) || $id <= 0);
    }

    protected function setName($name)
    {
        $name = trim($name);
        if ($this->isInvalidName($name)) {
            throw new InvalidArgumentException("Name can't be empty");
        }
        $this->name = $name;
    }

    protected function isInvalidName($name)
    {
        return (!is_string($name) || strlen($name) <= 0);
    }

    protected function setRouteId($routeId)
    {
        if ($this->isInvalidId($routeId)) {
           throw new InvalidArgumentException("Invalid RouteId");
        }
        $this->routeId = $routeId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getRouteId()
    {
        return $this->routeId;
    }
}
