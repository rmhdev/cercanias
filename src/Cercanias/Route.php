<?php

namespace Cercanias;

use Cercanias\Exception\InvalidArgumentException;

class Route
{

    protected $slug;

    public function __construct($slug)
    {
        if ($this->isInvalidSlug($slug)) {
            throw new InvalidArgumentException("Invalid slug");
        }
        $this->slug = $slug;
    }

    protected function isInvalidSlug($slug)
    {
        return (!is_string($slug) || strlen($slug) <= 0);
    }

    public function getSlug()
    {
        return $this->slug;
    }
}