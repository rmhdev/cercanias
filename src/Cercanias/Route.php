<?php

namespace Cercanias;

use Cercanias\Exception\InvalidArgumentException;

class Route
{

    protected $name;

    public function __construct($name)
    {
        if ($this->isInvalidName($name)) {
            throw new InvalidArgumentException("Invalid name");
        }
        $this->name = $name;
    }

    protected function isInvalidName($name)
    {
        return (!is_string($name) || strlen($name) <= 0);
    }

    public function getName()
    {
        return $this->name;
    }
}