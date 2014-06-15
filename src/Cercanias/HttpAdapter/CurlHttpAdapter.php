<?php

namespace Cercanias\HttpAdapter;

class CurlHttpAdapter implements HttpAdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function getContent($url)
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'curl';
    }

}
