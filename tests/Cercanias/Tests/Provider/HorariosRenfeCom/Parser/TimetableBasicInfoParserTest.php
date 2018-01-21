<?php

/**
 * This file is part of the Cercanias package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license MIT License
 */

namespace Cercanias\Tests\Provider\HorariosRenfeCom\Parser;

use Cercanias\Provider\HorariosRenfeCom\Parser\TimetableBasicInfoParser;

/**
 * @author Rober Martín H <rmh.dev@gmail.com>
 */
class TimetableBasicInfoParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider timetableDataProvider
     */
    public function testItParsesDepartureAndDestinationStationNames($html, $departure, $arrival)
    {
        $parser = new TimetableBasicInfoParser($html);

        $this->assertEquals($departure, $parser->departureStationName());
        $this->assertEquals($arrival, $parser->arrivalStationName());
    }

    public function timetableDataProvider()
    {
        return array(
            array($this->getDefaultData(), "Brincola", "Irun"),
            array($this->getData2018(), "Brincola", "Irun"),
        );
    }

    private function getDefaultData()
    {
        return <<<EOL

<div id="contenedor">
<h1>
    <!--RNF09-CER001 RQ CERRF001 Introducir i18n en cercanías.-->
    Horarios Solicitados
    <!--FIN RNF09-CER001 RQ CERRF001.-->
</h1>
<h4>

    Horarios válidos desde   <b> 10-02-2014 </b>
    hasta   <b> 10-02-2014 </b>



    <br><br>

    <a href="/cer/hjcer310.jsp?&nucleo=61&o=11600
&d=11305&tc=DIA&td=D&df=20140210&th=1
&ho=00&i=s&cp=NO&TXTInfo=" class="titulo_negro"><img src="/cer/gif/cerinverso.jpg" border=0 alt="Solicitar los Horarios de Regreso en el día">
        Solicitar los Horarios de Regreso en el día </a>
    <div id="imprimir">
        <a href="javascript:window.print();" id="imprimir" class="titulo_negro"><img src="/cer/gif/cercaimp.jpg" border=0 alt="Imprimir horarios"> Imprimir horarios</a>
    </div>

</h4>

<span class="titulo_rojo">Origen :</span> <span class="titulo_negro">Brincola                                </span><br>

<span class="titulo_rojo">Destino  :</span> <span class="titulo_negro">Irun
	</span><br>
<br>
<span class="titulo_rojo">Día de viaje:  :</span> <span class="titulo_negro">10-02-2014</span>



<br><br>
EOL;
    }

    private function getData2018()
    {
        return <<<EOL

<div id="contenedor">



	
		<h1>Horarios Solicitados</h1> 
            

				 
				    <h4>Horarios válidos desde   <b> 14-01-2018 </b> hasta   <b> 14-01-2018 </b></h4>
					
					
						<h4>
						<a href="/cer/hjcer310.jsp?&nucleo=61&o=11600
							&d=11305&tc=DIA&td=D&df=20180114&th=1
							&ho=00&i=s&cp=NO&TXTInfo=" class="titulo_negro">
							<img src="/cer/gif/cerinverso.jpg" border=0 alt="Solicitar los Horarios de Regreso en el dia"> Solicitar los Horarios de Regreso en el día 
						</a>
					<div id="imprimir">
						<a href="javascript:window.print();" id="imprimir" class="titulo_negro">
							<img src="/cer/gif/cercaimp.jpg" border=0 alt="Imprimir horarios"> Imprimir horarios
						</a>
						</h4>
					</div> 
					<br>
					
					<div class="titulo_rojo" style="width:55px; float:left;">Origen :</div> 
					<div class="titulo_negro">
						
						Brincola                                
					</div>
					<br>
					<div class="titulo_rojo" style="width:55px; float:left;">Destino  :</div> 
					<div class="titulo_negro">
						 
						Irun                                    
					</div>
					<br>
					
						<br><span class="titulo_rojo">Día de viaje:  :</span> <span class="titulo_negro">14-01-2018</span>
					
EOL;

    }

    /**
     * @dataProvider timetableDayProvider
     */
    public function testItParsesQueryDay($html, $day)
    {
        $parser = new TimetableBasicInfoParser($html);

        $this->assertEquals($day, $parser->date());
        $this->assertEquals($day, $parser->date());
    }

    public function timetableDayProvider()
    {
        return array(
            array($this->getDefaultData(), "2014-02-10"),
            array($this->getData2018(), "2018-01-14"),
        );
    }
}
