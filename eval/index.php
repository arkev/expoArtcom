<?php
	session_start();
	include("../dbconfig.php");
	include("../config_loader.php");

  include '../variables.php';
        
	mysql_connect($config['host'],$config['user'],$config['pass']);
	mysql_select_db($config['db']);

	$hash = mysql_real_escape_string($_GET['hash']);

	$access = mysql_query("SELECT * 
	FROM acceso_votacion 
	WHERE start IS NULL and hash=\"$hash\"");

	if(mysql_num_rows($access)==0){
		echo "<!-- nothing to see here ;) (1) -->";
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
	$_SESSION['name'] = $access['name'];
	$_SESSION['profesor_id'] = $access['profesor_id'];
	$_SESSION['categorias'] = $access['categorias'];
	$_SESSION['enfoques'] = $access['enfoques'];
	$_SESSION['expo'] = $access['expo'];

	$seconds = strtotime($access['end'])-time();
	$minutes = round($seconds/60)%60;
	$hours = round($seconds/3600);
	$expiration_msg = $hours." ".($hours==1?"hora":"horas")." y ".$minutes." ".($minutes==1?"minuto":"minutos");
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf8">
    <meta name="author" content="Robert Valencia">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="eval2.css">
    <style>
      html,body{background-color: rgba(51,51,51,1);margin: 0em;padding: 0em;}
      .envoltura{max-width:300px;margin: 0em auto;}
      h1{font-weight: normal;}
      a{color: inherit;text-decoration: none;}
      a:hover{}
      .splash{width:auto;position:relative;margin:0 auto 2em;padding:0;box-sizing:border-box;}
      .splash p{line-height:200%;font-weight:lighter;font-size:16px;color:rgb(165,165,165);}
      .splash a{text-align: center;display: block;color:rgb(255,255,255);border:1px solid;padding:7px  0px 7px;-webkit-border-radius:4px;-webkit-font-smoothing:antialiased;text-decoration:none;}
      .splash a:active{background-color: rgba(236,236,236,.2);color: rgba(51,51,51,1);border-color: rgba(51,51,51,1);}
      .splash h1{color:rgba(236,236,236,1);font-weight:normal;}
      nav{}
      nav ul{list-style-type: none;margin: 1em 0;padding: 0;font-size: 1.5em;position: relative;}
      nav ul li{border-top: 1px solid #999;border-bottom: 1px solid #999;}
      nav ul li+li{border-top:none;}
      nav ul li a{line-height: 2;display: block;padding-left: 1em;z-index: 3;}
      nav ul li a:hover{background-color: rgba(236,236,236,.2);}
/*      nav ul li:before{content:"Evaluar »";position: absolute;padding-left: 17em;margin-top: .9em;font-size: .67em;z-index: 1;}*/
    </style>
  </head>
  <body>
    <div class="envoltura">
      
      <h1><span>Expo <abbr>ARTCOM</abbr></span> Sistema de evaluación <abbr>VIP</abbr></h1>
      <h2>¡Hola <?php echo utf8_encode($access['name']); ?>!</h2>
      <p>Te agradecemos por participar en la evaluación de la expo artcom. La primera parte del proceso es a través del recorrido.</p>
      <p>A lo largo del recorrido, ve grabando en este sistema —o si prefieres, primero en el papelito que se te entregó y luego en el sistema— los números de los proyectos que consideras dignos de tu voto.</p>
      <p>Una vez terminado el recorrido, hay partes adicionales que queremos que evalúes: proyectos por enfoques, por categorías y el proyecto de tu predilección en tus materias.</p>
      <p>Esta última parte, si prefieres, podrás realizarla desde una computadora de escritorio.</p>
      <div class="splash">
        <a href="recorrido.php">Entendido, déjame evaluar</a>
      </div>    
    </div><!-- .envoltura -->
  </body>
</html>