# Notes

Basic information about data providers.

## horarios.renfe.com

### Timetables

Base url:

`http://horarios.renfe.com/cer/hjcer310.jsp`

Parameters:

* `nucleo`
    * Type: integer.
    * Description: Route id.
    * Example: 61 for *San Sebastián*.
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

### Routes

Base url:

`http://horarios.renfe.com/cer/hjcer300.jsp`

Parameters:

* `NUCLEO`
    * Type: string.
    * Description: Station id.
    * Example: `17000` for *Chamartin*.
* `CP`
    * Type: string.
    * Description: ?
    * Default: "NO"
* `I`
    * Type: string.
    * Description: ?
    * Default: "s"


## Information about routes

There is a fixed number of routes.

* `20`: Asturias
* `50`: Barcelona
* `60`: Bilbao
* `31`: Cadiz
* `10`: Madrid
* `32`: Málaga
* `41`: Murcia/Alicante
* `61`: San Sebastián
* `62`: Santander
* `30`: Sevilla
* `40`: Valencia
* `70`: Zaragoza
