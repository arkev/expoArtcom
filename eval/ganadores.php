<?php
    
  // THIS CODE IS EXTREMELY VALUABLE. When uncommented, it sets this page OFF LIMITS for anyone not authorized.
  // session_start();
  // if(!isset($_SESSION['acceso_id'])){
  //  echo "<!-- nothing to see here ;) -->";
  //  die(1);
  // }
  include '../variables.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ganadores &middot; Sistema de evaluaci&oacute;n &middot; <?php echo $coleccion; ?></title>
    <meta name="description" content="">
    <meta name="author" content="Robert Valencia">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<link rel="shortcut icon" href="../<?php echo $favicon; ?>" type="image/png">
    <link rel="stylesheet" href="eval2.css">
    <script src="<?php echo $jquery; ?>"></script>
    <script>
        // window.setTimeout('location.reload()', 9000);
    </script>
    <style>
      header h1{text-align: left;}
    </style>
</head>
<body id="winners">
    <header>
    <!-- <nav>
        <ul class="main">
        <li class="home"><a href="../" title="Regresar al menú principal"><span>Home</span></a></li>
        <li style= class="active menu">
            <a "width: 14.7em !important;"href="#">Todos los votos</a>
        </li>
        <li><a href="recorrido-externo.php">Seguir votando</a></li>
        </ul>
    </nav> -->
    <h1><?php echo $coleccion; ?> · Selección vox pópuli</h1>
    <h2>Los proyectos con más votos del público</h2>
    </header>
