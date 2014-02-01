<?php

namespace Cercanias;

use Cercanias\Exception\InvalidArgumentException;

class City
{
    protected $name;

    public function __construct($name)
    {
        if (!is_string($name) || strlen($name) <= 0) {
            throw new InvalidArgumentException("Name can't be empty");
        }
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
