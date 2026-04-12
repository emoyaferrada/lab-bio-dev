<?
  session_start();
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
	<h5>INGRESAR PROVEEDOR</h5>
    <div class="form-group row">
      <label for="rut" class="col-4 col-form-label">RUT</label> 
      <div class="col">
        <input id="rut" name="rut" type="text" class="form-control" value="<?echo $_POST["rut"];?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="nombre" class="col-4 col-form-label">Nombre</label> 
      <div class="col-8">
        <input id="nombre" name="nombre" type="text" class="form-control" value="">
      </div>
    </div>
    <div class="form-group row">
      <label for="direccion" class="col-4 col-form-label">Direcci&oacute;n</label> 
      <div class="col-8">
        <input id="direccion" name="direccion" type="text" class="form-control" value="">
      </div>
    </div>    
    <div class="form-group row">
      <label for="fono" class="col-4 col-form-label">Tel&eacute;fono</label> 
      <div class="col-8">
        <input id="fono" name="fono" type="text" class="form-control" value="">
      </div>
    </div>
	<div class="form-group row">
      <label for="email" class="col-4 col-form-label">Email</label> 
      <div class="col-8">
        <input type="email" id="email" name="email" placeholder="ejemplo@gmail.com" type="text" class="form-control" value="">
      </div>
    </div>
    <div class="form-group row">
      <label for="contacto" class="col-4 col-form-label">Contacto</label> 
      <div class="col-8">
        <input id="contacto" name="contacto" type="text" class="form-control" value="">
      </div>
    </div>   
    <div class="form-group row">
      <div class="col">
		  <input type="hidden" name="guarda_proveedor" id="guarda_proveedor" value="1">
      	<button name="guardar_proveedor" id="guardar_proveedor" value="1" type="submit" class="btn btn-primary">Guardar</button>          
      </div>
    </div>   
  </form>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>