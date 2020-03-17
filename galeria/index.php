<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <?php
  // will insert the current collection name
  include '../variables.php';
  ?>
  <title>Galer&iacute;a digital &middot; <?php echo $coleccion; ?></title>
  <link rel="shortcut icon" href="../<?php echo $favicon; ?>" type="image/png">
  
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  
  <link rel="stylesheet" href="css/style.css" />
  
  <script src="js/modernizr-transitions.js"></script>
  
  <!-- scripts at bottom of page -->
	<style>
		#logo{
			position:relative;
			left:15px;
		}
		#menu-container{
			position: relative;
			right: 15px;
		}
		.link{
			float: right;
			text-decoration: none;
			margin: 12px 20px 0 0;
			color:black;
		}
		.link:hover{color:gray;}
	</style>
</head>
<body>
<div id="header" >
	<div id="menu-bar">
		<a href="../galeria">
			<img id="logo" src="../<?php echo $headerlogo; ?>" height="15px" style="padding:12px 0 0 0;float:left;" />
		</a>
		<div id="menu-container">
			<input id="search" type="text" placeholder="Búsqueda"/>
			<div class="line"></div>
			<a href="../galeria"><img id="home" src="images/home.png" height="17px"></a>
			<a href="../preguntas.php" class="link">preguntas frecuentes</a>
			<a href="../lista.php" class="link">listado</a>
			<a href="../../<?php echo $cnc; ?>/registro" class="link">registro</a>
		</div>
	</div>
</div>
<br>
<div id="container" class="transitions-enabled clearfix">
<script>
	//Arreglo de imágenes que aparecen al principio
	var boxes = [];
	var primeras = [];
