<?php
if($_GET[id]){
	echo "La informacion se ha guardado exitosamente";
}
echo "editar equipo id:".$_GET[id];

?>
  <form  method="post" action="../../controller/mantencion.controller.php">  
	  <div class="form-group row">
		<label class="col-4 col-form-label" for="nombre">Nombre</label> 
		<div class="col-8">
		  <input id="nombre" name="nombre" type="text" class="form-control">
		</div>
	  </div>
	  <div class="form-group row">
		<label for="tipo" class="col-4 col-form-label">Tipo</label> 
		<div class="col-8">
		  <input id="tipo" name="tipo" type="text" class="form-control">
		</div>
	  </div>
	  <div class="form-group row">
		<label for="marca" class="col-4 col-form-label">Marca</label> 
		<div class="col-8">
		  <input id="marca" name="marca" type="text" class="form-control">
		</div>
	  </div>
	  <div class="form-group row">
		<label for="modelo" class="col-4 col-form-label">Modelo</label> 
		<div class="col-8">
		  <input id="modelo" name="modelo" type="text" class="form-control">
		</div>
	  </div>
	  <div class="form-group row">
		<label for="serie" class="col-4 col-form-label">N&uacute;mero de Serie</label> 
		<div class="col-8">
		  <input id="serie" name="serie" type="text" class="form-control">
		</div>
	  </div>	  
	  <div class="form-group row">
		<label for="year" class="col-4 col-form-label">Año</label> 
		<div class="col-8">
		  <input id="year" name="year" type="text" class="form-control">
		</div>
	  </div>
	  <div class="form-group row">
		<label for="Proveedor" class="col-4 col-form-label">Proveedor</label> 
		<div class="col-8">
		  <input id="proveedor" name="proveedor" type="text" class="form-control">
		</div>
	  </div>
	  <div class="form-group row">
		<label for="fecha_alta" class="col-4 col-form-label">Fecha de Alta</label> 
		<div class="col-8">
		  <input id="fecha_alta" name="fecha_alta" type="date" class="form-control">
		</div>
	  </div>
	  <div class="form-group row">
		<label for="tiempo_alerta" class="col-4 col-form-label">Tiempo Alerta</label> 
		<div class="col-8">
		  <input id="tiempo_alerta" name="tiempo_alerta" type="text" class="form-control">
		</div>
	  </div>
	  <div class="form-group row">
		<label for="fecha_mantencion" class="col-4 col-form-label">Fecha Prox. Mantención</label> 
		<div class="col-8">
		  <input id="fecha_mantencion" name="fecha_mantencion" type="date" class="form-control" access="false">
		</div>
	  </div>
	  <div class="form-group row">
		<label for="fecha_calibracion" class="col-4 col-form-label">Fecha Prox. Calibración</label> 
		<div class="col-8">
		  <input id="fecha_calibracion" name="fecha_calibracion" type="date" class="form-control" access="false">
		</div>
	  </div>
	  <div class="form-group row">
		<label for="valor_calibracion" class="col-4 col-form-label">Valor de Calibración del Instrumento</label> 
		<div class="col-8">
		  <input id="valor_calibracion" name="valor_calibracion" type="text" class="form-control">
		</div>
	  </div> 
	  <div class="form-group row">
		<div class="offset-4 col-8">
		  <button name="guardar_datos" type="submit" class="btn btn-primary">Guardar</button>
		</div>
	  </div>
  </form>

