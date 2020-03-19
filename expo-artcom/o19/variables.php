<?php
$coleccion = 'Expo ARTCOM Portafolio estudiantil &middot; primavera 2019';
$jquery = 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js';
$jquerylocal = 'js/jquery.min.js';
$favicon = 'img/bowtie-favicon@2x.png';
$coleccion_nombre_corto = 'p19';
$cnc = $coleccion_nombre_corto;
$ccn = $coleccion_nombre_corto;
$headerlogo = 'img/fade-expoartcom.png';

// conditional timers for registro/index.php
$hora_actual = date('Y-m-d H:i',  strtotime("now"));
$inicia_registro = date('Y-m-d H:i',  strtotime("2019-05-07 22:30"));
$cierra_registro = date('Y-m-d H:i', strtotime("2019-05-09 21:30"));


$inicio_de_registro = $inicia_registro;
$cierre_de_registro = $cierra_registro;
$registro_fin = $cierra_registro;
$fin_de_registro = $cierra_registro;
?>