<?
  session_start();
  require_once("../../model/analisis.model.php");

  $analisis = new analisisModel();
  
  if ($_GET["id_reingreso"]>0){
      $analisis->modificar_batch_reingreso($_GET["id_reingreso"]);
  }


  
  $id_batch=$analisis->obtener_batch_usuario($_SESSION["rut"]);

  $id_rechazados=$analisis->obtener_batch_rechazados();

  $tabla_resultado="<table class='table'>
  					<thead>
  						<th>Id</th>
  						<th>Fecha</th>
  						<th>Tipo (muestra)</th>
  						<th></th>
              <th></th>
              <th></th>
  					<thead>
                    <tbody>";

  if ($id_rechazados){
    foreach ($id_rechazados as $key => $value) {
        $tabla_resultado.="<tr><td class='alert alert-danger'>".$value[0]
                ."</td><td class='alert alert-danger'>".$value[1]
              ."</td><td class='alert alert-danger'>".$value[2]
              ."</td><td class='alert alert-danger'><a href='ingreso-detalle-batch.php?tipo_rechazado=1&id=".$value[0]."' class='btn btn-primary'>Ingresar</a>
                </td><td class='alert alert-danger'>Muestra Rechazada
                </td><td class='alert alert-danger'></td></tr>";
      }
  }                     

  if ($id_batch){
    foreach ($id_batch as $key => $value) {
        //$tipo_analisis=$analisis->nombre_tipo_analisis($value["2"]);

        $tabla_resultado.="<tr><td>".$value[0]
                ."</td><td>".$value[1]
              ."</td><td>".$value[2]
              ."</td><td><a href='ingreso-detalle-batch.php?tipo_rechazado=0&id=".$value[0]."' class='btn btn-primary'>Ingresar</a>
                </td><td><a href='modifica-detalle-batch.php?id=".$value[0]."' class='btn btn-primary'>Modificar</a></td>
                </td><td><a href='../../controller/analisis.controller.php?accion=elimina_batch&id_eliminar=".$value[0]."' onClick='return confirm(\"ALERTA: ¿Confirma Eliminar el Batch?\")' class='btn btn-primary'>Eliminar</a></td>
                </tr>";
      }
  } 
  
  $tabla_resultado.="</tbody></table>";
?>

<!DOCTYPE html>
<html>
<head>
  <title>LAB-BIO</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="container">
    <h4>Batch ingresados</h4>
<?
if ($_GET["res"]=="exito"){
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}
if ($_GET["res"]=="exito_elimina"){
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro eliminado exitosamente</div>';
}
if ($_GET["res"]=="error"){
    echo '<div class="alert alert-danger" id="alerta" role="alert">Error al eliminar</div>';
}
if ($_GET["id_reingreso"]>0){
    echo '<div class="alert alert-success" id="alerta" role="alert">Análisis para reingreso </div>';
}
?>    
    <div class="form-group row">
      <div class="col">
	  	<? echo $tabla_resultado;?>
      </div>
    </div> 
</div>

<script>
function confirmar(id) {
  if (confirm("ALERTA: ¿Confirma Eliminar el Batch?")){
    var destino = "../../controller/analisis.controller.php?accion=elimina_batch&id_eliminar="+id;
    document.getElementById('enlace_eliminar').href = destino;
    console.log("eliminando",destino);
    return true;
  }
  else{ 
    return false;
  }
  
}
</script>
</body>
</html>