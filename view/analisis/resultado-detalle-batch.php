<?
  require_once("../../model/analisis.model.php");
  $analisis = new analisisModel();

  $batch_analisis=$analisis->obtener_batch_ingreso($_GET["id"]);
  //var_dump($batch_analisis);
  foreach ($batch_analisis as $key3 => $value3) {
    $json_analisis=$analisis->obtener_json2($value3[0]);
    //print_r($json_analisis);

    $nombre=$json_analisis[0][0];
    $variables_resultado=(array) json_decode($json_analisis[0][1]);
    $variables_ingreso=(array) json_decode($json_analisis[0][2]);
    //var_dump($variables_ingreso);

    $tabla_resultado.="<h4>ID: ".$value3[1]."-".$value3[0]."</h4>
                      <table class='table'>
                      <thead>
                        <th>Variable</th>
                        <th>Unidad</th>
                        <th>Valor Ingreso</th>
                        <th>Factor de Dilución</th>
                        <th>Resultado</th>
                        <th>Rango</th>
                        <th>Observación</th>
                      </thead>
                      <tbody>";
    foreach ($variables_resultado as $key => $value2) {
      $salida[$key]["nombre"]=$value2->nombre;
      $salida[$key]["unidad"]=$value2->unidad;
      $salida[$key]["valor"]=$value2->valor;
      $salida[$key]["obs"]=$value2->obs;
      $salida[$key]["rango"]=$value2->rango;
    }
    foreach ($variables_ingreso as $key => $value2) {
      $salida[$value2[0]]["ingreso"]=$value2[1];
      $salida[$value2[0]]["dilucion"]=$value2[2];
    }
    foreach ($salida as $key => $value) {
      $tabla_resultado.="<tr><td>".$value["nombre"]
                      ."</td><td>".$value["unidad"]
                      ."</td><td>".$value["ingreso"]
                      ."</td><td>".$value["dilucion"]
                      ."</td><td>".$value["valor"]
                      ."</td><td>".$value["rango"]
                      ."</td><td>".$value["obs"]
                      ."</td></tr>";
    }
    
    $tabla_resultado.="</tbody></table>";
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
    <h4>Resultado</h4>
    <div class="form-group row">
      <div class="col">
	  	<? echo $tabla_resultado;?>
      </div>
    </div> 

    <div class="form-group row">
      <div class="offset-4 col-8">
        <button name="aprobar" id="aprobar" value="1" type="submit" class="btn btn-primary" onClick="window.location.assign('ingreso-batch.php');">Aceptar</button>
      </div>    
    </div>

</div>

</body>
</html>
