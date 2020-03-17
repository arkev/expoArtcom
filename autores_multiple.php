<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Plantilla de autores</title>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="js/canvas-bowtie.js"></script>
  <link rel="stylesheet" href="css/expo-fichas-autores.css">
</head>
<body id="autores" onload="init()">
  <?php
    include("dbconfig.php");
    include("config_loader.php");

    mysql_connect($config['host'],$config['user'],$config['pass']);
    mysql_select_db($config['db']);

    $obras = mysql_query("SELECT 
    obra_id as obra, Obra.titulo 
    FROM obra_artista 
    LEFT JOIN `obra` AS Obra ON Obra.id = obra_id 
    GROUP BY obra_id 
    HAVING count(*) > 1");

    while($obra = mysql_fetch_assoc($obras)){
      
    echo "
<!--
PROYECTO #
-->

    <div class=\"ficha break\">

      <!-- marcas de corte -->
      <div class=\"bl\"></div>
      <div class=\"br\"></div>

      <span class=\"obra_titulo\">".htmlentities($obra['titulo'])."</span>\n";
      echo "
      <ul class=\"grupal\">\n";

        $autores = mysql_query("SELECT DISTINCT
        OA.autor_id, OA.obra_id, 
        Autor.carrera_id, Autor.nombres, Autor.apellidos, Autor.matricula, Autor.semestre, 
        Carrera.inicial as carrera_inicial
        FROM `obra_artista` AS OA
        INNER JOIN `autor` AS Autor ON Autor.matricula = OA.autor_id 
        LEFT JOIN `carrera` AS Carrera ON Carrera.id = Autor.carrera_id 
        WHERE OA.obra_id = ".$obra['obra']);

        while($autor = mysql_fetch_assoc($autores)){
        echo "        <li><span class=\"semestre_carrera\">";
          if($autor['es_profesor'] == '1'){
            echo "(Profesor)";
          }else{
            echo "(".$autor['semestre'].$autor['carrera_inicial'].")";
          }
          echo "</span>&nbsp;";
          echo str_replace(" ","&nbsp;",htmlentities($autor['nombres'])."&nbsp;");
          echo str_replace(" ","&nbsp;",htmlentities($autor['apellidos']));
        
          echo "</li>\n";
        }
      ?>
        <li class="footer">
          <canvas class="bowtie" width="44" height="19" style=""></canvas>
          EXPO ARTCOM <span class="portafolio_estudiantil">Portafolio estudiantil</span> <span class="semestre">primavera 2013</span>
        </li>
      </ul>
    </div><!-- .ficha .break-->

  <?php
    }
  ?>
</body>
</html>