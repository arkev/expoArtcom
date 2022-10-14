<?php
include("../dbconfig.php");
include("../config_loader.php");

mysql_connect($config['host'],$config['user'],$config['pass']);
mysql_select_db($config['db']);

mysql_set_charset('utf8'); 
if($_POST['tmp_id']){
	$archivo_prefix = "archivo_desc";
	$descriptions = array();

	foreach($_POST as $key => $value){
		if(strpos($key,$archivo_prefix) === false){
			//WHY!
		}else{
			array_push($descriptions,$key);
		}
	}
	
	foreach($descriptions as $desc){
		$id_archivo = substr($desc,strlen($archivo_prefix));//Remove the prefix: "archivo_desc22" -> "22"

		if($_POST[$desc]!="!DELETED!"){
			mysql_query("update `archivo` set descripcion=\"".$_POST[$desc]."\" where id=".$id_archivo);
			handle_failure();
		}else{
			mysql_query("delete from `archivo` where id=".$id_archivo);
			handle_failure();
		}
	}

	$title 	= mysql_real_escape_string($_POST['titulo']);
	$specs = mysql_real_escape_string($_POST['especificaciones']);
	//$materia = mysql_real_escape_string($_POST['materia']);
	$clasificacion = mysql_real_escape_string($_POST['clasificacion']);
	$descripcion = mysql_real_escape_string($_POST['descripcion']);
	$tmp_id = mysql_real_escape_string($_POST['tmp_id']);
	$categoria_id = mysql_real_escape_string($_POST['categoria']);

	//if($materia==ConfigLoader::getValue("independent_id"))
	
	$query = "delete from `pre_obra` where id=".$tmp_id;
	mysql_query($query);
	handle_failure();
	
	$query = "insert into `obra` (titulo,clasificacion_id,descripcion,specs,coleccion_id,date,categoria_id) values (\"".$title."\",".$clasificacion.",\"".$descripcion."\",\"".$specs."\",".ConfigLoader::getValue("current_collection").",(now() + interval 2 hour),".$categoria_id.")";
	//echo $query
	mysql_query($query);
	handle_failure();
	
	$new_id = mysql_insert_id();
	
	$artistas = explode(",", mysql_real_escape_string($_POST['artistas']));
	
	foreach($artistas as $artista ){
		$query = "insert into `obra_artista` (obra_id,autor_id,semestre) values(".$new_id.",".$artista.",(select semestre from `autor` where matricula=".$artista."))";
		mysql_query($query);
		handle_failure();
	}

	$materias = explode(",", mysql_real_escape_string($_POST['materias']));
	
	foreach($materias as $materia ){
		$query = "insert into `obra_materia` (obra_id,materia_id) values(".$new_id.",".$materia.")";
		mysql_query($query);
		handle_failure();
	}
	
	if(isset($_POST['enfoque_fe'])){
		$id = 2;
		if(isset($_POST['fe_plus'])){
			$id = 7;
		}
		$descripcion = mysql_real_escape_string($_POST['descripcion_fe']);
		$query = "insert into `obra_enfoque` (obra_id,enfoque_id,descripcion) values(".$new_id.",".$id.",\"".$descripcion."\")";
		mysql_query($query);
		handle_failure();
	}

	if(isset($_POST['enfoque_negocios'])){
		$id = 1;
		if(isset($_POST['negocios_plus'])){
			$id = 6;
		}
		$descripcion = mysql_real_escape_string($_POST['descripcion_negocios']);
		$query = "insert into `obra_enfoque` (obra_id,enfoque_id,descripcion) values(".$new_id.",".$id.",\"".$descripcion."\")";
		mysql_query($query);
		handle_failure();
	}

	if(isset($_POST['enfoque_entorno'])){
		$id = 3;
		if(isset($_POST['entorno_plus'])){
			$id = 8;
		}
		$descripcion = mysql_real_escape_string($_POST['descripcion_entorno']);
		$query = "insert into `obra_enfoque` (obra_id,enfoque_id,descripcion) values(".$new_id.",".$id.",\"".$descripcion."\")";
		mysql_query($query);
		handle_failure();
	}

	if(isset($_POST['enfoque_investigacion'])){
		$id = 4;
		if(isset($_POST['investigacion_plus'])){
			$id = 5;
		}
		$descripcion = mysql_real_escape_string($_POST['descripcion_investigacion']);
		$query = "insert into `obra_enfoque` (obra_id,enfoque_id,descripcion) values(".$new_id.",".$id.",\"".$descripcion."\")";
		mysql_query($query);
		handle_failure();
	}

	$query = "select id from `archivo` where pre_obra_id = ".mysql_real_escape_string($tmp_id);
	$archivos = mysql_query($query);
	handle_failure();

	while($row = mysql_fetch_row($archivos)){
		mysql_query("update `archivo` set obra_id=".$new_id." where id=".$row[0]);
		handle_failure();
	}
	
	mysql_free_result($archivos);
	
	include("upload_success.php");
}

function handle_failure(){
	if(mysql_error()!=""){
		echo mysql_error();
		include("upload_error.php");
		die(1);
	}
}


?>