</script>
	<?php
		//CONEXIÓN A LA BASE DE DATOS
        include("../dbconfig.php");
        include("../config_loader.php");
      
        mysql_connect($config['host'],$config['user'],$config['pass']);
        mysql_select_db($config['db']);
        
    
      	$i = 'A';
      	// TRAER OBRAS ------------------>
      	
      	$contador = 1;
      	
      	$proyectos = 1;
      	
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
          WHERE Obra.coleccion_id=".ConfigLoader::getValue("current_collection")." ");
          
          //RECORRER OBRAS
          while($row = mysql_fetch_assoc($obras)){
          	//echo "hola";
          	
          	//TRAER AUTORES DE CADA OBRA ----------------->
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
            $nombresApellidos1 = "";
             while($row2 = mysql_fetch_assoc($autores)){
                      
                      $nombresApellidos .= str_replace("&nbsp;"," ",htmlentities($row2['nombres']));
                      $nombresApellidos .= " ";
                      $nombresApellidos .= str_replace("&nbsp;"," ",htmlentities($row2['apellidos']));
                      $nombresApellidos .= "|";
                      
                      
                      $nombresApellidos1 .= str_replace("&nbsp;"," ",htmlentities($row2['nombres']));
                      $nombresApellidos1 .= " ";
                      $nombresApellidos1 .= str_replace("&nbsp;"," ",htmlentities($row2['apellidos']));
                      $nombresApellidos1 .= "<br>";
            }
            
              //TRAER LOS ARCHIVOS DE CADA OBRA----------------->
             $files = mysql_query("select * from `archivo` where obra_id = ".$row['id']);
           	 
           	 //RECORRER LOS ARCHIVOS DE CADA OBRA
          	 while($row3 = mysql_fetch_assoc($files)){
          	 	
          	
          	 
          	 		$titulo = htmlentities($row['titulo']);
    				
    				$titulo = str_replace("&aacute;","\u00e1",$titulo);
    				$titulo = str_replace("&eacute;","\u00e9",$titulo);
    				$titulo = str_replace("&iacute;","\u00ed",$titulo);
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
          	 ?>
          	 	<script>
					boxes.push( "<?php echo $row3['nuevo']."%".htmlentities($row['titulo'])." - ".$row['id'].$i."%".$titulo."%".$nombresApellidos."%".$row['id'].$i."%".$nombresApellidos1."%".htmlentities($row['materiaNombre'])."%".htmlentities($row['clasificacionNombre'])."%".htmlentities($row['specs'])."%".htmlentities($row['descripcion'])."%".$row['id']."%".htmlentities($row3['id'])."%".$i; ?>" );
    			</script>
          	 
          	 <?php
          	
          	}
          	 
          	 if($proyectos<=20){
          	 
          	 ?>
          	 <script>
					primeras.push( "<?php echo $row['id']; ?>" );
    		</script>
          	 
          	 
          	 <a onclick="lightbox('<?php echo $row['id'];?>');" class="various1" href="#inline<?php echo $row['id'];?>">
    			<div id="box" class="box photo col3" onmouseover="over('<?php echo $row['id'].$i;?>');" onmouseout="out('<?php echo $row['id'].$i;?>');">
          	 
          	 <?php
           
            
			$images = array("png","jpg","jpeg","gif");
			$text = array("pdf","txt","doc","ppt");
			$video = array("mp4","m4v");
			$audio = array("ogg","mp3");
			
			$encontroImagen = false;
			$files2 = mysql_query("select * from `archivo` where obra_id = ".$row['id']);
			
			while($row3 = mysql_fetch_assoc($files2)){
			 
				$extension = preg_split('/\./',$row3['nuevo']);
				$extension = strtolower($extension[1]);
				
				//SI LA EXTENSION DEL ARCHIVO ES DE TIPO IMAGEN (PNG, JPG, JPEG, GIF) PONER IMAGEN EN GALERIA
				if(array_search($extension,$images) !== FALSE){		 
			 	?>
			 		<img src="/i/w360-h360-c1x1/expo-artcom/<?php echo $ccn; ?>/upload/<?php echo $row3['nuevo'];?>" alt="<?php echo htmlentities($row['titulo'])." - ".$row['id'].$i?>" /> 
			 	<?php
			 		$encontroImagen = true;
			 		break;
			 	}	
			}
			
			if($encontroImagen == false){
				?>
				<img src="http://placehold.it/300x300&text=<?php echo strtoupper($extension);?>" alt="<?php echo htmlentities($row['titulo'])." - ".$row['id'].$i?>" /> 
				<?php
			}
			
			?>
				<div class="cover boxcaption <?php echo $row['id'].$i;?>">
					<h3><?php echo htmlentities($row['titulo']);?></h3>
					<p><?php echo $nombresApellidos1;?></p>
				</div>     
    			</div>
    		</a>
	
			<?php 
				$proyectos++;
			}?>
			
					<!-- DISPLAY FANCY BOX-->
					<div style="display: none;">
		
						
							<div id="inline<?php echo $row['id'];?>">
								<iframe id="fb-like<?php echo $row['id'];?>" src=""
        scrolling="no" frameborder="0"
        style="border:none; overflow:hidden; width:150px; height:21px;" allowTransparency="true"></iframe>
        
        						<br>
								<dd class="titulo" id="titulo"><?php echo htmlentities($row['titulo']);?></dd>
								<br>
								<dt>AUTOR(ES)</dt>
								<dd id="autores"><?php echo $nombresApellidos1;?></dd>
					
								<dt>MATERIA</dt>
								<dd id="materia"><?php echo htmlentities($row['materiaNombre']);?></dd>
					
								<dt>CLASIFICACIÓN</dt>
								<dd id="clasificacion"><?php echo htmlentities($row['clasificacionNombre']);?></dd>
					
								<dt>ESPECIFICACIONES</dt>
								<dd id="especificaciones"><?php echo htmlentities($row['specs']);?></dd>
					
								<dt>DESCRIPCIÓN</dt>
								<dd id="descripcion"><?php echo htmlentities($row['descripcion']);?></dd>
								
								<div id="imgs-container<?php echo $row['id'];?>" style="width:100%;background:black;">
									<?php 
									$files3 = mysql_query("select * from `archivo` where obra_id = ".$row['id']);
									
									if($contador<=20){//Cargar los primeros 20 proyectos, los demas se haran con js al desplegar su vista previa respectiva
										
										 while($row4 = mysql_fetch_assoc($files3)){
										 
										 $extension2 = preg_split('/\./',$row4['nuevo']);
										 $extension2 = strtolower($extension2[1]);
										 if(array_search($extension2,$images) !== FALSE){
									?>
										
										<img class='imgs' src='/i/w360-h360-c1x1/expo-artcom/<?php echo $cnc; ?>/upload/<?php echo $row4['nuevo'];?>'/>
										
									<?php
										   }else{
										   ?>
										   
										   <a target="_blank"href="../upload/<?php echo $row4['nuevo'];?>"><img class="imgs" src="http://placehold.it/300x300&text=<?php echo strtoupper($extension2);?>"></a>
										   <?php
										   }
										}
										$contador++;
									}
									?>
								</div>
								
							</div>
						
					</div>
					
			<!-- end displaying fancybox-->
			
			<?php
			
		}
	?>

