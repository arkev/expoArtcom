<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <?php
  include '../variables.php';
  include("../dbconfig.php");
  include("../config_loader.php");
  $baseurl = "/expo-artcom/".$cnc."/";

  mysql_connect($config['host'],$config['user'],$config['pass']);
  mysql_select_db($config['db']);

  $obras = mysql_query("SELECT 
    Obra.id, Obra.titulo, Obra.date, Obra.specs, Obra.descripcion, 
    Clasificacion.nombre as clasificacionNombre, 
    Coleccion.nombre AS coleccionNombre 
    FROM `obra` as Obra 
    INNER JOIN `clasificacion` AS Clasificacion ON Clasificacion.id = Obra.clasificacion_id 
    INNER JOIN `coleccion` AS Coleccion ON Coleccion.id = Obra.coleccion_id 
    WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection"));

  while($obra = mysql_fetch_assoc($obras)){
  ?>


  <title>Muro de proyectos · <?php echo $coleccion; ?></title>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="../js/canvas-bowtie.js"></script>
  <link rel="shortcut icon" href="../images/expo-artcom-p2012-favicon-2012-bw.png" type="image/png">
  <link rel="stylesheet" href="../css/expo-fichas.css" media="screen,print">
  <style type="text/css" media="screen">
    .metaficha{float: left;width: 306px !important;margin-top: 3em;}
    .media{float: left;width: 720px;margin-left: 10px;margin-top: 3em;}
    .ficha{width: 1036px !important;}
    .ficha + .ficha{clear: both;border-top: 1px solid rgba(210,210,210,.6);margin-bottom: 1.75em;}
    .wrap{width: 1040px;margin: 0 auto;clear: both;}
  </style>
</head>
<body>
  <div class="wrap">  
<!-- 
FICHA #<?php echo $obra['id']; ?>

-->

  <!-- Inicia ficha -->
    <div id="ficha_<?php echo $obra['id']; ?>" class="ficha tag column ficha-<?php echo $obra['id']; ?>">
      
      <div class="metaficha">

        <!-- moño y número de ficha -->
        <canvas class="bowtie" width="44" height="19" style=""></canvas>
        <h2><? echo htmlentities($obra['id']); ?></h2>
      
        <!-- Título y datos técnicos -->
        <h3><? echo str_replace("\\&quot;","&quot;",htmlentities($obra['titulo'])); ?></h3>
        <p><span class="descriptor">Autor(es):</span> <?php
        $autores = mysql_query("SELECT DISTINCT
    		OA.autor_id, OA.obra_id, 
    		Autor.carrera_id, Autor.nombres, Autor.apellidos, Autor.matricula, Autor.semestre, 
    		Obra.coleccion_id, 
    		Carrera.inicial as carrera_inicial
    		FROM `obra_artista` AS OA
    		INNER JOIN `autor` AS Autor ON Autor.matricula = OA.autor_id 
    		INNER JOIN `obra` AS Obra ON Obra.id = OA.obra_id 
    		LEFT JOIN `carrera` AS Carrera ON Carrera.id = Autor.carrera_id 
    		WHERE OA.obra_id = ".$obra['id']);

    		$buffer = "";
    		$buffer2 = "";
    		$counterAutores = 0;
    		$total = mysql_num_rows($autores);

    		while($autor = mysql_fetch_assoc($autores)){
    			$buffer .= str_replace(" ","&nbsp;",htmlentities($autor['nombres']));
    			$buffer .= " ";
    			$buffer .= str_replace(" ","&nbsp;",htmlentities($autor['apellidos']));
    			$buffer .= " ";
    			if($autor['es_profesor'] == '1'){
    				$buffer .= "(Profesor)";
    				$buffer .= "<br />";
    			}else{
    				$buffer .= "(";
    				$buffer .= $autor['semestre'];
    				$buffer .= $autor['carrera_inicial'];
    				$buffer .= ")";
    			}
    			echo $buffer . "<br />";
    				$buffer ="";
    		}
    		



        ?></p>
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
    </div><!-- .metaficha -->    
<?php
    $files = mysql_query("SELECT * 
        FROM `archivo`
        WHERE obra_id = ".$obra['id']);
      $i = 'A';
      $images = array("png","jpg","jpeg","gif");
      $text = array("pdf","txt","doc","ppt","odt","rtf","wpd");
      $video = array("mp4","m4v");
      $audio = array("ogg","mp3");

            echo '
          <div class="media">';
            while($row3 = mysql_fetch_assoc($files)){
      $extension = preg_split('/\./',$row3['nuevo']);
      $extension = strtolower($extension[1]);

        // Code for images:
      if(array_search($extension,$images) !== FALSE){
              echo '
            <img class="mediaimage" src="/slir/w720'.$baseurl.'upload/'.$row3["nuevo"].'" alt="'.str_replace("\\&quot;","&quot;",htmlentities($obra['titulo'])).' - '.$obra["id"].$i.'"/>
            <p>'.htmlentities($row3["descripcion"]).'</p>';
      // str_replace("\\&quot;","&quot;",htmlentities($obra['titulo']))
        // Code for text documents:
      }else if(array_search($extension,$text) !== FALSE){
      	echo '
      <div class="download_button text"><a href="'.$baseurl.'upload/'.$row3["nuevo"].'" alt="'.htmlentities($obra["titulo"]).' - '.$obra["id"].$i.'"><img src="http://placehold.it/720x300/000000/ffffff&text=Cargar documento en Issuu">'.htmlentities($obra["titulo"]).'</a></div>';

        // Code for video:
      }else if(array_search($extension,$video) !== FALSE){
      		echo '
      <video class="video-js vjs-default-skin" data-setup=\'{ "controls": true, "autoplay": false, "preload": "auto" }\' src="'.$baseurl.'upload/'.$row3["nuevo"].'" alt="'.htmlentities($obra["titulo"]).' - '.$obra["id"].$i.'" width="720" height="405">Get a real browser yo!</video>';


        // Code for audio:
      }else if(array_search($extension,$audio) !== FALSE){
      		echo '
      <audio src="'.$baseurl.'upload/'.$row3["nuevo"].'" alt="'.htmlentities($obra["titulo"]).' - '.$obra["id"].$i.'">Get a real browser yo!</audio>';


      // All others:
      }else{
      	echo '
      <div class="download_button file"><a href="'.$baseurl.'upload/'.$row3["nuevo"].'" alt="'.htmlentities($obra["titulo"]).' - '.$obra["id"].$i.'">'.htmlentities($obra["titulo"]).'</a></div>';
      }
      $i++;
      }     
      echo "
    </div><!-- .media -->
  </div><!-- Termina .ficha -->
  <hr>
   ";
   }

?>
  
  </div><!-- .wrap -->
  
<!-- ISSUU SMARTLOOK code -->

 <!-- smartlook includes --> 
	<script type="text/javascript"> 
		var issuuConfig = { 
			guid: '448dd5de-662e-4544-9bab-592b879b54dd', 
			domain: '*.artcom.um.edu.mx' 
		}; 
	</script> 
	<script type="text/javascript"> 
		document.write(unescape("%3Cscript src='http://static.issuu.com/smartlook/ISSUU.smartlook.js' type='text/javascript'%3E%3C/script%3E")); 
	</script> 
	<!-- end smartlook includes --> 


</body>
</html>