<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
  <?php include '../variables.php'; ?>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  
  <title>&iexcl;Gracias! &middot; <?php echo $coleccion; ?></title>
  <meta name="description" content="">
  <meta name="author" content="">
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="stylesheet" href="../webfonts/stylesheet.css">
  <link rel="stylesheet" href="../css/style.css?v=2">

  <script src="../js/foutbgone.js"></script> 
  <script type="text/javascript">
    fbg.hideFOUT('asap');
    setTimeout(function(){location.replace("./")},3000);
  </script>
  
  <style type="text/css" media="screen">
    @import url('../css/PTSansWeb/stylesheet.css');
    #container{width: 640px;margin: 0 auto;}
    h1{font-family: "DekarLightRegular", Verdana, Arial, Helvetica, sans-serif;font-size: 20px;font-weight: normal;line-height: 1;margin:0;}
    h1 .year{font-family: "DekarRegular",sans-serif;font-size: .8em;letter-spacing: .1em;}
    h2{font: 2.3/1.3em "DekarLightRegular", Arial, Helvetica, sans-serif;color: #CCC;font-weight: normal;}
    p{font: 18px/1.3em "PT Sans","Lucida Grande", Helvetica, sans-serif;color: #999;}
    a{color: inherit;}
    h1 img{height: 15px;}
  </style>
</head>
<body>
  <div id="container">
    <header>
      <h1><img src="../<?php echo $headerlogo; ?>"></h1>
    </header>

    <div id="main" role="main">
      <h2>¡Gracias!</h2>
      <p>Tu pieza se ha registrado exitosamente. Puedes verificar tu registro en <a target="_blank" href="../lista.php" title="Lista de participantes en la <?php echo $coleccion; ?>">esta lista</a>.</p>
      <p>Para registrar otra pieza <a href="./" title="Registra una pieza">haz click aquí</a> o espera unos segundos y la página de registro se cargará de nuevo.</p>
    </div>

    <footer>

    </footer>
  </div>
</body>
</html>