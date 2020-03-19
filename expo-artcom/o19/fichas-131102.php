<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Fichas técnicas · Expo ARTCOM Portafolio estudiantil · primavera 2013</title>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="js/canvas-bowtie.js"></script>
  <script type="text/javascript">
    jQuery(function(){

      var fichas = $('body').find('.ficha');
      //console.log(fichas)
      for(var i = 0;i < fichas.length;i++){
        ficha = $('#'+fichas[i].id);
        ficha.css("page-break-inside","avoid");
      }
      
    });
  </script>
  <link rel="shortcut icon" href="images/expo-artcom-p2012-favicon-2012-bw.png" type="image/png">
  <link rel="stylesheet" href="css/expo-fichas.css" media="screen,print">
</head>
<body>
  <?php

  include("dbconfig.php");
  include("config_loader.php");

  mysql_connect($config['host'],$config['user'],$config['pass']);
  mysql_select_db($config['db']);

  $artista = $_GET['trato'];

  $query = "SELECT 
    Obra.id, Obra.titulo, Obra.date, Obra.specs, Obra.descripcion, 
    Clasificacion.nombre as clasificacionNombre, 
    Coleccion.nombre AS coleccionNombre 
    FROM `obra` as Obra 
    INNER JOIN `clasificacion` AS Clasificacion ON Clasificacion.id = Obra.clasificacion_id 
    INNER JOIN `coleccion` AS Coleccion ON Coleccion.id = Obra.coleccion_id 
    INNER JOIN `obra_artista` AS OA on Obra.id = OA.obra_id
    WHERE Obra.desaprobado = 0 AND OA.autor_id = ". $artista;

  $obras = mysql_query($query);

  $counter = 0;
  while($obra = mysql_fetch_assoc($obras)){
    
    //$toot = "SELECT OA.autor_id, OA.obra_id, Autor.carrera_id, Autor.nombres, Autor.apellidos, Autor.matricula, Autor.semestre, Obra.coleccion_id, Carrera.inicial as carrera_inicial FROM obra_artista AS OA INNER JOIN autor AS Autor ON Autor.matricula = OA.autor_id INNER JOIN obra AS Obra ON Obra.id = OA.obra_id LEFT JOIN carrera AS Carrera ON Carrera.id = Autor.carrera_id WHERE OA.obra_id = " . htmlentities($obra['id']);
  ?>
  
<!-- 
FICHA #<?php echo $obra['id']; ?>

-->
  
  <!-- Inicia página-->
  <?php 
    if($counter%1 == 0){
      //echo "<div class=\"page break\">";
      echo "<div class=\"page \">";
    }
  ?>

  <!-- Inicia ficha -->
    <div id="ficha_<?php echo $obra['id']; ?>" class="ficha tag column <?php echo ($counter%2 == 0)?"left":"right";?> ficha-<?php echo $obra['id']; ?>">

      <!-- marcas de corte -->
      <div class="bl"></div><div class="br"></div>
      
      <!-- moño y número de ficha -->
      <canvas class="bowtie" width="44" height="19" style=""></canvas>
      <h2><? echo htmlentities($obra['id']); ?></h2>
      
      <!-- Título y datos técnicos -->
      <h3><? echo str_replace("\\&quot;","&quot;",htmlentities($obra['titulo'])); ?></h3>
      <?php 
      
      $autores = mysql_query("SELECT DISTINCT
        OA.autor_id, OA.obra_id, 
        Autor.carrera_id, Autor.nombres, Autor.apellidos, Autor.matricula, Autor.semestre, 
        Obra.coleccion_id, 
        Carrera.inicial as carrera_inicial
        FROM `obra_artista` AS OA
        INNER JOIN `autor` AS Autor ON Autor.matricula = OA.autor_id 
        INNER JOIN `obra` AS Obra ON Obra.id = OA.obra_id 
        inner JOIN `carrera` AS Carrera ON Carrera.id = Autor.carrera_id 
        WHERE OA.obra_id = ".$obra['id']);

        $buffer = "";
        $buffer2 = "";
        $counterAutores = 0;
        $total = mysql_num_rows($autores);
        $personas = array();
        echo '<p><span class="descriptor">Autor(es): </span><br>';
        while($autor = mysql_fetch_assoc($autores)){  
          $buffer .= str_replace(" ","&nbsp;",htmlentities($autor['nombres']));
          $buffer .= " ";
          $buffer .= str_replace(" ","&nbsp;",htmlentities($autor['apellidos']));
          $buffer .= " ";
          if($autor['es_profesor'] === '1'){
            $buffer .= "(Profesor)";
          }else{
            $buffer .= "(";
            $buffer .= $autor['semestre'];
            $buffer .= $autor['carrera_inicial'];
            $buffer .= ")";
          }
          echo $buffer . "<br>";
          $buffer ="";
        }
        echo "</p>";
      ?>

      <?php
      //
      // DEAL WITH MATERIAS
      //
      $materias = mysql_query("SELECT DISTINCT
              Materia.nombre
              FROM `obra_materia` AS OM
              INNER JOIN `materia` AS Materia ON Materia.id = OM.materia_id 
              WHERE OM.obra_id = ".$obra['id']);
    
      $materias_concat = "";
      while($materia = mysql_fetch_assoc($materias)){
        $materias_concat .= htmlentities($materia['nombre']).", ";
      }
      $materias_concat = substr($materias_concat,0,strlen($materias_concat)-2);
      ?><p><span class="descriptor">Materias:</span> <?php echo $materias_concat; ?></p>
      <p><span class="descriptor">Clasificación:</span> <? echo htmlentities($obra['clasificacionNombre']); ?></p>
      <p><span class="descriptor">especificaciones:</span> <? echo htmlentities($obra['specs']); ?></p>
      <p><span class="descriptor">Descripci&oacute;n:</span> <? echo str_replace("\\&quot;","&quot;",htmlentities($obra['descripcion'])); ?></p>

      <?php
        //
        // DEAL WITH ENFOQUES
        //
        $enfoques = mysql_query("SELECT DISTINCT
                descripcion, Enfoque.nombre, Enfoque.id, Enfoque.padre, Enfoque.icono 
                FROM `obra_enfoque` AS OE
                INNER JOIN `enfoque` AS Enfoque ON Enfoque.id = OE.enfoque_id 
                WHERE OE.obra_id = ".$obra['id']." 
                ORDER BY Enfoque.id");
    
        $has_enfoques = false;
        if(mysql_num_rows($enfoques)>0){
          $has_enfoques = true;
        }

        if($has_enfoques){
      ?>
<!-- Listado de enfoques, si acaso existen -->
      <fieldset id="enfoques" class="enfoques">
        <legend>enfoques</legend>
        <?php
          }

          while($enfoque = mysql_fetch_assoc($enfoques)){
            $has_parent = false;
            if( isset($enfoque['padre']) ){
              $has_parent = true;
            }
            $enfoque['has_parent'] = $has_parent;
        ?>
        
        <div class="enfoque <?php echo $enfoque['has_parent']?"emprendimiento":""; ?>">
          <img src="<? echo $enfoque['icono']; ?>" />
          <h4><? echo htmlentities($enfoque['nombre']); ?></h4>
          <p><? echo str_replace("\\&quot;","&quot;",htmlentities($enfoque['descripcion'])); ?></p>
        </div><!-- .enfoque <? echo htmlentities($enfoque['nombre']); ?> -->
  <?php
    }
  ?>
  <?php
    if($has_enfoques){
  ?>
  </fieldset>
  <?php
    }
  ?>
  </div><!-- Termina .ficha -->
  <?php
      if($counter%1 == 0){
        echo "
  </div><!-- .page.break -->
  <!-- Termina página -->
  ";
      }
      $counter++;
    }//END WHILE
  ?>
</body>
</html>