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
$idc=0;
$idf=0;
if ($_GET["id"]=="exito"){
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}
if($_GET["idc"]){
	$idc=$_GET["idc"];
}
if($_GET["idf"]){
	$idf=$_GET["idf"];
}
?>
  <form  method="post" id="frmfacturaspago" action="../../controller/facturas.controller.php" enctype="multipart/form-data">
    <h4>Pagos Pendientes</h4>
    <div class="form-group row" id="mostrar_cliente">
      <div class="col">
        <label for="cliente" class="col-form-label"><b>Cliente</b></label> 
        <select id="cliente" name="cliente" class="custom-select">
          <option value=0>Seleccione</option>
          <? echo $factura->clientes_deuda($idc); ?>
        </select>
      </div>
    </div>
	<div class="form-group row" id="div_deudas"><? echo $factura->listar_deudas($idc, $idf); ?></div>     
	<div class="form-group row">	  
	  <div class="col">
        <label for="Fecha Pago" class="col-form-label"><b>Fecha Pago</b></label> 
        <input type="date" class="form-control" name="fecha_pago" access="false" id="fecha_pago" required="required" aria-required="true">
      </div>
	  <div class="col">  
        <label for="Monto a Pagar"><b>Monto a Pagar</b></label> 
       <input type="text" class="form-control" name="monto" id="monto" readonly>
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
		  <label for="Subir Comprobante" class="col-form-label"><b>Subir Comprobante de Pago</b></label> 
        <input name="arch_comprobante" type="file"> 
	  </div>
    </div>		
	<div class="form-group row">
      <div class="offset-4 col-8">
        <button name="guardar_pago" id="guardar_pago" value="1" type="submit" class="btn btn-primary">Guardar</button>
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
		
	if($('#facturach'+id).prop('checked')==true){		
		$("#monto").val($("#monto"+id).val());		
	}	 
}
function validar(){
	
	var ident = $('input:checkbox[name=facturach]:checked').val();	
	
	if($("#tipo_pago"+ident).val()=='abono'){
		if($("#monto").val() > $("#monto_total".ident).val()){
		   alert("EL MONTO INGRESADO ES MAYOR A LO ADEUDADO");
		 }
	}else{
		 $("#frmfacturaspago").submit();
		// return true;
	 }	
} 
</script>  