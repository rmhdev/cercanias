<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Provider\HorariosRenfeCom\Parser;

use Cercanias\Provider\HorariosRenfeCom\Parser\TimetableTripsParser;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class TimetableTripsParserTest extends \PHPUnit_Framework_TestCase
{
    public function testItParsesDepartureSimpleTrips()
    {
        $parser = new TimetableTripsParser($this->getSimpleTimetableData());

        $this->assertFalse($parser->hasTransfers());
        $trips = $parser->trips();
        $this->assertEquals(2, count($trips));

        $this->assertEquals("C1", $trips[0]["line"]);
        $this->assertEquals("05:33", $trips[0]["departure"]);
        $this->assertEquals("06:34", $trips[0]["arrival"]);
        $this->assertEquals("1:01", $trips[0]["duration"]);
        $this->assertEquals("", $trips[0]["description"]);

        $this->assertEquals("C1", $trips[1]["line"]);
        $this->assertEquals("13:24", $trips[1]["departure"]);
        $this->assertEquals("14:16", $trips[1]["arrival"]);
        $this->assertEquals("0:52", $trips[1]["duration"]);
        $this->assertEquals("CIVIS", $trips[1]["description"]);
    }

    public function getSimpleTimetableData()
    {
        return <<<HTML
<table border="0" width="95%" cellpadding="1" cellspacing="1" align="center" id="tabla" class="horarios">
		<tbody>
			 
			        <tr>          
			          	<td class="cabe" align="center"> Línea </td>
			             <td class="cabe" align="center"> </td> 
			            <td class="cabe" align="center"> Hora Salida  </td>
			            <td class="cabe" align="center"> Hora Llegada  </td>           
			            <td class="cabe" align="center"> Tiempo de Viaje </td>
			        </tr>
		        
			        <tr class="par">
			            <td class="linea-cercanias _61C1" align="center" name="codLinea">
			            	 C1   
			            </td>
			            
			            <td align="center"><span class="rojo4"> </span>
			            	
			            </td>
			            
			            <td align="center">05.33</td>
			            <td align="center">06.34</td>            
			            <td align="center">1.01</td>
			        </tr>
		        
			        
		        
			        
		        
			        <tr class="impar">
			            <td class="linea-cercanias _61C1" align="center" name="codLinea">
			            	 C1   
			            </td>
			            
			            <td align="center"><span class="rojo4">CIVIS</span>
			            	
			            </td>
			            
			            <td align="center">13.24</td>
			            <td align="center">14.16</td>            
			            <td align="center">0.52</td>
			        </tr>
		</tbody>		  
	</table>
HTML;

    }
}
