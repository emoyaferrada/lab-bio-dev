<?php

//echo "entro";
  require_once("../../model/usuario.model.php");
  require_once("../../model/facturas.model.php");
  
  $usuario = new usuarioModel();
  $factura = new facturasModel();


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
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}

?>

  <form  method="post" action="../../controller/facturas.controller.php">
    <h4>Pago Cuotas Pendientes</h4>
    <div class="form-group row">
      <div class="col">
        <label for="cliente" class="col-form-label"><b>Cliente</b></label> 
        <select id="cliente" name="cliente" class="custom-select">
          <option value=0>Seleccione</option>
          <? echo $factura->clientes_deuda(); ?>
        </select>
      </div>
    </div>
	<div class="form-group row" id="div_deudas"></div>     
	<div class="form-group row">      
	  <div class="col">
        <label for="Monto a Pagar" class="col-form-label"><b>Cantidad de Cuota</b></label> 
        <input type="text" class="form-control" name="cuota" id="cuota">
      </div>
	  <div class="col">  
        <label for="monto Pago"><b>Monto a Pagar</b></label> 
        <div id="monto"></div>
      </div>     
    </div>  
	<div class="form-group row">  
	  <div class="col">  
        <label for="forma Pago"><b>Forma de pago</b></label> 
        <select id="forma_pago" name="forma_pago" class="custom-select">
          <option value="Efectivo">Efectivo</option>
          <option value="Tarjeta">Tarjeta</option>
          <option value="Transferencia">Transferencia</option>
        </select>
      </div>     
	  <div class="col">
        <label for="Monto a Pagar" class="col-form-label"><b>Fecha Factura</b></label> 
        <input type="date" class="form-control" name="fecha_factura" access="false" id="fecha_factura">
      </div>
	  <div class="col">
        <label for="Numero Factura" class="col-form-label"><b>N&uacute;mero Factura</b></label> 
        <input type="text" class="form-control" name="num_factura" id="num_factura">
      </div>
    </div>		
	<div class="form-group row">
      <div class="offset-4 col-8">
        <button name="guardar_cuota" id="guardar_cuota" value="1" type="submit" class="btn btn-primary">Guardar</button>
      </div>    
    </div>
  </form>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
  <script>
	 $( document ).ready(function() {	
		
  		
		$('#cuota').on('change', function(){
			var id_fact = $('input:checkbox[name=facturach]:checked').val();	
			var num_cuota = $("#cuota").val();		// ingresadas
			var total_cuota = $("#num_cuota").val();	// total de cuotas
			var pagada_cuota = $("#valor_cuota_pag").val();	// cuotas pagadas	
			var valor_cuota = $("#valor_cuota"+id_fact).val();
			var dif = total_cuota - pagada_cuota;
			if(num_cuota == "" || num_cuota == 0){
				alert("Debe Ingresar Numero de Cuota(s) a Pagar");			  
		   	}else if(num_cuota>dif){
				alert("Las Cuotas a Pagar Deben ser Menor o Igual a"+dif);
			}else{
				var monto = Math.round(parseInt(valor_cuota) * parseInt(num_cuota));				
				$("#monto").html(monto+'<input type="hidden" name="monto_pagar" value="'+monto+'">');	
			}
		})
       
		$('#cliente').on('change', function(){			
			  var rut=$("#cliente").val();
			 
			  if (rut != 0){
				$.ajax({
					url:"../../controller/facturas.controller.php",    //the page containing php script
					type: "post",    //request type,
					data: {cliente_deuda: rut },
					success:function(result){
						console.log(result);
						//poner en el div
						$("#div_deudas").html(result);
					}
				});      
			  }
		})    
    
  }); 
function seleccionarchek(id){
	var cont_fact = $("#cont_fact").val();	
	for(var i=1; i<=cont_fact; i++){
		$('#facturach'+i).prop('checked',false);
	}
	$('#facturach'+id).prop('checked', true);	
	$("#cuota").val("");
	$("#monto").html('');
}
</script>  