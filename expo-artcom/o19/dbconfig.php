<?php
  $production = true;
  
	$config = array();
  
  if($production){
    $config['host']  = 'expoartcomp19.db.9077264.991.hostedresource.net';  //database host
  	$config['db']    = 'expoartcomp19';       //database name
  	$config['user']  = 'expoartcomp19';       //database user
  	$config['pass']  = 'CRE8ing!';            //database password
  }else{
    $config['host']  = 'localhost';  //database host
    $config['db']    = 'expo';       //database name
    $config['user']  = 'root';       //database user
    $config['pass']  = 'parrot';     //database password
  }
  
?>