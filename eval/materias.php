<?php
session_start();
include '../variables.php';
// $ccn = 'p13'; // $ccn = current collection nombre_corto
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Evaluaci&oacute;n de proyectos por categor&iacute;as &middot; <?php echo $coleccion; ?></title>
<meta name="description" content="">
<meta name="author" content="Robert Valencia">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="../<?php echo $favicon; ?>" type="image/png">
<link href="//netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="eval2.css">
<style>
.nav dl{margin:0;padding:0 0 1px 0;line-height:1.6;position:relative;font-size: .875em;}
.nav dd{display:inline;margin:0;padding:0;position:absolute;}
.nav .instrucciones{left:4px;}
.nav .meta{right:4px;}
.nav{display: block;height: 20px;}
/*code for styling close up*/
.zoom{position: absolute;z-index: 500;top: 0px;right: 24px;height: 24px;width: 24px;background-color:rgba(1,1,1,.65);text-align: center;}
.zoom a{color: rgba(200,200,200,.75);}
</style>
<script src="<?php echo $jquery; ?>"></script>
</head>
<body>
<?php
	//CONEXION A LA BASE DE DATOS
	include("../dbconfig.php");
	include("../config_loader.php");

	mysql_connect($config['host'],$config['user'],$config['pass']);
	mysql_select_db($config['db']);
	mysql_set_charset("UTF8");

	$materia = -1;
	if (isset($_GET["materia_id"])){
		$materia = mysql_real_escape_string($_GET["materia_id"]);
	}

?>
<div class="container">
	<header>
	<nav>
		<ul class="main">
		<li class="home"><a href="" title="Regresar al menú principal"><span>Home</span></a></li>
		<li class="active menu">
			<a href="#">Materias</a>
			<ul id="menu">
			<li class="menu"><a href="materias.php">Materias</a></li>
			<li class="menu"><a href="categorias.php">Categorías</a></li>
			<li class="menu active"><a href="enfoques.php">Enfoques</a></li>
			<li class="menu"><a href="recorrido.php">Recorrido</a></li>
			</ul>
		</li>
		<li class="active submenu enfoques">
			<?php
				$active_materias = mysql_query("SELECT * 
												FROM  `coleccion_materia_grupo_profesor` AS CMGP 
												INNER JOIN materia AS Materia ON Materia.id = CMGP.materia_id 
												WHERE CMGP.coleccion_id = ".ConfigLoader::getValue("current_collection")." 
												AND CMGP.profesor=".$_SESSION['profesor_id']." ");
				$current = "";
				
				$list = "";
				while($row = mysql_fetch_assoc($active_materias)){
					if($row['id'] == $materia || $materia == -1){
						$current = "<a href=\"materias.php?materia_id={$row['id']}\">{$row['nombre']}</a>";
						$materia = $row['id'];
					}else{
						$list .= "<li><a href=\"materias.php?materia_id={$row['id']}\">{$row['nombre']}</a></li>";
					}
					
				}
				echo $current;
				echo "<ul>{$list}</ul>";



				$obras = mysql_query("SELECT 
				Obra.id, Obra.titulo, Obra.date, Obra.specs, Obra.descripcion,
				Clasificacion.nombre AS clasificacionNombre, Materia.nombre AS materiaNombre, 
				Coleccion.nombre AS coleccionNombre,
				Coleccion.nombre_corto AS coleccionURL 
				FROM `obra` as Obra 
				INNER JOIN `clasificacion` AS Clasificacion ON Clasificacion.id = Obra.clasificacion_id 
				INNER JOIN `obra_materia` AS obra_materia on obra_materia.obra_id = Obra.id
				INNER JOIN `materia` AS Materia ON Materia.id = obra_materia.materia_id 
				INNER JOIN `coleccion` AS Coleccion ON Coleccion.id = Obra.coleccion_id 
				WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection")." 
				AND Materia.id = ".$materia."
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
			var voded,checked;
			jQuery(".checkbox input").click(function(e){
				var checked = 0;
				/*if($(this).attr("checked")=="checked"){
					checked = 1;
				}*/
				voded = $(this).parents('dd.checkbox').find('.voded_1');

				if(voded.length == 0){
					checked = 1;
					var test = $(this).parents('dd.checkbox').find('.voded_0');
					test.removeClass("voded_0");
					test.addClass("voded_1");
				}else{
					voded.removeClass("voded_1");
					voded.addClass("voded_0");
				}
				var id = $(this).parents("dl.proyecto").find("dt").text();
				var parent = $(this).parents("dl.proyecto");
				var feedback = parent.find("#ajax_status");
				var checkbox = parent.find(".checkbox");
				checkbox.removeClass("error");
				feedback.removeClass("exito");
				feedback.removeClass("error");
				$.ajax({
					url: "ajax.php?d="+(Math.random()*1000),
					data: {action:"vote",obra:parseInt(id),category:1,checked:checked},
					type: "POST"
				}).done(function ( data ) {
					json = jQuery.parseJSON(data);
					if(json.status=="OK"){
						feedback.addClass("exito");
						setTimeout(function(){feedback.removeClass("exito");},4000)
					}else{
						feedback.addClass("error");
						checkbox.addClass("error");
					}
				}).error(function(request, status, error){
					feedback.addClass("error");
					checkbox.addClass("error");
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
				$a = 'SELECT * FROM votacion WHERE obra_id = '.$row["id"].' AND acceso_id = '.$_SESSION["acceso_id"];
				$query = mysql_query($a);
				echo mysql_error();
				$ok = mysql_num_rows($query);
				echo "	<dl class=\"proyecto\">
							<dd id=\"ajax_status\" class=\"feedback\" style=\"diplay:block\"></dd>
							<dt id=\"proyecto_{$row['id']}\" class=\"proyecto_id\">{$row['id']}</dt>
              <!--<dd class=\"zoom\"><a href=\"http://artcom.um.edu.mx/g/p13-{$row['id']}\" rel=\"fancybox\"><i class=\"icon-resize-full\"></i></a></dd>-->
							<dd class=\"checkbox\"><input type=\"radio\" name=\"sel_materias\" value=\"{$row['id']}\" id=\"obra_{$row['id']}\"><label class=\"label voded_{$ok}\" for=\"obra_{$row['id']}\"></label></dd>
							<dd id=\"imagen_{$row['id']}\" class=\"thumbnail\"><img src=\"{$url}\" alt=\"{$row['titulo']} - {$row['id']}\"></dd>
							<dd class=\"titulo\">{$titulo}</dd>
							<dd class=\"autores\">{$nombresApellidos}</dd>
						</dl>";
			}
		?>
	</fieldset>
	</form>
	</div><!-- .main -->
	
	<footer>

	</footer>
</div>
</body>
</html>