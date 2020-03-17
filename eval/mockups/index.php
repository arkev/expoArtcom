<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf8">
    <title>evaluation system beta 2 - mockups</title>
    <style>
    @import url(http://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic);
      html, body{margin: 0;padding: 0;background-color: black;color: #fff;font: 1em/1.5 "PT Sans", "PTSans-Regular", Helvetica, sans-serif;-webkit-font-smoothing:antialiased;}
    
      body{background-color: #222;padding:3em 0 50em;}
      hr{margin-bottom: 3em;margin-top: 3em;color: none;background-color: #333;height: 1px;border:none;}
      h2{font-weight: normal;font-size: 2em;margin-left: 2em;}
      p{width: 37em;color: #bbb;padding-bottom: 2em;margin-left: 4em;}
    </style>
    <!-- <link rel="stylesheet" href="sexyCycle.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="jquery.sexyCycle.js"></script>
    <script>
      $(".uno").sexyCycle();
    </script> -->
  </head>
  <body>
    <!-- <img src="01-intro-color.png"> -->
    <h2>Evaluación por Materias</h2>
    <p>Del panel de inicio, al elegir el menú de Materias, aparecerá una ventana intermedia, explicando la mecánica de cómo evaluar por materias, y una vez leído, el usuario dará click para avanzar a la página de la primera materia con proyectos para evaluar. Ahí podrá votar por un solo proyecto. Podrá cambiar su voto eligiendo cualquier otra proyecto. Una vez terminado, podrá seleccionar la materia del menú de arriba y seleccionar la siguiente materia.</p>
    
    <div class="uno">
      <img src="01-intro.png">
      <img src="02-evaluacion-materias.png">
      <img src="03-evaluacion-materias.png">
    </div><!-- .uno -->

    <hr />
    <h2>Evaluación por Categorías</h2>
    <p>Este funciona similar a la evaluación por Materias, solo que permite votar por más de un proyecto por categoría.</p>
    <img src="01-intro.png">
    <img src="02-evaluacion-categorias.png">
    <img src="03-evaluacion-categorias.png">
    <hr />
    <h2>Evaluación por Enfoques</h2>
    <p>Similar a la evaluación por Categorías, permite votar por más de un proyecto por enfoque, y además incluye la descripción de tal enfoque.</p>
    <img src="01-intro.png">
    <img src="02-evaluacion-enfoques.png">
    <img src="03-evaluacion-enfoques.png">
    <hr />
    <h2>Selección de proyectos para Linares</h2>
    <p>Para ‘votar’ en esta modalidad, el usuario ingresa el número del proyecto deseado y le da click en <key>Agregar</key>. Esto agregará su elección a la pantalla, desde la cual, de haberse equivocado, podrá eliminar la pieza.</p>
    <img src="01-intro.png">
    <img src="02-seleccion-linares.png">
    <img src="03-seleccion-linares.png">
    <hr id="menus" />
    <h2>Menús</h2>
    <p>Aquí se muestra cómo funciona la modalidad de los menús y submenús. Solo Materias, Categorías y Enfoques tienen submenús. <br /><br />
      Los submenús de Materias y Categorías deberán generarse de acuerdo a los privilegios que el usuario ha sido concedido.<br /><br />
      El submenú de Materias se genera al acceder al listado de materias que corresponde a cada maestro evaluador.<br /><br />
      El submenú de Categorías se genera al acceder al array de categorías que el maestro evaluador ha sido otorgado.
    </p>
    <img src="menu-submenu.png">
    <img src="menu-activo.png">
    <img src="menu-submenu-activo.png">
    <hr id="feedback" />
    <h2>Realimentación visual</h2>
    <p>El sistema enviará realimentación visual de haber sincronizado la información a la base de datos. Para una anotación exitosa, mostrará un ícono de éxito por 4 segundos, para una anotación fallida, mostrará un ícono de error de conexión hasta que uno intente otra vez o cancele la orden.</p>
    <img src="error-exito-categorias.png">
    <img src="error-exito-enfoques.png">
    
  </body>
</html>