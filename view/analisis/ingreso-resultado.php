<!DOCTYPE html>
<html>
<head>
  <title></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php
if ( ! $_POST){
	echo '
		<div class="container">
		  <form  method="post" action="ingreso-resultado.php">
			<div class="form-group row">
				<label for="id_muestra" class="col-4 col-form-label">ID Muestra</label> 
				<div class="col-8">
				  <input id="id_muestra" name="id_muestra" type="text" class="form-control">
				</div>
			</div>
		    <div class="form-group row"> 
		      <div class="offset-4 col-8">
		        <button name="mostrar" id="mostrar" type="submit" class="btn btn-primary">Ingresar Valores</button>
		      </div>    
		    </div>
		  </form>
		</div>
	';
}
else{
	require_once("../../model/analisis.model.php");
	$analisis = new analisisModel();
	//analizar tipo de análisis para vistas separadas
	$datos_analisis=$analisis->obtener_analisis($_POST["id_muestra"]);
	switch ($datos_analisis->tipo_analisis_id){
		case 1:
			header('Location: ingreso-foliar.php?id_muestra='.$_POST["id_muestra"]);
			break;
		case 2:
			header('Location: ingreso-agua.php?id_muestra='.$_POST["id_muestra"]);
			break;
		case 3:
			header('Location: ingreso-suelo.php?id_muestra='.$_POST["id_muestra"]);
			break;
	}

}
