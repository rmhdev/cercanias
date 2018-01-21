<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Provider\HorariosRenfeCom\Parser;

use Cercanias\Provider\HorariosRenfeCom\Parser\TimetableAssertParser;
use PHPUnit\Framework\TestCase;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class TimetableAssertParserTest extends TestCase
{
    /**
     * @expectedException \Cercanias\Exception\NotFoundException
     */
    public function testItThrowsExceptionWhenNoTimetableIsDetected()
    {
        new TimetableAssertParser($this->getNoTimetableData());
    }

    public function getNoTimetableData()
    {
        return <<<HTML
<div id="contenedor">
    <h1>
        <!--RNF09-CER001 RQ CERRF001 Introducir i18n en cercanías.-->
        Horarios Solicitados
        <!--FIN RNF09-CER001 RQ CERRF001.-->
    </h1>
    <h4>

        <br>
        Datos no disponibles.<br><br>
        Para este día (15-02-2014 ) No Existen Servicios entre el Origen y el Destino solicitado



</div>
HTML;
    }

    /**
     * @expectedException \Cercanias\Exception\ServiceUnavailableException
     */
    public function testItThrowsExceptionWhenServiceIsUnavailable()
    {
        new TimetableAssertParser($this->getServiceUnavailableData());
    }

    public function getServiceUnavailableData()
    {
        return <<<HTML
<div class="plaston_700_gris">
    <div class="lista_cuadradorosa posicion_cuadrado">Servicio temporalmente no disponible. Vuelva a intentarlo pasados unos  minutos.</div>
    <div class="posicion_texto"> Si lo desea puede:
        <ul><li>ir a la p&aacute;gina principal de <a href="http://www.renfe.com" class="link_gris">Renfe</a></li>
        </ul>
    </div>
    <div class="plaston_cierre700_gris"></div>
</div>
HTML;

    }

    public function testItReturnsTrueWhenThereIsTimetableData()
    {
        $parser = new TimetableAssertParser($this->getSimpleTimetableData());

        $this->assertTrue($parser->hasTrips());
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
