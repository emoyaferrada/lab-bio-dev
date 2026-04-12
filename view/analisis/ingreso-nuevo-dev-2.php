<?
  require_once("../../model/cliente.model.php");
  require_once("../../model/analisis.model.php");
  require_once("../../model/usuario.model.php");
  $cliente = new clienteModel();
  $analisis = new analisisModel();
  $usuario = new usuarioModel();

  $nuevo_id=($analisis->obtener_ultimo_id()) + 1;

?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="container">
<?
if ($_GET["id"]==1){
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}

?>

  <form id="form_ingreso" method="post" action="../../controller/analisis.controller.php">

    <h4>Datos cliente</h4>
    <div class="form-group row">
      <div class="col">
        <label for="cliente">Cliente</label> 
        <select id="cliente_id" name="cliente_id" class="custom-select">
          <option value="0">Seleccione cliente</option>
          <? echo $cliente->cliente_select();?>
        </select>
      </div>
      <div class="col">
        <label for="predio">Predio</label> 
          <select id="predio_cliente_id" name="predio_cliente_id" class="custom-select">
            <option value="0">Seleccione Predio</option>
          </select>
      </div>
      <div class="col">
        <label for="zona">Zona</label> 
          <select name="zona" id="zona" class="custom-select">
            <option>Seleccione Zona</option>
          </select>
      </div>    
      <div class="col">
        <label for="cliente">Nombre de Muestra (informe)</label> 
        <input type="text" class="form-control" name="nombre_muestra_reporte" id="nombre_muestra_reporte">
      </div>
    </div>

    <h4>Datos Ingreso</h4>
    <div class="form-group row">
      <div class="col">
        <label for="fecha_ingreso" class="col-form-label">Fecha Toma de muestra</label> 
        <input type="date" class="form-control" name="fecha_ingreso" access="false" id="fecha_ingreso" required="required" aria-required="true">
      </div>
      <div class="col">
        <label for="resp_muestra">Responsable Muestreo</label> 
        <select id="resp_muestra" name="resp_muestra" class="custom-select"> 
          <option value="0">Cliente</option>
          <option value="1">LAB-BIO</option>
          <option value="2">Otro</option>
        </select>    
	  </div>
      <div class="col">
        <label for="usuario_toma_muestra">Muestreador</label> 
        <input type="text" class="form-control" id="nombre_muestra" name="nombre_muestra"  placeholder="Ingrese nombre responsable">
        <select id="usuario_toma_muestra" name="usuario_toma_muestra" class="custom-select">
        	<option value="0">Usuario</option>
          <? echo $usuario->usuario_select();?>
        </select>
        <select id="tercero_toma_muestra" name="tercero_toma_muestra" class="custom-select">
          <option value="0">Usuario</option>
          <? echo $usuario->usuario_externo_select();?>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <div class="col">
        <label for="tipo_analisis" class="col-form-label">Tipo de análisis</label> 
        <select id="tipo_analisis" name="tipo_analisis" class="custom-select">
          <option value=0>Seleccione</option>
          <? echo $analisis->tipo_analisis_select();?>
        </select>
      </div>
      <!--
      <div class="col">
        <label for="tipo_analisis2" class="col-form-label">Tipo dinámico</label> 
        <select id="tipo_analisis2" name="tipo_analisis2" class="custom-select">
          <option value=0>Seleccione</option>
          <? //echo $analisis->tipo_analisis_dinamico_select();?>
        </select>
      </div>
      -->      
    </div>
        
    <div class="form-group row alert-success" id="div_suelo">
      <label for="prof1" class="col-4 col-form-label">Profundidad muestra 1</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad_1" id="profundidad_1">
      </div>
      <label for="prof2" class="col-4 col-form-label">Profundidad muestra 2</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad_2" id="profundidad_2">
      </div>
      <label for="prof3" class="col-4 col-form-label">Profundidad muestra 3</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad_3" id="profundidad_3">
      </div>
      <label for="prof4" class="col-4 col-form-label">Profundidad muestra 4</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad_4" id="profundidad_4">
      </div>
    </div>
    
    <div class="form-group row alert-success"  id="div_agua">
      <table class="table">
        <thead>
          <th>#</th>
          <th>Nombre origen</th>
          <th>Tipo origen</th>
          <th>Tipo emisor</th>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td><input type="text" class="col-4 form-control" name="nombre_1" id="nombre_1"></td>
            <td>
              <select id="origen_1" name="origen_1" class="custom-select">
                <option value="1">Pozo</option>
                <option value="2">Canal</option>
                <option value="3">Tranque</option>
              </select>
            </td>
            <td>
              <select id="emisor_1" name="emisor_1" class="custom-select">
                <option value="1">Riego goteo</option>
                <option value="2">Aspersor</option>
                <option value="3">Pivote central</option>
              </select>     
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td><input type="text" class="col-4 form-control" name="nombre_2" id="nombre_2"></td>
            <td>
              <select id="origen_2" name="origen_2" class="custom-select">
                <option value="1">Pozo</option>
                <option value="2">Canal</option>
                <option value="3">Tranque</option>
              </select>
            </td>
            <td>
              <select id="emisor_2" name="emisor_2" class="custom-select">
                <option value="1">Riego goteo</option>
                <option value="2">Aspersor</option>
                <option value="3">Pivote central</option>
              </select>     
            </td>
          </tr>          
          <tr>
            <td>3</td>
            <td><input type="text" class="col-4 form-control" name="nombre_3" id="nombre_3"></td>
            <td>
              <select id="origen_3" name="origen_3" class="custom-select">
                <option value="1">Pozo</option>
                <option value="2">Canal</option>
                <option value="3">Tranque</option>
              </select>
            </td>
            <td>
              <select id="emisor_3" name="emisor_3" class="custom-select">
                <option value="1">Riego goteo</option>
                <option value="2">Aspersor</option>
                <option value="3">Pivote central</option>
              </select>     
            </td>
          </tr>
          <tr>
            <td>4</td>
            <td><input type="text" class="col-4 form-control" name="nombre_4" id="nombre_4"></td>
            <td>
              <select id="origen_4" name="origen_4" class="custom-select">
                <option value="1">Pozo</option>
                <option value="2">Canal</option>
                <option value="3">Tranque</option>
              </select>
            </td>
            <td>
              <select id="emisor_4" name="emisor_4" class="custom-select">
                <option value="1">Riego goteo</option>
                <option value="2">Aspersor</option>
                <option value="3">Pivote central</option>
              </select>     
            </td>
          </tr>
        </tbody>
      </table>    
    </div>     

    <div class="form-group row alert-success" id="div_fertilizante">
      <div class="col">
        <label for="fuente" class="col-form-label">Fuente (tipo fertilizante)</label> 
        <input type="text" class="form-control" name="fuente" id="fuente">
      </div>
    </div>

    <div class="form-group row alert-success" id="div_foliar">
      <div class="col">
        <label for="especie">Especie</label> 
        <select id="especie" name="especie" class="custom-select">
          <option value="0">Especie</option>
          <? echo $analisis->tipo_especie_select();?>
        </select>
      </div>
      <div class="col">
        <label for="variedad">Variedad</label> 
        <select id="variedad" name="variedad" class="custom-select">
          <option value="0">Variedad</option>
          <? //echo $analisis->variedad_select();?>
        </select>
      </div>
      <div class="col">
        <label for="estado_fenologico">Estado Fenologico</label> 
        <select id="estado_fenologico" name="estado_fenologico" class="custom-select">
          <option value="0">Estado fenológico</option> 
          <? //echo $analisis->estado_fenologico_select();?>
        </select>
      </div>
    </div>
	  
   <div class="form-group row">
      <div class="offset-4 col-8">
        <button name="agregaranalisis" id="agregaranalisis" value="1" type="button" class="btn btn-primary" >Agregar Análisis</button>
      </div>    
   </div>
   <h4>Detalles</h4> 
    
   <div class="form-group row">
     <div class="col">
  		 <input type="hidden" id="cont" name="cont" value="0">
  		 <input id="idinicial" name="idinicial" type="hidden" value="<? echo $nuevo_id;?>" readonly />
  		 <div class="tabla_content" id="tabla_content">
           <table class="table" id="tabla_datos" width="100%" border="0" cellspacing="0" cellpadding="0">
      			<thead>
              <tr>
                <th></th>
                <th>Fecha</th>
                <th>Zona / Nombre Muestra</th>
                <th>Tipo An&aacute;lisis</th>
                <th>Responsable</th>
        				<th>Nombre</th>
                <th>id_tipo_analisis</th>
                <th>id_responsable</th>
                <th>id_nombre_responsable</th>
                <th>id_nombre_muestra_reporte</th>
                <th>d1</th>
                <th>d2</th>
                <th>d3</th>
                <th>d4</th>
                <th>d5</th>
                <th>d6</th>
                <th>d7</th>
                <th>d8</th>
                <th>d9</th>
                <th>d10</th>
                <th>d11</th>
                <th>d12</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
           </table>	  
  		 </div> 
	   </div> 
  </div> 

    <div class="form-group row">
      <div class="col">
        <label for="observaciones" class="col-form-label">Observaciones</label> 
        <input type="text" class="form-control" name="observaciones" id="observaciones">
      </div>
    </div>  
    <div class="form-group row">
      <div class="offset-4 col-8">
        <input type="hidden" name="detalle_analisis" id="detalle_analisis" value="">
        <button name="guardar_nuevo_analisis" id="guardar_nuevo_analisis" value="1" type="submit" class="btn btn-primary">Guardar</button>
      </div>    
    </div>
  </form>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/tabletojson/jquery.tabletojson.js"></script>

