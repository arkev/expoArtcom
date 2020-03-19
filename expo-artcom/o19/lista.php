<!DOCTYPE html>  
<?php
$expo_section = 'lista';
include 'variables.php';
?>
<head>
  <meta charset="utf-8">
  <title>Lista de participantes · <?php echo $coleccion; ?></title>
  <link rel="stylesheet" href="css/expo-<?php echo $expo_section; ?>.css">
  <link rel="shortcut icon" href="<?php echo $favicon ?>" type="image/png">
  <script src="<?php echo $jquery; ?>"></script>
  <script src="js/application.js"></script>
</head>

<body id="<?php echo $expo_section; ?>">

  <div id="container">
    <header>
      <?php include 'nav.php'; ?>
      <h1>Proyectos registrados</h1>
        <div id="search">
              <label for="filter">Filtrar</label> <input type="text" name="filter" value="" id="filter" placeholder="Escribe un nombre, materia, título, etc."/>
            </div>
    </header>
    
    <div id="main">
      
      <table>
      <thead>
      <tr>
        <th class="proyecto_id" align="right">Nº</th>
        <th class="author" align="left">Autor(es)</th>
        <th class="titulo" align="left">Título</th>
        <th class="materia" align="left">Materi(as)</th>
        <th class="archivo" align="left" colspan="10">Evidencia(s) digital(es)</th>
      </tr>
      </thead>
      <tbody>
      <?php
	include("dbconfig.php");
	include("config_loader.php");
        
	mysql_connect($config['host'],$config['user'],$config['pass']);
	mysql_select_db($config['db']);
        
	$obras = mysql_query("SELECT 
		Obra.id, Obra.titulo, Obra.date, Obra.specs, 
		Clasificacion.nombre, 
		Coleccion.nombre AS coleccionNombre
		FROM `obra` as Obra 
		INNER JOIN `clasificacion` AS Clasificacion ON Clasificacion.id = Obra.clasificacion_id 
		INNER JOIN `coleccion` AS Coleccion ON Coleccion.id = Obra.coleccion_id 
		WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection"));

	while($row = mysql_fetch_assoc($obras)){

		echo "<tr id=\"row".$row['id']."\"><td class=\"row_right proyecto_id\">".$row['id']."</td>";

		$autores = mysql_query("SELECT DISTINCT
		OA.autor_id, OA.obra_id, 
		Autor.carrera_id, Autor.nombres, Autor.apellidos, Autor.matricula, Autor.semestre, 
		Obra.coleccion_id, 
		Carrera.inicial as carrera_inicial
		FROM `obra_artista` AS OA
		INNER JOIN `autor` AS Autor ON Autor.matricula = OA.autor_id 
		INNER JOIN `obra` AS Obra ON Obra.id = OA.obra_id 
		LEFT JOIN `carrera` AS Carrera ON Carrera.id = Autor.carrera_id 
		WHERE OA.obra_id = ".$row['id']);

		$buffer = "";
		$buffer2 = "";
		$counterAutores = 0;
		$total = mysql_num_rows($autores);

		echo "<td class=\"row_left author\">";
		while($autor = mysql_fetch_assoc($autores)){
			$buffer = "<div class=\"author\">";
			$buffer .= str_replace(" ","&nbsp;",htmlentities($autor['nombres']));
			$buffer .= " ";
			$buffer .= str_replace(" ","&nbsp;",htmlentities($autor['apellidos']));
			$buffer .= " ";
			if($autor['es_profesor'] == '1'){
				$buffer .= "(Profesor)";
				$buffer .= "</div>";
			}else{
				$buffer .= "(";
				$buffer .= $autor['semestre'];
				$buffer .= $autor['carrera_inicial'];
				$buffer .= ")";
				$buffer .= "</div>";
			}
			echo $buffer;
		}
		echo "</td>";
		
    // echo "<td class=\"row_left titulo\">".htmlentities($row['titulo'])."</td>";
		echo "<td class=\"row_left titulo\"><a href=\"/g/".$coleccion_nombre_corto."-".$row['id']."\" title=\"Enlace permanente a este proyecto\" target=\"_blank\">".htmlentities($row['titulo'])."</a></td>";

			$materias = mysql_query("SELECT DISTINCT
            Materia.nombre
            FROM `obra_materia` AS OM
            INNER JOIN `materia` AS Materia ON Materia.id = OM.materia_id 
            WHERE OM.obra_id = ".$row['id']);
			$materias_str = "";
			while($materia = mysql_fetch_assoc($materias)){
				$materias_str .= "&dash; ".htmlentities($materia['nombre'])."<br />";
			}
			$materias_str = substr($materias_str,0,strlen($materias_str)-6);
			echo "<td class=\"row_left materia\">".$materias_str."</td>";
          
          $files = mysql_query("select * from `archivo` where obra_id = ".$row['id']);
          $i = 'A';
          
          echo "<td class=\"row_left archivo\">";
          while($row3 = mysql_fetch_assoc($files)){
            echo "<a href=\"download.php?f=".$row3['nuevo']."&fc=".$row['id'].$i."\">".$i."</a>";
            echo " ";
            $i++;
          }     
          echo "</td></tr>";
        }
        
        mysql_free_result($obras);
        mysql_free_result($autores);
		mysql_free_result($materias);
        //mysql_free_result($semester);
        mysql_free_result($files);

      ?>
      </tbody>
      </table>
    </div>
    <?php include '_footer.php'; ?>
</body>
</html>