</div> <!-- #container -->
<div id="append" class="morePictues">
	Ver más proyectos
</div>

<script src="<?php echo $jquery; ?>"></script>
<script src="jquery.masonry.js"></script>
<script src="js/box-maker.js"></script>

<script>
  
  $(function(){
  

    var $container = $('#container');

  
    $container.imagesLoaded( function(){
      $container.masonry({
        itemSelector : '.box'
      });
    });
  
  	
  
	$('#append').click(function(){
	
		
		document.getElementById('append').innerHTML="<img src='http://content.boostmobile.com/boostwebapp/images/loading_bar.gif' height=13px/> &nbsp;cargando...";
		
	
      	var $boxes = $( getImagenes() );
      
		
		$( $boxes ).css({ opacity: 0 })
		$boxes.imagesLoaded(function(){
		// show elems now they're ready
			$boxes.animate({ opacity: 1 });
		    $container.append( $boxes ).masonry( 'appended', $boxes, true );
			
			
			document.getElementById('append').innerHTML="Ver más proyectos";
			
			
			$(".various1").fancybox({
					'titlePosition'		: 'inside',
					'transitionIn'		: 'elastic',
					'transitionOut'		: 'elastic',
					'overlayShow'	: false,
					'transitionIn'	: 'slidedown',
					'transitionOut'	: 'slidedown'
			});
			
			
		});
    });
	
  });
  
  if(boxes.length<=20){//Si no hay mas de 20imagenes a desplegar no mostrar el boton de "ver mas proyectos"
	document.getElementById('append').style.display="none";
  }
  

  	
  //TRAE MAS IMAGENES PARA PONER EN LA GALERIA
  var i = 0;
  
  var proAnt = "";
  
  function getImagenes(){
  
		var imagenes = [];
		
		var proyectoAnterior = "";
		
		var count = 1;
		while(count<=20){
			if(i>=boxes.length-1){//Si ia no hay imagenes que despleagar
				document.getElementById('append').style.display="none";
				break;
			}
			
			var str = boxes[i].split("%");
			i++;
			
			var found = false;
			for(var w=0; w<primeras.length; w++){
				if(str[10]==primeras[w]){
					found = true;
				}
			}
			
			if(found==true){
				continue;
			}
			
			
			if(proyectoAnterior!=str[10]){
				proyectoAnterior = str[10];
				
				var imgEncontrada = false;
				var jEncontrada = 0;
				for(var j=0; j<boxes.length; j++){
					
					var str2 = boxes[j].split("%");
					
					if(str[10]==str2[10]){
						var extension = str2[0].split(".")[1].toLowerCase();
						
						
						if(extension.indexOf("png") != -1 || extension.indexOf("jpg") != -1 || extension.indexOf("jpeg") != -1 || extension.indexOf("gif") != -1){
							imgEncontrada = true;
							jEncontrada = j;
							break;
						}
						
					}
					
					
					
				}
				
				//CREAR NODOS
				
				
				var showImg = "http://placehold.it/300x300&text="+str[0].split(".")[1].toUpperCase();
				
				
				if(imgEncontrada==true){
					var str3 = boxes[jEncontrada].split("%");
					showImg = "/i/w360-h360-c1x1/expo-artcom/<?php echo $ccn; ?>/upload/"+str3[0];
				}
				
				
				var box = document.createElement('div');
				box.className = 'box photo col3';
				box.id = 'box';
				box.onmouseover= new Function('over("'+str[4]+'")');
				box.onmouseout= new Function('out("'+str[4]+'")');
		
				var img = document.createElement('img');
				img.src=showImg;
				img.alt = str[1];		
			
				box.appendChild(img);
			
			
				var description = document.createElement('div');
				description.className = 'cover boxcaption '+str[4];
			
				var title = document.createElement("h3");
				var titletxt = document.createTextNode(""+str[2]);
				title.appendChild(titletxt);
				description.appendChild(title);
			
				var nombres = str[3].split("|");
			
				for(var cont=0; cont<nombres.length; cont++){
					var names = document.createElement("p");
					var namestxt = document.createTextNode(""+nombres[cont]);
					names.appendChild(namestxt);
					description.appendChild(names);
				}
			
				box.appendChild(description);
			
			
				var link = document.createElement("a");
				link.className = "various1";
				link.setAttribute('href', '#inline'+str[10]);
				link.onclick = new Function('lightbox("'+str[10]+'")');
			
				link.appendChild(box);
			
				imagenes.push(link);
				
				
				count++;
				
				
				//Poner imagenes en su lightbox correspondiente
					
					
				
				for(var n=0; n<boxes.length; n++){
					var arreglo = boxes[n].split("%");
					
					if(arreglo[10]==str[10]){
					
					
						var extension = str[0].split(".")[1].toLowerCase();
						
						
						if(extension.indexOf("png") != -1 || extension.indexOf("jpg") != -1 || extension.indexOf("jpeg") != -1 || extension.indexOf("gif") != -1){
										
							document.getElementById("imgs-container"+str[10]).innerHTML = document.getElementById("imgs-container"+str[10]).innerHTML+" <img class='imgs' src='/i/w360-h360-c1x1/expo-artcom/<?php echo $ccn; ?>/upload/"+arreglo[0]+"'/>";
						
					
						}else{
						
							document.getElementById("imgs-container"+str[10]).innerHTML = document.getElementById("imgs-container"+str[10]).innerHTML + "<a target='_blank'href='../upload/"+arreglo[0]+"'><img class='imgs' src='http://placehold.it/300x300&text="+arreglo[0].split(".")[1].toUpperCase()+"'></a>";
							
						}
						
						
					}
					
				}
					
				//------End poner imagenes----
			}
			
		}
		
		
		
		
		return imagenes;
  }
  
