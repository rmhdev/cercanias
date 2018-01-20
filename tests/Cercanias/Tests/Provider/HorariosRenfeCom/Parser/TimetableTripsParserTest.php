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

        $this->assertFalse($parser->hasTransfer());
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

    public function testItParsesTimetableWithTransfer()
    {
        $parser = new TimetableTripsParser($this->getTimetableWithTransferData());
        $this->assertTrue($parser->hasTransfer());
        $this->assertEquals("Chamartin", $parser->transferStationName());
    }

    /**
     * @dataProvider getIncorrectHeaderDataProvider
     * @expectedException \Cercanias\Exception\ParseException
     */
    public function testItThrowsExceptionWhenTimetableHasIncorrectHeader($header, $comments)
    {
        new TimetableTripsParser($header);
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

    public function getTimetableWithTransferData()
    {
        return <<<HTML
<table border="0" width="95%" cellpadding="1" cellspacing="1" align="center" id="tabla" class="horarios">
		<tbody>
			 
		        	<tr>
			            <td valign="center" class="cabe" align="center" rowspan="3">Línea</td>
			             <td valign="center" class="cabe" align="center" rowspan="3"></td>  
			            <td valign="center" class="cabe" align="center" rowspan="3">Salida<br>Origen </td>
			            <td class="cabe" colspan="2" align="center">Transbordo en</td>
			            <td valign="center" class="cabe" align="center" rowspan="3">Línea</td>
			             <td valign="center" class="cabe" align="center" rowspan="3"></td>  
			            <td valign="center" class="cabe" align="center" rowspan="3">Llegada<br>Destino </td>	           
			            <td valign="center" class="cabe" align="center" rowspan="3">Tiempo de Viaje</td>
		          	</tr>
			        <tr>
			            <td class="cabe" colspan="2" align="center">Chamartin                               </td>
			        </tr>
			        <tr>
			            <td class="cabe" align="center">Llegada</td>
			            <td class="cabe" align="center">Salida</td>
			        </tr>
	          	
		          	<tr class="par">
		            	<td class="linea-cercanias _10C1" align="center" name="codLinea"> 
		            		 C1    
		            	</td>
		            	
			 			<td align="center">
			 				<span class="rojo4"> </span>
			            	
			            </td>
			            
		            	<td class="" align="center"> 05.56 </td>
						
			            	<td class="" align="center"> 06.11</td>
			            	<td class="" align="center"> 06.14 </td>
			 				
			 			<td class="linea-cercanias _10C4B" align="center" name="codLinea"> 
			 				 C4B   
			 			</td>
			 			
			            <td align="center">
			            <span class="rojo4"> </span>
			            	
			            </td>
			            
			            <td class="" align="center"> 06.19</td>            
			            <td class="" align="center"> 0.23 </td>
		          	</tr>
	          	
		          	<tr class="impar">
		            	<td class="linea-cercanias _10" align="center" name="codLinea"> 
		            		   
		            	</td>
		            	
			 			<td align="center">
			 				<span class="rojo4"> </span>
			            	
			            </td>
			            
		            	<td class="" align="center">  </td>
						
			            	<td class="" align="center"> </td>
			            	<td class="" align="center"> 06.25 </td>
			 				
			 			<td class="linea-cercanias _10C4A" align="center" name="codLinea"> 
			 				 C4A   
			 			</td>
			 			
			            <td align="center">
			            <span class="rojo4"> </span>
			            	
			            </td>
			            
			            <td class="" align="center"> 06.30</td>            
			            <td class="" align="center"> 0.34 </td>
		          	</tr>
	          	
		          	<tr class="par">
		            	<td class="linea-cercanias _10C10" align="center" name="codLinea"> 
		            		 C10   
		            	</td>
		            	
			 			<td align="center">
			 				<span class="rojo4"> </span>
			            	
			            </td>
			            
		            	<td class="" align="center"> 09.16 </td>
						
			            	<td class="" align="center"> 09.31</td>
			            	<td class="" align="center"> 09.38 </td>
			 				
			 			<td class="linea-cercanias _10C4B" align="center" name="codLinea"> 
			 				 C4B   
			 			</td>
			 			
			            <td align="center">
			            <span class="rojo4"> </span>
			            	
			            </td>
			            
			            <td class="" align="center"> 09.43</td>            
			            <td class="" align="center"> 0.27 </td>
		          	</tr>
	          	
		          	<tr class="impar">
		            	<td class="linea-cercanias _10C1" align="center" name="codLinea"> 
		            		 C1    
		            	</td>
		            	
			 			<td align="center">
			 				<span class="rojo4"> </span>
			            	
			            </td>
			            
		            	<td class="" align="center"> 09.25 </td>
						
			            	<td class="" align="center"> 09.40</td>
			            	<td class="" align="center"> 09.44 </td>
			 				
			 			<td class="linea-cercanias _10C4A" align="center" name="codLinea"> 
			 				 C4A   
			 			</td>
			 			
			            <td align="center">
			            <span class="rojo4"> </span>
			            	
			            </td>
			            
			            <td class="" align="center"> 09.49</td>            
			            <td class="" align="center"> 0.24 </td>
		          	</tr>
	          	
		          	<tr class="par">
		            	<td class="linea-cercanias _10" align="center" name="codLinea"> 
		            		   
		            	</td>
		            	
			 			<td align="center">
			 				<span class="rojo4"> </span>
			            	
			            </td>
			            
		            	<td class="" align="center">  </td>
						
			            	<td class="" align="center"> </td>
			            	<td class="" align="center"> 09.52 </td>
			 				
			 			<td class="linea-cercanias _10C4B" align="center" name="codLinea"> 
			 				 C4B   
			 			</td>
			 			
			            <td align="center">
			            <span class="rojo4"> </span>
			            	
			            </td>
			            
			            <td class="" align="center"> 09.57</td>            
			            <td class="" align="center"> 0.32 </td>
		          	</tr>
		</tbody>		  
	</table>
HTML;
    }

    public function getIncorrectHeaderDataProvider()
    {
        return array(
            array(
                '<table><tbody><tr></tr></tbody></table>',
                'No columns'
            ),
            array(
                '<table><tbody><tr><td></td></tr></tbody></table>',
                'Few columns'
            ),
            array(
                '<table><tbody><tr><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>',
                'Too many columns'
            ),
        );
    }
}
