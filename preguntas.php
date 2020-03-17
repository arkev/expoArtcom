<!DOCTYPE html>
<?php
$expo_section = 'preguntas'; 
include 'variables.php';
?>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Preguntas frecuentes &middot; <?php echo $coleccion; ?></title>
  <meta name="description" content="sección de preguntas frecuentes respecto al registro de proyectos para la Expo ARTCOM">
  <meta name="author" content="Robert Valencia">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="stylesheet" href="css/<?php echo $expo_section; ?>.css"/>
  <link rel="shortcut icon" href="<?php echo $favicon; ?>" type="image/png">

</head>
<body id="<?php echo $expo_section; ?>">

  <!-- start Fb code -->
  <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
  <!-- finish Fb code -->

  <div id="container">
    <header>
      <?php include 'nav.php'; ?>
      <h1>Preguntas frecuentes</h1>
    </header>

    <div id="main" role="main">
      <h2>¿Qué dimensiones y formato deben tener los documentos que suba?</h2>
      <p class="instrucciones">Hay un límite de 30MB por documento que subas.</p>
      <p class="instrucciones">Si es un documento bidimensional, procura proveer una copia en alta resolución de tu proyecto en PDF, y algunos ejemplos en formato PNG, JPG o GIF.</p>
      <p class="instrucciones">En caso de ser multimedio, como un documento de audio o video, Incluye una ‘carátula’ que represente gráficamente tu proyecto. Esto puede ser como un diseño de una portada, o una captura de algún fotograma de tu video.</p>
      <h2>¿y si quiero presentar videos cómo le hago?</h2>
      <p class="instrucciones">Puedes subir videos en formato .mp4 o .m4v siempre y cuando no pesen más de 30MB.</p>
      <h2>¿y si me equivoqué y ya publiqué un proyecto?</h2>
      <p>Por el momento no es posible que tú mism@ lo elimines, pero no te preocupes, anota el número del proyecto y avísanos para eliminarlo de la base de datos.</p>

      <h2 style="margin-top:2em;border-top:1px solid rgba(222,222,222,.3);padding-top:2em !important;">¿Tienes más preguntas?</h2>
      <p class="instrucciones">Envíanos un tweet y te responderemos en breve: <a href="https://twitter.com/intent/tweet?button_hashtag=<?php echo $cnc; ?>&text=%23expoartcom%20cc%20%40expoartcom" class="twitter-hashtag-button" data-lang="es" data-related="expoartcom,artcomx">Tweet #expoartcom #<?php echo $cnc; ?></a>
      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></p>
      <p style="margin-bottom:2em;">O si prefieres, envía tu pregunta en un comentario de Fb en el siguiente módulo:</p>
      <div class="fb-comments" data-href="http://artcom.um.edu.mx/expo-artcom/<?php echo $cnc; ?>/preguntas.php" data-num-posts="2" data-width="700"></div>
      <!-- 
      Otras preguntas:
      ¿y si tengo una pregunta que no se ha cubierto aquí?
      envíanos tu pregunta y te responderemos en breve
       -->
    </div><!-- #main -->
    <?php include '_footer.php'; ?>
  </div><!-- #container -->
</body>
</html>