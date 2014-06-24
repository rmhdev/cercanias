# Cercanias

Retrieve Renfe's *Cercanías* information easily.

**THIS PROJECT IS IN ALPHA STAGE**. Please use it carefully.

## Requirements

The `Cercanias` library has the following requirements:

- `PHP 5.3+`

Depending on the chosen `HttpAdapter`, you may need:

- `php5-curl`
- `kriswallsmith/Buzz`

## Installation

Clone the repo: `git clone https://github.com/rmhdev/cercanias.git`

Install `Composer`:

``` bash
$ curl -sS https://getcomposer.org/installer | php
$ composer install
```

After that, the autoloader will be available:

``` php
<?php
require 'vendor/autoload.php';
```

## Basic classes

### `HttpAdapter`

Responsible for making the HTTP connection. Available adapters:

- `CurlHttpAdapter`: cURL
- `BuzzHttpAdapter`: Kris Wallsmith's [Buzz] HTTP client.

### `Provider`

Indicates where is the information taken. Available providers:

- `Provider\Web\Provider`: Cercanias' [default web page]

### Results

- `Route`: Information about a specific route.
- `Timetable`: Information about trips between stations for a given day.

### Other classes

- `Station`: information about a station from a `Route`.
- `Trip`: information about a trip from a `Timetable`.
- `Train`: information about departure and arrival time for a train in a `Trip`.

## How to use it

1. Choose a `HttpAdapter`
2. Choose a `Provider`
3. Make the call and retrieve the information.

For example:

``` php
<?php
require 'vendor/autoload.php';

use Cercanias\HttpAdapter\CurlHttpAdapter;
use Cercanias\Provider\Web\Provider;

$httpAdapter  = new CurlHttpAdapter();                              // 1. HttpAdapter
$provider     = new Provider($httpAdapter);                         // 2. Provider
$route        = $provider->getRoute(Provider::ROUTE_SAN_SEBASTIAN); // 3. Call
```

View more [examples](examples).

## Changelog

* `0.0.1` (June 21, 2014): initial release.
* `0.0.2` (June 22, 2014): added ´BuzzHttpAdapter´.
* `0.0.3` (June 24, 2014): simplify Timetable queries.

## Copyright and license

Code and documentation copyright 2014 Rober Martín H.
Code released under [MIT license](LICENSE).
Docs released under [Creative Commons CC BY 4.0][].

Part of the project is based on [William Durand]'s [Geocoder][].

## Author

My name is [Rober Martín H][] ([@rmhdev][]). I'm a developer from Donostia / San Sebastián.

[Buzz]: https://github.com/kriswallsmith/Buzz
[default web page]: http://www.renfe.com/viajeros/cercanias/
[Creative Commons CC BY 4.0]: http://creativecommons.org/licenses/by/4.0/
[William Durand]: http://williamdurand.fr/
[Geocoder]: https://github.com/geocoder-php/Geocoder
[Rober Martín H]: http://rmhdev.net/
[@rmhdev]: http://twitter.com/rmhdev
