<?php
include '../variables.php';

//CONEXIÓN A LA BASE DE DATOS
  include("../dbconfig.php");
  include("../config_loader.php");

  mysql_connect($config['host'],$config['user'],$config['pass']);
  mysql_select_db($config['db']);
  mysql_set_charset("UTF8");

  $code = $_POST['code'];
  if (isset($code)) {

    $query = "SELECT * FROM evaluadores_externos WHERE usuario = '".$code."' AND cerrada = 0";
    $exists = mysql_query($query);
    $done = false;
    if (mysql_num_rows($exists) > 0) {
      session_start();
      $access = mysql_fetch_assoc($exists);
      $_SESSION['acceso_externo'] = $access['usuario'];
      $_SESSION['end'] = time() + ( 2 * 0 * 0);;
      $_SESSION['expo-externo'] = 2;
      $done = true;
    }
    if (!$done) {

      $query = 'SELECT * FROM  coleccion_autor_semestre_carrera WHERE autor_id = '.$code;
      $exists = mysql_query($query);
      if (mysql_num_rows($exists) > 0) {
        session_start();
        $access = mysql_fetch_assoc($exists);

        $_SESSION['acceso_id'] = $access['autor_id'];
        $_SESSION['expo-externo'] = 2;

        $done = false;
      }
    }
    if ($done) {
      header("Location:http://artcom.um.edu.mx/expo-artcom/".$cnc."/eval/recorrido-externo.php");
    }
  }


?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Selecci&oacute;n del recorrido &middot; <?php echo $coleccion; ?></title>
  <meta name="description" content="">
  <meta name="author" content="Robert Valencia">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/expo-artcom/<?php echo $ccn; ?>/eval/eval2.css">
  <link rel="shortcut icon" href="../<?php echo $favicon; ?>" type="image/png">
  <script src="<?php echo $jquery; ?>"></script>

</head>
<body>
<div class="container">
  <?php if (isset($_POST['code']) & $done == false) {
    echo '<p>El c&oacute;digo no es valido</p>';
  } ?>
  <form action="/expo-artcom/<?php echo $ccn; ?>/eval/user-login.php" method="POST">
        <p>Introduce tu c&oacute;digo de acceso: <input type="text" name="code" autofocus autocomplete="off"></p>
        <input type="submit" value="Entrar">
    </form>
  <footer>

  </footer>
</div>
</body>
</html>