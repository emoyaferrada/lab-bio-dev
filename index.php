<?php
  session_start();
  require_once 'controller/index.controller.php';
  
  $login = $_SESSION["rut"];
  //echo mostrar_menu($_SESSION["rol"]);
  echo mostrar_menu(1);

?>

 <!-- Header -->
    <div class="header bg-info pb-2">		
       <h4 class="h3 text-white d-inline-block mb-0" style="align-content: center;"> <div id="titulo_contenido"></div></h4>        
    </div>
<!-- iframe contenido subportal  -->
<div id="div_contenido" class="embed-responsive embed-responsive-1by1"><br>
  <iframe id="iframe_contenido" src=""  frameborder="0" class="embed-responsive-item"></iframe>
</div>

<!-- Modal login  -->
<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">

  <div class="modal-dialog" role="document">
    <div class="modal-content text-center col-12" >
      <div class="modal-header text-center">
        <img class="img-fluid" alt="Login" src="assets/img/brand/lab-bio.jpg">
      </div>
      <span>LAB-BIO, empresa de análisis químico de suelos y agua, ubicada en la ciudad de Los Ángeles en la Región del Biobio. Perteneciente a grupo <a href="http://www.lb-track.cl">LB-TRACK</a></span>
      <iframe src='view/login.php' width="100%" height="300" frameborder="0" id="login"></iframe>
    </div>
  </div>
</div>

  <script>
    $( document ).ready(function() {
        var login = "<?php echo $login;?>";

        //funcion global que cierra el modal
        window.closeModal = function(){
          $('#login_modal').modal('hide');
          location.reload();  
        };
        
        $("#div_contenido").hide();
        $("#form_nuevo_analisis").hide();

        //Muestra login en caso de no estar logeado
        if (! login){
          //Mostrar pantalla de login 
          $('#login_modal').modal('show');         
        }

    });

    function muestra(modulo,titulo){
      if (modulo=="Mantención") modulo="mantencion";
      $("#div_dashboard").hide();
      $("#iframe_contenido").attr("src","controller/" + modulo + ".controller.launcher.php");
      $("#div_contenido").show(); 
	   //$("#div_contenido").load("controller/" + modulo + ".controller.launcher.php"); 
	  document.getElementById("titulo_contenido").innerHTML=' <i class="ni ni-button-play text-black"></i> '+titulo;
    }

	function muestraview(nombre,titulo){      
      $("#div_dashboard").hide();
      $("#iframe_contenido").attr("src","view/" + nombre + ".php");
      $("#div_contenido").show(); 
	 
	  //$("#div_contenido").load("view/" + nombre + ".php"); 
	  document.getElementById("titulo_contenido").innerHTML=' <i class="ni ni-button-play text-black"></i> '+titulo;
    }

  </script>
</body>

</html>

