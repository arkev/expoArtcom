<?php

	session_start();
	include("../dbconfig.php");
	include("../config_loader.php");
        
	mysql_connect($config['host'],$config['user'],$config['pass']);
	mysql_select_db($config['db']);

	$hash = mysql_real_escape_string($_GET['hash']);

	$access = mysql_query("SELECT * 
	FROM acceso_votacion 
	WHERE start IS NULL and hash=\"$hash\"");

	if(mysql_num_rows($access)==0){
		echo "<!-- nothing to see here ;) -->";
		die(1);
	}

	$access = mysql_fetch_assoc($access);
	/*
	$update = mysql_query("UPDATE acceso_votacion 
	SET start = now() 
	WHERE hash=\"$hash\"");
	*/

	$_SESSION['end'] = strtotime($access['end']);
	$_SESSION['acceso_id'] = $access['id'];

	$seconds = strtotime($access['end'])-time();
	$minutes = round($seconds/60)%60;
	$hours = round($seconds/3600);
	$expiration_msg = $hours." ".($hours==1?"hora":"horas")." y ".$minutes." ".($minutes==1?"minuto":"minutos");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sistema de evaluación · Expo ARTCOM primavera 2012</title>
  <meta name="description" content="">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <style type="text/css" media="screen">
    
  </style>
  <link rel="stylesheet" href="css/evaluacion.css"/>
</head>
<body id="bienvenida">
  <div id="container">
    
    <?php include '_evaluacion_header.php'; ?>
    
    <div id="main" role="main">
      
      <!-- 
        Could the system detect the Name of the person,
        based on the URL we give them? 
      -->
      
      <p class="texto_grande azul">¡Bienvenido <?php echo htmlentities($access['name']); ?>!</p>
      <p>Tu sesión ha iniciado y expira en <?php echo $expiration_msg; ?></p>
    </div>
    
    <a class="button" href="sistema-de-evaluacion.php" title="Continuar">Continuar</a>

    <?php include '../_footer.php'; ?>
    
  </div>
</body>
</html>