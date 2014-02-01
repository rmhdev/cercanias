<?php

namespace Cercanias;

use Cercanias\Exception\InvalidArgumentException;

class Route
{

    protected $slug;

    public function __construct($slug)
    {
        if (strlen($slug) <= 0) {
            throw new InvalidArgumentException("Invalid slug");
        }
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }
}