</script>

		<!--  STYLE & JS OF DESCRIPTION BOX AND SHOW&HIDE BIG PICTURE  -->
    
		<script type="text/javascript">
			
			
			function over(div){
				$("."+div).stop().animate({
				    bottom: "0px"
				  }, 300 );	
				
			}
			
			function out(div){
			
				$("."+div).stop().animate({
				    bottom: "-200px"
				  }, 300 );	
			}
			
			
			
			
		</script>
		<style>
			
			dt{
			  	font-family: "M1cregular",sans-serif;
			  	letter-spacing: .1em;
			  	color: #bbb;
			  	margin: 0;
			  	font-size: 10px;
			  	text-transform: uppercase;
			}
			dd{
				margin-left: 0;
				color: #333;
				font-family: "M1cregular",sans-serif;
				font-size: 15px;
			}
			.titulo{
				color:black;
				font-size:19px;
			}
			.bigPic{
				max-width: 500px;
				display: block;
				margin:auto;
			}
		</style>
		
		
	
		<style type="text/css">
		
			h3{ margin: 10px 10px 0 10px; color:#FFF; font:18pt Arial, sans-serif; letter-spacing:-1px; font-weight: bold;  }
			
			.box{
				float:left;  
				overflow: hidden; 
				position: relative;
			}
			.box p{ 
				padding: 0 10px; 
				color:#afafaf; 
				font-weight:bold; 
				font:10pt "Lucida Grande", Arial, sans-serif; 
			}
				
			.boxcaption{
				bottom:-200px;
				visibility: visible;
				float: left; 
				position: absolute; 
				background: #000; 
				/*height: 130px;*/
				overflow:auto; 
				width: 100%; 
				opacity: .8; 
				/* For IE 5-7 */
				filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=80);
				/* For IE 8 */
				-MS-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=80)";
				padding:0 0 13px 0;
 			}
 			
			
			
		</style>
		
		<script>
			jQuery.preloadImages = function(){
  				for(var i = 0; i<arguments.length; i++){
    				jQuery("<img>").attr("src", arguments[i]);
  				}
			}
			// Para utilizar el script y cargar tus imágenes:
			$.preloadImages("http://content.boostmobile.com/boostwebapp/images/loading_bar.gif");
			
			
		</script>
		
		<!--  FANCY BOX-->

		<script type="text/javascript" src="./fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
		<script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.4.js"></script>
		<link rel="stylesheet" type="text/css" href="./fancybox/jquery.fancybox-1.3.4.css" media="screen" />
 		<!--<link rel="stylesheet" href="style.css" />-->
 		
		<script type="text/javascript">
			$(document).ready(function() {
	

				$(".various1").fancybox({
					'titlePosition'		: 'inside',
					'transitionIn'		: 'elastic',
					'transitionOut'		: 'elastic',
					'overlayShow'	: false,
					'transitionIn'	: 'slidedown',
					'transitionOut'	: 'slidedown'
			    });

			});
			
			function lightbox(id){
				
				window.history.replaceState("object or string", "Title", "proyectos1280.php?id="+id);
				
				document.getElementById("fb-like"+id).src = "http://www.facebook.com/plugins/like.php?href=http://artcom.um.edu.mx/g/<?php echo $ccn; ?>-"+id+"&send=false&layout=button_count&width=150&show_faces=false&action=like&colorscheme=light&font=lucida+grande&height=21";
				
			}
			
		</script>
		
		<style>
			.imgs{
				line-height: 0;
				display: block;
				margin: auto;
			
			}
		</style>
		
		<script>
		
		var windowWidth 	= $(window).width();
		if(windowWidth<850){
  			document.getElementById("logo").src = "../<?php echo $headerlogo; ?>";
  			document.getElementById("logo").style.height = "25px";
  			document.getElementById("logo").style.padding = "8px 0 0 0";
  		}else{
  			document.getElementById("logo").src = "../<?php echo $headerlogo; ?>";
  			document.getElementById("logo").style.height = "15px";
  			document.getElementById("logo").style.padding = "12px 0 0 0";
  		}
  		
  		
  		$(window).resize(function(){			
  			
  			windowWidth 	= $(window).width();
  			
  			if(windowWidth<850){
  				document.getElementById("logo").src = "../<?php echo $headerlogo; ?>";
  				document.getElementById("logo").style.height = "25px";
  			document.getElementById("logo").style.padding = "8px 0 0 0";
  			}else{
  				document.getElementById("logo").src = "../<?php echo $headerlogo; ?>";
  				document.getElementById("logo").style.height = "15px";
  				document.getElementById("logo").style.padding = "12px 0 0 0";
  			}
  			
  		});
  		
		
		</script>
</body>
</html>