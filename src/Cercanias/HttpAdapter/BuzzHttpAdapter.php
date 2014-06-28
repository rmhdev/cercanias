<?php

namespace Cercanias\HttpAdapter;

use Buzz\Browser;

class BuzzHttpAdapter implements HttpAdapterInterface
{

    private $browser;

    public function __construct(Browser $browser = null)
    {
        $this->browser = (null === $browser) ? new Browser() : $browser;
    }

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

    public function getName()
    {
        return 'buzz';
    }
}
