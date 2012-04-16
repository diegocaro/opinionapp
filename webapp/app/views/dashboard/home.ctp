<?php

$arrate = urlencode('@jorgearrate @arrate arrate #arrate');
$meo = urlencode('@marco2010 #meo marco henriquez ominami');
$pinera = urlencode('piñera @sebastianpinera #piñera #pinera pinera');
$frei = urlencode('frei @noticiasfrei #frei');
  

$startTime = '2009-10-08';
$endTime = '2009-11-01';
$period = '3 days';

$anchor = 'metrics?data[Metrics][startTime]='. $startTime;
$anchor .= '&data[Metrics][endTime]='. $endTime;
$anchor .= '&data[Metrics][period]='. $period;
$anchor .= '&data[Metrics][keywords]=%s';

?>

<div class="dashboard home">

<div class="msg">
<p>Este sitio corresponde a un prototipo de la memoria de 
título "Análisis automático 
de 
opiniones para sistemas de micro-blogging", desarrollada por Diego Caro Alarcón. 
</p>
<p>En este prototipo es posible visualizar las opiniones con respecto a 
los <i>candidatos a la presidencia</i> de Chile 2010, 
utilizando 
mensajes 
escritas por 
usuarios de <i>Twitter</i> durante el <i>10 y 31 de 
Octubre</i> de 
2010.
</p>
</div>

<div id="candidatos">
<ul>
    <li>
        <a href="<?php printf($anchor,$arrate);?>">
        <img src="img/candidatos/arrate.jpg" width="144" height="144" />
        <h3>Jorge Arrate</h3>
        </a>
    </li>
<li>
        <a href="<?php printf($anchor,$meo);?>">
        <img src="img/candidatos/meo.jpg" width="144" height="144" />
        <h3>Marco Enríquez-Ominami</h3>
        </a>
</li>
<li>


        <a href="<?php printf($anchor,$pinera);?>">
        <img src="img/candidatos/pinera.jpg" width="144" height="144" />
        <h3>Sebastián Piñera</h3>
        </a>

</li>
<li>



        <a href="<?php printf($anchor,$frei);?>">
        <img src="img/candidatos/frei.jpg" width="144" height="144" />
        <h3>Eduardo Frei</h3>
        </a>
</li>
</ul>
<!--
<div>
<ul>
    <li>
        <a href="<?php printf($anchor,$arrate);?>">
        Jorge Arrate
        </a>
    </li>
<li>
        <a href="<?php printf($anchor,$meo);?>">
        Marco Enríquez-Ominami
        </a>
</li>
<li>
        <a href="<?php printf($anchor,$pinera);?>">
	Sebastián Piñera
        </a>
</li>
<li>
        <a href="<?php printf($anchor,$frei);?>">
        Eduardo Frei
        </a>
</li>
</ul>
</div>
-->
</div>

