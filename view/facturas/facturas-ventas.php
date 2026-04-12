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
<?
if ($_GET["id"]=="exito"){
    echo '                
         <div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}
?>
	<div class="form-group row" id="ventas_pendientes">
	    <div class="col-xl-12">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Ventas Pendiente de Pago</h3>
                </div>
            </div>
            <div class="table-responsive">
				 <?php echo $factura->ventas_pendientes();?>              
            </div>
          </div>
        </div>
      </div>
	</div>
	<div class="modal fade" id="modal_pagar" tabindex="-1" role="dialog" aria-labelledby="modal-eventLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="event-title">Facturar Abono</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<iframe src="" id="ver_pago" width="800" height="600" frameborder="0"></iframe>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script>
	$('#modal_pagar').on('shown.bs.modal', function () {
		var iframe = document.getElementById('ver_pago');
		var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

		// Ejemplo: ocultar un encabezado o sección dentro del iframe
		$(iframeDoc).find('#mostrar_cliente').hide();
	});
	function abre_detalles_pago(idc,idf){
	
		$("#ver_pago").attr("src","../../view/facturas/facturas-abono.php?c="+idc+"&v="+idf);
		$('#modal_pagar').modal('show');

	}
	
</script>
</body>
</html> 