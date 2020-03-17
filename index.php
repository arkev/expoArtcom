<?php 
// Use if you want this to redirect
// Header( "Location: 02.php" ); 
include 'variables.php';

if($hora_actual > $cierra_registro){
  include 'lista.php';
}else{
  Header('Location: registro');  
}

// if($hora_actual > $cierra){
//   include 'lista.php';
// }else{
//   Header('Location: registro');  
// }

// Use this if you want to load contents from other page into this page:
// include 'lista.php';
// include 'registro/index.php';

?>