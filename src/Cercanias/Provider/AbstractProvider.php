<?php
/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Provider;

use Cercanias\HttpAdapter\HttpAdapterInterface;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
abstract class AbstractProvider
{
    const ROUTE_ASTURIAS = 20;
    const ROUTE_BARCELONA = 50;
    const ROUTE_BILBAO = 60;
    const ROUTE_CADIZ = 31;
    const ROUTE_MADRID = 10;
    const ROUTE_MALAGA = 32;
    const ROUTE_MURCIA_ALICANTE = 41;
    const ROUTE_SAN_SEBASTIAN = 61;
    const ROUTE_SANTANDER = 62;
    const ROUTE_SEVILLA = 30;
    const ROUTE_VALENCIA = 40;
    const ROUTE_ZARAGOZA = 70;

    private $httpAdapter;

    /**
     * @param HttpAdapterInterface $httpAdapter
     */
    public function __construct(HttpAdapterInterface $httpAdapter)
    {
        $this->httpAdapter = $httpAdapter;
    }

    /**
     * @return HttpAdapterInterface
     */
    protected function getHttpAdapter()
    {
        return $this->httpAdapter;
    }

    public static function getRoutes()
    {
        return array(
            self::ROUTE_ASTURIAS        => "Asturias",
            self::ROUTE_BARCELONA       => "Barcelona",
            self::ROUTE_BILBAO          => "Bilbao",
            self::ROUTE_CADIZ           => "Cádiz",
            self::ROUTE_MADRID          => "Madrid",
            self::ROUTE_MALAGA          => "Málaga",
            self::ROUTE_MURCIA_ALICANTE => "Murcia-Alicante",
            self::ROUTE_SAN_SEBASTIAN   => "San Sebastián",
            self::ROUTE_SANTANDER       => "Santander",
            self::ROUTE_SEVILLA         => "Sevilla",
            self::ROUTE_VALENCIA        => "Valencia",
            self::ROUTE_ZARAGOZA        => "Zaragoza",
        );
    }
}
