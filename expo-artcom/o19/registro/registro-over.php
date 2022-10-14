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
      <h1>Ha concluido el registro<br />para esta colección.<br /><br /></h1>
    </header>
    <div id="main">
      <p class="instrucciones">Si tienes alguna duda, envíanos un pitazo y te contestaremos en breve:
        <br />
        <br />
        <a href="https://twitter.com/intent/tweet?screen_name=robertvalencia&text=y%20%40expoartcom%20contactenme%20porfa%2C%20re%3A" class="twitter-mention-button" data-lang="es" data-related="robertvalencia">@robertvalencia</a>
      <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></p>
    </div><!-- #main -->
    <?php include '../_footer.php'; ?>
  </div><!-- #container -->
</body>
</html>