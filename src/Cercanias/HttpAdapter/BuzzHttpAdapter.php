<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\HttpAdapter;

use Buzz\Browser;

/**
 * Based on https://github.com/geocoder-php/Geocoder
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class BuzzHttpAdapter implements HttpAdapterInterface
{
    private $browser;

    public function __construct(Browser $browser = null)
    {
        $this->browser = (null === $browser) ? new Browser() : $browser;
    }

    /**
     * {@inheritDoc}
     */
    public function getContent($url)
    {
        $content = null;
        if ($url) {
            try {
                $response = $this->getBrowser()->get($url);
                $content = $response->getContent();
            } catch (\Exception $e) {
                $content = null;
            }
        }

        return $content;
    }

    private function getBrowser()
    {
        return $this->browser;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'buzz';
    }
}
