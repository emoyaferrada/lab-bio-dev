<?
  require_once("../../model/planificacion.model.php");
  require_once("../../model/cliente.model.php");
  require_once("../../model/analisis.model.php");
  require_once("../../model/usuario.model.php");
  $cliente = new clienteModel();
  $analisis = new analisisModel();
  $usuario = new usuarioModel();
  $planificacion = new planificacionModel();

  $id_programacion=$_GET["id"];
  $tabla_planificacion=$planificacion->obtiene_tabla_id($id_programacion);

  $tabla_detalle_planificacion=$planificacion->obtiene_tabla_detalle($id_programacion);

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
if ($_GET["id"]==1){
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro modificado exitosamente</div>';
}
?>

  <form id="form_ingreso" method="post" action="../../controller/planificacion.controller.php">
    <h4>ID : <? echo $id_programacion;?> </h4>
    <h4>Datos Programación</h4>
    <div class="row">
      <?php echo $tabla_planificacion?>
    </div>
    
    <h4>Detalle análisis</h4> 
    <div class="row">
      <?php echo $tabla_detalle_planificacion; ?>
    </div>


    <div class="form-group row">
      <div class="offset-4 col-8">
        <input type="hidden" name="detalle_analisis" id="detalle_analisis" value="">
        <button name="ingresa_detalle_planificacion" id="ingresa_detalle_planificacion" value="1" type="submit" class="btn btn-primary">Ingresar muestras</button>
      </div>    
    </div>
  </form>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>


<script type="text/javascript">
$( document ).ready(function(){

    $("#alerta").delay(4000).slideUp(200, function() {
        $(this).alert('close');
    });  

  $('#guardar_nuevo').on('click',function(){
      //recorrer la tabla para registrar los check box activos 
      //por cada activo cambiar el estado de l id_anañisis
      //modificar la fecha de ingreso y el estado del analisis

/*
     var convertedIntoArray = [];
     $("table#tabla_detalle_analisis tr").each(function() {
        var rowDataArray = [];
        var actualData = $(this).find('td');
        
        if (actualData.length > 0) {
           actualData.each(function() {
              rowDataArray.push($(this).text());
           });
           convertedIntoArray.push(rowDataArray);
        }        
     });
     console.log(convertedIntoArray);

      var table = $('#tabla_datos').tableToJSON();
      $('#detalle_analisis').val(JSON.stringify(table));
      //$('#observaciones').val(table);
      //console.log(table);
      $('#guardar_nuevo').val(1);
      $("#form_ingreso").submit();
      */
  });
   
   

});
</script>
</body>
</html>
