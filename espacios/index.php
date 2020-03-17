<?php
session_start();
include("../dbconfig.php");
include("../config_loader.php");

$ccn = 'p13'; // $ccn = current collection nombre_corto
      
mysql_connect($config['host'],$config['user'],$config['pass']);
mysql_select_db($config['db']);

// 

//  por cada espacio listado,
//  buscar materias y si son del mismo profesor, agruparlas listando al profesor una sola vez
//  buscar grupos y buscar cualquier autor que sea profesor
//  buscar cualquier autor que no sea profesor
//  
//  por cada alumno, listar proyectos aprobados
//  
//  si el listado inicial fue por materia_id, envolver en span…
  /*$query = 'SELECT ED.* , espacio.nombre, espacio.id AS es_id FROM espacio_distribucion AS ED INNER JOIN espacio ON espacio.id = ED.espacio_id';
  $busacndo = mysql_query($query);
  $total = array();
  $grupos = array();
  $aulas = array();
  while ($row = mysql_fetch_array($busando)) {
    $mat_ids = explode(",",$row['materia_ids']);
    if (count($grupo) == 0) {
      $mat =  explode(",",$row['']);
    }
  }*/
  $query = 'SELECT * FROM espacio';
  $espacios = mysql_query($query);

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Distribución de espacios › Expo ARTCOM Portafolio estudiantil · primavera 2013</title>
  <meta name="description" content="">
  <meta name="author" content="Robert Valencia">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="shortcut icon" href="/expo-artcom/<?php echo $ccn; ?>/img/<?php echo $ccn; ?>-favicon.png" type="image/png">
  <link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="eval2.css">
  <style>
    #main{padding: .5em;}
    #main ul{margin-left: 0;padding-left: 0;}
    #main li{list-style-type: none;margin-left: 0;}
    #main li a, .unchecked{text-decoration: none;color: white;margin-right: 1em;background-color: transparent;border-radius:8px;padding: .2em .7em;}
    #main li a:after{content:" \f00c ";font-family: 'FontAwesome',sans-serif;color: transparent;}
    #main li a:after:active{content:" \f00c ";font-family: 'FontAwesome',sans-serif;color: white;}
    #main li a:active, .checked{color: white; background-color: green;}
    
    @media print{
      body{background-color: none;color: black;}
      header{display: none;}
    }
  </style>

</head>
<body>
  <div id="container">
    <header>
      <nav>
        <ul class="main">
    		<li class="home"><a href="../" title="Regresar al menú principal"><span>Home</span></a></li>
    		<li class="active menu">
    			<a href="index.php">Distribución</a>
    			<ul id="menu">
            <?php 
              while ($espacio = mysql_fetch_array($espacios)) {
                if ($espacio['id'] == 12 ||$espacio['id'] == 12) {
                  continue;
                }else{?>
                  <li class="menu"><a href="index.php?espacio=<?php echo $espacio['id']  ?>"><?php echo utf8_encode($espacio['nombre']); ?></a></li>
                <?php
                }
              }
            ?>
    			</ul>
    		</li>
      	</ul>
      </nav>
    </header>
  	<section class="nav">
  	<dl>
  		<!-- <dd class="instrucciones">Espacio de lectura</dd> -->
      <?php
        $espacio;$nombre;
        //if (isset($_GET['espacio'])) {
            $info = mysql_real_escape_string($_GET['espacio']);
            if($info == 2 || $info == 1 || $info == 3 || $info == 6 || $info==8 || $info == 9 || $info==11){
              $espacio = $info;
              $query = 'SELECT * FROM espacio_distribucion WHERE espacio_id = '.$espacio;
              $espacio = mysql_query($query);
              //data tiene toda la info dle espacio
              $data = mysql_fetch_object($espacio);
              $query = 'SELECT * FROM espacio_distribucion WHERE espacio_id = '.$espacio;
              //busco maestro------------------->
              $maestro = mysql_query('SELECT nombres,apellidos FROM autor where matricula ='.$data->autor_id);
              $nombre = mysql_fetch_object($maestro);
            }
        //}
      ?>
  	</dl>
  	</section>

    <div id="main" role="main">
      <h2>
          <?php echo $nombre->nombres." ".utf8_encode($nombre->apellidos); ?> - <span class="grupo"><?php echo $data->grupos;?></span>
      </h2>
      <?php 
        //$ok;
