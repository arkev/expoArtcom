<?php 
// Header( "Location: 02.php" ); 
// include 'registro-over.php';

/**
 * Página de inicio para el registro de proyectos
 */

include '../variables.php';

if($hora_actual < $inicia_registro){
  // echo htmlentities("aguanta, aún no empieza todo el show");
  include 'registro-pre.php';
}elseif ($hora_actual < $cierra_registro){
  // echo "entra al paraiso y registra tu proyecto";
  include 'registro.php';
}else{
  // echo htmlentities("lo sentimos, ya terminó el tiempo de registro");
  include 'registro-over.php';
}

?>