<?php

namespace Cercanias;

class Route
{

    protected $slug;

    public function __construct($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug()
    {
        return $this->slug;
    }
}