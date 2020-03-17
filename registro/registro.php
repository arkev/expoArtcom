<!DOCTYPE HTML>
<html lang="es">
<head>
  <meta charset="utf-8">

<?php

include("../dbconfig.php");
include("../config_loader.php");
include '../variables.php';
// mysql > SET time_zone = timezone;
// mysql> SET GLOBAL time_zone = timezone;
mysql_connect($config['host'],$config['user'],$config['pass']);
mysql_select_db($config['db']);
mysql_query("insert into `pre_obra` (titulo,tecnica,specs,materia_id,semestre,coleccion_id,date) values (0,0,0,0,0,0,(now() + interval 2 hour))");
$tmp_id = mysql_insert_id();

$expo_section = "registro";
?>
  <title>Registro de proyectos &middot; <?php echo $coleccion; ?></title>
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/jquery-ui.css" id="theme"/>
  <link rel="stylesheet" href="../css/<?php echo $expo_section; ?>.css"/>
  <!-- <link rel="stylesheet" href="css/jquery.fileupload-ui.css"> -->
  <link rel="stylesheet" href="../css/uniform.default.css" media="screen"/>
  <link rel="shortcut icon" href="../<?php echo $favicon; ?>" type="image/png">
  <script type="text/javascript" src="<?php echo $jquery; ?>"></script>
  <!--<script src="../js/jquery.min.js"></script>-->
  <script src="../js/bootstrap.min.js"></script>
  <script src="../js/bootstrap-button.js"></script>
  <script src="../js/jquery.autoresize.js"></script>
  <script src="../js/jquery-ui.min.js"></script>
  <script src="../js/jquery.fileupload.js"></script>
  <script src="../js/jquery.fileupload-ui.js"></script>
  <script src="../js/jquery.jqEasyCharCounter.min.js"></script>
  
  <link rel="stylesheet" href="../css/TextboxList.css" media="screen"/>
  <link rel="stylesheet" href="../css/TextboxList.Autocomplete.css" media="screen"/>  
  <script src="../js/foutbgone.js"></script>
  <script src="../js/TextboxList/SuggestInput.js"></script>
  <script src="../js/TextboxList/GrowingInput.js"></script>
  <script src="../js/TextboxList/TextboxList.js"></script>    
  <script src="../js/TextboxList/TextboxList.Autocomplete.js"></script>
  <script src="../js/TextboxList/TextboxList.Autocomplete.Binary.js"></script>
  <script src="../js/jquery.uniform.min.js" type="text/javascript"></script>
  <script type='text/javascript'>
    $(document).ready(function() {
      $("select, input:checkbox:visible, input:radio").uniform();
      <?php
/* The following four lines were appearing commented out… was this intentional???  for now I’m ‘de-commenting it’ and thus leaving the code ‘active’

		$q = mysql_query('DESCRIBE obra');
while($row = mysql_fetch_array($q)) {
    echo "{$row['Field']} - {$row['Type']}\n";
}
*/

		/* ADDING PROFESORES is too much hassle, just add a column called is_profesor in table `artista` */
        $result = mysql_query(
         "SELECT autor_id as id,Autor.nombres,Autor.apellidos,Autor.es_profesor 
          FROM `coleccion_autor_semestre_carrera` 
          INNER JOIN `autor` AS Autor ON Autor.matricula = autor_id
          WHERE coleccion_id=".ConfigLoader::getValue("current_collection"));
        
        $buffer = "";
        while($row = mysql_fetch_assoc($result)){
          $id = $row["id"];
          $nombre = htmlentities($row["nombres"])." ".htmlentities($row["apellidos"]);
			if($row['es_profesor']=="1"){
				$buffer.="[{$id},\"{$nombre}\",null,\"<span class='profesor'>Profesor</span>{$nombre}\"],";
			}else{
				$buffer.="[{$id},\"{$nombre}\",null,\"{$nombre}\"],";
			}
        }
        mysql_free_result($result);
        
        $buffer = substr($buffer,0,strlen($buffer)-1);
        
        echo "var data_autor = [".$buffer."]";

		$result = mysql_query(
         // "SELECT materia.id,materia.nombre,Carrera.inicial
         //  FROM `materia`
         //  LEFT JOIN `carrera` Carrera on Carrera.id = materia.carrera_id"

//          "SELECT mgp.materia_id, mat.nombre, car.inicial
// FROM `coleccion_materia_grupo_profesor` AS mgp
// LEFT JOIN `carrera` AS car
// ON mgp.carrera_id = car.id
// LEFT JOIN `materia` AS mat
// ON mgp.materia_id = mat.id
// WHERE mgp.coleccion_id = ".ConfigLoader::getValue("current_collection")

"SELECT mgp.materia_id, mat.nombre, GROUP_CONCAT(DISTINCT car.inicial ORDER BY car.inicial ASC SEPARATOR ' | ') AS iniciales
FROM `coleccion_materia_grupo_profesor` AS mgp
LEFT JOIN `carrera` AS car
ON mgp.carrera_id = car.id
LEFT JOIN `materia` AS mat
ON mgp.materia_id = mat.id
WHERE mgp.coleccion_id = ".ConfigLoader::getValue("current_collection")."
GROUP BY mat.nombre"
      );

		echo "\n\n\n";
        
        $buffer = "";
        while($row = mysql_fetch_assoc($result)){
          $id = $row["materia_id"];
          $nombre = htmlentities($row["nombre"]." (".$row['iniciales'].")");
          $buffer.="[{$id},\"{$nombre}\",null,\"{$nombre}\"],";
        }
        mysql_free_result($result);
        
        $buffer = substr($buffer,0,strlen($buffer)-1);
        
        echo "var data_materia = [".$buffer."]";

      ?>
      
      autoresAutocomplete = new $.TextboxList('#artistas', {unique: true, plugins: {autocomplete: {onlyFromValues:true,inlineSuggest:false}}});
      autoresAutocomplete.plugins['autocomplete'].setValues(data_autor);

      materiaAutocomplete = new $.TextboxList('#materias', {unique: true, plugins: {autocomplete: {onlyFromValues:true,inlineSuggest:false}}});
      materiaAutocomplete.plugins['autocomplete'].setValues(data_materia);
      
      $("#segundos").blur(function(){
        segundos = parseInt($("#segundos").val());
        minutos =  0;
        if(segundos>=60){
          minutos+=parseInt(segundos/60);
          segundos = segundos%60;
          if(segundos<10){segundos="0"+segundos;}
          if(minutos<10){minutos="0"+minutos;}
          $("#minutos").val(minutos);
          $("#segundos").val(segundos);
          $("#minutos").blur();
        }
      });
      
      $("#minutos").blur(function(){
        minutos = parseInt($("#minutos").val());
        horas =  0;
        if(minutos>=60){
          horas+=parseInt(minutos/60);
          minutos = minutos%60;
          if(minutos<10){minutos="0"+minutos;}
          if(horas<10){horas="0"+horas;}
          $("#minutos").val(minutos);
          $("#horas").val(horas);
          
          $("#segundos").val("00");
          
        }
      });
      
      $("#details").submit(function(evt){

        $("#files").find("textarea").each(function(i, e){
          hidden = document.createElement("input");
          hidden.type="hidden";
          hidden.name = e.id;
          hidden.value = e.value;
          $("#details").prepend(hidden);
        });

		if(autoresAutocomplete.getValues().length==0){
          alert("Has intentado enviar el proyecto sin asignar autor\(es\). No se vale... \:\)");
          return false;
        }
        
        if(jQuery(".file_uploaded:visible").length==0){
          alert("Debes agregar evidencias digitales antes de enviar tu proyecto.");
          return false;
        }
        
        if(materiaAutocomplete.getValues().length==0){
          alert("Elige una materia o indicar que es un proyecto independiente antes de enviar.");
          return false;
        }
        
        if ($("#titulo").val()=="") {
            alert("Ingresa un título para este proyecto");
            return false;
        }

        if ($("#descripcion").val()=="") {
            alert("Agrega una descripción para este proyecto");
            return false;
        }

        
        if($("#clasificacion").val()==""){
          alert("Elige una clasificación para tu proyecto antes de guardar.");
          return false;
        }
		
    
        if($("input[name='categoria']").serializeArray().length==0){
          alert("Elige una categoría para tu trabajo.");
          return false;
        }
    
		
		if( $('input[name=enfoque_investigacion]').attr('checked') != undefined && $('textarea[name=descripcion_investigacion]').val() == "" ){
			alert("Has intentado enviar el proyecto sin explicar cómo se demuestra el enfoque en la técnica");
			return false;
		}
		if( $('input[name=enfoque_negocios]').attr('checked') != undefined && $('textarea[name=descripcion_negocios]').val() == "" ){
			alert("Has intentado enviar el proyecto sin explicar cómo se demuestra el enfoque en los negocios");
			return false;
		}
		if( $('input[name=enfoque_fe]').attr('checked') != undefined && $('textarea[name=descripcion_fe]').val() == "" ){
			alert("Has intentado enviar el proyecto sin explicar cómo se demuestra el enfoque en la fe");
			return false;
		}
		if( $('input[name=enfoque_entorno]').attr('checked') != undefined && $('textarea[name=descripcion_entorno]').val() == "" ){
			alert("Has intentado enviar el proyecto sin explicar cómo se demuestra el enfoque en el entorno");
			return false;
		}

		selected = $( "#tabs li.active" ).attr("id");

        switch(selected){
          case "specs_1":
            unidad = jQuery("#bi_unidad_de_medida").val();
			if( isNaN(parseInt($("#bi_altura").val())) || isNaN(parseInt($("#bi_anchura").val())) ||
				$("#bi_altura").val()=="" || $("#bi_anchura").val()==""
			){
				alert("No has ingresado un valor válido en las especificaciones!");
				return false;
			}
            if(unidad==""){
              alert("Selecciona una unidad!");
              return false;
            }
            specs = jQuery("#bi_altura").val() + " × " + jQuery("#bi_anchura").val() + " " + unidad;
            // " × " is equivalent to "&nbsp;&times;&nbsp;" // THIS IS EVIL, special chars dont work well everywhere
          break;
          case "specs_2":
            unidad = jQuery("#tri_unidad_de_medida").val();
			if( isNaN(parseInt($("#tri_altura").val())) || isNaN(parseInt($("#tri_anchura").val())) || isNaN(parseInt($("#profundidad").val())) ||
				$("#tri_altura").val()=="" || $("#tri_anchura").val()=="" || $("#profundidad").val()==""
			){
				alert("No has ingresado un valor válido en las especificaciones!");
				return false;
			}
            if(unidad==""){
              alert("Selecciona una unidad!");
              return false;
            }
            specs = jQuery("#tri_altura").val() + " × " + jQuery("#tri_anchura").val() + " × " + jQuery("#profundidad").val() + " " + unidad;
            // " × " is equivalent to "&nbsp;&times;&nbsp;"
          break;
          case "specs_3":

			if( isNaN(parseInt($("#horas").val())) || isNaN(parseInt($("#minutos").val())) || isNaN(parseInt($("#segundos").val())) ||
				$("#horas").val()=="" || $("#minutos").val()=="" || $("#segundos").val()==""
			){
				alert("No has agregado un valor válido en las especificaciones!");
				return false;
			}

            hora = jQuery("#horas").val();
            minutos = jQuery("#minutos").val();
            segundos = jQuery("#segundos").val();

            hora = hora==""?"00":hora;
            minutos = minutos==""?"00":minutos;
            segundos = segundos==""?"00":segundos;

            if(hora.length==1){hora="0"+hora;}
            if(minutos.length==1){minutos="0"+minutos;}
            if(segundos.length==1){segundos="0"+segundos;}
            
            specs = hora + ":" + minutos + ":" + segundos;
          break;
          case "specs_4":
			if(jQuery("#otros").val()==""){
				alert("Las especificaciones no fueron ingresadas");
				return false;
			}
            specs = jQuery("#otros").val();
          break;
        }

		jQuery("#especificaciones").val(specs);

        values = autoresAutocomplete.getValues();
		ids = "";
		jQuery.each(values,function(i,e){
          ids+=e[0]+","
        });
		hidden = document.createElement("input");
		hidden.type = "hidden";
		hidden.name = "artistas";
		hidden.value = ids.substr(0,ids.length-1)
		$("#details").prepend(hidden);
      })
    });
    
    //FOUT-B-Gone script
    fbg.hideFOUT('asap');
    //jQuery easy character counter
	$(document).ready(function(){
		$('#titulo').jqEasyCounter({
			'maxChars': 55,
			'maxCharsWarning': 40,
			'msgFontSize': '11px',
			'msgFontColor': '#000',
			'msgFontFamily': 'Arial',
			'msgTextAlign': 'left',
			'msgWarningColor': '#F00',
			'msgAppendMethod': 'insertBefore'              
		});

		$('textarea.upload_textarea,textarea#descripcion').autoResize({
			minWidth: "original",
			minHeight: 16,
			extraSpace: 16
		});

    });
  </script>
</head>

<body>
  <div id="container">
    
    <header>
      <?php include '../nav-registro.php'; ?>
      <h1>Registra un proyecto</h1>
    </header>
    <div id="main">
      <h2 id="sube_documentos">Evidencias digitales <span id="evidencias_popover" class="" rel="popover" data-content="Cada proyecto participante puede tener más de un documento digital, puedes subir todos los que quieras, pero no subas documentos que pertenezcan a otros proyectos." data-original-title="Sube tus documentos"></i> <i class="icon-question-sign"></i></span></h2> 
      <!-- <p class="instrucciones">Cada proyecto participante puede tener más de un documento digital, puedes subir todos los que quieras, pero no subas documentos que pertenezcan a otros proyectos.</p> -->
      <!-- FILE UPLOAD FORM
      ********************* -->

      <form id="file_upload" action="../upload.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="tmp_id" value="<?php echo $tmp_id; ?>"/>
        <input type="file" name="file" multiple>
        <button>Subir los archivos</button>
        <div>Arrastra aquí tus archivos</div>
      </form>
      <table id="files"></table>
  
      <!-- #DETAILS FORM
      ******************** -->
  
      <form id="details" action="saver.php" method="POST">
        <input type="hidden" name="tmp_id" value="<?php echo $tmp_id; ?>"/>

        <!-- AUTORES Input
        ********************* 
    
        TODO  I’d like to add the names of profesores to the list. For this I’ve created 
              a ‘profesor’ table on the database. Similar to the Artista table, ‘profesor’ has
              id, carrera_id, matricula, nombres, apellidos, and nombre_corto.
        -->

        <h2>Autor(es) <span id="autores_popover" class="" rel="popover" data-content="Añade <em>todos</em> los autores que participaron en este proyecto." data-original-title="Autor(es)"></i> <i class="icon-question-sign"></i></span></h2>
        <!-- <p class="instrucciones">Añade <em>todos</em> los autores que participaron en este proyecto.</p> -->
        <table id="autorTable">
          <tr class="datos personales" id="autorRow">
            <!-- <td class="tdlabel"><label for="select_alumno">Nombre del autor</label></td> -->
            <td><input type="text" name="artistas" id="artistas" placeholder="Nombre(s) de autor(es)"></td>
          </tr>
        </table>

          <!-- Selección de MATERIA
          ***************************** -->
          <h2>Materias <span id="materias_popover" class="" rel="popover" data-content="Escribe y elige el nombre de cada materia asociada a este proyecto, o <strong><em>Proyecto independiente</em></strong> si no corresponde a una materia. Las iniciales entre paréntesis representan las letras identificadoras de cada carrera:<br /> • Artes Visuales - (<strong>P</strong>)intura<br />• Artes Visuales - (<strong>F</strong>)otografía<br />• (<strong>A</strong>)rtes Visuales<br />• (<strong>D</strong>)iseño de Comunicación Visual<br />• Comunicación (<strong>V</strong>)isual<br />• Ciencias de la (<strong>C</strong>)omunicación<br />• Comunicación y (<strong>M</strong>)edios" data-original-title="Materias"></i> <i class="icon-question-sign"></i></span></h2>
          <!-- <p class="instrucciones">Escribe y elige el nombre de la materia o ‘<em>Proyecto independiente</em>’ si no corresponde a una materia.</p> -->
          <!-- <p class="instrucciones"><strong>Nota:</strong> Se permite asignar una sola materia. Si el proyecto fue utilizado para más de una materia, se debe registrar nuevamente.</p> -->
          <table>
            <tr>
              <!-- <td class="tdlabel"><label for="select_materia">Materia</label></td> -->
              <td><input type="text" name="materias" id="materias" placeholder="Materia"></td>
            </tr>
          </table>


        <h2>Datos de la pieza</h2>
        <table>
          <!-- TÍTULO de la obra
          ************************** -->
          <tr>
            <td class="tdlabel"><label for="título">Título</label></td>
            <td><input type="text" name="titulo" value="" id="titulo" placeholder="Ponle nombre a tu proyecto" size="33"></td>
          </tr>
      

    
          <!-- Selección de CLASIFICACIÓN
          ********************************** -->
          <tr>
            <td class="tdlabel"><label for="select_clasificacion">Clasificación</label></td>
            <td><select name="clasificacion" id="clasificacion">
              <option value="">Selecciona una clasificación</option>
              <?php
                $clasificaciones = mysql_query("select * from clasificacion");
                while($row = mysql_fetch_assoc($clasificaciones)){
                  echo "<option value=\"".$row['id']."\">".htmlentities($row['nombre'])."</option>";
                }
                mysql_free_result($clasificaciones);
              ?>
            </select></td>
          </tr>
      
          <!-- DESCRIPCIÓN general
          **************************** -->
          <tr>
            <td class="tdlabel"><label for="descripcion">Descripción general</label></td>
            <td><textarea type="text" name="descripcion" value="" id="descripcion" placeholder="Aquí puedes agregar una descripción general de tu proyecto."></textarea></td>
          </tr>
        </table>
    
        <!-- ESPECIFICACIONES input
        ******************************* -->
        <h2>Especificaciones <span id="specs_popover" class="" rel="popover" data-content="Elige una sección —según aplique a tu proyecto— y llena las especificaciones correspondientes." data-original-title="Llena las especificaciones"></i> <i class="icon-question-sign"></i></span></h2>
        <!-- <p class="instrucciones">Elige una sección —según aplique a tu proyecto— y llena las especificaciones correspondientes.</p> -->
        <input type="hidden" name="especificaciones" value="" id="especificaciones">

        <div id="tabs" class="tabbable tabs-left">
          <ul class="nav nav-tabs">
            <li id="specs_1" class="active"><a href="#boottabs-1" data-toggle="tab">Bidimensional</a></li>
            <li id="specs_2" ><a href="#boottabs-2" data-toggle="tab">Tridimensional</a></li>
            <li id="specs_3" ><a href="#boottabs-3" data-toggle="tab">Multimedia</a></li>
            <li id="specs_4" ><a href="#boottabs-4" data-toggle="tab">Otros</a></li>
          </ul>
          <div class="tab-content">
            <div id="boottabs-1" class="tab-pane active">
              <table class="bidimensional">
                <tr>
                  <!-- <th>Bidimensional</th> -->
                  <td>                  
                    <input type="text" name="altura" value="" id="bi_altura" placeholder="10"><br />
                    <label for="altura"><span class="altura_hint hint">Altura</span></label>
                  </td>
                  <td><span class="times">&times;</span></td>
                  <td>
                    <input type="text" name="anchura" value="" id="bi_anchura" placeholder="10"><br />
                    <label for="anchura"><span class="anchura_hint hint">Anchura</span></label>
                  </td>
                  <td class="selectbuttontd">
                    <select name="unidad_de_medida" id="bi_unidad_de_medida">
                      <option value="">Selecciona una unidad</option>
                      <option value="mm">milímetros</option>
                      <option value="cm">centímetros</option>
                      <option value="pulg.">pulgadas</option>
                      <option value="m">metros</option>
                      <option value="px">pixeles</option>
                    </select>
                  </td>
                </tr>
              </table>
            </div><!-- #tabs-1 -->
            <div id="boottabs-2" class="tab-pane">
              <table class="tridimensional">
                <tr>
                  <!-- <th>Tridimensional</th> -->
                  <td>
                    <input type="text" name="altura" value="" id="tri_altura" placeholder="10"><br />
                    <label for="altura"><span class="altura_hint hint">Altura</span></label>
                  </td>
                  <td class="middle_characters"><span class="times">&times;</span></td>
                  <td>
                    <input type="text" name="anchura" value="" id="tri_anchura" placeholder="10"><br />
                    <label for="anchura"><span class="anchura_hint hint">Anchura</span></label>
                  </td>
                  <td class="middle_characters"><span class="times_last">&times;</span></td>
                  <td>
                    <input type="text" name="profundidad" value="" id="profundidad" placeholder="10"><br />
                    <label for="profundidad"><span class="anchura_hint hint">Profundidad</span></label>
                  </td>
                  <td class="selectbuttontd">
                    <select name="tri_unidad_de_medida" id="tri_unidad_de_medida">
                      <option value="">Selecciona una unidad</option>
                      <option value="mm">milímetros</option>
                      <option value="cm">centímetros</option>
                      <option value="pulg.">pulgadas</option>
                      <option value="m">metros</option>
                      <option value="px">pixeles</option>
                    </select>
                  </td>
                </tr>
              </table>
            </div><!-- #tabs-2 -->
            <div id="boottabs-3" class="tab-pane">
              <table class="temporal">
                <tr>
                  <!-- <th>Multimedia</th> -->
                  <td>
                    <input type="text" name="horas" value="" id="horas" maxlength="2" placeholder="10"><br />
                    <label for="horas"><span class="horas_hint hint">Horas</span></label>
                  </td>
                  <td class="middle_characters"><span class="times bold">:</span><br /></td>

                  <td>
                    <input type="text" name="minutos" value="" id="minutos" placeholder="10"><br />
                    <label for="minutos"><span class="minutos_hint hint">Minutos</span><br /></label>
                  </td>
                  <td class="middle_characters"><span class="times_last bold">:</span><br /></td>

                  <td>
                    <input type="text" name="segundos" value="" id="segundos" placeholder="10"><br />
                    <label for="segundos"><span class="anchura_hint hint">Segundos</span></label>
                  </td>
                </tr>
              </table>
            </div><!-- #tabs-3 -->
            <div id="boottabs-4" class="tab-pane">
              <table class="otros">
                <tr>
                  <!-- <th>
                    Otros
                  </th> -->
                  <td>
                    <input type="text" name="otros" value="" id="otros" placeholder="Escribe una breve descripción aquí"/><br />
                    <span class="otros_hint hint">ej. 500 págs.</span>
                  </td>
              </table>
            </div><!-- #tabs-4 -->
          </div>
        </div>


        <!-- ENFOQUES
        *************************************** 
        
        
        
        -->
        <h2>Enfoques <span id="enfoques_popover" class="" rel="popover" data-content="Elige los enfoques que apliquen a tu proyecto. <br><br> Incluye una breve descripción explicando cómo tu proyecto atiende ese enfoque." data-original-title="Enfoque(s)"></i> <i class="icon-question-sign"></i></span></h2>
        <!-- <p class="instrucciones">Elige los enfoques que apliquen a tu proyecto. Da clic en <code>+</code> si emprendiste/innovaste en ese enfoque. Incluye una breve descripción explicando cómo tu proyecto atiende ese enfoque.</p> -->
		<script>
$(function(){
	jQuery('.enfoques button').on('click',function(e){
		var me = jQuery(this);
		if(me.hasClass('active')){
			me.removeClass('active');
			$("input[name="+me.attr('id')+"]").attr("checked", false);
			if(me.data("enable")!=undefined){
				var target = jQuery("#"+me.data("enable"));
				target.attr("disabled", "disabled");
				target.removeClass('active');
			}

			if(me.data("show")!=undefined){
				var target = jQuery("#"+me.data("show"));
				target.hide();
			}
		}else{
			me.addClass('active');
			$("input[name="+me.attr('id')+"]").attr("checked", true);
			if(me.data("enable")!=undefined){
				var target = jQuery("#"+me.data("enable"));
				target.removeAttr("disabled");
				target.removeClass('active');
			}
			if(me.data("show")!=undefined){
				var target = jQuery("#"+me.data("show"));
				target.show();
			}
		}
		
		e.preventDefault();
	});

});
</script>
	<div class="enfoques">
		
    <div class="btn-group enfoque_fe">
      <button class="enfoque_popover btn btn-warning" id="enfoque_fe" data-enable="fe_plus" data-show="descripcion_fe" rel="popover" data-content="Brinda propuestas que ayudan en la evangelización, educación o interrelación de los diversos públicos de interés para la iglesia." data-original-title="Enfoque en la fe">Fe</button>
      <!-- <button class="enfoque_popover btn" disabled id="fe_plus" rel="popover" data-content="Brinda propuestas innovadoras para evangelizar, educar, e interrelacionar a los diversos públicos de interés para la iglesia." data-original-title="Emprendimiento en la fe">+</button> -->
    </div>
    <input type="checkbox" style="display:none" name="enfoque_fe" />
    <!-- <input type="checkbox" style="display:none" name="fe_plus" /> -->
    <textarea class="enfoques_textarea charcount" style="display:none" name="descripcion_fe" id="descripcion_fe" placeholder="¿De qué forma tu proyecto demuestra un enfoque en la fe?"></textarea>
    <br />
    <div class="btn-group enfoque_negocios">
      <button class="enfoque_popover btn btn-primary" id="enfoque_negocios" data-enable="negocios_plus" data-show="descripcion_negocios" rel="popover" data-content="Brinda una propuesta que puede comercializarse o en la cual se genera una compra/venta." data-original-title="Negocios">Negocios</button>
      <!-- <button class="enfoque_popover btn" disabled id="negocios_plus" rel="popover" data-content="Encuentra oportunidades para comercializar un producto o brindar una propuesta innovadora a una problemática existente." data-original-title="Emprendimiento en Negocios+">+</button> -->
    </div>
    <input type="checkbox" style="display:none" name="enfoque_negocios" />
    <!-- <input type="checkbox" style="display:none" name="negocios_plus" /> -->
    <textarea class="enfoques_textarea charcount" style="display:none" name="descripcion_negocios" id="descripcion_negocios" placeholder="¿De qué forma tu proyecto demuestra un enfoque en los negocios?"></textarea>
    <br />
    <div class="btn-group enfoque_entorno">
      <button class="enfoque_popover btn btn-success" id="enfoque_entorno" data-enable="entorno_plus" data-show="descripcion_entorno" rel="popover" data-content="Propone soluciones sensibles a las necesidades de otros seres humanos, la ecología, y la comunidad global y local." data-original-title="Entorno">Entorno</button>
      <!-- <button class="enfoque_popover btn" disabled id="entorno_plus" rel="popover" data-content="Propone soluciones sensibles e innovadoras a las necesidades de otros seres humanos, la ecología, y la comunidad global y local." data-original-title="Emprendimiento en el entorno">+</button> -->
		</div>
		<input type="checkbox" style="display:none" name="enfoque_entorno" />
    <!-- <input type="checkbox" style="display:none" name="entorno_plus" /> -->
		<textarea class="enfoques_textarea charcount" style="display:none" name="descripcion_entorno" id="descripcion_entorno" placeholder="¿De qué forma tu proyecto demuestra un enfoque en el entorno?"></textarea>
    <br />
		<div class="btn-group enfoque_investigacion">
			<button class="enfoque_popover btn" id="enfoque_investigacion" data-enable="investigacion_plus" data-show="descripcion_investigacion" rel="popover" data-content="Atiende un enfoque específico en alguna de las líneas de investigación que tenemos." data-original-title="Investigación">Investigación</button>
      <!-- <button class="enfoque_popover btn" disabled id="investigacion_plus" rel="popover" data-content="Atiende un enfoque específico en alguna de las líneas de investigación que tenemos, con destreza." data-original-title="Investigación+ (más emprendimiento)">+</button> -->
    </div>
    <input type="checkbox" style="display:none;border:1px solid red" name="enfoque_investigacion" />
    <!-- <input type="checkbox" style="display:none" name="investigacion_plus" /> -->
    <textarea class="enfoques_textarea charcount" style="display:none" name="descripcion_investigacion" id="descripcion_investigacion" placeholder="¿De qué forma tu proyecto demuestra un enfoque en la investigación?"></textarea>
	</div>
	
	
  <!-- CATEGORÍAS -->
  <h2>Categoría  <span id="categorias_popover" class="" rel="popover" data-content="Para que tu proyecto sea considerado para recibir un reconocimiento, debes elegir la categoría que le quede mejor." data-original-title="Categoría"></i> <i class="icon-question-sign"></i></span></h2>
  <br />
  <div class="sec_categoria" style="clear:both;">
    <fieldset id="categorias" style="border:none;">
      <!-- <legend>Categoría para premiación</legend> -->
      <!-- <p class="instrucciones">Para que tu proyecto sea considerado para premiación, debes elegir la categoría que le quede mejor.</p>     -->
    	<?php 
		
    		$result = mysql_query("SELECT id,nombre
    		FROM categoria");
		
    		$buffer = "";
    		while($row = mysql_fetch_assoc($result)){
    			$id = $row["id"];
    			$nombre = htmlentities($row["nombre"]);
    			echo "
    			<label for=\"categoria_$id\" class=\"radio\">
      			<input type=\"radio\" name=\"categoria\" id=\"categoria_$id\" value=\"$id\" style=\"margin-left:0px !important;\">
  					$nombre
					</label>\n";
    		}
        mysql_free_result($result);
    	?>
    </fieldset>

  </div><!-- .sec_categoria -->  
    <?php
      // $clasificaciones = mysql_query("select * from clasificacion");
      // while($row = mysql_fetch_assoc($clasificaciones)){
      //   echo "<option value=\"".$row['id']."\">".htmlentities($row['nombre'])."</option>";
      // }
      // mysql_free_result($clasificaciones);
    ?>

	
	
        <!-- Form finished! GUARDAR ‘button’
        *************************************** -->
        <div id="guardar"><input type="submit" value="Guardar"/></div><!-- #guardar -->
      </form>

    </div><!-- #main -->
    <?php include '../_footer.php'; ?>
  </div><!-- #container -->
<script>
/*global $ */
$(function () {
    aa=$('#file_upload').fileUploadUI({
        uploadTable: $('#files'),
        downloadTable: $('#files'),
        buildUploadRow: function (files, index) {
            return $('<tr class="file_uploading"><td><em>Subiendo</em> ' + files[index].fileName + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancelar">' +
                    '<span class="ui-icon ui-icon-cancel">Cancelar<\/span>' +
                    '<\/button><\/td><\/tr>');
        },
        buildDownloadRow: function (file) {
            if(file.error){
              alert("Error uploading file...");
              return false;
            }
            return $('<tr id="file'+file.id+'" class="file_uploaded"><td>Se subi&oacute; el archivo <i>' + file.oldName + '<\/i> <button onclick="deleteFile('+file.id+')" class="ui-state-default ui-corner-all" ><span class="ui-icon ui-icon-trash">Eliminar<\/span></button><br/><textarea id="archivo_desc'+file.id+'" class="upload_textarea" placeholder=\"Agrega una descripción\"></textarea><\/td><\/tr>');
        },
		addNode:function (parentNode, node, callBack) {
            if (node) {
                node.css('display', 'none').appendTo(parentNode).fadeIn(function () {
					$('textarea.upload_textarea').autoResize({
						minWidth: "original",
						minHeight: 17,
						extraSpace: 40
					});
                    if (typeof callBack === 'function') {
                        try {
                            callBack();
                        } catch (e) {
                            // Fix endless exception loop:
                            $(this).stop();
                            throw e;
                        }
                    }
                });
            } else if (typeof callBack === 'function') {
                callBack();
            }
        }
    });
    /*
    $(window).bind('beforeunload',function() {
      return 'There are unsaved changes';
    });
    */

});

  function deleteFile(id){
    $("#file"+id).hide();
    $("#archivo_desc"+id)[0].value = "!DELETED!"
  }

</script> 
<script type="text/javascript">
  $('#evidencias_popover').popover('hide');
  $('#autores_popover').popover('hide');
  $('#materias_popover').popover('hide');
  $('#specs_popover').popover('hide');
  $('#enfoques_popover').popover('hide');
  $('#categorias_popover').popover('hide');
  $('.enfoque_popover').popover({
    'trigger': 'hover',
    'placement': 'top'
    });
  // $('.enfoque_investigacion').button()
  $('textarea.enfoques_textarea').autoResize({
		minWidth: "original",
		minHeight: 17,
		extraSpace: 40
	});
  // character count for enfoques textareas
  $('.charcount').jqEasyCounter({
		'maxChars': 200,
		'maxCharsWarning': 180,
		'msgFontSize': '11px',
		'msgFontColor': '#000',
		'msgFontFamily': 'Arial',
		'msgTextAlign': 'left',
		'msgWarningColor': '#F00',
		'msgAppendMethod': 'insertBefore'              
	});

  // character count for enfoques textareas
  $('#descripcion').jqEasyCounter({
		'maxChars': 1000,
		'maxCharsWarning': 980,
		'msgFontSize': '11px',
		'msgFontColor': '#000',
		'msgFontFamily': 'Arial',
		'msgTextAlign': 'left',
		'msgWarningColor': '#F00',
		'msgAppendMethod': 'insertBefore'              
	});
	
</script>


</body>
</html>