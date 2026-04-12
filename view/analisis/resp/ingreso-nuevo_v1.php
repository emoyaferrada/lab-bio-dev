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
  <form  method="post" action="../../controller/analisis.controller.php">
    <div class="form-group row">
      <label for="tipo_analisis" class="col-4 col-form-label">Tipo de análisis</label> 
      <div class="col-8">
        <select id="tipo_analisis" name="tipo_analisis" class="custom-select">
          <option value=0>Seleccione</option>
          <? echo $analisis->tipo_analisis_select();?>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <label for="tipo_analisis" class="col-4 col-form-label">Responsable muestra</label> 
      <div class="col-8">
        <select id="resp_muestra" name="resp_muestra" class="custom-select">
          <option>1. Propietario</option>
          <option>2. LB-TRACK</option>
          <option>3. Otro</option>
        </select>
      </div>
    </div>    
    <div class="form-group row">
      <label for="fecha_toma_muestra" class="col-4 col-form-label">Fecha toma muestra</label> 
      <div class="col-8">
      <input type="date" class="form-control" name="fecha_toma_muestra" access="false" id="fecha_toma_muestra" required="required" aria-required="true">
      </div>
    </div>    
    <div class="form-group row">
      <label for="usuario_toma_muestra" class="col-4 col-form-label">Usuario toma muestra</label> 
      <div class="col-8">
        <input type="text" class="form-control" id="nombre_muestra" name="nombre_muestra">
        <select id="usuario_toma_muestra" name="usuario_toma_muestra" class="custom-select">
        	<option value="0">Usuario</option>
          <? echo $usuario->usuario_select();?>
        </select>
      </div>
    </div>

    <div class="form-group row">
      <label for="cliente" class="col-4 col-form-label">Cliente</label> 
      <div class="col-8">
        <select id="cliente_id" name="cliente_id" class="custom-select">
          <option value="0">Seleccione cliente</option>
          <? echo $cliente->cliente_select();?>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <label for="predio" class="col-4 col-form-label">Predio</label> 
      <div class="col-8">
        <select id="predio_cliente_id" name="predio_cliente_id" class="custom-select">
        </select>
      </div>
    </div>
  
    <div class="form-group row">
      <label for="fecha_ingreso" class="col-4 col-form-label">Fecha ingreso laboratorio</label> 
      <div class="col-8">
      <input type="date" class="form-control" name="fecha_ingreso" access="false" id="fecha_ingreso" required="required" aria-required="true">
      </div>
    </div> 
    
  
    <div class="form-group row" id="div_suelo">
      <label for="fecha_ingreso" class="col-4 col-form-label">Profundidad toma de muestra</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad" id="profundidad">
      </div>
    </div>
    
    <div class="form-group row" id="div_agua">
      <label for="origen" class="col-4 col-form-label">Origen de muestra</label> 
      <div class="col-8">
        <select id="origen" name="origen" class="custom-select">
          <option value="1">Pozo</option>
          <option value="2">Canal</option>
          <option value="3">Tranque</option>
        </select>
      </div>
      <label for="emisor" class="col-4 col-form-label">Tipo de emisor</label> 
      <div class="col-8">
        <select id="emisor" name="emisor" class="custom-select">
          <option value="1">Riego goteo</option>
          <option value="2">Aspersor</option>
          <option value="3">Pivote central</option>
        </select>
      </div>      
    </div>     

    <div class="form-group row" id="div_fertilizante">
      <label for="fuente" class="col-4 col-form-label">Fuente (tipo fertilizante)</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="fuente" id="fuente">
      </div>
    </div>

    <div class="form-group row" id="div_foliar">
      <label for="especie" class="col-4 col-form-label">Especie</label> 
      <div class="col-8">
        <select id="especie" name="especie" class="custom-select">
          <option value="0">Especie</option>
          <? echo $analisis->tipo_especie_select();?>
        </select>
      </div>
      <label for="variedad" class="col-4 col-form-label">Variedad</label> 
      <div class="col-8">
        <select id="variedad" name="variedad" class="custom-select">
          <option value="0">Variedad</option>
          <? //echo $analisis->variedad_select();?>
        </select>
      </div>
      <label for="estado_fenologico" class="col-4 col-form-label">Estado Fenologico</label> 
      <div class="col-8">
        <select id="estado_fenologico" name="estado_fenologico" class="custom-select">
          <option value="0">Estado fenológico</option>
          <? //echo $analisis->estado_fenologico_select();?>
        </select>
      </div>
    </div>

    <div class="form-group row">
      <label for="observaciones" class="col-4 col-form-label">Observaciones</label> 
      <div class="col-8">
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
        <button name="guardar" id="guardar" type="submit" class="btn btn-primary">Guardar</button>
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
    
    //$("#cliente").attr("disabled", true);


    //Cambiar options del select de predios de clinte
    $('#cliente_id').on( 'change', function() {
      predios.empty();
      var valor=this.value;
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
    

  });
</script>
</body>
</html>
