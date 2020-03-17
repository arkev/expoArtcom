<?php
session_start();
include '../variables.php';
// $ccn = 'p13';  // $ccn = current collection nombre_corto
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Selección del recorrido</title>
<meta name="description" content="">
<meta name="author" content="Robert Valencia">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="eval2.css">
	<script type="text/javascript" src="<?php echo $jquery; ?>"></script>
	<script type="text/javascript">
	$(document).ready(function() {
			var template = "<dl class=\"proyecto\"> \
				<dd id=\"ajax_status\" class=\"feedback\" style=\"diplay:block\"></dd> \
				<dt id=\"proyecto_%ID%\" class=\"proyecto_id\">%ID_VISUAL%</dt> \
				<dd class=\"checkbox\"> \
					<input type=\"button\" name=\"sel_materias_%ID%\" value=\"%ID%\" id=\"sel_materias_%ID%\"> \
					<label for=\"sel_materias_%ID%\"></label> \
				</dd> \
				<dd id=\"imagen_503\" class=\"thumbnail\"> \
					<img src=\"%IMG_URL%\" alt=\"%IMG_ALT%\"> \
				</dd> \
				<dd class=\"titulo\">%TITULO%</dd> \
				<dd class=\"autores\">%AUTORES%</dd> \
			</dl>"
			//$(window).bind.(
				$.ajax({
						url:"ajax_dos.php?d="+(Math.random()*1000),
						data: {action:"getVotes"},
						type: "POST"
					}).done(function ( votes ) {
						json = jQuery.parseJSON(votes);
						console.log(json)
					});
	});
		function vote(){
			console.log("hola")
		}
	</script>

</head>
<body>
<div class="container">

	<header>
	<nav>
		<ul class="main">
		<li class="home"><a href="/vp" title="Regresar al panel de votación"><span>Home</span></a></li>
		<li class="active menu">
			<a href="#">Recorrido</a>
			<!-- <ul id="menu">
			<li class="menu"><a href="materias.php">Materias</a></li>
			<li class="menu"><a href="categorias.php">Categorías</a></li>
			<li class="menu"><a href="enfoques.php">Enfoques</a></li>
			<li class="menu active"><a href="recorrido.php">Recorrido</a></li> -->
			</ul>
		</li>
		<li><a href="ganadores.php">Ver los resultados</a></li>

		</ul>
	</nav>
	</header>
