<!DOCTYPE html>  
<?php
$expo_section = 'lista-autores';
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
        <!-- <th class="proyecto_id" align="right"></th> -->
        <th class="author" align="left">Autor</th>
        <th class="titulo" align="left"></th>
        <th class="materia" align="left"></th>
        <th class="archivo" align="left" colspan="10"></th>
      </tr>
      </thead>
      <tbody>
      <?php
	include("dbconfig.php");
	include("config_loader.php");
        
	mysql_connect($config['host'],$config['user'],$config['pass']);
	mysql_select_db($config['db']);
        
	/*$obras = mysql_query("SELECT 
		Obra.id, Obra.titulo, Obra.date, Obra.specs, 
		Clasificacion.nombre, 
		Coleccion.nombre AS coleccionNombre
		FROM `obra` as Obra 
		INNER JOIN `clasificacion` AS Clasificacion ON Clasificacion.id = Obra.clasificacion_id 
		INNER JOIN `coleccion` AS Coleccion ON Coleccion.id = Obra.coleccion_id 
		WHERE Obra.desaprobada = 1 AND Obra.coleccion_id=".ConfigLoader::getValue("current_collection"));*/
    //$query = 'SELECT matricula,nombres,apellidos,es_profesor,semestre FROM autor';
    $query = 'SELECT DISTINCT OA.autor_id from obra_artista as OA inner join obra as Obra on OA.obra_id = Obra.id where Obra.desaprobado = 0';    
    $OA = mysql_query($query);
    while ($row = mysql_fetch_array($OA)){
      echo "<tr id=\"row".$row['autor_id']."\">";
      $query = 'SELECT nombres, apellidos FROM autor WHERE matricula = '.$row['autor_id'].' ORDER BY nombres ASC';
      $autores = mysql_query($query);
      $print;
      while ($autor = mysql_fetch_array($autores)) {
        $print = "<td class=\"row_left author\">";
        $print .= "<div class=\"author\">";
        $print .= str_replace(" ","&nbsp;",htmlentities($autor['nombres']))." ";
        $print .= str_replace(" ","&nbsp;",htmlentities($autor['apellidos']));
        $print .= "</div>";
      }
      echo $print;
      echo '<td class="row_left titulo"><a href="fichas.php?trato='.$row['autor_id'].'" title="Enlace permanente a fichas del autor" target="_blank">Ver Fichas</a></td>';
      echo "</tr>";
    }

    /*while($row = mysql_fetch_assoc($OA)){
      $buffer = "";
      echo "<td class=\"row_left author\">";
      $buffer = "<div class=\"author\">";
      $buffer .= str_replace(" ","&nbsp;",htmlentities($row['nombres']));
      $buffer .= " ";
      $buffer .= str_replace(" ","&nbsp;",htmlentities($row['apellidos']));
      $buffer .= " ";
      if($autor['es_profesor'] == '1'){
        $buffer .= "(Profesor)";
        $buffer .= "</div>";
      }else{
        $buffer .= "(";
        $buffer .= $autor['semestre'];
        $buffer .= ")";
        $buffer .= "</div>";
      }
		  echo $buffer;*/
  //}
  //mysql_free_result($autores);
  ?>
      </tbody>
      </table>
    </div>
    <?php include '_footer.php'; ?>
</body>
</html>