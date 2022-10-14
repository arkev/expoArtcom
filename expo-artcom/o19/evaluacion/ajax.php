<?php
	session_start();

	error_reporting(0);

	$action = isset($_GET['action'])?$_GET['action']:$_POST['action'];

	// SAVE STUFF
	if($action == "saveVotes"){
		isValid();

		include("../dbconfig.php");
		include("../config_loader.php");
		
		mysql_connect($config['host'],$config['user'],$config['pass']);
		mysql_select_db($config['db']);

		$obras_id = $_POST['obras'];
		$obras = explode(",",$obras_id);

		foreach($obras as $obra){
			$insert = mysql_query("INSERT INTO 
			votacion(obra_id, acceso_id, date) VALUES
			(".intval($obra).",".$_SESSION['acceso_id'].",now())");
		}

		echo "{\"status\":\"OK\"}";

	}
	// GET OBRA
	if($action == "getObra"){

		isValid();

		include("../dbconfig.php");
		include("../config_loader.php");
		
		mysql_connect($config['host'],$config['user'],$config['pass']);
		mysql_select_db($config['db']);

		if(!isset($_POST['id'])){
			die("{\"status\"=\"obra inv&aacute;lida\"}");
		}

		$obra_id = mysql_real_escape_string($_POST['id']);

		$obra_object = array();

		$obras = mysql_query("SELECT 
		Obra.id, Obra.titulo 
		FROM `obra` as Obra 
		WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection")." 
		AND id = ".$obra_id);
		
		if(mysql_num_rows($obras)!=1){
			die("{\"status\":\"obra inv&aacute;lida\"}");
		}

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

		$obra_object['status'] = 'OK';

		echo json_encode($obra_object);

	}

	function isValid(){
		$seconds = $_SESSION['end']-time();
		if($seconds<=0){
			die("{\"status\":\"EXPIRED\"}");
		}
	}

?>