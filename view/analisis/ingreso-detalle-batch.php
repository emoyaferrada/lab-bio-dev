<?
	session_start();
	require_once("../../model/analisis.model.php");

	$analisis = new analisisModel();
	
 //obtener los analisis guardados del batch
 $id_batch=$analisis->obtener_batch_ingreso($_GET["id"]);//poner get
 $id_tipo=$analisis->obtener_id_tipo_batch($_GET["id"]);


 //Traer las variables asociadas a cada tipo de analisis del batch (pueden ser distintas)
 foreach ($id_batch as $key => $value) {
    //determinar si es analisis de tipo foliar para obtener especie y estado fenologico 
    $tipo_analisis_id=$analisis->obtener_variable_batch($value[0]);
    $datos_tipo_foliar=$analisis->obtener_datos_fenologicos($tipo_analisis_id);
    
    $variables_analisis[$value[0]]=$analisis->variable_tipo_analisis($tipo_analisis_id,$datos_tipo_foliar[0][0],$datos_tipo_foliar[0][1]);
    $especie_id=$datos_tipo_foliar[0][0];
    $estado_fenologico=$datos_tipo_foliar[0][1];    
 }
 //Crear un array para búsqueda de variables para no mostrar en todas las variables
 foreach($variables_analisis as $key=>$value){
    foreach($value as $key2=>$value2){
      $solo_variables_analisis[]=$key2."-".$key;
    }
}
 //Crear un array unico de variables (quitar variables duplicadas)
 foreach($variables_analisis as $key=>$value){
//echo "<br/>".var_dump($value);
$array_final[$value[0]]=$value;
 }
 foreach($array_final as $key=>$value){
$variables=$value;
 }
 
 //Obtener el listado de variables para los elementos del analisis 
 $json_ingreso= ($analisis->obtener_json_ingreso($_GET["id"]));
	//echo "variables: <pre>".var_export($variables)."</pre>";

	$arr_valores=array();
	$n_muestras=0;
	foreach ($json_ingreso as $key => $value) {
		//echo "<br>".$key." - ".var_dump($value);
		//$muestra=json_decode($value);
		$x=json_decode($value[2]);
		if (count($x) >0){
		foreach ($x as $key2 => $value2) {
		 $arr_valores[$value[0]][$value2[0]]=$value2[1];
		 $arr_dilucion[$value[0]][$value2[0]]=$value2[2];
		 $arr_obs[$value[0]][$value2[0]]=$value2[3];
 $arr_resultado[$value[0]][$value2[0]]=$value2[4];
		}
		}
	}

 //$tipo_analisis_titulo=$analisis->nombre_tipo_analisis($_GET["tipo"]);
 $tabla_resultado="<h5>Ingreso batch ID:".$_GET["id"]."<br/>".$analisis->obtener_batch_titulo($_GET["id"])."</h5>
 <table class='table table-sm' id='tabla_valores'>
 					<thead>
 						<th class='col-md-3'>Variable/ID</th>
 						<th class='col-md-2'>Valor lectura</th>
 						<th class='col-md-1'></th>
 						<th class='col-md-1'>Factor de dilución</th>
 <th class='col-md-1'>Factor de conversión</th>
 <th class='col-md-1'>Resultado</th>
 <th class='col-md-2'>Observación</th>
<tbody>";

	foreach ($variables as $key => $value) {
$tabla_resultado.="<tr><td class='table-active' colspan='7'><h4>".$value[1]."</h4></td></tr>";
 	foreach ($id_batch as $key2 => $value2) {
 		if ( ! $arr_dilucion[$value2[0]][$value[0]]) {
 			$arr_dilucion[$value2[0]][$value[0]]=1;
 		}
 //verificar que la variable exista en el listado del analisis (en caso de no tener todas)
 if(array_search($key."-".$value2[0], $solo_variables_analisis)!==FALSE){

$tabla_resultado.="<tr><td>ID:$key".$value2[1]."-".$value2[0]
					."</td><td><input type='text' class='form-control form-control-sm' name='valores_ingreso[".$value2[0]."][".$value[0]."]' id='valores_ingreso_".$value2[0].$value[0]."' value='".$arr_valores[$value2[0]][$value[0]]."' onFocus='calcula_aluminio(".$value2[0].",".$value[0].",".$value2[0].$value[0].")'>

					</td><td>".$value[3]."
 
 </td><td><input type='text' class='form-control form-control-sm' name='factor_dilucion[".$value2[0]."][".$value[0]."]' id='factor_dilucion_".$value2[0].$value[0]."' value='".$arr_dilucion[$value2[0]][$value[0]]."'>
 
 </td><td><input type='text' class='form-control form-control-sm' name='factor_calculo[".$value2[0]."][".$value[0]."]' id='factor_calculo_".$value2[0].$value[0]."' value='".$value[2]."' readonly>
 
 </td><td><input type='text' class='form-control form-control-sm' name='resultado[".$value2[0]."][".$value[0]."]' id='resultado_".$value2[0].$value[0]."' value='".$arr_resultado[$value2[0]][$value[0]]."' onFocus='calcula_valor(".$value2[0].$value[0].")'>
					
 </td><td><input type='text' class='form-control form-control-sm' name='observacion[".$value2[0]."][".$value[0]."]' value='".$arr_obs[$value2[0]][$value[0]]."'></td></tr>";
 }
}
 }
 $tabla_resultado.="</tbody></table>";
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
 <form method="post" action="../../controller/analisis.controller.php">
