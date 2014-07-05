<?php

namespace Cercanias\Entity;

use Cercanias\Exception\InvalidArgumentException;

class Station
{
    protected $id;
    protected $name;
    protected $routeId;

    /**
     * @param string $id
     * @param string $name
     * @param int $routeId
     */
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
        $this->id = (string) $id;
    }

    protected function isInvalidId($id)
    {
        return (is_null($id) || ($id == "")) ;
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

    /**
     * @return string
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
    public function getRouteId()
    {
        return $this->routeId;
    }
}
