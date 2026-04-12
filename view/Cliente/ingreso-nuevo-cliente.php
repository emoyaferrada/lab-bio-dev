<?
  session_start();
  require_once("../../model/cliente.model.php");

  $cliente = new clienteModel();
  
  if ($_POST["modificar_registro"]==1){
    //Guardar modificacion de registro de cliente
    $cliente->modificar_cliente($_POST);
  }

  if ($_POST["buscar"]==1){
    //buscar el rut y traer los predios
    $_POST=$cliente->trae_form($_POST["rut"]);
  }
  
  if ($_POST["guardar_predio"]==1){
    //guardar datos de cliente y predios
    if ($_POST["cliente_nuevo"]=='true')
      $cliente->guardar_nuevo($_POST);
    
    $cliente->guardar_nuevo_predio($_POST);
  }

  if ($_GET["rut"]) {
    echo "El RUT ingresado es: ".$_GET["rut"];

    $_POST=$cliente->trae_form($_GET["rut"]);
  }


?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="container">

  <form method="post" action="ingreso-nuevo-cliente.php">
<br>
    <div class="form-group row">
      <label for="rut" class="col-4 col-form-label">RUT</label> 
      <div class="col">
        <input id="rut" name="rut" type="text" class="form-control" maxlength="12" onkeyup="f_rut(this)"  value="<?echo $_POST["rut"];?>">
      </div>
      <div class="col">
        <button type="submit" id="buscar" name="buscar" value="1" class="btn btn-primary">Buscar RUT</button>
      </div>

    </div>
    <div class="form-group row">
      <label for="nombre" class="col-4 col-form-label">Nombre</label> 
      <div class="col-8">
        <input id="nombre" name="nombre" type="text" class="form-control" value="<?echo $_POST["nombre"];?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="razon_social" class="col-4 col-form-label">Razón Social</label> 
      <div class="col-8">
        <input id="razon_social" name="razon_social" type="text" class="form-control" value="<?echo $_POST["razon_social"];?>">
      </div>
    </div>    
    <div class="form-group row">
      <label for="giro" class="col-4 col-form-label">Giro</label> 
      <div class="col-8">
        <input id="giro" name="giro" type="text" class="form-control" value="<?echo $_POST["giro"];?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="direccion" class="col-4 col-form-label">Dirección</label> 
      <div class="col-8">
        <input id="direccion" name="direccion" type="text" class="form-control" value="<?echo $_POST["direccion"];?>">
      </div>
    </div>    
    <div class="form-group row">
      <label for="comuna" class="col-4 col-form-label">Comuna</label> 
      <div class="col-8">
        <select id="comuna" name="comuna" class="custom-select">
          <?echo $cliente->option_comunas($_POST["comuna"]);?>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <label for="telefono_1" class="col-4 col-form-label">Teléfono 1</label> 
      <div class="col-8">
        <input id="telefono_1" name="telefono_1" type="text" class="form-control" value="<?echo $_POST["telefono_1"];?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="telefono_2" class="col-4 col-form-label">Teléfono 2</label> 
      <div class="col-8">
        <input id="telefono_2" name="telefono_2" type="text" class="form-control" value="<?echo $_POST["telefono_2"];?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="email" class="col-4 col-form-label">Email</label> 
      <div class="col-8">
        <input type="email" id="email" name="email" placeholder="ejemplo@gmail.com" type="text" class="form-control" value="<?echo $_POST["email"];?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="email_2" class="col-4 col-form-label">Confirmar Email</label> 
      <div class="col-8">
        <input type="email" id="email_2" name="email_2" placeholder="ejemplo@gmail.com" type="text" class="form-control" value="<?echo $_POST["email"];?>" 
        onblur="if (form.email.value != form.email_2.value) {alert ('ATENCIÓN: no coincide email');}">
      </div>
    </div> 
    <div class="form-group row">
      <? if ($_POST["rut"]) echo '
      <button name="modificar_registro" id="modificar_registro" value="1" type="submit" class="btn btn-primary">Modificar</button>';?> 
    </div> 
    <div>
      <!--Listado de predios del cliente-->
      <div>
        <a href="#" class="btn btn-sm btn-neutral" id="btn_nuevo_predio"> + Nuevo predio</a>
      </div>
    </div>
    <!-- Ingreso nuevo Predio -->     
    <div  id="div_nuevo_predio">
      <div class="form-group row">
        <div class="col">
          <label for="nombre_predio" class="col-4 col-form-label">Nombre</label> 
          <input id="nombre_predio" name="nombre_predio" type="text" class="form-control">
        </div>
        <div class="col">
          <label for="sector_predio" class="col-4 col-form-label">Sector</label> 
          <input id="sector_predio" name="sector_predio" type="text" class="form-control">
        </div>
        <div class="col">
          <label for="comuna_predio" class="col-4 col-form-label">Comuna</label> 
          <select id="comuna_predio" name="comuna_predio" class="custom-select">
            <?echo $cliente->option_comunas();?>
          </select>
        </div>
      </div>
      <div class="form-group row">
        <div class="col">
            <? if (! $_POST["rut"]) echo '<input name="cliente_nuevo" type="hidden" value="true">';?> 
            <button name="guardar_predio" id="guardar_predio" value="1" type="submit" class="btn btn-primary">Guardar</button>          
        </div>
      </div>
    </div>
  </form>

  <div id="tabla_predios">
    <div class="row">
      <h4>Lista de predios cliente</h4>
    </div>
    <div class="row">
      <? echo $cliente->lista_predios_cliente($_POST["rut"]);?>
    </div> 
  </div>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
  $( document ).ready(function() {

    $("#div_nuevo_predio").hide();

    $("#btn_nuevo_predio").on("click", function(){
        //$("#div_nuevo_predio").show();
        $("#div_nuevo_predio").slideDown();
    })
})  
  
    function f_rut(rut)
      {rut.value=rut.value.replace(/[.-]/g, '').replace( /^(\d{1,2})(\d{3})(\d{3})(\w{1})$/, '$1.$2.$3-$4')}

</script>