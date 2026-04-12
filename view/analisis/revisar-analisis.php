<?
  require_once("../../model/analisis.model.php");
  $analisis = new analisisModel();
  
  $datos_analisis=($analisis->obtener_analisis_revision($_GET["id"]));
  
  $json_analisis= ($analisis->obtener_json($_GET["id"]));
 
  function muestra_json($arr_json){
    $arr_nombre=array();
    $arr_valores=array();
    $n_muestras=0;
    //ksort($arr_json);

  	foreach ($arr_json as $key => $value) {
  		//$muestra=json_decode($value);
  		$x=json_decode($value[1]);
  		$n_muestras++;
  		$titulo[$key]=$value[0];
  		foreach ($x as $key2 => $value2) {
  			$arr_nombre[$value2->id]=$value2->nombre;
  			$arr_valores[$key][$value2->id]=$value2->valor;
        $arr_rangos[$value2->id]=$value2->rango;
        $arr_unidad[$value2->id]=$value2->unidad;
  		}
  	}
    $table2="<table class='table'>";
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
  	echo $table2;

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
<form  method="post" action="../../controller/analisis.controller.php">
<div class="container">
<?
if ($_GET["estado"]=="exito"){
    echo '                
         <div class="alert alert-success" id="alerta" role="alert">Registro Aprobado exitosamente</div>
         <button type="button" class="btn btn-primary" onClick="window.parent.close_modal_revisar_analisis();">Aceptar</button>';
    exit;
}
if ($_GET["estado"]=="rechazo"){
    echo '                
         <div class="alert alert-danger" id="alerta" role="alert">Registro Rechazado, debe volver a ingresar resultados</div>
         <button type="button" class="btn btn-primary" onClick="window.parent.close_modal_revisar_analisis();">Aceptar</button>';
    exit;
}
?>
    <div class="form-group row">
      <div class="col">
        <label for="tipo_analisis" class="col-form-label">Tipo análisis </label> 
        <input type="text" class="form-control" name="tipo_analisis" id="tipo_analisis" value="<?echo $datos_analisis->nombre_tipo; ?>">
      </div>
      <div class="col">
        <label for="tipo_analisis" class="col-form-label">Cliente </label> 
        <input type="text" class="form-control" name="tipo_analisis" id="tipo_analisis" value="<?echo $datos_analisis->cliente; ?>">
      </div>
    </div> 
    <div class="form-group row">
      <div class="col">
        <label for="tipo_analisis" class="col-form-label">Fecha ingreso </label> 
        <input type="text" class="form-control" name="tipo_analisis" id="tipo_analisis" value="<?echo $datos_analisis->fecha_ingreso; ?>">
      </div>

      <div class="col">
        <label for="tipo_analisis" class="col-form-label">Fecha analisis </label> 
        <input type="text" class="form-control" name="tipo_analisis" id="tipo_analisis" value="<?echo $datos_analisis->fecha_analisis; ?>">
      </div>
    </div> 
    <div class="form-group row">
      <div class="col">
	  	<? muestra_json($json_analisis);?>
      </div>
    </div> 
    
    <input type="hidden" name="id_analisis" name="id_analisis" value="<? echo $_GET["id"] ?>">
    
    <div class="form-group row" id="div_rechazo">
      <div class="col">
      <label for="motivo_rechazo" class="col-form-label">Motivo del Rechazo</label> 
        <input type="text" class="form-control" name="motivo_rechazo" id="motivo_rechazo">
        <button name="rechazar_analisis" id="rechazar_analisis" value="1" type="submit" class="btn btn-danger">Rechazar</button>
      </div> 
    </div> 

    <div class="form-group row" id="botones">
      <div class="offset-4 col-8">
        <button name="aprobar_analisis" id="aprobar_analisis" value="1" type="submit" class="btn btn-primary">Aprobar y enviar reporte</button>        
        <button name="rechazar" id="rechazar" type="button" class="btn btn-danger">Rechazar</button>
      </div>    
    </div>
</div>
</form>
</body>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>

<script type="text/javascript">
$( document ).ready(function() {
  $("#div_rechazo").hide();

  $("#rechazar").on("click", function(){
      $("#div_rechazo").show();
      $("#botones").hide();
  })
})  

</script>
</html>
