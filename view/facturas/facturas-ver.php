<?php

  require_once("../../model/cliente.model.php");
  require_once("../../model/analisis.model.php");
  require_once("../../model/usuario.model.php");
  require_once("../../model/facturas.model.php");
  $cliente = new clienteModel();
  $analisis = new analisisModel();
  $usuario = new usuarioModel();

 // $nuevo_id=($analisis->obtener_ultimo_id()) + 1;

?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    .modal-dialog {
      width: fit-content !important;
      max-width: none;
    }
    .modal-content {
      width: fit-content;      
    }
    iframe {
      display: block;
      border: none;
    }
  </style>
</head>
<body>

<div class="container">
  <form  method="post" id="frmverfacturas" action="../../controller/facturas.controller.php">
    <h4>Buscar facturas</h4>
    <div class="form-group row">
      <div class="col">
        <label for="cliente" class="col-form-label"><b>Nombre Cliente</b></label> 
        <select id="cliente" name="cliente" class="custom-select">
          <option value=0>Seleccione</option>
          <? echo $cliente->cliente_select();?>
        </select>
      </div>
	  <div class="col">
        <label for="Rut Cliente" class="col-form-label"><b>RUT Cliente</b></label> 
        <input type="text" class="form-control" name="rut" id="rut">
      </div>
    </div>
	<div class="form-group row">      
	  <div class="col">
        <label for="Fecha inicio" class="col-form-label"><b>Fecha Inicio</b></label> 
        <input type="date" class="form-control" name="fecha_inicio" access="false" id="fecha_inicio" aria-required="true">
      </div>
	  <div class="col">
        <label for="Fecha fin" class="col-form-label"><b>Fecha Fin</b></label> 
        <input type="date" class="form-control" name="fecha_fin" access="false" id="fecha_fin" aria-required="true">
      </div>
    </div>   
	<div class="form-group row">
      <div class="offset-4 col-8">
        <button name="buscar_factura" id="buscar_factura" value="1" type="button" class="btn btn-primary">Buscar</button>
      </div>    
    </div>
	<div class="form-group row" id="div_facturas"></div>	
  </form>
</div>
 <div class="modal fade" id="modal_ver_detalle" tabindex="-1" role="dialog" aria-labelledby="modal-eventLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="event-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<iframe src="" id="ver_detalle" width="800" height="600" frameborder="0"></iframe>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
  <script>
	
    $( document ).ready(function() {			
       
		$('#buscar_factura').on('click', function(){
			var rut=0;			
			var nom_cliente=$("#cliente").val();
			var rut_cliente=$("#rut").val();
			var fecha_inicio=$("#fecha_inicio").val();
			var fecha_fin=$("#fecha_fin").val();
			
			if(nom_cliente !=0){
				rut=nom_cliente;
			}else if(rut_cliente !=""){
				rut=rut_cliente;
			}
			 
			if (rut != 0){
				$.ajax({
					url:"../../controller/facturas.controller.php",    //the page containing php script
					type: "post",    //request type,
					data: {cliente_busq: rut, fechai:fecha_inicio, fechaf:fecha_inicio },
					success:function(result){
						console.log(result);
						//poner en el div
						$("#div_facturas").html(result);
					}
				});      
			}
		})    
    
  }); 
function validar(){
	var cliente = $("#contador").val();
	var fechai  = $("#fecha_inicio").val();
	var fechaf  = $("#fecha_fin").val();
	
	if(cliente==0 && fechai=="" && fechaf==""){
		 alert("DEBE SELECCIONAR CLIENTE O RANGO DE FECHA");
		 return false;
	 }else if(cliente==0 && fechai=="" && fechaf!=""){
		 alert("DEBE SELECCIONAR FECHA DE INICIO");
		 return false;
	 }else if(cliente!=0 && fechai!="" && fechaf==""){
		 alert("DEBE SELECCIONAR FECHA DE FIN");
		 return false;
	 }else{
		 return true;
		 $("#frmverfacturas").submit();
	 }
	
}

function abre_detalles(id,tipo){
	//alert ("data-id=" + id);
	$("#ver_detalle").attr("src","../../view/facturas/detalle.php?i="+id+"&t="+tipo);

	if(tipo=="ifa"){ $('#event-title').text('VER FACTURA'); }
	else if(tipo=="ihes"){ $('#event-title').text('VER HES'); }
	else if(tipo=="ioc"){ $('#event-title').text('VER ORDEN DE COMPRA'); }
	else if(tipo=="ic"){ $('#event-title').text('VER COMPROBANTE DE PAGO'); }	

	$('#modal_ver_detalle').modal('show');
}	  
	  
	  
  </script>  