<?php

namespace Cercanias\HttpAdapter;

/**
 * Based on https://github.com/geocoder-php/Geocoder
 * Interface HttpAdapterInterface
 * @package Cercanias\HttpAdapter
 */
interface HttpAdapterInterface
{
    /**
     * Returns content from a given url
     * @param string $url
     * @return string
     */
    public function getContent($url);

    /**
     * Returns the name of the HttpAdapter
     * @return string
     */
    public function getName();
}
