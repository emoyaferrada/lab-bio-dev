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
    <h5>DATOS DE VENTA</h5>
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
	   <div class="col">  
        <label for="Tipo Pago"><b>Tipo de pago</b></label> 
        <select id="tipo_pago" name="tipo_pago" class="custom-select">
          <option value="abono">Abono</option>
          <option value="total">Pago Total</option>          
        </select>
      </div> 
    </div>  
	<div class="form-group row">   
	  <div class="col"><h5>DATOS FACTURA</h5></div>	   
    </div>  
	<div class="form-group row">      
	  <div class="col">
        <label for="Fecha Factura" class="col-form-label"><b>Fecha Factura</b></label> 
        <input type="date" class="form-control" name="fecha_factura" access="false" id="fecha_factura" required="required" aria-required="true">
      </div>
	  <div class="col">
        <label for="Numero Factura" class="col-form-label"><b>N&uacute;mero Factura</b></label> 
        <input type="text" class="form-control" name="num_factura" id="num_factura">
      </div>
	  <div class="col">
        <label for="Valor Factura" class="col-form-label"><b>Valor Factura</b></label> 
        <input type="text" class="form-control" name="val_factura" id="val_factura">
      </div>
	  <div class="col">
		<label for="Subir Factura" class="col-form-label"><b>Subir Factura</b></label> 
        <input name="arch_factura" type="file"> 
	  </div>
    </div>  
	<div class="form-group row">   
	  <div class="col"><h5>DATOS ORDEN DE COMPRA O HES</h5></div>	   
    </div>   
	<div class="form-group row">   
	  <div class="col">
        <label for="HES" class="col-form-label"><b>N&uacute;mero HES</b></label> 
        <input type="text" class="form-control" name="hes" id="hes">
      </div>
	  <div class="col">
		  <label for="Subir Archivo" class="col-form-label"><b>Subir HES</b></label>
          <input name="arch_hes" type="file"> 
	  </div>	 
	  <div class="col">
        <label for="Numero Orden de Compra" class="col-form-label"><b>N&uacute;mero OC</b></label>
        <input type="text" class="form-control" name="num_orden" id="num_orden">
      </div>	 
	  <div class="col">
		  <label for="Subir Archivo" class="col-form-label"><b>Subir Orden de Compra</b></label>
          <input name="arch_orden" type="file"> 
	  </div>
    </div>		
	<div class="form-group row">   
	  <div class="col"><h5>COTIZACION</h5></div>	   
    </div>   
	<div class="form-group row">   
	  <div class="col">
		  <label for="Subir Archivo" class="col-form-label"><b>Subir Cotizaci&oacute;n</b></label> 
          <input name="arch_cotizacion" type="file"> 
	  </div>
    </div>	
	<div class="form-group row">	
      <div class="col">
        <label for="Observaciones" class="col-form-label"><b>Observaciones</b></label> 
        <input type="text" class="form-control" name="observaciones" id="observaciones">
      </div>	   
    </div>
	<div class="form-group row">
      <div class="offset-4 col-8">
		  <input type="hidden" name="guarda_ventas" id="guarda_ventas" value="1">
        <button name="guardar_venta" id="guardar_venta" value="1" onClick="return validar();" type="button" class="btn btn-primary">Guardar</button>
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
function seleccionarchek(id){	
	var total= 0;
	var iva  = 0;
	var neto = 0;
	if($('#facturach'+id).prop('checked')==true){
		if($("#valor"+id).val()){
			neto  = parseInt($("#total_neto").val()) + parseInt($("#valor"+id).val());
			total = parseInt(neto) * 1.19;
			iva   = parseInt(total) - parseInt(neto);
		}else{
			alert("Debe Ingresar Precio al Analisis");
			$('#facturach'+id).prop('checked',false);
			neto  = parseInt($("#total_neto").val());
			total = parseInt(neto) * 1.19;
			iva   = parseInt(total) - parseInt(neto);
		}
	}else{
		neto  = parseInt($("#total_neto").val()) - parseInt($("#valor"+id).val());
		if(neto <= 0){
			neto  = 0;
			total = 0;
			iva   = 0;
		}else{			
			total = parseInt(neto) * 1.19;
			iva   = parseInt(total) - parseInt(neto);
		}	
	}
	$("#total_neto").val(neto)
	$("#neto").html(neto);
	$("#iva").html(iva); 
	$("#total").html(total);
	$("#monto").val(neto)
	 
}
function actualizavalor(id,total){	
	var total= 0;
	var iva  = 0;
	var neto = 0;
	var sumneto=0;

	for(var i=0; i<$("#contador").val(); i++){
		if($('#facturach'+i).prop('checked')==true){
			if(i != id){
				if($("#valor"+i).val()!='' || $("#valor"+i).val()!=0){
					sumneto += parseInt($("#valor"+i).val());
				}
			}
			
		}
	}
	if($('#facturach'+id).prop('checked')==true){	
		if($("#valor"+id).val() != '' || $("#valor"+id).val()!=0){
			neto = parseInt(sumneto) + parseInt($("#valor"+id).val());
		}else{
			$("#valor"+id).val(0);
			neto = parseInt(sumneto);
		}
			
		total = parseInt(neto) * 1.19;
		iva   = parseInt(total) - parseInt(neto);		
	}else{
		neto  = parseInt(sumneto);
		total = parseInt(neto) * 1.19;
		iva   = parseInt(total) - parseInt(neto);
	}
	$("#total_neto").val(neto)
	$("#neto").html(neto);
	$("#iva").html(iva); 
	$("#total").html(total);
	$("#monto").val(neto);
		
}

function validar(){
	var cliente = $("#cliente").val();
	var cont = $("#contador").val();
	var tipo_pago = $("#tipo_pago").val();
	var monto = $("#monto").val();
	var monto_fact = $("#val_factura").val();
	
	if(cliente==0){		
		 alert("DEBE SELECCIONAR EL CLIENTE");
		 return false;
	 }else if(cont==0){
		 alert("NO SE PUEDE GUARDAR NO HAY ANALISIS");
		 return false;
	 }else if(tipo_pago=="total" && monto != monto_fact){
		 alert("El MONTO A PAGAR DEBE SER IGUAL AL VALOR DE LA FACTURA");
		 return false;
	 }else{
		 $("#frmfacturas").submit();
		// return true;
	 }	
}	  
</script>  