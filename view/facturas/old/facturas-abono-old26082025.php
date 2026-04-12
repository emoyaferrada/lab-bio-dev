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
	$idc=0;
	$idf=0;
	require_once '../../model/cliente.model.php'; 
	$cliente = new clienteModel();

if ($_GET["id"]=="exito"){
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}
if($_GET["c"]){
	$idc=$_GET["c"];
}
if($_GET["v"]){
	$idf=$_GET["v"];
}
?>
	<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
	<script> 
		function llenarventa(c, v){			 
			if (c != 0 && v !=0){
				$.ajax({
					url:"../../controller/facturas.controller.php",     //the page containing php script
					type: "post",    								//request type,
					data: {venta_cliente: c, idventa: v},
					success:function(result){						                
						$("#div_ventas").html(result);  //poner en el div
					}
				});      
			}
		}
	</script>
  <form method="post" id="frmfacturas" action="../../controller/facturas.controller.php" enctype="multipart/form-data">
    <h5>DATOS DE VENTA</h5>
    <div class="form-group row">
      <div class="col">
        <label for="cliente" class="col-form-label"><b>Cliente</b></label> 
        <select id="cliente" name="cliente" class="custom-select">
          <option value=0>Seleccione</option>
          <?php echo $cliente->cliente_select($idc);?>
        </select>
      </div>
    </div>
	<div class="form-group row" id="div_ventas"><script>llenarventa(<?php echo $idc.",".$idf; ?>);</script></div>     
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
	  <div class="col"><h5>DATOS ORDEN DE COMPRA</h5></div>	   
    </div>   
	<div class="form-group row">	
      <div class="col">
        <label for="Observaciones" class="col-form-label"><b>Observaciones</b></label> 
        <input type="text" class="form-control" name="observaciones" id="observaciones">
      </div>	   
    </div>
	<div class="form-group row">
      <div class="offset-4 col-8">
		  <input type="hidden" name="guarda_factura" id="guarda_factura" value="1">
        <button name="guardar_factura" id="guardar_factura" value="1" onClick="return validar();" type="button" class="btn btn-primary">Guardar</button>
      </div>    
    </div>
  </form>
</div>

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
					data: {venta_cliente: rut, idventa: 0 },
					success:function(result){						                
						$("#div_ventas").html(result);  //poner en el div
					}
				});      
			  }
		})    
    
  }); 
function seleccionarchek(id){	
	var cont_venta = $("#contventa").val();	
	for(var i=1; i<= cont_venta; i++){
		$('#ventach'+i).prop('checked',false);
	}
	$('#ventach'+id).prop('checked', true);
}
function validar(){
	var valingresado=0;
	var valpendiente=0;
	var ident = 0;
	var cliente = $("#cliente").val();
	
	if($('input:checkbox[name=ventach]:checked').val()){
		ident=$('input:checkbox[name=ventach]:checked').val();
	}
	if($("#val_factura").val()){
		valingresado=$("#val_factura").val();
	}
	if($("#monto_pendi"+ident).val()){
		valpendiente=$("#monto_pendi"+ident).val();
	}
	
	//alert("ident"+ident);
	if(cliente==0){		
		 alert("DEBE SELECCIONAR EL CLIENTE");
		 return false;
	 }else if(ident===0){
		 alert("DEBE SELECCIONAR UNA VENTA");
		 return false;
	 }else if(valingresado==0){
		 alert("DEBE INGRESAR VALOR DE LA FACTURA");
		 return false;
	 }else if(valingresado > valpendiente){
		 alert("EL VALOR DE LA FACTURA NO PUEDE SER MAYOR AL VALOR PENDIENTE");
		 return false;
	 }else{
		 $("#frmfacturas").submit();
		// return true;
	 }	
}	  
</script>  