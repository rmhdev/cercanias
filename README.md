# Cercanias

Retrieve Renfe's *Cercanías*[1] information easily.

[1]: Spain's commuter trains service.

[![Build Status](https://travis-ci.org/rmhdev/cercanias.svg?branch=master)](https://travis-ci.org/rmhdev/cercanias)

## Requirements

The `Cercanias` library has the following requirements:

- `PHP 5.3+`

Depending on the chosen `HttpAdapter`, you may need:

- `php5-curl`
- `kriswallsmith/Buzz`

## Installation

The recommended way to install `Cercanias` is through [Composer][].

Create a `composer.json` file:

``` json
{
    "require": {
        "rmhdev/cercanias": "0.1.*"
    }
}
```

Then install `Composer`:

``` bash
$ curl -sS https://getcomposer.org/installer | php
$ composer install
```

After that, the autoloader will be available:

``` php
<?php
require 'vendor/autoload.php';
```

## How to use it

1. Choose a `HttpAdapter`
2. Choose a `Provider`
3. Create a `Cercanias` object and call `getRoute` or `getTimetable`.

For example, if you want to retrieve all the stations from a route (*San Sebastián*):

``` php
<?php
require 'vendor/autoload.php';

use Cercanias\HttpAdapter\CurlHttpAdapter;
use Cercanias\Provider\HorariosRenfeCom\Provider;
use Cercanias\Cercanias;

$httpAdapter  = new CurlHttpAdapter();          // 1. HttpAdapter
$provider     = new Provider($httpAdapter);     // 2. Provider
$cercanias    = new Cercanias($provider);       // 3. Cercanias
$route        = $cercanias->getRoute(Provider::ROUTE_SAN_SEBASTIAN);
```

If you want to know all the trips from *Brinkola* to *Irun* for *tomorrow*:

``` php
<?php
require 'vendor/autoload.php';

use Cercanias\HttpAdapter\CurlHttpAdapter;
use Cercanias\Provider\HorariosRenfeCom\Provider;
use Cercanias\Provider\TimetableQuery;
use Cercanias\Cercanias;

$query = new TimetableQuery();
$query
    ->setRoute(Provider::ROUTE_SAN_SEBASTIAN)
    ->setDeparture("11305")   // from Brinkola
    ->setDestination("11600") // to Irun
    ->setDate(new \DateTime("tomorrow"));

$httpAdapter  = new CurlHttpAdapter();              // 1. HttpAdapter
$provider     = new Provider($httpAdapter);         // 2. Provider
$cercanias    = new Cercanias($provider);           // 3. Cercanias
$timetable    = $cercanias->getTimetable($query);
```

View more [examples](examples).

## Basic classes

### `HttpAdapter`

Responsible for making the HTTP connection. Available adapters:

- `CurlHttpAdapter`: cURL
- `BuzzHttpAdapter`: Kris Wallsmith's [Buzz] HTTP client.

### `Provider`

Indicates where is the information taken. Available providers:

- `Provider\HorariosRenfeCom\Provider`: Cercanias' [default web page]

### Results

- `Route`: Information about a specific route.
- `Timetable`: Information about trips between stations for a given day.

### Other classes

- `Station`: information about a station from a `Route`.
- `Trip`: information about a trip from a `Timetable`.
- `Train`: information about departure and arrival time for a train in a `Trip`.

## Changelog

* `0.0.1` (June 21, 2014): initial release.
* `0.0.2` (June 22, 2014): added `BuzzHttpAdapter`.
* `0.0.3` (June 24, 2014): simplify timetable queries.
* `0.0.4` (June 29, 2014): fix bugs, improve naming and parsing.
* `0.0.5` (July 01, 2014): improve queries generation.
* `0.0.6` (July 06, 2014): add `Cercanias` class to simplify usage.
* `0.1.0` (July 06, 2014): add library to [Packagist][].

## Copyright and license

Code and documentation copyright 2014 Rober Martín H.
Code released under [MIT license](LICENSE).
Docs released under [Creative Commons CC BY 4.0][].

Part of the project is based on [William Durand]'s [Geocoder][].

## Author

My name is [Rober Martín H][] ([@rmhdev][]). I'm a developer from Donostia / San Sebastián.

[Composer]: https://getcomposer.org/
[Packagist]: https://packagist.org/
[Buzz]: https://github.com/kriswallsmith/Buzz
[default web page]: http://www.renfe.com/viajeros/cercanias/
[Creative Commons CC BY 4.0]: http://creativecommons.org/licenses/by/4.0/
[William Durand]: http://williamdurand.fr/
[Geocoder]: https://github.com/geocoder-php/Geocoder
[Rober Martín H]: http://rmhdev.net/
[@rmhdev]: http://twitter.com/rmhdev
