<?
if ($_GET<>"") {
	$_POST["sel"]=json_decode($_GET["sel"]);
	$sel=$_GET["sel"];
	$_POST["hacer_reporte"]=1;
}
if (strlen($sel)==2){
	echo "<h4>No se seleccionaron análisis</h4>";
	exit;
}

require_once("../../model/cliente.model.php");
require_once("../../model/analisis.model.php");
$cliente = new clienteModel();
$analisis = new analisisModel();

if ($_POST["aprobar"]==1){
	//var_dump($_POST);
	//guardar el reporte para el cliente y predio con el html
	$analisis->guarda_reporte_cliente($_POST);
	foreach (json_decode($_POST["id_analisis"]) as $key => $value) {
		$res=$analisis->aprobar_analisis($value);
	}
	if ($res==1) echo '
			<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
	exit;
}
if ($_POST["rechazar_analisis"]==1){
	//echo "RECHAZO ... ";
	foreach (json_decode($_POST["id_analisis"]) as $key => $value) {
		$res=$analisis->rechazar_analisis($value,$_POST["motivo_rechazo"]);
	}
	exit;	
}


if ($_POST["buscar"]==1){
	$tabla=$analisis->tabla_reporte_cliente($_POST["cliente_id"]);
}
if ($_POST["hacer_reporte"]==1){
  	foreach ($_POST["sel"] as $key => $value2) {
  		$datos_analisis=$analisis->obtener_analisis_revision($value2);
	  	$json_analisis=$analisis->obtener_json($value2);
	  	$encabezado=$analisis->obtener_encabezado_reporte($value2);
	  	$cuarteles_predio=$analisis->obtener_cuarteles($value2);

	  	$predio_id=$encabezado->predio_cliente_id;
	  	$cliente_id=$encabezado->cliente_id;
			$tipo_analisis_id=$encabezado->tipo_analisis_id;

	    $arr_nombre=array();
	    $arr_valores=array();
	    $n_muestras=0;
	    //ksort($arr_json);

	    //obtener la salida desde la tabla analisis_muestra_variable con el id_analisis_variable
	    $variables_resultado=$analisis->obtener_resultados_muestra($value2);
	    
	    $table3="<h4>Detalle</h4>
	    					<table class='table'>";
	  	$table3.="<th> Variable</th>
	              <th> Unidad</th>
	              <th> Valor</th>
	              <th>Nivel de Suficiencia</th>
	              <th>Observacion</th>";
	   	foreach ($variables_resultado as $value) {
	   		$table3.="<tr><td>".$value[1]."</td>
	   							<td>".$value[2]."</td>
	   							<td>".$value[3]."</td>
	   							<td>".$value[4]."</td>
	   							<td>".$value[5]."</td></tr>";
	   	}

			/*
	  	foreach ($json_analisis as $key3 => $value) {
	  		//$muestra=json_decode($value);
	  		$x=json_decode($value[1]);
	  		$n_muestras++;
	  		$titulo[$key3]=$value[0];
	  		foreach ($x as $key2 => $value2) {
	  			$arr_nombre[$value2->id]=$value2->nombre;
	  			$arr_valores[$key3][$value2->id]=$value2->valor;
		        $arr_rangos[$value2->id]=$value2->rango;
		        $arr_unidad[$value2->id]=$value2->unidad;
		        //echo "<br/>".$value2->id.": ".$value2->nombre."->".$value2->valor;
	  		}
	  	}*/
	  	$table_encabezado="<div align='center'><h2>Informe de ".$encabezado->nombre_tipo."</h2></div>
	  					<table class='table'>
	  					<tr><th>Razón Social/Nombre:</th><td>".$encabezado->razon_social."</td>
	  							<th>RUT:</th><td>".$encabezado->rut."</td></tr>
	  					<tr><th>Persona de Contacto:</th><td>".$encabezado->nombre_cliente."</td>
	  							<th>Fecha Muestreo:</th><td>".$encabezado->fecha_toma_muestra."</td></tr>
	  					<tr><th>Email:</th><td>".$encabezado->email."</td>
	  							<th>Fecha Ingreso Laboratorio:</th><td>".$encabezado->fecha_ingreso."</td></tr>
	  					<tr><th>Muestreador:</th><td>".$encabezado->responsable_muestra."</td>
	  							<th>Fecha Salida Resultados:</th><td>".$encabezado->fecha_analisis."</td></tr>
	  					<tr><th>Predio:</th><td>".$encabezado->nombre_predio."</td>
	  							<th>Cuarteles:</th><td>".$cuarteles_predio."</td></tr>
							<tr><th>Fecha Toma Muestra:</th><td>".$encabezado->fecha_toma_muestra."</td>
	  							<th>Muestreador:</th><td>".$encabezado->responsable_muestra."</td></tr>
	  					<tr><th>Profundidad:</th><td>".$encabezado->profundidad_analisis."</td>
	  					</table>";
	  	/*
	    $table2="<h4>Detalle</h4>
	    		<table class='table'>";
	  	$table2.="<th> Variable"."</th>
	              <th> Unidad"."</th>
	              <th>".$titulo[0]."</th>";
	    if ($n_muestras == 2) $table2.="<th>".$titulo[1]."</th>";
	    if ($n_muestras == 3) {
	        $table2.="<th>".$titulo[1]."</th>";
	        $table2.="<th>".$titulo[2]."</th>";
	    }
	    if ($n_muestras == 4){
	      $table2.="<th>".$titulo[1]."</th>";
	      $table2.="<th>".$titulo[2]."</th>";
	      $table2.="<th>".$titulo[3]."</th>";
	    }
			$table2.="<th>Niveles de suficiencia</th>";

	    foreach ($arr_nombre as $key => $value) {
	  		$table2.="<tr>
	                  <td>".$value."</td>
	                  <td>".$arr_unidad[$key]."</td>
	                  <td>".$arr_valores[0][$key]."</td>";
	  		if ($n_muestras == 2) $table2.="<td>".$arr_valores[1][$key]."</td>";
	  		if ($n_muestras == 3){
	        $table2.="<td>".$arr_valores[1][$key]."</td>";
	        $table2.="<td>".$arr_valores[2][$key]."</td>";
	      }
	  		if ($n_muestras == 4){
	        $table2.="<td>".$arr_valores[1][$key]."</td>";
	        $table2.="<td>".$arr_valores[2][$key]."</td>";
	        $table2.="<td>".$arr_valores[3][$key]."</td>";
	      }
	      //mostrar rango o nivel de suficiencia
	      $table2.="<td>".$arr_rangos[$key]."</td></tr>";
	    }
	  	
	  	$table2.="</table>";	
	  	*/
	  	$table3.="</table>";

	  	$inicio="<div id='contenido'>";
	  	$encabezado="<div align='right' class='d-print-none'><a href='#' id='imprimir'><h4><i class='fa fa-print' aria-hidden='true'></h4></i></a></div>";
	  	$final="<div style='page-break-after: always;'></div></div>";

	  	$reporte_html=$inicio.$encabezado.$table_encabezado.$table2.$final;
	  	//echo $inicio.$encabezado.$table_encabezado.$table2.$final;
	  	echo $inicio.$encabezado.$table_encabezado.$table3.$final;
	  }


  	//$tabla=$analisis->guarda_reporte_cliente($_POST);
  }
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
<form method="POST" action="reporte-analisis.php"> 
    <div class="form-group row" id="div_rechazo">
      <div class="col">
      <label for="motivo_rechazo" class="col-form-label">Motivo del Rechazo</label> 
        <input type="text" class="form-control" name="motivo_rechazo" id="motivo_rechazo">
        <button name="rechazar_analisis" id="rechazar_analisis" value="1" type="submit" class="btn btn-danger">Rechazar</button>
      </div> 
    </div> 

  <div class="form-group row">
  	<input type="hidden" name="id_analisis" value="<?echo $sel;?>">
  	<input type="hidden" name="reporte_html" value="<?echo $reporte_html;?>">
  	<input type="hidden" name="cliente_id" value="<?echo $cliente_id;?>">
  	<input type="hidden" name="predio_id" value="<?echo $predio_id;?>">
	<input type="hidden" name="tipo_analisis_id" value="<?echo $tipo_analisis_id;?>">
	
    <button type="submit" id="aprobar" name="aprobar" value="1" class="btn btn-primary">Aprobar Reporte</button>

    <button name="rechazar" id="rechazar" type="button" class="btn btn-danger">Rechazar</button>
  </div>
  </form>
</div>
</body>

<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
$( document ).ready(function(){
	$('#imprimir').on('click', function(){
		//$("#contenido").printElement();
		window.print();
	});

	$("#div_rechazo").hide();
  	$("#rechazar").on("click", function(){
      $("#div_rechazo").show();
      $("#botones").hide();
  	});
})
</script>
</html>
