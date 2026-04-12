<!DOCTYPE html>
<html>
<head>
  <title></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php
	require_once("../../model/analisis.model.php");
	$analisis = new analisisModel();

	//Mostrar form con variables del análisis para el id y del tipo de análisis
	echo '
		<div class="container">
		  <form  method="post" action="../../controller/analisis.controller.php">
  			<div class="form-group row">
				<label for="id_muestra" class="col-4 col-form-label">ID Muestra</label> 
				<div class="col-8">
				  <input id="id_muestra" name="id_muestra" type="text" value="'.$_GET["id_muestra"].'" class="form-control" readonly>
				</div>
			</div>';
	echo '<div class="form-group row">
			<div class="col-3"><h4>Determinación</h4></div>
			<div class="col-3"><h4>Valor lectura</h4></div>
			<div class="col-3"><h4>Factor</h4></div>
			<div class="col-3"><h4>Factor de dilución</h4></div>
			
		</div>';
	//Mostrar textos para las variables correspondientes al tipo de analisis
	$datos_analisis=$analisis->obtener_analisis($_GET["id_muestra"]);
	//var_dump($datos_analisis);
	
	$variables=$analisis->variable_tipo_analisis($datos_analisis->tipo_analisis_id);
	//print_r($variables);

	//Manejar por separado los 3 analisis por la cantidad de variables
	foreach ($variables as $variable) {
		$i++;
		echo '<div class="form-group row">
				<div class="col-3">
				  <p>'.$variable[1].'</p> 
				</div>				
				<div class="col-3">
				  <input id="valor['.$variable[0].']" name="valor['.$variable[0].']" type="text" class="form-control">
				</div>
				<div class="col-3">
				  <input id="factor['.$variable[0].']" name="factor['.$variable[0].']" type="text" value="'.$variable[2].' " class="form-control" style="text-align:right;" >
				</div>	
				<div class="col-3">
					<input id="factor2['.$variable[0].']" name="factor2['.$variable[0].']" type="text" value="1" class="form-control" style="text-align:right;" >
				</div>							
			</div>';
	}
	echo'
			<div class="form-group row">
				<label for="observaciones" class="col-4 col-form-label">Observaciones</label> 
				<div class="col-8">
				  <textarea id="observaciones" name="observaciones" cols="40" rows="5" class="form-control"></textarea>
				</div>
			</div> 
		    <div class="form-group row"> 
		      <div class="offset-4 col-8">
		        <button name="guardar_datos_suelo" id="guardar_datos_suelo" value="guardar_datos_suelo" type="submit" class="btn btn-primary">Guardar Datos</button>
		      </div>    
		    </div>
		  </form>
		</div>
	';