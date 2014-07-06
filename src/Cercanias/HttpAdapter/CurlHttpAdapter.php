<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\HttpAdapter;

use Cercanias\Exception\ExtensionNotLoadedException;

/**
 * Based on https://github.com/geocoder-php/Geocoder
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class CurlHttpAdapter implements HttpAdapterInterface
{
    private $curlOptions;

    public function __construct($curlOptions = array())
    {
        $this->curlOptions = $curlOptions;
    }

    /**
     * {@inheritDoc}
     */
    public function getContent($url)
    {
        if (!function_exists('curl_init')) {
            throw new ExtensionNotLoadedException("cURL has to be enabled");
        }
        $c = curl_init();
        curl_setopt_array($c, $this->prepareCurlOptions($url));
        $content = curl_exec($c);
        curl_close($c);

        return (false === $content) ? null : $content;
    }

    protected function prepareCurlOptions($url)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url
        );
        if ($this->curlOptions) {
            $options = $options + $this->curlOptions;
        }

        return $options;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'curl';
    }
}
