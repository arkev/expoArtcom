<?php
    session_start();
    include '../variables.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Aprobaci&oacute;n de proyectos por categor&iacute;as &middot; <?php echo $coleccion; ?></title>
<meta name="description" content="">
<meta name="author" content="Robert Valencia">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="../<?php echo $favicon; ?>" type="image/png">
<link rel="stylesheet" href="eval2.css">
<script src="<?php echo $jquery; ?>"></script>
</head>
<body>
<?php

    //CONEXIÓN A LA BASE DE DATOS
    include("../dbconfig.php");
    include("../config_loader.php");

    mysql_connect($config['host'],$config['user'],$config['pass']);
    mysql_select_db($config['db']);
    mysql_set_charset("UTF8");

    $categoria = -1;
    if (isset($_GET["categoria_id"])){
        $categoria = mysql_real_escape_string($_GET["categoria_id"]);
    }

?>
<div class="container">
    <header>
    <nav>
        <ul class="main">
        <li class="home"><a href="../" title="Regresar al menú principal"><span>Home</span></a></li>
        <li class="active menu">
            <a href="#">Categorías</a>
            <ul id="menu">
            <li class="menu"><a href="materias.php">Materias</a></li>
            <li class="menu active"><a href="categorias.php">Categorías</a></li>
            <li class="menu"><a href="enfoques.php">Enfoques</a></li>
            <li class="menu"><a href="linares.php">Expo Linares</a></li>
            </ul>
        </li>
        <li class="active submenu enfoques">
            <?php
                $categorias_enabled = explode(",",$_SESSION['categorias']);
                $categorias = mysql_query("SELECT * FROM `categoria`");
                $current = "";
                $list = "";
                while($row = mysql_fetch_assoc($categorias)){
                    if(array_search($row['id'],$categorias_enabled)===FALSE){
                        continue;
                    }
                    if($row['id'] == $categoria || $categoria == -1){
                        $current = "<a href=\"aprobar-proyectos.php?categoria_id={$row['id']}\">{$row['nombre']}</a>";
                        $categoria = $row['id'];
                    }else{
                        $list .= "<li><a href=\"aprobar-proyectos.php?categoria_id={$row['id']}\">{$row['nombre']}</a></li>";
                    }
                    
                }
                echo $current;
                echo "<ul>{$list}</ul>";

                // TRAER OBRAS ------------------>
                $obras = mysql_query("SELECT
                Obra.id, Obra.titulo, Obra.date, Obra.specs, Obra.descripcion,
                Clasificacion.nombre AS clasificacionNombre, Materia.nombre AS materiaNombre, Obra.desaprobado AS estado,
                Coleccion.nombre AS coleccionNombre, 
                Coleccion.nombre_corto AS coleccionURL, 
                Categoria.id as categoria 
                FROM `obra` as Obra 
                INNER JOIN `clasificacion` AS Clasificacion ON Clasificacion.id = Obra.clasificacion_id 
                INNER JOIN `obra_materia` AS obra_materia on obra_materia.obra_id = Obra.id 
                INNER JOIN `materia` AS Materia ON Materia.id = obra_materia.materia_id 
                INNER JOIN `coleccion` AS Coleccion ON Coleccion.id = Obra.coleccion_id 
                INNER JOIN `categoria` AS Categoria ON Categoria.id = Obra.categoria_id 
                WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection")." 
                AND Categoria.id=".$categoria." 
                GROUP BY Obra.id");

                $total = mysql_num_rows($obras);

            ?>
        </li>
        </ul>
    </nav>
    </header>

    <section class="nav">
    <dl>
        <dd class="instrucciones">Elige uno o más proyectos meritorios</dd>
        <dd class="meta"><?php echo $total; ?> proyectos</dd>
    </dl>
    </section>
    <script type="text/javascript">
        $(document).ready(function(){
            jQuery(".checkbox input").click(function(e){
                var checked = 0;
                var box = $(this).parents('.checkbox');
                
                var label = box.find('label');
                label.removeClass('label-0');
                label.addClass('label-loading');

                if($(this).attr("checked")=="checked"){
                    checked = 1;
                }
                var id = $(this).parents("dl.proyecto").find("dt").text();
                var parent = $(this).parents("dl.proyecto");
                var feedback = $(this).parents("dl.proyecto").find("#ajax_status")
                parent.removeClass("error");
                feedback.removeClass("exito");
                feedback.removeClass("error");
                $.ajax({
                    url: "ajax.php?d="+(Math.random()*1000),
                    data: {action:"vote",obra:parseInt(id),category:2,checked:checked},
                    type: "POST"
                }).done(function ( data ) {
                    json = jQuery.parseJSON(data);
                    if(json.status=="OK"){
                        label.removeClass('label-loading');  
                        label.addClass('label-1');
                        feedback.addClass("exito");
                        setTimeout(function(){feedback.removeClass("exito");},4000)
                    }else{
                        parent.find("#ajax_status").addClass("error");
                        feedback.addClass("error");
                    }
                }).error(function(request, status, error){
                    parent.find("#ajax_status").addClass("error");
                    feedback.addClass("error");
                });
            });
        });
    </script>
    <div class="main">
    <form class="eval_materias">
        <fieldset>
        <?php

            //RECORRER OBRAS
            while($row = mysql_fetch_assoc($obras)){
            
                $autores = mysql_query("SELECT DISTINCT
                OA.autor_id, OA.obra_id, 
                Artista.carrera_id, Artista.nombres as nombres, Artista.apellidos, Artista.matricula,
                Obra.coleccion_id, 
                Carrera.inicial  
                FROM `obra_artista` AS OA
                INNER JOIN `autor` AS Artista ON Artista.matricula = OA.autor_id 
                INNER JOIN `obra` AS Obra ON Obra.id = OA.obra_id 
                LEFT JOIN `carrera` AS Carrera ON Carrera.id = Artista.carrera_id 
                WHERE OA.obra_id = ".$row['id']);
                
                $nombresApellidos = "";
                while($row2 = mysql_fetch_assoc($autores)){
                    //$nombresApellidos .= str_replace("&nbsp;"," ",htmlentities($row2['nombres']));
                    $nombresApellidos .= str_replace("&nbsp;"," ",$row2['nombres']);
                    $nombresApellidos .= " ";
                    //$nombresApellidos .= str_replace("&nbsp;"," ",htmlentities($row2['apellidos']));
                    $nombresApellidos .= str_replace("&nbsp;"," ",$row2['apellidos']);
                    $nombresApellidos .= "<br>";
                }
                /*
                $nombresApellidos = str_replace("&aacute;","\u00e1",$nombresApellidos);
                $nombresApellidos = str_replace("&eacute;","\u00e9",$nombresApellidos);
                $nombresApellidos = str_replace("&iacute;","\u00ed",$nombresApellidos);
                $nombresApellidos = str_replace("&oacute;","\u00f3",$nombresApellidos);
                $nombresApellidos = str_replace("&uacute;","\u00fa",$nombresApellidos);
                $nombresApellidos = str_replace("&Aacute;","\u00e1",$nombresApellidos);
                $nombresApellidos = str_replace("&Eacute;","\u00e9",$nombresApellidos);
                $nombresApellidos = str_replace("&Iacute;","\u00ed",$nombresApellidos);
                $nombresApellidos = str_replace("&Oacute;","\u00f3",$nombresApellidos);
                $nombresApellidos = str_replace("&Uacute;","\u00fa",$nombresApellidos);
                $nombresApellidos = str_replace("&Ntilde;","\u00D1",$nombresApellidos);
                $nombresApellidos = str_replace("&ntilde;","\u00F1",$nombresApellidos);
                $nombresApellidos = str_replace("&quot;","\u0022",$nombresApellidos);
                */
                //TRAER LOS ARCHIVOS DE CADA OBRA----------------->
                $files = mysql_query("select * from `archivo` where obra_id = ".$row['id']);
                
                $titulo = $row['titulo'];
                $titulo = str_replace("\\'","'",$titulo);
                $titulo = str_replace("\\\"","\"",$titulo);
                /*
                $titulo = str_replace("&oacute;","\u00f3",$titulo);
                $titulo = str_replace("&uacute;","\u00fa",$titulo);
                $titulo = str_replace("&Aacute;","\u00e1",$titulo);
                $titulo = str_replace("&Eacute;","\u00e9",$titulo);
                $titulo = str_replace("&Iacute;","\u00ed",$titulo);
                $titulo = str_replace("&Oacute;","\u00f3",$titulo);
                $titulo = str_replace("&Uacute;","\u00fa",$titulo);
                $titulo = str_replace("&Ntilde;","\u00D1",$titulo);
                $titulo = str_replace("&ntilde;","\u00F1",$titulo);
                $titulo = str_replace("&quot;","\u0022",$titulo);
                */

                

                $images = array("png","jpg","jpeg","gif");
                $text = array("pdf","txt","doc","ppt");
                $video = array("mp4","m4v");
                $audio = array("ogg","mp3");
                
                $encontroImagen = false;
                $files = mysql_query("select * from `archivo` where obra_id = ".$row['id']);
                
                $row3 = mysql_fetch_assoc($files);
                
                $url = "";
                $extension = preg_split('/\./',$row3['nuevo']);
                $extension = strtolower($extension[1]);

                if(array_search($extension,$images) !== FALSE){
                    $url = "http://artcom.um.edu.mx/i/w320-h106-c320x106/expo-artcom/".$ccn."/upload/{$row3['nuevo']}";
                }else{
                    $url = "http://placehold.it/320x106&text=".strtoupper($extension);
                }
                //$class = ($row['estado']==1) ? 'label-aprobado' : 'label-desaprobado' ;   
                echo "  <dl class=\"proyecto\">
                            <dd id=\"ajax_status\" class=\"feedback\" style=\"diplay:block\"></dd>
                            <dt id=\"proyecto_{$row['id']}\" class=\"proyecto_id\">{$row['id']}</dt>
                            <dd class=\"checkbox\"><input type=\"checkbox\" name=\"categoria_${categoria}_{$row['id']}\" value=\"{$row['id']}\" id=\"obra_{$row['id']}\"><label class='label-".$row['estado']."' for=\"obra_{$row['id']}\"></label></dd>
                            <dd id=\"imagen_{$row['id']}\" class=\"thumbnail\"><img src=\"{$url}\" alt=\"{$row['titulo']} - {$row['id']}\"></dd>
                            <dd class=\"titulo\">{$titulo}</dd>
                            <dd class=\"autores\">{$nombresApellidos}</dd>
                        </dl>";
            } 
        ?>
        </fieldset>
      </form><!-- .eval_materias -->
    </div><!-- .main -->
    <footer>

    </footer>
</div>
</body>
</html>