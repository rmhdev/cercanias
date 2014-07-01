<?php

namespace Cercanias\Provider;

interface QueryInterface
{
    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return string
     */
    public function generateUrl();
}
