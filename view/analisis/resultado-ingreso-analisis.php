<?
  require_once("../../model/analisis.model.php");
  $analisis = new analisisModel();
  $datos_analisis=($analisis->obtener_analisis_revision2($_GET["id"]));
  $json_analisis=($analisis->obtener_json2($_GET["id"]));
  //print_r($json_analisis);

  $nombre=$json_analisis[0][0];
  $variables_resultado=(array) json_decode($json_analisis[0][1]);
  $variables_ingreso=(array) json_decode($json_analisis[0][2]);

  $tabla_resultado="<table class='table'>
                    <thead>
                      <th>Variable</th>
                      <th>Valor Ingreso</th>
                      <th>Factor de Dilución</th>
                      <th>Resultado</th>
                    </thead>
                    <tbody>";
  foreach ($variables_resultado as $key => $value2) {
    $salida[$key]["nombre"]=$value2->nombre;
    $salida[$key]["valor"]=$value2->valor;
  }
  foreach ($variables_ingreso as $key => $value2) {
    $salida[$key]["ingreso"]=$value2->valor;
    $salida[$key]["dilucion"]=$value2->factor_dilucion;
  }
  foreach ($salida as $key => $value) {
    $tabla_resultado.="<tr><td>".$value["nombre"]."</td><td>".$value["ingreso"].
                      "</td><td>".$value["dilucion"]."</td><td>".$value["valor"]."</td></tr>";
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
