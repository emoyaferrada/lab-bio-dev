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
if ($_GET["id"]=="exito"){
    echo '                
         <div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}

?>

  <form  method="post" action="../../controller/analisis.controller.php">
    <h4>Datos ingreso</h4>
    <div class="form-group row">
      <div class="col">
        <label for="tipo_analisis" class="col-form-label">Tipo de análisis</label> 
        <select id="tipo_analisis" name="tipo_analisis" class="custom-select">
          <option value=0>Seleccione</option>
          <? echo $analisis->tipo_analisis_select();?>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <div class="col">
        <label for="fecha_ingreso" class="col-form-label">Fecha ingreso laboratorio</label> 
        <input type="date" class="form-control" name="fecha_ingreso" access="false" id="fecha_ingreso" required="required" aria-required="true">
      </div>
    </div>

    <div class="form-group row">
      <div class="col">  
        <label for="resp_muestra">Responsable muestra</label> 
        <select id="resp_muestra" name="resp_muestra" class="custom-select">
          <option>1. Propietario</option>
          <option>2. LAB-BIO</option>
          <option>3. Otro</option>
        </select>
      </div>  
      <div class="col">
        <label for="fecha_toma_muestra">Fecha toma muestra</label> 
        <input type="date" class="form-control" name="fecha_toma_muestra" access="false" id="fecha_toma_muestra" required="required" aria-required="true">
      </div>
      <div class="col">
        <label for="usuario_toma_muestra">Usuario toma muestra</label> 
        <input type="text" class="form-control" id="nombre_muestra" name="nombre_muestra">
        <select id="usuario_toma_muestra" name="usuario_toma_muestra" class="custom-select">
        	<option value="0">Usuario</option>
          <? echo $usuario->usuario_select();?>
        </select>
      </div>
    </div>

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
        <label for="zona">Sector</label> 
          <select name="zona" id="zona" class="custom-select">
            <option>Seleccione Zona</option>
          </select>
      </div>    
    </div>
    <div class="form-group row" id="tabla_cuarteles">
    </div>
    
    <h4>Detalles</h4> 
    <div class="form-group row" id="div_suelo">
      <label for="fecha_ingreso" class="col-4 col-form-label">Profundidad muestra 1</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad_1" id="profundidad_1">
      </div>
      <label for="fecha_ingreso" class="col-4 col-form-label">Profundidad muestra 2</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad_2" id="profundidad_2">
      </div>
      <label for="fecha_ingreso" class="col-4 col-form-label">Profundidad muestra 3</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad_3" id="profundidad_3">
      </div>
      <label for="fecha_ingreso" class="col-4 col-form-label">Profundidad muestra 4</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad_4" id="profundidad_4">
      </div>
    </div>
    
    <div class="form-group row" id="div_agua">
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

    <div class="form-group row" id="div_fertilizante">
      <div class="col">
        <label for="fuente" class="col-form-label">Fuente (tipo fertilizante)</label> 
        <input type="text" class="form-control" name="fuente" id="fuente">
      </div>
    </div>

    <div class="form-group row" id="div_foliar">
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
      <div class="col">
        <label for="observaciones" class="col-form-label">Observaciones</label> 
        <input type="text" class="form-control" name="observaciones" id="observaciones">
      </div>
    </div>      
    <div class="form-group row">
      <label for="giro" class="col-4 col-form-label">ID análisis</label> 
      <div class="col-8">
        <input id="id_analisis1" name="id_analisis1" type="text" class="form-control" value="<? echo $nuevo_id;?>" readonly />
      </div>
    </div>
    <div class="form-group row">
      <label class="col-4">Código de barras</label> 
      <div class="col-8">
        <div id="svg_barcode"><svg id="barcode"></svg></div>
		<button name="imprime_barcode" id="imprime_barcode" type="button" class="btn btn-warning">Imprimir Código</button>		
      </div>
    </div> 

    <div class="form-group row">
      <div class="offset-4 col-8">
        <button name="guardar_nuevo" id="guardar_nuevo" value="1" type="submit" class="btn btn-primary">Guardar</button>
      </div>    
    </div>
  </form>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/vendor/jsBarcode/JsBarcode.code128.min.js"></script>

<script type="text/javascript">
  $( document ).ready(function(){
    var lista_predios= <?echo json_encode($cliente->predio_select());?>;
    var lista_estados_fenologicos= <?echo json_encode($analisis->estado_fenologico_select());?>;
    var predios=$('#predio_cliente_id');
    var estado_fenologico=$('#estado_fenologico');
    //Creación del codigo de Barras al pinchar el botón de codigo
    $('#barcode').JsBarcode(<?echo $nuevo_id;?>);
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
      console.log(lista_estados_fenologicos);
      $.each(lista_estados_fenologicos, function(key, value) {  
        //console.log(valor,value[0]);
        if (valor==value[0]) estado_fenologico.append($("<option></option>").attr("value", value[2]).text(value[1]));
      });
    });

    $('#barcode').on('click', function(){
    	$("#cliente").attr("disabled", true);

    })

	$('#imprime_barcode').on( 'click', function() {
		myWindow=window.open('imp_barcode.php?id=' + <?echo $nuevo_id;?>,'Imprimir','width=1000,height=500');
	    myWindow.focus();
	    myWindow.print();
    });

	$('#tipo_analisis').on( 'change', function() {
		if (this.value==1){
			$('#div_foliar').show();
			$('#div_suelo').hide();
			$('#div_agua').hide();
			$('#div_fertilizante').hide();
		}
		if (this.value==2){
			$('#div_foliar').hide();
			$('#div_suelo').hide();
			$('#div_agua').show();
			$('#div_fertilizante').hide();
		}
		if (this.value==3){
			$('#div_foliar').hide();
			$('#div_suelo').show();
			$('#div_agua').hide();
			$('#div_fertilizante').hide();
		}
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
                console.log(result);
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
                  zona.append($("<option></option>").attr("value", value).text(value));
                });
            }
        });      
      }
    })
  });
</script>
</body>
</html>
