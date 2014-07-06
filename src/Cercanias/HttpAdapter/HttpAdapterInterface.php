<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\HttpAdapter;

/**
 * Based on https://github.com/geocoder-php/Geocoder
 * @author Rober Martín H <rmh.dev@gmail.com>
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
