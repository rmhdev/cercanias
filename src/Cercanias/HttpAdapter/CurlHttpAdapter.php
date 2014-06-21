<?php

namespace Cercanias\HttpAdapter;

use Cercanias\Exception\ExtensionNotLoadedException;

/**
 * Based on https://github.com/geocoder-php/Geocoder
 * Class CurlHttpAdapter
 * @package Cercanias\HttpAdapter
 */
class CurlHttpAdapter implements HttpAdapterInterface
{

    private $timeout;
    private $connectTimeout;

    public function __construct($timeout = null, $connectTimeout = null)
    {
        $this->timeout = $timeout;
        $this->connectTimeout = $connectTimeout;
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
        curl_setopt_array($c, $this->getCurlOptions($url));
        $content = curl_exec($c);
        curl_close($c);

        return (false === $content) ? null : $content;
    }

    protected function getCurlOptions($url)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url
        );
        if ($this->timeout) {
            $options[CURLOPT_TIMEOUT] = $this->timeout;
        }
        if ($this->connectTimeout) {
            $options[CURLOPT_CONNECTTIMEOUT] = $this->connectTimeout;
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
