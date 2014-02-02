<?php

namespace Cercanias;

use Cercanias\Exception\InvalidArgumentException;

class Station
{
    protected
        $id,
        $name;

    public function __construct($id, $name)
    {
        if ($this->isInvalidId($id)) {
            throw new InvalidArgumentException("Invalid Id");
        }
        $name = $this->cleanName($name);
        if ($this->isInvalidName($name)) {
            throw new InvalidArgumentException("Name can't be empty");
        }
        $this->id = $id;
        $this->name = $name;
    }

    protected function cleanName($name)
    {
        return trim($name);
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
}
