<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Plantilla de autores</title>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="js/canvas-bowtie-autores.js"></script>
  <link rel="stylesheet" href="css/expo-fichas-autores.css">
  </head>
<body id="autores_individuales" onload="init()">
  <?php
    include("dbconfig.php");
    include("config_loader.php");

    mysql_connect($config['host'],$config['user'],$config['pass']);
    mysql_select_db($config['db']);
    
    $autores = mysql_query("SELECT 
      Carrera.inicial as carrera_inicial, Autor.nombres, Autor.apellidos, Autor.semestre, Autor.es_profesor, Autor.matricula 
      FROM `autor` as Autor 
      LEFT JOIN `carrera` AS Carrera ON Carrera.id = Autor.carrera_id 
      INNER JOIN `obra_artista` ON Autor.matricula = obra_artista.autor_id 
      GROUP BY Autor.matricula");

      $total = mysql_num_rows($autores);

      $counter = 0;
      while($autor = mysql_fetch_assoc($autores)){
  ?>
  <?php 
    if($counter%7 == 0){
      echo "<!-- Inicia página -->
    <div class=\"page break\">";
    }
  ?>
   <? if($autor['es_profesor'] != '1'){
        echo "
        <ul class=\"individual\">
          <!-- marcas de corte --><li class=\"bl\"></li><li class=\"br\"></li>";
        echo "
          <li><span class=\"semestre_carrera\">";
        // echo "(".$autor['semestre'].$autor['carrera_inicial'].")";
        echo "</span>&nbsp;";
        echo str_replace(" ","&nbsp;",htmlentities($autor['nombres'])."&nbsp;");
        echo str_replace(" ","&nbsp;",htmlentities($autor['apellidos']));
        echo "</li>";
  ?>

          <li class="footer"><canvas class="bowtie-autores" width="25" height="11" style=""></canvas> EXPO ARTCOM Portafolio estudiantil <span class="semestre">primavera 2013</span></li>
        </ul>
  <?php
      }
  ?>
  <?php
      if($counter%7 == 6){
        echo "</div><!-- .page.break -->
    <!-- Termina página -->

  ";
      }
      $counter++;
    }//END WHILE
  ?>
</body>  
</html>