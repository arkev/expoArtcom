<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <?php
  include '../variables.php';
  include("../dbconfig.php");
  include("../config_loader.php");

  mysql_connect($config['host'],$config['user'],$config['pass']);
  mysql_select_db($config['db']);
  
  $baseurl = "/expo-artcom/".$cnc."/";
  
  $id 	= $_GET['id'];
  
  $obras = mysql_query("SELECT 
    Obra.id, Obra.titulo, Obra.date, Obra.specs, Obra.descripcion, 
    Clasificacion.nombre as clasificacionNombre, 
    Coleccion.nombre AS coleccionNombre 
    FROM `obra` as Obra 
    INNER JOIN `clasificacion` AS Clasificacion ON Clasificacion.id = Obra.clasificacion_id 
    INNER JOIN `coleccion` AS Coleccion ON Coleccion.id = Obra.coleccion_id 
    WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection")." and Obra.id = ".$id);

  $obra = mysql_fetch_assoc($obras)
  ?>
<?php
// Include our dom parser
// include('simple_html_dom.php');

// Get the current page URL
// function curPageURL() {
//  $pageURL = 'http';
//  if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
//  $pageURL .= "://";
//  if ($_SERVER["SERVER_PORT"] != "80") {
  // $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 // } else {
  // $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 // }
 // return $pageURL;
// }

// $thispage = curPageURL();

// Grab our data
// $html = file_get_html($thispage);
// $imgtag = $html->find('img', 0)->src;
// $desc = $html->find('p', 0)->plaintext;

// Build the URL
// $pinURL = 'http://pinterest.com/pin/create/button/?url=' . $thispage . '&media=' . $imgtag . '&description=' . $desc . '';

// $html->clear(); 
// unset($html);


?>
  <title><? echo str_replace("\\&quot;","&quot;",htmlentities($obra['titulo'])); ?> &middot; <?php echo $coleccion; ?></title>
  <script type="text/javascript" src="<?php echo $jquery; ?>"></script>
  <link href="//vjs.zencdn.net/4.0/video-js.css" rel="stylesheet">
  <script src="//vjs.zencdn.net/4.0/video.js"></script>
  <script src="<?php echo $baseurl; ?>js/canvas-bowtie.js"></script>
  <script>
  $(document).ready(function () {

      $(document).keydown(function(e) {
          var url = false;
          if (e.which == 37) {  // Left arrow key code
              url = $('.anterior a').attr('href');
          }
          else if (e.which == 39) {  // Right arrow key code
              url = $('.siguiente a').attr('href');
          }
          if (url) {
              window.location = url;
          }
      });

  });
  </script>
  <link rel="shortcut icon" href="<?php echo $baseurl.$favicon; ?>" type="image/png">
  <!-- <link rel="stylesheet" href="<?php echo $baseurl; ?>css/common.css"> -->
  <link rel="stylesheet" href="<?php echo $baseurl; ?>css/expo-fichas.css" media="screen,print">
  <style type="text/css" media="screen">
    @import url('<?php echo $baseurl; ?>css/PTSansWeb/stylesheet.css');
    .metaficha{float: left;width: 306px !important;margin-top: 3em;}
    .media{float: left;width: 720px;margin-left: 10px;margin-top: 3em;}
    .ficha{width: 1036px !important;}
    .ficha + .ficha{clear: both;border-top: 1px solid rgba(210,210,210,.6);margin-bottom: 1.75em;}
    .wrap{width: 1040px;margin: 0 auto;clear: both;position: relative;}
/*    body{background: url('<?php echo $baseurl; ?>css/img/skin_side_up.png') repeat fixed;}*/
    .anterior, .siguiente{position: fixed;top:10em;}
    .anterior a, .siguiente a{text-decoration: none;color: inherit;display: block;height: 88px;width: 31px;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;padding: 10px;}
    .anterior a{background: rgba(50,50,50,.2) url(<?php echo $baseurl; ?>css/images/slider-arrow-35.png) no-repeat center center ;}
    .siguiente a{background: rgba(50,50,50,.2) url(<?php echo $baseurl; ?>css/images/slider-arrow-right-35.png) no-repeat center center ;}
    .anterior a:hover{background: rgba(0,0,0,.6) url(<?php echo $baseurl; ?>css/images/slider-arrow-65.png) no-repeat center center ;}
    .siguiente a:hover{background: rgba(0,0,0,.6) url(<?php echo $baseurl; ?>css/images/slider-arrow-right.png) no-repeat center center ;}
    .anterior{z-index: 10;left:20px;}
    .siguiente{z-index: 11;right: 20px;}
    
    header{width: 1036px;margin: 0 auto;}
    header #nav {display: block;padding-top: .8em;font: .6875em Arial,Helvetica, sans-serif;}
    header #nav li{float: left;list-style-type: none;padding-left: 3em;}
    header #nav li+li{padding-left: 2em;}

    #nav a {
    text-decoration: none;
    padding: .5em;
    color: #27333E;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    }
    #nav a:hover {
    background-color: rgba(111,111,111,.1);
    }
    .highlight_registro .nav_registro,.highlight_lista .nav_lista,.highlight_preguntas .nav_preguntas {
    background-color: rgba(111,111,111,.1);
    /*color: rgba(168,33,187,1) !important;*/
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    }
    header p{display: inline;margin: 0 0 0 3em;width: 300px;float: left;padding: 0;line-height: 1;}
    header img{/*width: 300px;*/height: 20px;}
  </style>
