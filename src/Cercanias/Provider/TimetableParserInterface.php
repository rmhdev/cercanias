<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
interface TimetableParserInterface
{
    /**
     * @return \DateTime
     */
    public function getDate();

    /**
     * @return string
     */
    public function getDepartureName();

    /**
     * @return string
     */
    public function getDestinationName();

    /**
     * @param int $transferLinkNumer
     * @return string
     */
    public function getTransferName($transferLinkNumer = 0);

    /**
     * @return \ArrayIterator
     */
    public function getTrips();
}
