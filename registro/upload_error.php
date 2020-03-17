<!DOCTYPE html>
<html lang="es">
<head>
  <?php include '../variables.php'; ?>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>Error &middot; <?php echo $coleccion; ?></title>
	<meta name="description" content="">
	<meta name="author" content="">
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="shortcut icon" href="../<?php echo $favicon; ?>" type="image/png">
	<link rel="stylesheet" href="../webfonts/stylesheet.css">
	<link rel="stylesheet" href="../css/common.css">

	<script src="js/foutbgone.js"></script> 
	<script type="text/javascript">
		fbg.hideFOUT('asap');
	</script>
	
  <style type="text/css" media="screen">
    #container{width: 360px;}
    h1{font-family: "DekarLightRegular", Verdana, Arial, Helvetica, sans-serif;font-size: 20px;font-weight: normal;line-height: 1;margin:0;}
		h1 .year{font-family: "DekarRegular",sans-serif;font-size: .8em;letter-spacing: .1em;}
    h2{font: 19px/1.3em Arial, Helvetica, sans-serif;color: #CCC;font-weight: normal;}
    p{font: 11px/1.3em "Lucida Grande", Helvetica, sans-serif;color: #999;}
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
		  <h2>¡ERROR!</h2>
      <p>Lo sentimos</p>
		</div>

		<footer>

		</footer>
	</div>
</body>
</html>