/*        if ($espacio ==1) {
          $ok = mysql_query('SELECT obra.id, obra.titulo,artista.obra_id,artista.semestre FROM coleccion_autor_semestre_carrera as CC inner join obra_artista as artista on artista.autor_id = CC.autor_id inner join obra on obra.id = artista.obra_id WHERE artista.semestre =6 and CC.coleccion_id=12 and obra.desaprobado = 0');
        }else if ($espacio == 2) {
          $ok = mysql_query('SELECT obra.id, obra.titulo,artista.obra_id,artista.semestre FROM coleccion_autor_semestre_carrera as CC inner join obra_artista as artista on artista.autor_id = CC.autor_id inner join obra on obra.id = artista.obra_id WHERE artista.semestre =8 and CC.coleccion_id=12 and obra.desaprobado = 0');
        }*/
      ?>
      <ul id="">
        <!-- <h3>Lisandra Vicente</h3> -->
        <li>
          <?php 
          if ($info == 1) {
            $ok = mysql_query('SELECT DISTINCT obra.id
                              FROM obra
                              INNER JOIN obra_artista artista ON obra.id = artista.obra_id
                              INNER JOIN autor ON artista.autor_id = autor.matricula
                              INNER JOIN coleccion_autor_semestre_carrera AS CC ON CC.autor_id = artista.autor_id
                              INNER JOIN carrera ON carrera.id = CC.carrera_id
                              WHERE CC.coleccion_id =12
                              AND CC.semestre_id =6
                              AND obra.desaprobado = 0
                              AND carrera.inicial =  "D"
                              OR  carrera.inicial =  "M"
                              OR  carrera.inicial =  "A"');
            while ($obras = mysql_fetch_array($ok)) {?>
            <a href="#"><?php echo $obras['id'] ?></a>
          <?php  } 
          }else if ($info == 2) {
            $ok = mysql_query('SELECT DISTINCT obra.id
                                          FROM obra
                                          INNER JOIN obra_artista artista ON obra.id = artista.obra_id
                                          INNER JOIN autor ON artista.autor_id = autor.matricula
                                          INNER JOIN coleccion_autor_semestre_carrera AS CC ON CC.autor_id = artista.autor_id
                                          INNER JOIN carrera ON carrera.id = CC.carrera_id
                                          WHERE CC.coleccion_id =12
                                          AND CC.semestre_id =8
                                          AND obra.desaprobado = 0
                                          AND carrera.inicial =  "V"
                                          OR  carrera.inicial =  "P"');
            while ($obras = mysql_fetch_array($ok)) {?>
            <a href="#"><?php echo $obras['id'] ?></a>
            <?php  } 
          }else if ($info == 3) {
            $ok = mysql_query('SELECT DISTINCT obra.id
                              FROM obra
                              INNER JOIN obra_artista artista ON obra.id = artista.obra_id
                              INNER JOIN autor ON artista.autor_id = autor.matricula
                              INNER JOIN coleccion_autor_semestre_carrera AS CC ON CC.autor_id = artista.autor_id
                              INNER JOIN carrera ON carrera.id = CC.carrera_id
                              WHERE CC.coleccion_id =12
                              AND CC.semestre_id =8
                              AND carrera.inicial =  "C"');
            while ($obras = mysql_fetch_array($ok)) {?>
              <a href="#"><?php echo $obras['id'] ?></a>
            <?php  } 
          }else if($info == 4){
            $ok = mysql_query('SELECT DISTINCT obra.id
                              FROM obra
                              INNER JOIN obra_artista artista ON obra.id = artista.obra_id
                              INNER JOIN autor ON artista.autor_id = autor.matricula
                              INNER JOIN coleccion_autor_semestre_carrera AS CC ON CC.autor_id = artista.autor_id
                              INNER JOIN carrera ON carrera.id = CC.carrera_id
                              WHERE CC.coleccion_id =12
                              AND CC.semestre_id =8
                              AND obra.desaprobado = 0
                              AND carrera.inicial =  "C"');
            while ($obras = mysql_fetch_array($ok)) {?>
              <a href="#"><?php echo $obras['id'] ?></a>
            <?php  } 
          }else if ($info == 6) {
            $ok = mysql_query('SELECT DISTINCT obra.id
                              FROM obra
                              INNER JOIN obra_artista artista ON obra.id = artista.obra_id
                              INNER JOIN autor ON artista.autor_id = autor.matricula
                              INNER JOIN coleccion_autor_semestre_carrera AS CC ON CC.autor_id = artista.autor_id
                              INNER JOIN carrera ON carrera.id = CC.carrera_id
                              WHERE CC.coleccion_id =12
                              AND CC.semestre_id =2
                              AND obra.desaprobado = 0
                              AND carrera.inicial =  "D"
                              OR  carrera.inicial =  "M"');
            while ($obras = mysql_fetch_array($ok)) {?>
              <a href="#"><?php echo $obras['id'] ?></a>
            <?php  }
          }else if ($info == 7) {
            $ok = mysql_query('SELECT DISTINCT obra.id
                              FROM obra
                              INNER JOIN obra_artista artista ON obra.id = artista.obra_id
                              INNER JOIN autor ON artista.autor_id = autor.matricula
                              INNER JOIN coleccion_autor_semestre_carrera AS CC ON CC.autor_id = artista.autor_id
                              INNER JOIN carrera ON carrera.id = CC.carrera_id
                              WHERE CC.coleccion_id =12
                              AND CC.semestre_id =4
                              AND obra.desaprobado = 0
                              AND carrera.inicial =  "D"
                              OR  carrera.inicial =  "M"');
            while ($obras = mysql_fetch_array($ok)) {?>
              <a href="#"><?php echo $obras['id'] ?></a>
            <?php  }
          }else if ($info == 8) {/*
            $ok = mysql_query('SELECT DISTINCT obra.id
                              FROM obra
                              INNER JOIN obra_artista artista ON obra.id = artista.obra_id
                              INNER JOIN autor ON artista.autor_id = autor.matricula
                              INNER JOIN coleccion_autor_semestre_carrera AS CC ON CC.autor_id = artista.autor_id
                              INNER JOIN carrera ON carrera.id = CC.carrera_id
                              WHERE CC.coleccion_id =12
                              AND CC.semestre_id =4
                              AND obra.desaprobado = 0
                              AND carrera.inicial =  "D"
                              OR  carrera.inicial =  "M"');
            while ($obras = mysql_fetch_array($ok)) {?>
              <a href="#"><?php echo $obras['id'] ?></a>
            <?php  }
          */}else if ($info == 9) {
            $ok = mysql_query('SELECT DISTINCT obra.id
                              FROM obra
                              INNER JOIN obra_artista artista ON obra.id = artista.obra_id
                              INNER JOIN autor ON artista.autor_id = autor.matricula
                              INNER JOIN coleccion_autor_semestre_carrera AS CC ON CC.autor_id = artista.autor_id
                              INNER JOIN carrera ON carrera.id = CC.carrera_id
                              WHERE CC.coleccion_id =12
                              AND obra.desaprobado = 0
                              AND carrera.inicial =  "A"
                              AND CC.semestre_id =2
                              OR CC.semestre_id =4
                              OR CC.semestre_id =6 ');
            while ($obras = mysql_fetch_array($ok)) {?>
              <a href="#"><?php echo $obras['id'] ?></a>
            <?php  }
          }else if ($info == 11) {
            $ok = mysql_query('SELECT DISTINCT obra.id
                              FROM obra
                              INNER JOIN obra_artista artista ON obra.id = artista.obra_id
                              INNER JOIN autor ON artista.autor_id = autor.matricula
                              INNER JOIN coleccion_autor_semestre_carrera AS CC ON CC.autor_id = artista.autor_id
                              INNER JOIN carrera ON carrera.id = CC.carrera_id
                              WHERE CC.coleccion_id =12
                              AND obra.desaprobado = 0
                              AND carrera.inicial =  "A"
                              AND CC.semestre_id =2
                              OR CC.semestre_id =4
                              OR CC.semestre_id =6 ');
            while ($obras = mysql_fetch_array($ok)) {?>
              <a href="#"><?php echo $obras['id'] ?></a>
            <?php  }
          }
          ?>
            
        </li>
        
      </ul>
    </div>

    <footer>

    </footer>
  </div>
</body>
</html>