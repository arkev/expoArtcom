<?php
	
	session_start();
	if(!isset($_SESSION['acceso_id'])){
		echo "<!-- nothing to see here ;) -->";
		die(1);
	}

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
	<script type="text/javascript">

		var template = "<div class=\"proyecto\" data-obra-id=\"%ID%\"><i class=\"icon-close\"><a href=\"#\" title=\"Eliminar\">&times;</a></i><dt id=\"proyecto_id\" class=\"proyecto_id\">%ID_VISUAL%</dt><dd id=\"imagen_503\" class=\"thumbnail\"><img src=\"%IMG_URL%\" width=\"80\" height=\"80\" alt=\"%IMG_ALT%\" /></dd><dd class=\"titulo\">%TITULO%</dd><dd class=\"autores\">%AUTORES%</dd><dd class=\"materias\">%MATERIAS%</dd></div>";

		$(function(){
			$("#proyecto_id").keypress(function(e){
				if(e.which == 13){
					$('#add_btn').click();
				}
			})

			$('#add_btn').click(function(e){
				$("#agregador").animate({opacity: 0.25},500);
				$.ajax({
					url: "ajax.php?d="+(Math.random()*1000),
					data: {action:"getObra",id:parseInt($("#proyecto_id").val())},
					type: "POST"
				}).done(function ( data ) {
					$("#agregador").animate({opacity: 1},500);
					$("#proyecto_id").val("");
					json = jQuery.parseJSON(data);
					if(json.status == 'OK'){
						$("#proyecto_id").css({border: "none"});
						var current = template;
						current = current.replace("%ID%",json.id);
						current = current.replace("%ID_VISUAL%",json.id);
						current = current.replace("%IMG_URL%","../slir/w80-h80-c1:1/expo-artcom/p12/upload/"+json.image);
						current = current.replace("%IMG_ALT%",json.titulo);
						current = current.replace("%TITULO%",json.titulo);
						current = current.replace("%AUTORES%",json.autores);
						current = current.replace("%MATERIAS%",json.materias);
						var element = $(current)
						element.hide();
						element.find(".icon-close").click(function(e){
							$(this).parent('.proyecto').slideUp('slow',function(){
								$(this).remove();
							});
						});
						$("#proyectos").append(element)
						element.slideDown();
					}else{
						$("#proyecto_id").css({border: "1px solid red"});
					}
				});
			})

			$('#guardar').click(function(e){
				var obras = [];
				$(".proyecto").each(function(i,e){
					obras.push($(e).data("obra-id"));
				});
				obras = obras.join(",")
				$.ajax({
					url: "ajax.php?d="+(Math.random()*1000),
					data: {action:"saveVotes",obras:obras},
					type: "POST"
				}).done(function ( data ) {
					json = jQuery.parseJSON(data);
					console.log(data)
					if(json.status == 'OK'){
						document.location.replace("exito.php");
					}
				});
			});
			
		});
	</script>
</head>
<body id="evaluacion">
	<div id="container">
		<?php include '_evaluacion_header.php'; ?>
		
		<div id="main" role="main">
			<p><strong>Instrucciones:</strong> Ingresa un número de proyecto por el cual deseas votar.* El proyecto aparecerá abajo.
				Verifica que sea el proyecto correcto y confirma o elimina tu selección.</p>
			<p class="texto_peque">*puedes ingresar más de un proyecto</p>
			<div id="agregador" class="agregador">
				<label for="proyecto_id">Nº</label><input type="text" name="proyecto_id" value="" id="proyecto_id"> <input type="submit" name="" value="Agregar" id="add_btn">
			</div><!-- .agregador -->
			<div id="proyectos" class="proyectos">
			</div><!-- .proyectos --> 
		
			<div class="guardar">
				<input type="submit" name="guardar" value="Guardar" id="guardar">
			</div><!-- .guardar -->
		</div>
		<?php include '../_footer.php'; ?>
	</div>
</body>
</html>