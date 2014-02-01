<?php

namespace Cercanias;

use Cercanias\Exception\InvalidArgumentException;

class Route
{

    protected
        $id,
        $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        if ($this->isInvalidName($name)) {
            throw new InvalidArgumentException("Invalid name");
        }
        $this->name = $name;
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