<div class="form-group row">
 <div class="col">
	 	 <? echo $tabla_resultado;?>
 </div>
</div> 

<div class="form-group row">
 <div class="offset-4 col-8">
<input type="hidden" name="batch_id" value="<? echo $_GET["id"];?>">
<input type="hidden" name="tipo_muestra" value="<? echo $id_tipo;?>">
<input type="hidden" name="tipo_rechazado" value="<? echo $_GET['tipo_rechazado'];?>">
<input type="hidden" name="especie_id" value="<? echo $especie_id;?>">
<input type="hidden" name="estado_fenologico" value="<? echo $estado_fenologico;?>">


<button name="guardar_parcial" id="guardar_parcial" value="1" type="submit" class="btn btn-primary">Guardar</button>
<button name="guardar_total" id="guardar_total" value="1" type="submit" class="btn btn-primary">Guardar y Terminar</button>
 </div>
</div>
 </form>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
<script type="text/javascript">
 
 function calcula_valor(x){
var valores_ingreso_="#valores_ingreso_"+x;
var factor_dilucion="#factor_dilucion_"+x;
var factor_calculo="#factor_calculo_"+x;
var resultado="#resultado_"+x;
var calculo=$(valores_ingreso_).val()*$(factor_dilucion).val()*$(factor_calculo).val();
var res_calculo=Math.round(calculo * 100) / 100;


//console.log("resultado ",$(valores_ingreso_).val() + " - " + calculo+ " - " + res_calculo);
$(resultado).val(res_calculo);
 }

 function calcula_aluminio(x,y,z){
  console.log("Valor de Y",y);

  if (y==101){
    var valores_ingreso_="#valores_ingreso_"+x+"81";
    var valor_aluminio_="#valores_ingreso_"+z;
    
    var valor_ph=$(valores_ingreso_).val();
    //console.log("PH",valor_ph);

    var valor=0;

    if (valor_ph >= 7.2) valor= 0.01;
    if (valor_ph>=7.1) valor= 0.013;
    if (valor_ph>=7) valor= 0.015;
    if (valor_ph>=6.9) valor= 0.018;
    if (valor_ph>=6.8) valor= 0.02;
    if (valor_ph>=6.7) valor= 0.021;
    if (valor_ph>=6.6) valor= 0.023;
    if (valor_ph>=6.5) valor= 0.024;
    if (valor_ph>=6.4) valor= 0.025;
    if (valor_ph>=6.3) valor= 0.028;
    if (valor_ph>=6.2) valor= 0.03;
    if (valor_ph>=6.1) valor= 0.032;
    if (valor_ph>=6) valor= 0.033;
    if (valor_ph>=5.9) valor= 0.04;
    if (valor_ph>=5.8) valor= 0.05;
    if (valor_ph>=5.7) valor= 0.06;
    if (valor_ph>=5.6) valor= 0.07;
    if (valor_ph>=5.5) valor= 0.08;
    if (valor_ph>=5.4) valor= 0.11;
    if (valor_ph>=5.3) valor= 0.15;
    if (valor_ph>=5.2) valor= 0.25;
    if (valor_ph>=5.1) valor= 0.3;
    if (valor_ph>=5) valor= 0.4;
    if (valor_ph>=4.9) valor= 0.55;


    $(valor_aluminio_).val(valor);
  }
 
 }
</script>
</body>
</html>
