<?php

	if(!isset($_FILES['file']) || $_FILES['file']["size"] > 40960000){
		echo "{\"error\":\"The file didn't arrive (too big perhaps?)\"}";
		die(0);
	}

	$file = $_FILES['file'];

	if ($file["error"] > 0){
		echo "{\"error\":" . $file["error"] . "}";
		return;
	}

	$chunks = explode('.',$file['name']);
	$newName = md5($file["name"].microtime()).'.'.$chunks[count($chunks)-1];
	move_uploaded_file($file["tmp_name"], "./upload/" . $newName);

	include("dbconfig.php");
	include("config_loader.php");

	mysql_connect($config['host'],$config['user'],$config['pass']);
	mysql_select_db($config['db']);

	$query = "insert into `archivo` (original,nuevo,pre_obra_id) values (\"".$file['name']."\",\"".$newName."\",".mysql_real_escape_string($_POST['tmp_id']).")";
	
	mysql_query($query);
	$id = mysql_insert_id();

	echo '{"name":"'.$newName.'","oldName":"'.$file['name'].'","type":"'.$file['type'].'","size":"'.$file['size'].'","id":'.$id.'}';

?>
