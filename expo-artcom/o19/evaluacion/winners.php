<?php
	
  // THIS CODE IS EXTREMELY VALUABLE. When uncommented, it sets this page OFF LIMITS for anyone not authorized.
  // session_start();
  // if(!isset($_SESSION['acceso_id'])){
  //  echo "<!-- nothing to see here ;) -->";
  //  die(1);
  // }

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Sistema de evaluación · Expo ARTCOM Portafolio estudiantil · otoño 2018</title>
	<meta name="description" content="">
	<meta name="author" content="Robert Valencia">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/evaluacion.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
</head>
<body id="winners">
<?php

	include("../dbconfig.php");
	include("../config_loader.php");
        
	mysql_connect($config['host'],$config['user'],$config['pass']);
	mysql_select_db($config['db']);

	$votacion_total = mysql_query("SELECT obra_id, count(*) as votos from votacion WHERE obra_id > 0 group by obra_id order by votos DESC LIMIT 10");

	echo "<div style=\"width:400px;float:left;margin:0 auto\">";
	echo "<h2>Todos los trabajos</h2>";
	while($voto = mysql_fetch_assoc($votacion_total)){
		$obra_object = array();
		$obra_id = $voto['obra_id'];
		$obras = mysql_query("SELECT 
		Obra.id, Obra.titulo 
		FROM `obra` as Obra 
		WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection")." 
		AND id = ".$obra_id);

		$obra = mysql_fetch_assoc($obras);
		$obra_object['titulo'] = htmlentities($obra['titulo']);

		$autores = mysql_query("SELECT DISTINCT
		Autor.nombres, Autor.apellidos 
		FROM `obra_artista` AS OA
		INNER JOIN `autor` AS Autor ON Autor.matricula = OA.autor_id 
		WHERE OA.obra_id = ".$obra_id);

		$buffer = "";
		while($autor = mysql_fetch_assoc($autores)){
			$buffer .= $autor['nombres']." ".$autor['apellidos'].", ";
		}
		$buffer = substr($buffer, 0, strlen($buffer)-2);
		$obra_object['autores'] = htmlentities($buffer);

		$materias = mysql_query("SELECT DISTINCT
		Materia.nombre
		FROM `obra_materia` AS OM
		INNER JOIN `materia` AS Materia ON Materia.id = OM.materia_id 
		WHERE OM.obra_id = ".$obra_id);

		$buffer = "";
		while($materia = mysql_fetch_assoc($materias)){
			$buffer .= $materia['nombre'].", ";
		}
		$buffer = substr($buffer, 0, strlen($buffer)-2);
		$obra_object['materias'] = htmlentities($buffer);

		$obra_object['id'] = $obra_id;

		$image = mysql_query("SELECT nuevo
		FROM archivo 
		WHERE obra_id = ".$obra_id." 
		LIMIT 1");

		$image = mysql_fetch_assoc($image);

		$obra_object['image'] = $image['nuevo'];
	?>

		<div class="proyecto">
			<div class="votos"><?php echo $voto['votos'];?> <small>votos</small></div>
			<dt id="proyecto_id" class="proyecto_id"><?php echo $obra_object['id'];?></dt>
			<dd id="imagen_503" class="thumbnail"><img src="../slir/w80-h80-c1:1/expo-artcom/p12/upload/<?php echo $obra_object['image'];?>" width="80" height="80" /></dd>
			<dd class="titulo"><?php echo $obra_object['titulo'];?></dd>
			<dd class="autores"><?php echo $obra_object['autores'];?></dd>
			<dd class="materias"><?php echo $obra_object['materias'];?></dd>
		</div>

	<?php
	}
	echo "</div>";

	$enfoques = mysql_query("SELECT DISTINCT
            nombre, id, padre, icono 
            FROM `enfoque` 
            ORDER BY id");

	while($enfoque = mysql_fetch_assoc($enfoques)){
	
		$votacion_total = mysql_query("SELECT votacion.obra_id, count(*) as votos from votacion left join obra_enfoque on obra_enfoque.obra_id = votacion.obra_id  WHERE  obra_enfoque.enfoque_id = ".$enfoque['id']." and votacion.obra_id > 0 group by obra_id,enfoque_id order by votos DESC LIMIT 10");

		echo "<div style=\"margin-left:20px;width:400px;float:left;\">";
		echo "<h2><img style=\"width:20px;padding-right:10px;\" src=".$enfoque['icono']." />".htmlentities($enfoque['nombre'])."</h2>";
		while($voto = mysql_fetch_assoc($votacion_total)){
			$obra_object = array();
			$obra_id = $voto['obra_id'];
			$obras = mysql_query("SELECT 
			Obra.id, Obra.titulo 
			FROM `obra` as Obra 
			WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection")." 
			AND id = ".$obra_id);

			$obra = mysql_fetch_assoc($obras);
			$obra_object['titulo'] = htmlentities($obra['titulo']);

			$autores = mysql_query("SELECT DISTINCT
			Autor.nombres, Autor.apellidos 
			FROM `obra_artista` AS OA
			INNER JOIN `autor` AS Autor ON Autor.matricula = OA.autor_id 
			WHERE OA.obra_id = ".$obra_id);

			$buffer = "";
			while($autor = mysql_fetch_assoc($autores)){
				$buffer .= $autor['nombres']." ".$autor['apellidos'].", ";
			}
			$buffer = substr($buffer, 0, strlen($buffer)-2);
			$obra_object['autores'] = htmlentities($buffer);

			$materias = mysql_query("SELECT DISTINCT
			Materia.nombre
			FROM `obra_materia` AS OM
			INNER JOIN `materia` AS Materia ON Materia.id = OM.materia_id 
			WHERE OM.obra_id = ".$obra_id);

			$buffer = "";
			while($materia = mysql_fetch_assoc($materias)){
				$buffer .= $materia['nombre'].", ";
			}
			$buffer = substr($buffer, 0, strlen($buffer)-2);
			$obra_object['materias'] = htmlentities($buffer);

			$obra_object['id'] = $obra_id;

			$image = mysql_query("SELECT nuevo
			FROM archivo 
			WHERE obra_id = ".$obra_id." 
			LIMIT 1");

			$image = mysql_fetch_assoc($image);

			$obra_object['image'] = $image['nuevo'];
		?>

			<div class="proyecto">
				<div class="votos"><?php echo $voto['votos'];?> <small>votos</small></div>
				<dt id="proyecto_id" class="proyecto_id"><?php echo $obra_object['id'];?></dt>
				<dd id="imagen_503" class="thumbnail"><img src="../slir/w80-h80-c1:1/expo-artcom/o18/upload/<?php echo $obra_object['image'];?>" width="80" height="80" /></dd>
				<dd class="titulo"><?php echo $obra_object['titulo'];?></dd>
				<dd class="autores"><?php echo $obra_object['autores'];?></dd>
				<dd class="materias"><?php echo $obra_object['materias'];?></dd>
			</div>

		<?php
		}
		echo "</div>";
	}

?> 
	</div>
</body>
</html>