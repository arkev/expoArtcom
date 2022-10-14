<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  
  <title>Expo ARTCOM - Galería digital</title>
  <link rel="shortcut icon" href="../images/favicon-2012-bw.png" type="image/png">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  
  <!-- <link rel="stylesheet" href="/expo-artcom/p12/galeria/css/style.css" /> -->
  <link rel="stylesheet" href="/expo-artcom/p12/css/expo-fichas.css" media="screen,print">
  <!-- scripts at bottom of page -->
  <style>
  	body{
		overflow: auto;
	}	
  </style>
</head>
<body>

<!-- FB LIKE BUTTON -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<div id="header" style="text-align:center;">
	<div style="width: 890px;margin:0px auto;">
		<a href="../galeria">
			<img id="logo" src="/expo-artcom/p12/galeria/images/logo.png" height="15px" style="padding:12px 0 0 0;float:left;" />
		</a>
		<div id="menu-container">
			<input id="search" type="text" placeholder="Búsqueda"/>
			<div class="line"></div>
			<a href="/expo-artcom/p12/galeria"><img id="home" src="/expo-artcom/p12/galeria/images/home.png" height="17px"></a>
		</div>
	</div>
</div>
<br>

<script>
</script>
	<?php
		//CONEXIÓN A LA BASE DE DATOS
        include("../dbconfig.php");
        include("../config_loader.php");
      
        mysql_connect($config['host'],$config['user'],$config['pass']);
        mysql_select_db($config['db']);
        
        $id 	= $_GET['id'];
        
        $images = array("png","jpg","jpeg","gif");
        
        $files = mysql_query("select * from `archivo` where obra_id = ".$id);
        
         echo "<div class='contenedor-imagen'>";
         
         while($row = mysql_fetch_assoc($files)){
        
        		$extension = preg_split('/\./',$row['nuevo']);
				$extension = strtolower($extension[1]);
				
				//SI LA EXTENSIÓN DEL ARCHIVO ES DE TIPO IMAGEN (PNG, JPG, JPEG, GIF) PONER IMAGEN EN GALERIA
				if(array_search($extension,$images) !== FALSE){
    
    ?>
     
     	<img id="imagen" src="/expo-artcom/p12/slir/w720/expo-artcom/p12/upload/<?php echo $row['nuevo'];?>" /> 

	
	<?php 		
				}else{
	?>
		<a href="../upload/<?php echo $row['nuevo'];?>" target="_blank"><img id="imagen" src="http://placehold.it/720x400&text=<?php echo strtoupper($extension);?>" /></a> 		
	<?php
				}
		}
			
		 echo "</div>";	
					
		$autores = mysql_query("SELECT DISTINCT
            OA.autor_id, OA.obra_id, 
            Artista.carrera_id, Artista.nombres as nombres, Artista.apellidos, Artista.matricula,
            Obra.coleccion_id, 
            Carrera.inicial  
            FROM `obra_artista` AS OA
            INNER JOIN `autor` AS Artista ON Artista.matricula = OA.autor_id 
            INNER JOIN `obra` AS Obra ON Obra.id = OA.obra_id 
            LEFT JOIN `carrera` AS Carrera ON Carrera.id = Artista.carrera_id 
            WHERE OA.obra_id = ".$id);
            
            $nombresApellidos1 = "";
             while($row2 = mysql_fetch_assoc($autores)){
                      
                      
                      $nombresApellidos1 .= str_replace("&nbsp;"," ",htmlentities($row2['nombres']));
                      $nombresApellidos1 .= " ";
                      $nombresApellidos1 .= str_replace("&nbsp;"," ",htmlentities($row2['apellidos']));
                      $nombresApellidos1 .= "<br>";
            }
            
         $obras = mysql_query("SELECT 
          Obra.id, Obra.titulo, Obra.date, Obra.specs, Obra.descripcion,
          Clasificacion.nombre AS clasificacionNombre, Materia.nombre AS materiaNombre, 
          Coleccion.nombre AS coleccionNombre,
          Coleccion.nombre_corto AS coleccionURL 
          FROM `obra` as Obra 
          INNER JOIN `clasificacion` AS Clasificacion ON Clasificacion.id = Obra.clasificacion_id 
          INNER JOIN `obra_materia` AS obra_materia on obra_materia.obra_id = Obra.id
          INNER JOIN `materia` AS Materia ON Materia.id = obra_materia.materia_id 
          INNER JOIN `coleccion` AS Coleccion ON Coleccion.id = Obra.coleccion_id 
          WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection")." and Obra.id = ".$id);
          
          
		 $row2 = mysql_fetch_assoc($obras);
	?>
	
	<div class="descripcion">	
					<div class="fb-like" data-href="http://artcom.um.edu.mx/g/p12-<?php echo $id; ?>" data-send="true" data-layout="button_count" data-width="450" data-show-faces="true"></div>
					<br><br>
					<dd class="titulo" id="titulo"><?php echo htmlentities($row2['titulo']);?></dd>
					<br>
					<dt>AUTOR(ES)</dt>
					<dd id="autores"><?php echo $nombresApellidos1; ?></dd>
					
					<dt>MATERIA</dt>
					<dd id="materia"><?php echo htmlentities($row2['materiaNombre']);?></dd>
					
					<dt>CLASIFICACIÓN</dt>
					<dd id="clasificacion"><?php echo htmlentities($row2['clasificacionNombre']);?></dd>
					
					<dt>ESPECIFICACIONES</dt>
					<dd id="especificaciones"><?php echo htmlentities($row2['specs']);?></dd>
					
					<dt>DESCRIPCIÓN</dt>
					<dd id="descripcion"><?php echo htmlentities($row2['descripcion']);?></dd>
	
	</div>
	<br>
</body>

<style>
	
	#imagen{
		display: block;
		margin: auto;
		max-width: 890px;
	}
	.contenedor-imagen{
		width: 890px;
		display: block;
		margin: 0 auto;
		background: #F3F3F3;
		
	}
	.descripcion{
		width: 850px;
		display: block;
		margin: 0 auto;
		color:black;
		padding:20px;
		background: white;
	}
	dt{
			  	font-family: "M1cregular",sans-serif;
			  	letter-spacing: .1em;
			  	color: #bbb;
			  	margin: 0;
			  	font-size: 10px;
			  	text-transform: uppercase;
	}
	dd{
				margin-left: 0;
				color: #333;
				font-family: "M1cregular",sans-serif;
				font-size: 15px;
	}
	.titulo{
				color:black;
				font-size:19px;
	}
</style>
</html>