<!DOCTYPE html>
<html>
<head>
  <title></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="container">
<?php
	require_once '../../model/cliente.model.php'; 
	$cliente = new clienteModel();

if ($_GET["id"]=="exito"){
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}
?>
  <form method="post" id="frmfacturas" action="../../controller/facturas.controller.php" enctype="multipart/form-data">
    <h5>DATOS DE FACTURA</h5>
    <div class="form-group row">
      <div class="col">
        <label for="cliente" class="col-form-label"><b>Cliente</b></label> 
        <select id="cliente" name="cliente" class="custom-select">
          <option value=0>Seleccione</option>
          <?php echo $cliente->cliente_select();?>
        </select>
      </div>
    </div>
	<div class="form-group row" id="div_analisis"></div>     
	<div class="form-group row">      
	  <div class="col">
        <label for="Monto a Pagar" class="col-form-label"><b>Monto a Pagar</b></label> 
        <input type="text" class="form-control" name="monto" id="monto">
      </div>	   
    </div>  
	<div class="form-group row">   
	  <div class="col"><h5>DATOS FACTURA</h5></div>	   
    </div>  
	<div class="form-group row">      
	  <div class="col">
        <label for="Monto a Pagar" class="col-form-label"><b>Fecha Factura</b></label> 
        <input type="date" class="form-control" name="fecha_factura" access="false" id="fecha_factura" required="required" aria-required="true">
      </div>
	  <div class="col">
        <label for="Numero Factura" class="col-form-label"><b>N&uacute;mero Factura</b></label> 
        <input type="text" class="form-control" name="num_factura" id="num_factura">
      </div>
    </div>  
	<div class="form-group row">   
	  <div class="col"><h5>DATOS ORDEN DE COMPRA</h5></div>	   
    </div>   
	<div class="form-group row">   
	  <div class="col">
        <label for="GES Orden de Compra" class="col-form-label"><b>GES</b></label> 
        <input type="text" class="form-control" name="ges" id="ges">
      </div>
	  <div class="col">
        <label for="Numero Orden de Compra" class="col-form-label"><b>N&uacute;mero</b></label> 
        <input type="text" class="form-control" name="num_orden" id="num_orden">
      </div>
	  <div class="col">
        <label for="Fecha Orden de Compra" class="col-form-label"><b>Fecha</b></label> 
        <input type="date" class="form-control" name="fecha_orden" access="false" id="fecha_orden" >
      </div>
	  <div class="col">
        <label for="Valor Orden de Compra" class="col-form-label"><b>Valor</b></label> 
        <input type="text" class="form-control" name="valor_orden" id="valor_orden">
      </div>		
    </div>   
	<div class="form-group row">   
	  <div class="col">
		  <label for="Subir Archivo" class="col-form-label"><b>Subir Orden de Compra</b></label> 
          <input name="arch_orden" type="file"> 
	  </div>
    </div>   
	<div class="form-group row">   
	  <div class="col"><h5>DATOS DE PAGO</h5></div>	   
    </div> 	  
	<div class="form-group row"> 
	  <div class="col">  
        <label for="Tipo Pago"><b>Tipo de Pago</b></label> 
        <select id="tipo_pago" name="tipo_pago" class="custom-select">
          <option value="0">Seleccione</option>
          <option value="cuota">Cuotas</option>
          <option value="abono">Abono</option>
		  <option value="total">Valor Total</option>
        </select>
      </div>    
	  <div class="col">
        <label for="Numero cuotas"><b>Numero de cuotas</b></label> 
        <input type="text" class="form-control" id="num_cuota" name="num_cuota" disabled>        
      </div>
      <div class="col">
        <label for="Valor Abono"><b>Valor Cuota</b></label> 
        <input type="text" class="form-control" id="valor_cuota" name="valor_cuota" disabled> 
		<input type="hidden" name="valor_cuotaf" id="valor_cuotaf">
      </div>
	  <div class="col">
		<div id="info_cuota"></div>        
      </div>
    </div>  
	<div class="form-group row">	
      <div class="col">
        <label for="observaciones" class="col-form-label"><b>Observaciones</b></label> 
        <input type="text" class="form-control" name="observaciones" id="observaciones">
      </div>
	   <div class="col" id="selectformapago"></div>
    </div>
	<div class="form-group row">
      <div class="offset-4 col-8">
		  <input type="hidden" name="guarda_factura" id="guarda_factura" value="1">
        <button name="guardar_factura" id="guardar_factura" value="1" onClick="return validar();" type="button" class="btn btn-primary">Guardar</button>
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
		$('#tipo_pago').on('change', function(){
			var tipo_pago = $("#tipo_pago").val();
			if(tipo_pago == "cuota"){
				$("#num_cuota").prop('disabled', false);				
				$("#valor_cuota").val('');		
				$("#info_cuota").html('');
				$("#selectformapago").html('');
			}else if(tipo_pago == "total"){
				$("#valor_cuota").val('');	
				$("#num_cuota").val('');	
				$("#info_cuota").html('');
				$("#num_cuota").prop('disabled', true);	
				$("#selectformapago").html('<label for="forma Pago"><b>Forma de pago</b></label><select id="forma_pago" name="forma_pago" class="custom-select"><option value="0">Seleccione</option><option value="efectivo">Efectivo</option><option value="tarjeta">Tarjeta</option><option value="transferencia">Transferencia</option></select>');
			}else{
				$("#num_cuota").prop('disabled', true);				
				$("#num_cuota").val('');	
				$("#valor_cuota").val('')
				//$("#valor_cuota").prop('disabled', false);
				$("#selectformapago").html('');
			}
		})
		$('#num_cuota').on('change', function(){
			var num_cuota = $("#num_cuota").val();
			var tipo_pago = $("#tipo_pago").val();
			
			if(tipo_pago == "cuota"){
				var cuota = 0;	
				if(num_cuota != "" && num_cuota != 0 ){				
					cuota = Math.round(parseInt($("#monto").val()) / parseInt(num_cuota));					
					$("#valor_cuota").val(cuota);
					$("#valor_cuotaf").val(cuota);
				}	
			}else {
				alert ("Debe cambiar a 'Cuotas' en Tipo de Pago");
			}			
		})
		$('#valor_cuota').on('change', function(){			
			var tipo_pago = $("#tipo_pago").val();			
			if(tipo_pago == "abono"){
				var pendiente = 0;	
				pendiente  = Math.round(parseInt($("#monto").val()) - parseInt($("#valor_cuota").val()));
				$("#info_cuota").html('<label for="valor_cuotas"><b>Valor Pendiente</b></label><div>'+pendiente+'</div>');					
			}		
		})
       
		$('#cliente').on('change', function(){			
			  var rut=$("#cliente").val();			 
			  if (rut != 0){
				$.ajax({
					url:"../../controller/facturas.controller.php",     //the page containing php script
					type: "post",    								//request type,
					data: {ajax_cliente: rut },
					success:function(result){						                
						$("#div_analisis").html(result);  //poner en el div
					}
				});      
			  }
		})    
    
  }); 
function sumar(i){	 
	var total = parseInt(0);
	if(i>1){
	 for(var j=1; j<=i; j++){
		  total = parseInt(total) + parseInt($("#valor"+j).val());
	 }
	}else{
	  total = parseInt($("#valor"+i).val());
	}

	var iva = parseInt(total) * 0.19;
	var neto= total-iva;
	$("#neto").html(neto);
	$("#iva").html(iva); 
	$("#total").html(total);
	 
}
function validar(){
	var cliente = $("#cliente").val();
	var cont = $("#contador").val();
	var tipo_pago = $("#tipo_pago").val();
	
	if(cliente==0){		
		 alert("DEBE SELECCIONAR EL CLIENTE");
		 return false;
	 }else if(cont==0){
		 alert("NO SE PUEDE GUARDAR NO HAY ANALISIS");
		 return false;
	 }else if(tipo_pago=="cuota" && ($("#num_cuota").val()=="" || $("#num_cuota").val()==0)){		 
		alert("DEBE INGRESAR NUMERO DE CUOTAS");
		return false;		 	 
	 }else{
		 $("#frmfacturas").submit();
		// return true;
	 }	
}	  
</script>  