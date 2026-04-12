<?
  session_start();
  require_once '../../model/analisis.model.php';
  $analisis = new analisisModel();
  /*
  if ($_GET["id"]=="exito") $_POST["tipo_analisis"]=$_GET["tipo_analisis"];

  if (($_POST["mostrar"]==1) or ($_GET["id"]=="exito")){
    require_once '../../model/analisis.model.php';
    $analisis = new analisisModel();

    $batch=$analisis->muestras_para_batch($_POST["tipo_analisis"]);
    
    $tabla_resultado="<table class='table'>
                      <thead>
                        <th>Sel.</th>
                        <th>Tipo</th>
                        <th>Fecha Ingreso</th>
                        <th>Id</th>
                      </thead>
                      <tbody>";
    if ($batch[0]){
      $sin_datos=false;
      foreach ($batch as $value) {
        $tabla_resultado.="<tr><td><input type='checkbox' name='sel[]' value='".$value[0]."'>"
                        ."</td><td>".$value[3]
                        ."</td><td>".$value[2]
                        ."</td><td>".$value[1]."-".$value[0]."</td></tr>";
      }
      $tabla_resultado.="</tbody></table>";  
    }
    else $sin_datos=true;
    
  }
  if (! $_POST AND ! $_GET){
    require_once '../../model/analisis.model.php';
    $analisis = new analisisModel();
  }
  if ($_POST["1"]){
    require_once '../../model/analisis.model.php';
    $analisis = new analisisModel();
  }  
  */
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
<?
if ($_GET["id"]=="exito"){
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}
elseif ($_GET["id"]=="error"){
    echo '<div class="alert alert-danger" id="alerta" role="alert">Error al guardar registros</div>';
}

?>
  <form  method="post" action="../../controller/analisis.controller.php">
    <div class="form-group row">
      <div class="col">
        <h2>Seleccione muestras ingresadas para crear un Batch</h2>
          <div>
            <? echo $analisis->analisis_para_batch($analisis->muestras_para_batch());?>
      </div>
    </div>
  </form>

<?


  if ($_POST){
    if ($sin_datos){
      echo '<h4>No se encontraron muestras ingresadas</h4>';
      exit;
    }
    echo '../../controller/analisis.controller.php'. '<form  method="post" action="../../controller/analisis.controller.php">
    <h4>Muestras Ingresadas</h4>
    <div class="form-group row">
      <div class="col">';
      echo $tabla_resultado_batch;
    echo'
      </div>
    </div> 

    <div class="form-group row">
      <div class="offset-4 col-8">
        <input type="hidden" name="tipo_analisis" value="'.$_POST["tipo_analisis"].'">
        <button name="guardar_batch" id="guardar_batch" value="1" type="submit" class="btn btn-primary">Guardar</button>
      </div>    
    </div>
  </form>';
  }
  ?>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script>
$( document ).ready(function(){
  $("#alerta").delay(4000).slideUp(200, function() {
        //$(this).alert('close');
  });
})
</script>
</body>


</html>
