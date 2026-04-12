<?
	session_start();
	require_once("../../model/analisis.model.php");
	$analisis = new analisisModel();  
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
<?
if ($_GET["res"]=="exito"){
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}
elseif ($_GET["res"]=="error"){
    echo '<div class="alert alert-danger" id="alerta" role="alert">Error al guardar registros</div>';
}

?>
  <form  method="post" action="../../controller/analisis.controller.php">
    <div class="form-group row">
      <div class="col">
        <h2>Seleccione muestras ingresadas para eliminar del Batch</h2>
          <div>
            <? echo $analisis->analisis_para_modificar($analisis->obtener_batch_modificar($_GET["id"]),$_GET["id"]);?>
          </div>
          <div>
            <a href="ingreso-batch.php" class='btn btn-primary'>Volver</a>
          </div>
          
      </div>
    </div>
  </form>
</div>    

<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
</body>
</html>