<?php
  // $ccn = 'p13'; // $ccn = current collection nombre_corto

    include("../dbconfig.php");
    include("../config_loader.php");
        
    mysql_connect($config['host'],$config['user'],$config['pass']);
    mysql_select_db($config['db']);

    $votacion_total = mysql_query("SELECT obra_id, count(*) as votos from votacion_externos WHERE obra_id > 0 group by obra_id order by votos DESC");
    echo "<div style=\"width:100%;height:auto; padding-top: 22px;\"><div style=\"height:100%\">";
    echo "<div class=\"winners_column\" style='width:100%'><div>";
    //echo "<h2>Todos los trabajos</h2><fieldset>";
    while($voto = mysql_fetch_assoc($votacion_total)){
        $obra_object = array();
        $obra_id = $voto['obra_id'];
        $obras = mysql_query("SELECT 
        Obra.id, Obra.titulo 
        FROM `obra` as Obra 
        WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection")." 
        AND id = ".$obra_id." LIMIT 100");

        $obra = mysql_fetch_assoc($obras);
        $obra_object['titulo'] = $obra['titulo'];
        $obra_object['titulo'] = str_replace("\\'","'",$obra_object['titulo']);
        $obra_object['titulo'] = str_replace("\\\"","\"",$obra_object['titulo']);
        $obra_object['titulo'] = htmlentities($obra_object['titulo']);

        $autores = mysql_query("SELECT DISTINCT
        Autor.nombres, Autor.apellidos 
        FROM `obra_artista` AS OA
        INNER JOIN `autor` AS Autor ON Autor.matricula = OA.autor_id 
        WHERE OA.obra_id = ".$obra_id." LIMIT 100");

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
        WHERE OM.obra_id = ".$obra_id." LIMIT 100");

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
        $url = "http://artcom.um.edu.mx/i/w320-h106-c320x106/expo-artcom/{$ccn}/upload/{$image['nuevo']}";

        echo "  <dl class=\"proyecto\">
                <a href=\"/g/{$ccn}-{$obra_object['id']}\" target=\"_blank\">
                <dt id=\"proyecto_{$obra_object['id']}\" class=\"proyecto_id\">{$obra_object['id']}</dt> 
                <dd id=\"imagen_{$obra_object['id']}\" class=\"thumbnail\"><img src=\"{$url}\" alt=\"{$row['titulo']} - {$row['id']}\"></dd>
                <dd class=\"votos\">{$voto['votos']} votos</dd>
                <dd class=\"titulo\">{$obra_object['titulo']}</dd>
                <dd class=\"autores\">{$obra_object['autores']}</dd>
                </a>
            </dl>";
    }
    echo "</fieldset></div></div>";

    /*$enfoques = mysql_query("SELECT DISTINCT
            nombre, id, padre, icono 
            FROM `enfoque` 
            ORDER BY id");

    while($enfoque = mysql_fetch_assoc($enfoques)){
    
        $votacion_total = mysql_query("SELECT votacion.obra_id, count(*) as votos 
                                        FROM `votacion` 
                                        LEFT JOIN `obra_enfoque` on obra_enfoque.obra_id = votacion.obra_id 
                                        WHERE  obra_enfoque.enfoque_id = ".$enfoque['id']." 
                                        AND votacion.obra_id > 0 
                                        AND votacion.category = 3 
                                        GROUP BY obra_id, enfoque_id 
                                        ORDER BY votos 
                                        DESC LIMIT 10");

        //FAUX COLUMNS
        echo "<div class=\"winners_column\"><div>";
        #echo "<h2><img style=\"width:20px;padding-right:10px;\" src=".$enfoque['icono']." />".htmlentities($enfoque['nombre'])."</h2><fieldset>";
        echo "<h2>Enfoque: ".htmlentities($enfoque['nombre'])."</h2><fieldset>";
        while($voto = mysql_fetch_assoc($votacion_total)){
            $obra_object = array();
            $obra_id = $voto['obra_id'];
            $obras = mysql_query("SELECT 
            Obra.id, Obra.titulo 
            FROM `obra` as Obra 
            WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection")." 
            AND id = ".$obra_id);

            $obra = mysql_fetch_assoc($obras);
            $obra_object['titulo'] = $obra['titulo'];
            $obra_object['titulo'] = str_replace("\\'","'",$obra_object['titulo']);
            $obra_object['titulo'] = str_replace("\\\"","\"",$obra_object['titulo']);
            $obra_object['titulo'] = htmlentities($obra_object['titulo']);

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
            $url = "http://artcom.um.edu.mx/i/w320-h106-c320x106/expo-artcom/{$ccn}/upload/{$image['nuevo']}";

            $obra_object['image'] = $image['nuevo'];
            
            echo "  <dl class=\"proyecto\">
                <dt id=\"proyecto_{$obra_object['id']}\" class=\"proyecto_id\">{$obra_object['id']}</dt> 
                <dd id=\"imagen_{$obra_object['id']}\" class=\"thumbnail\"><img src=\"{$url}\" alt=\"{$row['titulo']} - {$row['id']}\"></dd>
                <dd class=\"votos\">{$voto['votos']} ".($voto['votos']==1?"voto":"votos")."</dd>
                <dd class=\"titulo\">{$obra_object['titulo']}</dd>
                <dd class=\"autores\">{$obra_object['autores']}</dd>
            </dl>";

        }
        echo "</fieldset></div></div>";
    }
    


    $categorias = mysql_query("SELECT DISTINCT
            nombre, id
            FROM `categoria` 
            ORDER BY id");

    while($categoria = mysql_fetch_assoc($categorias)){
    
        $votacion_total = mysql_query("SELECT votacion.obra_id, count(*) as votos 
                                        FROM `votacion` 
                                        LEFT JOIN `obra` ON obra.id = votacion.obra_id 
                                        WHERE  obra.categoria_id = ".$categoria['id']." 
                                        AND votacion.obra_id > 0 
                                        AND votacion.category = 2 
                                        GROUP BY obra_id, categoria_id 
                                        ORDER BY votos 
                                        DESC LIMIT 10");

        //FAUX COLUMNS
        echo "<div class=\"winners_column\"><div>";
        #echo "<h2><img style=\"width:20px;padding-right:10px;\" src=".$categoria['icono']." />".htmlentities($categoria['nombre'])."</h2><fieldset>";
        echo "<h2>Categor&iacute;a: ".htmlentities($categoria['nombre'])."</h2><fieldset>";
        while($voto = mysql_fetch_assoc($votacion_total)){
            $obra_object = array();
            $obra_id = $voto['obra_id'];
            $obras = mysql_query("SELECT 
            Obra.id, Obra.titulo 
            FROM `obra` as Obra 
            WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection")." 
            AND id = ".$obra_id);

            $obra = mysql_fetch_assoc($obras);
            $obra_object['titulo'] = $obra['titulo'];
            $obra_object['titulo'] = str_replace("\\'","'",$obra_object['titulo']);
            $obra_object['titulo'] = str_replace("\\\"","\"",$obra_object['titulo']);
            $obra_object['titulo'] = htmlentities($obra_object['titulo']);

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
            $url = "http://artcom.um.edu.mx/i/w320-h106-c320x106/expo-artcom/{$ccn}/upload/{$image['nuevo']}";

            $obra_object['image'] = $image['nuevo'];
            
            echo "  <dl class=\"proyecto\">
                <dt id=\"proyecto_{$obra_object['id']}\" class=\"proyecto_id\">{$obra_object['id']}</dt> 
                <dd id=\"imagen_{$obra_object['id']}\" class=\"thumbnail\"><img src=\"{$url}\" alt=\"{$row['titulo']} - {$row['id']}\"></dd>
                <dd class=\"votos\">{$voto['votos']} ".($voto['votos']==1?"voto":"votos")."</dd>
                <dd class=\"titulo\">{$obra_object['titulo']}</dd>
                <dd class=\"autores\">{$obra_object['autores']}</dd>
            </dl>";

        }
        echo "</fieldset></div></div>";
    }
    echo "</div></div>";*/

?> 
    </div>
    <!-- Análisis por Google de las visitas a este sitio -->
    <?php include '../../../analyticstracking.php'; ?>
</body>
</html>