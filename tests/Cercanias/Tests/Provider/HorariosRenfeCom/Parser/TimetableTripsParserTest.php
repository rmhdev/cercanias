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
use PHPUnit\Framework\TestCase;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class TimetableTripsParserTest extends TestCase
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

    public function testItParsesTimetableWithMultiTransfer()
    {
        $parser = new TimetableTripsParser($this->getTimetableWithTransferData());
        $this->assertTrue($parser->hasTransfer());
        $this->assertEquals("Chamartin", $parser->transferStationName());
        $trips = $parser->trips();
        $multiTransferTrip = $trips[0];

        $this->assertEquals("C1", $multiTransferTrip["line"]);
        $this->assertEquals("", $multiTransferTrip["description"]);
        $this->assertEquals("05:56", $multiTransferTrip["departure"]);
        $this->assertEquals("06:11", $multiTransferTrip["arrival"]);

        $transfers = $multiTransferTrip["transfers"];
        $this->assertEquals(3, sizeof($transfers));

        $this->assertEquals("06:14", $transfers[0]["departure"]);
        $this->assertEquals("C4B", $transfers[0]["line"]);
        $this->assertEquals("", $transfers[0]["description"]);
        $this->assertEquals("06:19", $transfers[0]["arrival"]);
        $this->assertEquals("0:23", $transfers[0]["duration"]);

        $this->assertEquals("06:25", $transfers[1]["departure"]);
        $this->assertEquals("C4A", $transfers[1]["line"]);
        $this->assertEquals("", $transfers[1]["description"]);
        $this->assertEquals("06:30", $transfers[1]["arrival"]);
        $this->assertEquals("0:34", $transfers[1]["duration"]);

        $this->assertEquals("06:31", $transfers[2]["departure"]);
        $this->assertEquals("C4Z", $transfers[2]["line"]);
        $this->assertEquals("", $transfers[2]["description"]);
        $this->assertEquals("06:36", $transfers[2]["arrival"]);
        $this->assertEquals("0:40", $transfers[2]["duration"]);
    }

    public function testItParsesTimetableWithSingleTransfer()
    {
        $parser = new TimetableTripsParser($this->getTimetableWithTransferData());
        $this->assertTrue($parser->hasTransfer());
        $this->assertEquals("Chamartin", $parser->transferStationName());
        $trips = $parser->trips();

        $singleTransferTrip = $trips[1];

        $this->assertEquals("C10", $singleTransferTrip["line"]);
        $this->assertEquals("", $singleTransferTrip["description"]);
        $this->assertEquals("09:16", $singleTransferTrip["departure"]);
        $this->assertEquals("09:31", $singleTransferTrip["arrival"]);

        $transfers = $singleTransferTrip["transfers"];
        $this->assertEquals(1, sizeof($transfers));

        $this->assertEquals("09:38", $transfers[0]["departure"]);
        $this->assertEquals("C4B", $transfers[0]["line"]);
        $this->assertEquals("", $transfers[0]["description"]);
        $this->assertEquals("0:27", $transfers[0]["duration"]);
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
		          	
		          	<tr class="impar">
		            	<td class="linea-cercanias _10" align="center" name="codLinea"> 
		            		   
		            	</td>
		            	
			 			<td align="center">
			 				<span class="rojo4"> </span>
			            	
			            </td>
			            
		            	<td class="" align="center">  </td>
						
			            	<td class="" align="center"> </td>
			            	<td class="" align="center"> 06.31 </td>
			 				
			 			<td class="linea-cercanias _10C4A" align="center" name="codLinea"> 
			 				 C4Z   
			 			</td>
			 			
			            <td align="center">
			            <span class="rojo4"> </span>
			            	
			            </td>
			            
			            <td class="" align="center"> 06.36</td>            
			            <td class="" align="center"> 0.40 </td>
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

    /**
     * @dataProvider getIncorrectHeaderDataProvider
     * @expectedException \Cercanias\Exception\ParseException
     */
    public function testItThrowsExceptionWhenTimetableHasIncorrectHeader($header, $comments)
    {
        new TimetableTripsParser($header);
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

    public function testItParsesSimpleTimetableHavingDifferentTimeFormats()
    {
        $parser = new TimetableTripsParser($this->getSimpleTimetableWithDifferentTimeFormats());

        $expected = array(
            "line" => "R3",
            "description" => "",
            "departure" => "07:14",
            "arrival" => "09:40",
            "duration" => "2:26",
        );
        $trips = $parser->trips();

        $this->assertEquals($expected, $trips[0]);
    }

    public function getSimpleTimetableWithDifferentTimeFormats()
    {
        return <<<HTML
<table id="tablaHorarios" width="95%" align="center" class="horarios" border="0" cellspacing="1" cellpadding="1">
    <thead>
        <tr>
            <td valign="center" align="center" class="cabe" rowspan="3"> Línea</td>
            <td valign="center" align="center" class="cabe" rowspan="3"> </td>
            <td valign="center" align="center" class="cabe" rowspan="3"> Time of Departure</td>
            <td valign="center" align="center" class="cabe" rowspan="3"> Time of Arrival</td>
            <td valign="center" align="center" class="cabe" rowspan="3"> Time of travel</td>
        </tr>
        <tr></tr>
        <tr></tr>
    </thead>
    <tbody>
        <tr class="par">
            <td align="center" name="codLinea" class="linea-cercanias _50R3">R3</td>
            <td align="center"> </td>
            <td align="center">07:14</td>
            <td align="center">09:40</td>
            <td align="center">2h 26min.</td>
        </tr>
    </tbody>
</table>
HTML;
    }

    public function testItParsesTimetableWithTransfersHavingDifferentTimeFormats()
    {
        $parser = new TimetableTripsParser($this->getTimetableWithTransfersHavingDiferentTimeFormats());
        $this->assertEquals("BARCELONA-SANTS", $parser->transferStationName());

        $trips = $parser->trips();
        $trip = $trips[0];

        $this->assertEquals("05:42", $trip["departure"]);
        $this->assertEquals("Tren accesible", $trip["description"]);
    }

    public function getTimetableWithTransfersHavingDiferentTimeFormats()
    {
        return <<<HTML
<table id="tablaHorarios" width="95%" align="center" class="horarios" border="0" cellspacing="1" cellpadding="1">
    <thead>
        <tr>
            <td valign="center" align="center" class="cabe" rowspan="3"> Línea</td>
            <td valign="center" align="center" class="cabe" rowspan="3"> </td>
            <td valign="center" align="center" class="cabe" rowspan="3"> Salida Origen</td>
            <td valign="center" align="center" class="cabe" colspan="2"> Transbordo en</td>
            <td valign="center" align="center" class="cabe" rowspan="3"> Línea</td>
            <td valign="center" align="center" class="cabe" rowspan="3"> </td>
            <td valign="center" align="center" class="cabe" rowspan="3"> Llegada Destino</td>
            <td valign="center" align="center" class="cabe" rowspan="3"> Time of travel</td>
        </tr>
        <tr>
            <td colspan="2" align="center" class="cabe">BARCELONA-SANTS</td>
        </tr>
        <tr>
            <td align="center" class="cabe">Llegada</td>
            <td align="center" class="cabe">Salida</td>
        </tr>
    </thead>
    <tbody>
        <tr class="par">
            <td align="center" name="codLinea" class="linea-cercanias _50R2N">R2N</td>
            <td align="center">
                <img src="/cer/img/ab18x18.jpg" style="vertical-align:middle;" alt="Tren accesible">
            </td>
            <td align="center">05:42</td>
            <td align="center">05:58</td>
            <td align="center">06:12</td>
            <td align="center" class="linea-cercanias _50R1">R1</td>
            <td align="center">
                <img src="/cer/img/ab18x18.jpg" style="vertical-align:middle;" alt="Tren accesible"> 
            </td>
            <td align="center">07:16</td>
            <td align="center">1h 34min.</td>
        </tr>   
    </tbody>
</table>
HTML;

    }

    public function testItParsesTimetablesWithTransfersHavingSomeDirectTrips()
    {
        $parser = new TimetableTripsParser($this->getTimetableWithTransfersHavingSomeDirectTrips());

        $this->assertEquals("Lezo-Renteria", $parser->transferStationName());
        $this->assertTrue($parser->hasTransfer());
        $trips = $parser->trips();

        $directTrip = $trips[1];
        $this->assertEquals("Tren Directo", $directTrip["description"]);
        $this->assertEquals(array(), $directTrip["transfers"]);
    }

    public function getTimetableWithTransfersHavingSomeDirectTrips()
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
			            <td class="cabe" colspan="2" align="center">Lezo-Renteria                           </td>
			        </tr>
			        <tr>
			            <td class="cabe" align="center">Llegada</td>
			            <td class="cabe" align="center">Salida</td>
			        </tr>
	          	
		          	<tr class="impar">
		            	<td class="linea-cercanias _61C1" align="center" name="codLinea"> 
		            		 C1    
		            	</td>
		            	
			 			<td align="center">
			 				<span class="rojo4"> </span>
			            	
			            </td>
			            
		            	<td class="" align="center"> 12.02 </td>
						
			            	<td class="" align="center"> 13.28</td>
			            	<td class="" align="center"> 13.46 </td>
			 				
			 			<td class="linea-cercanias _61C1" align="center" name="codLinea"> 
			 				 C1    
			 			</td>
			 			
			            <td align="center">
			            <span class="rojo4"> </span>
			            	
			            </td>
			            
			            <td class="" align="center"> 14.03</td>            
			            <td class="" align="center"> 2.01 </td>
		          	</tr>
	          	
		          	<tr class="par">
		            	<td class="linea-cercanias _61" align="center" name="codLinea"> 
		            		   
		            	</td>
		            	
			 			<td align="center">
			 				<span class="rojo4"> </span>
			            	
			            </td>
			            
		            	<td class="" align="center">  </td>
						
			            	<td class="" align="center"> </td>
			            	<td class="" align="center"> 13.58 </td>
			 				
			 			<td class="linea-cercanias _61C1" align="center" name="codLinea"> 
			 				 C1    
			 			</td>
			 			
			            <td align="center">
			            <span class="rojo4"> </span>
			            	
			            </td>
			            
			            <td class="" align="center"> 14.13</td>            
			            <td class="" align="center"> 2.11 </td>
		          	</tr>
	          	
		          	<tr class="impar">
		            	<td class="linea-cercanias _61C1" align="center" name="codLinea"> 
		            		 C1    
		            	</td>
		            	
			 			<td align="center">
			 				<span class="rojo4"> </span>
			            	
			            </td>
			            
		            	<td class="" align="center"> 13.13 </td>
						
							<td colspan="2" class="" align="center"> Tren Directo </td>
							
			 			<td class="linea-cercanias _61C1" align="center" name="codLinea"> 
			 				 C1    
			 			</td>
			 			
			            <td align="center">
			            <span class="rojo4"></span>
			            	
			            </td>
			            
			            <td class="" align="center"> 14.48</td>            
			            <td class="" align="center"> 1.35 </td>
		          	</tr>
		</tbody>		  
	</table>
HTML;

    }
}
