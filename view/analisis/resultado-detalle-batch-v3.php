<?
  require_once("../../model/analisis.model.php");
  $analisis = new analisisModel();

  $batch_analisis=$analisis->obtener_batch_ingreso($_GET["id"]);
  //var_dump($batch_analisis);
  
  foreach ($batch_analisis as $key3 => $value3) {
    $json_analisis=$analisis->obtener_json2($value3[0]);

    $resultado=$analisis->obtener_resultado_reporte($value3[0]);
    //print_r($resultado);

    $tabla_resultado.="<h4>ID: ".$value3[1]."-".$value3[0]."</h4>
                      <table class='table table-sm table-hover table-bordered'>
                      <thead>
                        <th>Variable</th>
                        <th>Unidad</th>
                        <th>Valor Ingreso</th>
                        <th>Resultado</th>
                        <th>Rango</th>
                        <th>Observación</th>
                        <th>Resultado Original</th>
                      </thead>
                      <tbody>";
    foreach ($resultado as $key => $value2) {
      
      if ($value2[6]==0) $value2[6]='<0.1';

      $tabla_resultado.="<tr><td>".$value2[0]
                ."</td><td class='text-end'>".$value2[1]
                ."</td><td class='text-end'>".$value2[2]
                ."</td><td class='text-end'>".$value2[3]
                ."</td><td class='text-end'>".$value2[4]
                ."</td><td class='text-end'>".$value2[5]
                ."</td><td class='alert alert-warning text-end'>".$value2[6]
                ."</td></tr>";
    }
    
    $tabla_resultado.="</tbody></table>";
    //echo $tabla_resultado;
    //exit;
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
        <button name="aprobar" id="aprobar" value="1" type="submit" class="btn btn-primary" onClick="window.location.assign('ingreso-batch.php');">Aceptar / Enviar</button>
        <button name="reingresar" id="reingresar" value="1" type="submit" class="btn btn-warning" onClick="window.location.assign('ingreso-batch.php?id_reingreso=<?php echo $_GET["id"];?>');">Reingresar Valores</button>
      </div>    
    </div>

</div>

</body>
</html>
