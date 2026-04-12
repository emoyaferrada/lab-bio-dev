<?php
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
  <form  method="post" id="frmfacturaspago" action="../../controller/facturas.controller.php" enctype="multipart/form-data">
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
       <input type="text" class="form-control" name="monto" id="monto">
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
		
  		
		/*$('input:checkbox[name=facturach]:checked').on('change', function(){
		 	var id_fact = $('input:checkbox[name=facturach]:checked').val();
			var tipo = $('tipo_pago'+id_fact).val();
			
			if(tipo="cuota"){
				$("#cuota").prop('disabled', false);
				$("#monto").prop('disabled', true);
				
			}else{
				$("#cuota").prop('disabled', true);
				$("#monto").prop('disabled', false);
			}
		 
		 })*/
		 
		$('#cuota').on('change', function(){
			var ident = $('input:checkbox[name=facturach]:checked').val();	
			var num_cuota = $("#cuota").val();		// ingresadas
			var total_cuota = $("#num_cuota").val();	// total de cuotas
			var pagada_cuota = $("#valor_cuota_pag").val();	// cuotas pagadas	
			var valor_cuota = $("#valor_cuota"+ident).val();
			var dif = total_cuota - pagada_cuota;
			if(num_cuota == "" || num_cuota == 0){
				alert("Debe Ingresar Numero de Cuota(s) a Pagar");			  
		   	}else if(num_cuota>dif){
				alert("Las Cuotas a Pagar Deben ser Menor o Igual a"+dif);
			}else{
				var monto = Math.round(parseInt(valor_cuota) * parseInt(num_cuota));				
				$("#monto").val(monto);	
			}
		})
		 
		 $('#monto').on('change', function(){
			 var ident = $('input:checkbox[name=facturach]:checked').val();	
			 
			if($("#tipo_pago"+ident).val()=='cuota'){
				if($("#cuota").val()==" "){
				   alert("Debe Ingresar Cantidad de Cuotas a Pagar");
				 }
			}
			if($("#tipo_pago"+ident).val()=='abono'){
				if($("#monto").val() > $("#monto_total"+ident).val()){
				   alert("El Monto Ingresado es Mayor a lo Adeudado");
				 }
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
						//console.log(result);
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
	$("#monto").val('');
	if($("#tipo_pago"+id).val()=='abono'){
		$("#cuota").prop('disabled', true);
	}else{
		$("#cuota").prop('disabled', false);
	}	
}
/*function validar(){
	
	var ident = $('input:checkbox[name=facturach]:checked').val();	
	
	if($("#tipo_pago"+ident).val()=='abono'){
		if($("#monto").val() > $("#monto_total".ident).val()){
		   alert("EL MONTO INGRESADO ES MAYOR A LO ADEUDADO");
		 }
	}else{
		 $("#frmfacturaspago").submit();
		// return true;
	 }	
}	*/    
</script>  