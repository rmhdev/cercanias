<?php

namespace Cercanias;

use Cercanias\Exception\InvalidArgumentException;

class City
{
    protected $name;

    public function __construct($name)
    {
        if ($this->isInvalidName($name)) {
            throw new InvalidArgumentException("Name can't be empty");
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
