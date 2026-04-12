<?
  session_start();
  require_once("../../model/compras.model.php");

  $compras= new comprasModel();

?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="container">
<?php
if ($_GET["id"]=="exito"){
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}
?>
  <form method="post" action="../../controller/compras.controller.php">
	<h5>INGRESAR INSUMOS</h5>
    <div class="form-group row">
      <label for="nombre" class="col-4 col-form-label">Nombre</label> 
      <div class="col-8">
        <input id="nombre" name="nombre" type="text" class="form-control" value="">
      </div>
    </div>
    <div class="form-group row">
      <label for="descripcion" class="col-4 col-form-label">Descripci&oacute;n</label> 
      <div class="col-8">
        <input id="descripcion" name="descripcion" type="text" class="form-control" value="">
      </div>
    </div>    
    <div class="form-group row">
      <label for="unidad" class="col-4 col-form-label">Unidad de Medida</label> 
      <div class="col-8">
       <select id="unidad" name="unidad" class="custom-select">
          <?echo $compras->option_unidades();?>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <label for="alerta" class="col-4 col-form-label">Stock Alerta</label> 
      <div class="col-8">
        <input id="alerta" name="alerta" type="text" class="form-control" value="">
      </div>
    </div>    
    <div class="form-group row">
      <label for="tiempo" class="col-4 col-form-label">Tiempo de Compra</label>
		<div class="col-8">
			<input id="tiempo" name="tiempo" type="text" class="form-control" value="">
		</div>
	</div>
	<div class="form-group row">
      <label for="certificado" class="col-4 col-form-label">Certificado del producto</label>
		<div class="col-8">
			<input name="certificado" type="file">
    	</div>
	</div>
    <div class="form-group row">
		<div class="col-8">			
			<input type="hidden" name="guarda_insumo" id="guarda_insumo" value="1">
			<button name="guardar_insumo" id="guardar_insumo" value="1" type="submit" class="btn btn-primary">Guardar</button>          
		</div>
	</div>    
  </form>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script type="text/javascript"></script>