</head>
<body>
  <header>
    <p><a href="<?php echo $baseurl; ?>"><img src="<?php echo $baseurl; ?>img/fade-expoartcom.png"></a></p>
    <ul id="nav" class="highlight_<?php echo $expo_section; ?>">
     <!-- <li><a class="nav_registro" href="<?php echo $baseurl; ?>registro">registro</a></li> -->
     <li><a class="nav_lista" href="<?php echo $baseurl; ?>lista.php">listado</a></li>
     <!-- <li><a class="nav_preguntas" href="<?php echo $baseurl; ?>preguntas.php">preguntas frecuentes</a></li> -->
     <li><a class="nav_galeria" href="<?php echo $baseurl; ?>galeria">galería β&nbsp;(beta)</a></li>
  </header>
  
  <!-- FB LIKE BUTTON -->
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
  
  <div class="wrap">

<!-- 
FICHA #<?php echo $id; ?>

-->

  <!-- Inicia ficha -->
    <div id="ficha_<?php echo $id; ?>" class="ficha tag column ficha-<?php echo $id; ?>">
      
      <div class="metaficha">

        <!-- moño y número de ficha -->
        <canvas class="bowtie" width="44" height="19" style=""></canvas>
        <h2><? echo htmlentities($id); ?></h2>
      
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
    		WHERE OA.obra_id = ".$id);

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
                WHERE OM.obra_id = ".$id);
    
        $materias_concat = "";
        while($materia = mysql_fetch_assoc($materias)){
          $materias_concat .= htmlentities($materia['nombre']).", ";
        }
        $materias_concat = substr($materias_concat,0,strlen($materias_concat)-2);
        ?><p><span class="descriptor">Materias:</span> <?php echo $materias_concat; ?></p>
        <p><span class="descriptor">Clasificación:</span> <? echo htmlentities($obra['clasificacionNombre']); ?></p>
        <p><span class="descriptor">Especificaciones:</span> <? echo htmlentities($obra['specs']); ?></p>
        <p><span class="descriptor">Descripci&oacute;n:</span> <? echo str_replace("\\&quot;","&quot;",htmlentities($obra['descripcion'])); ?></p>

        <?php
          //
          // DEAL WITH ENFOQUES
          //
          $enfoques = mysql_query("SELECT DISTINCT
                  descripcion, Enfoque.nombre, Enfoque.id, Enfoque.padre, Enfoque.icono 
                  FROM `obra_enfoque` AS OE
                  INNER JOIN `enfoque` AS Enfoque ON Enfoque.id = OE.enfoque_id 
                  WHERE OE.obra_id = ".$id." 
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
        WHERE obra_id = ".$id);
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
      <img class="mediaimage" src="/i/w720'.$baseurl.'upload/'.$row3["nuevo"].'" alt="'.str_replace("\\&quot;","&quot;",htmlentities($obra['titulo'])).' - '.$obra["id"].$i.'"/>
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
      echo '
      <div class="fb-like" data-href="http://artcom.um.edu.mx/g/'.$cnc.'-'.$id.'" data-send="true" data-layout="button_count" data-width="450" data-show-faces="true"></div>
    <div class="tweet">
      <a href="https://twitter.com/share" class="twitter-share-button" data-via="expoartcom" data-lang="es">Twittear</a>
    </div>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
      ';
      
      
    // <div class="pinear">
    //   <a href="'.$pinURL.'" class="pin-it-button" count-layout="horizontal">Pin It</a>
    //   <script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>
    // </div>
      echo'

    </div><!-- .media --> 
  </div><!-- Termina .ficha -->
   ';
?>
  </div><!-- .wrap -->
<?php $anterior = $id; ?>
  <div class="anterior">
    <a href="<?php echo $cnc; ?>-<?php echo --$anterior; ?>" title="Ver el proyecto anterior"><!-- ← --></a>
  </div><!-- .anterior -->
<?php $siguiente = $id; ?>
  <div class="siguiente">
    <a href="<?php echo $cnc; ?>-<?php echo ++$siguiente; ?>" title="Ver el siguiente proyecto"><!-- → --></a>
  </div><!-- .siguiente -->

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

  <!-- Google Analytics -->
  <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-35098989-1']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

  </script>
</body>
</html>