<script type="text/javascript">

		//var template = "<div class=\"proyecto\" data-obra-id=\"%ID%\"><i class=\"icon-close\"><a href=\"#\" title=\"Eliminar\">&times;</a></i><dt id=\"proyecto_id\" class=\"proyecto_id\">%ID_VISUAL%</dt><dd id=\"imagen_503\" class=\"thumbnail\"><img src=\"%IMG_URL%\" width=\"80\" height=\"80\" alt=\"%IMG_ALT%\" /></dd><dd class=\"titulo\">%TITULO%</dd><dd class=\"autores\">%AUTORES%</dd><dd class=\"materias\">%MATERIAS%</dd></div>";

		var template = "<dl class=\"proyecto\"> \
			<dd id=\"ajax_status\" class=\"feedback\" style=\"diplay:block\"></dd> \
			<dt id=\"proyecto_%ID%\" class=\"proyecto_id\">%ID_VISUAL%</dt> \
			<dd class=\"checkbox\"> \
				<input type=\"button\" name=\"sel_materias_%ID%\" value=\"%ID%\" id=\"sel_materias_%ID%\"> \
				<label for=\"sel_materias_%ID%\"></label> \
			</dd> \
			<dd id=\"imagen_503\" class=\"thumbnail\"> \
				<img src=\"%IMG_URL%\" alt=\"%IMG_ALT%\"> \
			</dd> \
			<dd class=\"titulo\">%TITULO%</dd> \
			<dd class=\"autores\">%AUTORES%</dd> \
		</dl>"

		$(function(){
			/*
			$("#proyecto_id").keypress(function(e){
				if(e.which == 13){
					$('#add_btn').click();
				}
			})
			*/

			var current_items = new Array();
			$('#salir').click(function(e){
				$.ajax({
					url: "ajax_dos.php?d="+(Math.random()*1000),
					data: {action:"salir"},
					type: "POST"
				}).done(function ( data ) {
					json = jQuery.parseJSON(data);
					
					window.location = "user-login.php";
				});
			});
			$('#add_btn').click(function(e){
				if(jQuery.inArray(parseInt($("#proyecto_id").val()),current_items)>=0){
					$("#proyecto_id").css({border: "1px solid orange"});
					return false;
				}

				$("#agregador").animate({opacity: 0.25},500);
				$.ajax({
					url: "ajax.php?d="+(Math.random()*1000),
					data: {action:"getObra",id:parseInt($("#proyecto_id").val())},
					type: "POST"
				}).done(function ( data ) {
					current_items.push(parseInt($("#proyecto_id").val()))
					$("#agregador").animate({opacity: 1},500);
					$("#proyecto_id").val("");
					json = jQuery.parseJSON(data);
					if(json.status == 'OK'){
						$("#proyecto_id").css({border: "none"});
						var current = template;
						current = current.replace(/%ID%/g,json.id);
						current = current.replace(/%ID_VISUAL%/g,json.id);
						current = current.replace(/%IMG_URL%/g,"http://artcom.um.edu.mx/i/w360-h360-c1x1/expo-artcom/<?php echo $ccn ?>/upload/"+json.image);
						current = current.replace(/%IMG_ALT%/g,json.titulo);
						current = current.replace(/%TITULO%/g,json.titulo);
						current = current.replace(/%AUTORES%/g,json.autores);
						current = current.replace(/%MATERIAS%/g,json.materias);
						var element = $(current)
						element.hide();
						element.find("input").click(function(e){
							var proyecto = $(this).parents('.proyecto');
							$.ajax({
								url: "ajax_dos.php?d="+(Math.random()*1000),
								data: {action:"vote",obra:parseInt(id),category:4,checked:0},
								type: "POST"
							}).done(function ( data ){
								proyecto.slideUp('slow',function(){
									$(this).remove();
								});
							});
						});

						$("#eval_linares").append(element)
						element.slideDown();

						var parent = element;
						var feedback = parent.find("#ajax_status");
						element.removeClass("error");
						feedback.removeClass("exito");
						feedback.removeClass("error");

						var id = element.find("dt").text();
						$.ajax({
							url: "ajax_dos.php?d="+(Math.random()*1000),
							data: {action:"vote",obra:parseInt(id),category:4,checked:1},
							type: "POST"
						}).done(function ( data ) {
							json = jQuery.parseJSON(data);
							if(json.status=="OK"){
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

					}else{
						$("#proyecto_id").css({border: "1px solid red"});
					}
				});
				
				return false;
				
			})
			console.log(current_items);
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
	<div class="main">
	<div class="agregador">
	<?php
		if($_SESSION['expo-externo'] == 2){
	?>
		<form>
		<label for="proyecto_id"></label>
		<input type="text" pattern="[0-9]*" name="proyecto_id" value="" id="proyecto_id" placeholder="Nº" autofocus autocomplete="off"> <input type="submit" name="" value="Agregar" id="add_btn">
		<input type="button" name="" value="Salir" id="salir">

		</form>
	<?php
		}else{
			echo "No est&aacute;s autorizado a ver esta p&aacute;gina.";
		}
	?>
	</div><!-- .agregador -->

	<form class="eval_linares" id="eval_linares">
<!--
		<dl class="proyecto">
		<dt id="proyecto_150" class="proyecto_id">150</dt>
		<dd class="checkbox"><input type="submit" name="sel_materias_150" value="150" id="sel_materias_150"><label for="sel_materias_150"></label></dd>
		<dd id="imagen_503" class="thumbnail"><img src="http://artcom.um.edu.mx/i/w360-h360-c1x1/expo-artcom/p12/upload/370ac90fc52dd873eb373a60c0550705.jpg"></dd>
		<dd class="titulo">Naturaleza Humana</dd>
		<dd class="autores">H. Silva</dd>
		</dl>
		<dl class="proyecto">
		<dt id="proyecto_151" class="proyecto_id">151</dt>
		<dd class="checkbox"><input type="submit" name="sel_materias_151" value="151" id="sel_materias_151"><label for="sel_materias_151"></label></dd>
		<dd id="imagen_503" class="thumbnail"><img src="http://artcom.um.edu.mx/i/w360-h360-c1x1/expo-artcom/<?php echo $ccn ?>/upload/bc5d9a99b202ab9267c8654378667e3d.jpg"></dd>
		<dd class="titulo">El retrato en sueño</dd>
		<dd class="autores">G. Ramírez</dd>
		</dl>
		<dl class="proyecto">
		<dt id="proyecto_152" class="proyecto_id">152</dt>
		<dd class="checkbox"><input type="submit" name="sel_materias_152" value="152" id="sel_materias_152"><label for="sel_materias_152"></label></dd>
		<dd id="imagen_503" class="thumbnail"><img src="http://artcom.um.edu.mx/i/w360-h360-c1x1/expo-artcom/<?php echo $ccn ?>/upload/bc5d9a99b202ab9267c8654378667e3d.jpg"></dd>
		<dd class="titulo">El retrato en sueño</dd>
		<dd class="autores">G. Ramírez</dd>
		</dl>
		<dl class="proyecto">
		<dt id="proyecto_153" class="proyecto_id">153</dt>
		<dd class="checkbox"><input type="submit" name="sel_materias_153" value="153" id="sel_materias_153"><label for="sel_materias_153"></label></dd>
		<dd id="imagen_503" class="thumbnail"><img src="http://artcom.um.edu.mx/i/w360-h360-c1x1/expo-artcom/<?php echo $ccn ?>/upload/bc5d9a99b202ab9267c8654378667e3d.jpg"></dd>
		<dd class="titulo">El retrato en sueño</dd>
		<dd class="autores">G. Ramírez</dd>
		</dl>
		<dl class="proyecto">
		<dt id="proyecto_154" class="proyecto_id">154</dt>
		<dd class="checkbox"><input type="submit" name="sel_materias_154" value="154" id="sel_materias_154"><label for="sel_materias_154"></label></dd>
		<dd id="imagen_503" class="thumbnail"><img src="http://artcom.um.edu.mx/i/w360-h360-c1x1/expo-artcom/<?php echo $ccn ?>/upload/bc5d9a99b202ab9267c8654378667e3d.jpg"></dd>
		<dd class="titulo">El retrato en sueño</dd>
		<dd class="autores">G. Ramírez</dd>
		</dl>
		<dl class="proyecto">
		<dt id="proyecto_155" class="proyecto_id">155</dt>
		<dd class="checkbox"><input type="submit" name="sel_materias_155" value="155" id="sel_materias_155"><label for="sel_materias_155"></label></dd>
		<dd id="imagen_503" class="thumbnail"><img src="http://artcom.um.edu.mx/i/w360-h360-c1x1/expo-artcom/<?php echo $ccn ?>/upload/bc5d9a99b202ab9267c8654378667e3d.jpg"></dd>
		<dd class="titulo">El retrato en sueño</dd>
		<dd class="autores">G. Ramírez</dd>
		</dl>
	</form>
	</div>--><!-- .main -->
	<footer>

	</footer>
</div>
</body>
</html>