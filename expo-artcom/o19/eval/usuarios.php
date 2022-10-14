<?php 
    //CONEXIÓN A LA BASE DE DATOS
    include("../dbconfig.php");
    include("../config_loader.php");

    mysql_connect($config['host'],$config['user'],$config['pass']);
    mysql_select_db($config['db']);
    mysql_set_charset("UTF8");

/*
 * this page seems to create a list of 499 hash users which become our guest voters.
 */


    //echo $_SERVER['REMOTE_ADDR'];
    for ($i=500; $i < 999; $i++) { 
        $usuario = md5(uniqid(rand(), true));
        $short = substr($usuario, 0,3);
        $hash = md5($short);

        $query = "INSERT INTO evaluadores_externos (id,usuario,hash,ip,cerrada) VALUES (null,'vp".$short."','".$hash."',' ',0)";//.$_SERVER['REMOTE_ADDR'];
            //echo $query."<br>";
        //mysql_query($query);
        //echo mysql_error();
    }
    //print_r($users);
?>