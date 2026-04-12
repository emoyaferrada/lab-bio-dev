<?
  session_start();
  require_once("../../model/cliente.model.php");

  $cliente = new clienteModel();

  if ($_GET["predio_id"]) $_POST["predio_id"]=$_GET["predio_id"];
  if ($_GET["cliente_id"]) $_POST["cliente_id"]=$_GET["cliente_id"];
    
  
  if ($_POST["guardar_cuartel"]==1){
    //guardar datos de cliente y predios
    $cliente->guardar_nuevo_cuartel($_POST);
  }

  if ($_GET["accion"]=="del"){
  	$exito=$cliente->eliminar_cuartel($_GET["cuartel_id"],$_GET["zona_id"]);	
  }

?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="container">
<?
if ($exito==1){
echo '<div id="alerta" class="alert alert-warning alert-dismissible fade show" role="alert">
  	<strong>Registro Eliminado</strong>
	</div>';
}
?>
  <form method="post" action="ingreso-zona-cuartel.php">
    <!-- Ingreso nuevo Predio -->     
    <h4>Ingreso de zonas de riego y cuarteles asociados</h4>
    <div  id="div_nuevo_predio">
      <div class="form-group row">
        <div class="col">
          <label for="zona" class="col col-form-label">Nombre Zona</label> 
          <input id="zona" name="zona" type="text" class="form-control">
        </div>
        
        <div class="col">
          <label for="nombre" class="col col-form-label">Nombre Cuartel</label> 
          <input id="nombre" name="nombre" type="text" class="form-control">
        </div>
        <div class="col">
          <label for="descripcion" class="col col-form-label">Especie/Variedad</label> 
          <input id="descripcion" name="descripcion" type="text" class="form-control">
        </div>
      </div>
      <div class="form-group row">
        <div class="col">
            <input type="hidden" name="cliente_id" value="<?echo $_POST["cliente_id"];?>">
            <input type="hidden" name="predio_id" value="<?echo $_POST["predio_id"];?>">

            <? if(! $_POST) echo '<input name="cliente_nuevo" type="hidden" value="true">';?> 
            <button name="guardar_cuartel" id="guardar_cuartel" value="1" type="submit" class="btn btn-primary">Guardar Cuartel</button>    
            <button name="volver" id="volver" value="1" type="button" class="btn btn-primary">Volver</button>    

        </div>
      </div>
    </div>
  </form>

  <div id="tabla_predios">
    <div class="row">
      <h4>Lista de cuarteles del predio </h4>
    </div>
    <div class="row">
      <? if ($_POST["predio_id"]) echo $cliente->lista_cuartel_predio($_POST["predio_id"]);?>
    </div> 
  </div>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
  $( document ).ready(function() {
    var rut=<?php echo $_POST["cliente_id"];?>;
  	$("#volver").on("click", function(){
      location.href = "ingreso-nuevo-cliente.php?rut=" + rut;
  	});
	$("#alerta").delay(4000).slideUp(200, function() {
		$(this).alert('close');
 	});
})  

</script>