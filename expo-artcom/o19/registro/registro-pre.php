<!DOCTYPE HTML>

<?php
$expo_section = "registro";
include '../variables.php';
?>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>EXPO ARTCOM · Registro de piezas</title>
  <link rel="stylesheet" href="../css/jquery-ui.css" id="theme"/>
  <link rel="stylesheet" href="../css/<?php echo $expo_section; ?>.css"/>
  <link rel="shortcut icon" href="../img/bowtie-favicon@2x.png" type="image/png">
</head>

<body>
  <div id="container">
    
    <header>
      <?php include '../nav.php'; ?>
      <h1>¡Épale!<br />¡ya casi estamos listos para recibir tu proyecto!<br /></h1>
    </header>
    <div id="main">
      <p class="instrucciones">Revisa nuevamente mañana, y si tienes alguna duda, envíanos un pitazo y te contestaremos en breve:
        <br />
        <br />
        <a href="https://twitter.com/intent/tweet?screen_name=moisesvdelgado&text=y%20%40expoartcom%20cont%C3%A1ctenme%20porfa%2C%20re%3A" class="twitter-mention-button" data-lang="es" data-related="moisesvdelgado">@moisesvdelgado</a>
      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></p>
    </div><!-- #main -->
    <?php include '../_footer.php'; ?>
  </div><!-- #container -->
</body>
</html>