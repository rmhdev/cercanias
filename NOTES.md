# Notes

## Entities:

* Route:
    * Id.
    * Name.
    * list of stations.
* Station: place of departure or arrival.
    * Id.
    * Name.
    * Extra information?
* Timetable:
    * Station of departure.
    * Station of arrival.
    * List of departures.
* Departure: information.
    * Line.
    * Time of departure.
    * Duration.
    * Extra information.

## URLs

Base url:

`http://horarios.renfe.com/cer/hjcer310.jsp`

Parameters:

* `nucleo`
    * Type: integer.
    * Description: Route id.
    * Example: 61 for San Sebastián.
* `i`
    * Type: string.
    * Description: ?
    * Default: `s`.
* `cp`
    * Type: string.
    * Description: ?
    * Default: `NO`.
* `o`
    * Type: integer.
    * Description: id of departure station. "Origen".
    * Example: `1234`.
* `d`
    * Type: integer.
    * Description: id of arrival station. "Destino".
    * Example: `5678`.
* `df`
    * Type: string.
    * Description: timetable's date in `YYYYMMDD` format.
    * Example: `20140120` -> year: 2014, month: 01, day: 20.
* `ho`
    * Type: string.
    * Description: filter departure hour, from 01 to 24. "Hora origen".
    * Default: 00 means "all results"
    * Example: `09` -> get results with departure hour >= 09:00.
* `hd`
    * Type: string.
    * Description: filter arrival hour, from 00 to 25. "Hora destino".
    * Default: 26 means "all results"
    * Example: `09` -> get results with departure hour <= 09:00.
* `TXTInfo`
    * Type: string.
    * Description: ?
    * Default: ""

## Routes

There is a fixed number of routes.

* `XY`: Asturias
* `XY`: Barcelona
* `XY`: Bilbao
* `XY`: Cadiz
* `10`: Madrid
* `XY`: Málaga
* `XY`: Murcia/Alicante
* `61`: San Sebastián
* `XY`: Santander
* `XY`: Sevilla
* `XY`: Valencia
* `XY`: Zaragoza