<script type="text/javascript">
$( document ).ready(function(){
   //ocultar lista de usuarios
   $('#usuario_toma_muestra').hide();
   $('#tercero_toma_muestra').hide();
   //ocultar las columnas de la tabla
   for(i=7;i<23;i++){
     $('th:nth-child('+i+')').hide();
  }
   
   var fecha_prog = $('#fecha_ingreso').val();
	 var tipo_analisis= $('#tipo_analisis').val();
	 var resp_muestra = $('#resp_muestra').val();
	 var nom_muestra= $('#usuario_toma_muestra').val();	
	 var tercero_muestra= $('#tercero_toma_muestra').val();  
   
   var id = Number($('#idinicial').val());  
	

    var lista_predios= <?echo json_encode($cliente->predio_select());?>;
    var lista_estados_fenologicos= <?echo json_encode($analisis->estado_fenologico_select());?>;
    var predios=$('#predio_cliente_id');
    var estado_fenologico=$('#estado_fenologico');
    //Creación del codigo de Barras al pinchar el botón de codigo
    
    $('#div_foliar').hide();
    $('#div_suelo').hide();
    $('#div_agua').hide();
    $('#div_fertilizante').hide();
    //$("#cliente").attr("disabled", true);

    $("#alerta").delay(4000).slideUp(200, function() {
        $(this).alert('close');
    });

    //Cambiar options del select de predios de clinte
    $('#cliente_id').on( 'change', function() {
      predios.empty();
      var valor=this.value;
      predios.append($("<option></option>").attr("value", 0).text("Seleccione Predio"));
      //console.log(lista_predios);
      $.each(lista_predios, function(key, value) {  
        //console.log(valor,value[0]);
        if (valor==value[0]) predios.append($("<option></option>").attr("value", value[1]).text(value[2]));
      });
    });
    

    //Cambiar options del select de estados fenologicos por especie
    $('#especie').on( 'change', function() {
      estado_fenologico.empty();
      var valor=this.value;
      $.each(lista_estados_fenologicos, function(key, value) {  
        if (valor==value[0]) estado_fenologico.append($("<option></option>").attr("value", value[2]).text(value[1]));
      });
    });
    
	$('#tipo_analisis').on( 'change', function() {
		$('#div_foliar').hide();
    $('#div_suelo').hide();
    $('#div_agua').hide();
    $('#div_fertilizante').hide();
    
    //foliares
    if ((this.value==1) || (this.value==9) || (this.value==10) || (this.value==11) || (this.value==12)) {
			$('#div_foliar').show();
			$('#div_suelo').hide();
			$('#div_agua').hide();
			$('#div_fertilizante').hide();
		}
    //Agua
		if ((this.value==2) || (this.value==18)){
			$('#div_foliar').hide();
			$('#div_suelo').hide();
			$('#div_agua').show();
			$('#div_fertilizante').hide();
		}
    //Suelos
		if ((this.value==3) || (this.value==5) || (this.value==6) || (this.value==7) || (this.value==8) || (this.value==19)) {
			$('#div_foliar').hide();
			$('#div_suelo').show();
			$('#div_agua').hide();
			$('#div_fertilizante').hide();
		}
    //Fertilizante
		if (this.value==4){
			$('#div_foliar').hide();
			$('#div_suelo').hide();
			$('#div_agua').hide();
			$('#div_fertilizante').show();
		}

	})
    $('#zona').on( 'change', function() {
      //ajax para traer tabla de cuarteles de la zona
      var predio_id=$("#predio_cliente_id").val();
      var zona_id=$("#zona").val();
      if (zona_id != "Seleccione zona"){
        $.ajax({
            url:"../../controller/cliente.controller.php",    //the page containing php script
            type: "post",    //request type,
            data: {ajax_cuarteles: 1, predio_id: predio_id, zona_id:zona_id },
            success:function(result){
                //console.log(result);
                //poner en el div
                $("#tabla_cuarteles").html(result);
            }
        });      
      }
    })
    $('#predio_cliente_id').on( 'change', function() {
      //ajax para traer tabla de cuarteles de la zona
      var predio_id=$("#predio_cliente_id").val();
      if (predio_id != ""){
        $.ajax({
            url:"../../controller/cliente.controller.php",    //the page containing php script
            type: "post",    //request type,
            data: {cuarteles_predio: 1, predio_id: predio_id},
            success:function(result){
                var arr=JSON.parse(result);
                var zona=$("#zona");
                zona.empty();
                zona.append($("<option></option>").attr("value", 0).text("Seleccione Zona"));
                $.each(arr, function(key, value) {  
                  //console.log(key,value);
                  zona.append($("<option></option>").attr("value", key).text(value));
                });
            }
        });      
      }
    });

    $('#resp_muestra').on('change', function() {
        if ($('#resp_muestra').val() == 0){
          $('#nombre_muestra').show();
          $('#usuario_toma_muestra').hide(); 
          $('#tercero_toma_muestra').hide(); 
        }
        if ($('#resp_muestra').val() == 1){
          $('#nombre_muestra').hide();
          $('#usuario_toma_muestra').show();
          $('#tercero_toma_muestra').hide(); 
        }
        if ($('#resp_muestra').val() == 2){
          $('#nombre_muestra').hide();
          $('#usuario_toma_muestra').hide(); 
          $('#tercero_toma_muestra').show(); 
        }
    });
	  
  $('#agregaranalisis').click(function() {
    var detalleDatos="";
    
    if ($('#nombre_muestra_reporte').val() != ""){ 
        var nombre_muestra_reporte= $('#nombre_muestra_reporte').val();
        var id_nombre_muestra_reporte= nombre_muestra_reporte
    }
    else{ 
        var nombre_muestra_reporte= $('#zona').children("option:selected").text();
        var id_nombre_muestra_reporte= $('#zona').val();
    }

    if  ($('#resp_muestra').val() == 0) {
      var usuarioMuestra= $('#nombre_muestra').val();
      var usuarioIdMuestra= $('#nombre_muestra').val();
    }
    if  ($('#resp_muestra').val() == 1) {
      var usuarioMuestra= $('#usuario_toma_muestra').children("option:selected").text();
      var usuarioIdMuestra= $('#usuario_toma_muestra').val();
    }
    if  ($('#resp_muestra').val() == 2) {
      var usuarioMuestra= $('#tercero_toma_muestra').children("option:selected").text();
      var usuarioIdMuestra= $('#tercero_toma_muestra').val();
    }

    var detalleIdDatos="</td><td class='d-none'>"+  $('#tipo_analisis').val() +  
                       "</td><td class='d-none'>"+  $('#resp_muestra').val() +
                       "</td><td class='d-none'>"+  usuarioIdMuestra +
                       "</td><td class='d-none'>"+  id_nombre_muestra_reporte;

    
    if (($('#tipo_analisis').val() == 1) || ($('#tipo_analisis').val() == 9) || ($('#tipo_analisis').val() == 10) || ($('#tipo_analisis').val() == 11) || ($('#tipo_analisis').val() == 12)){
      //tipo foliar (especie, variedad y estado fenologico)
      var detalleDatos="</td><td class='d-none'>"+  $('#especie').val() +
                       "</td><td class='d-none'>"+  $('#variedad').val() +
                       "</td><td class='d-none'>"+  $('#estado_fenologico').val();
                     
      //alert(detalleDatos);

    }
    if (($('#tipo_analisis').val() ==2) || ($('#tipo_analisis').val() ==18)) {
      //tipo agua (origen 4 de tipo, origen y tipo emisorespecie variedad estado fenologico)
      var detalleDatos="</td><td class='d-none'>"+  $('#nombre_1').val() +
                       "</td><td class='d-none'>"+  $('#origen_1').val() +
                       "</td><td class='d-none'>"+  $('#emisor_1').val() +
                       "</td><td class='d-none'>"+  $('#nombre_2').val() +
                       "</td><td class='d-none'>"+  $('#origen_2').val() +
                       "</td><td class='d-none'>"+  $('#emisor_2').val() +
                       "</td><td class='d-none'>"+  $('#nombre_3').val() +
                       "</td><td class='d-none'>"+  $('#origen_3').val() +
                       "</td><td class='d-none'>"+  $('#emisor_3').val() +
                       "</td><td class='d-none'>"+  $('#nombre_4').val() +
                       "</td><td class='d-none'>"+  $('#origen_4').val() +
                       "</td><td class='d-none'>"+  $('#emisor_4').val();
    }
    
    if (($('#tipo_analisis').val() ==3) || ($('#tipo_analisis').val() ==5) || ($('#tipo_analisis').val() ==6)  || ($('#tipo_analisis').val() ==7) || ($('#tipo_analisis').val() ==8) || ($('#tipo_analisis').val() ==19))  {
      //tipo suelo ( 4 profundidades )
      var detalleDatos="</td><td class='d-none'>"+  $('#profundidad_1').val() +
                       "</td><td class='d-none'>"+  $('#profundidad_2').val() +
                       "</td><td class='d-none'>"+  $('#profundidad_3').val() +
                       "</td><td class='d-none'>"+  $('#profundidad_4').val();
    }
    if ($('#tipo_analisis').val() ==4) {
      //tipo suelo ( 4 profundidades )
      var detalleDatos="</td><td class='d-none'>"+  $('#fuente').val();
    }    
    //linea estandar de tabla nombre_muestra_reporte
    var lineaDatos = "<tr><td>" + '<button type="button" class="btnDelete"><i class="fa fa-trash-o"></i></button>' + 
                     "</td><td>"+ $('#fecha_ingreso').val() + 
                     "</td><td>"+ nombre_muestra_reporte + 
                     "</td><td>"+  $('#tipo_analisis').children("option:selected").text() + 
                     "</td><td>"+  $('#resp_muestra').children("option:selected").text()+ 
                     "</td><td>"+  usuarioMuestra + 
                     detalleIdDatos + 
                     detalleDatos + 
                     "</td></tr>";
    $('#tabla_datos tbody').append(lineaDatos);

  });

  $("#tabla_datos").on('click', '.btnDelete', function () {
    $(this).closest('tr').remove();
  });

  $('#guardar_nuevo_analisis').on('click',function(){
      var table = $('#tabla_datos').tableToJSON();
      
      $('#detalle_analisis').val(JSON.stringify(table));
      var valor_det_anal=$('#detalle_analisis').val();

      console.log("el valor de detalle_analisis", valor_det_anal);
      //$('#observaciones').val(table);
      $('#guardar_nuevo').val(1);
      $("#form_ingreso").submit();
  });
});

</script>
</body>
</html>
