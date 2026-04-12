<?php
  $encontrado=0;
  if ($_POST){
    //print_r($_POST);
    require_once("../model/usuario.model.php");
    $usuario=new usuarioModel();
    $res=$usuario->existe_usuario($_POST["usuario"],$_POST["clave"]);
    //print_r($res);
    
    if ($res<>0){
      //guardar $_SESSION
      session_start();
      session_regenerate_id(true); 
      $_SESSION["nombre"]=$res->nombre;
      $_SESSION["rut"]=$res->rut;
      $_SESSION["rol"]=$res->rol;
      $encontrado=1;
    }else{
      echo "<div class='alert alert-danger' role='alert'>ERROR: Usuario no encontrado o clave incorrecta</div>";
    }
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script type="text/javascript">
  var encontrado=<?echo $encontrado;?>;
  if (encontrado==1){
    window.parent.closeModal();
  } 
</script>
</head>
<body>


<form action="login.php" method="post">
<div class="container">
  <div class="form-group row">
    <label for="usuario" class="col-4 col-form-label"></label> 
    <div class="col-12">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-male"></i>
          </div>
        </div> 
        <input id="usuario" name="usuario" placeholder="Nombre de Usuario" type="text" required="required" class="form-control" aria-describedby="usuarioHelpBlock">
      </div> 
    </div>
  </div>
  <div class="form-group row">
    <label class="col-4 col-form-label" for="clave"></label> 
    <div class="col-12">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="fa fa-minus-circle"></i>
          </div>
        </div> 
        <input id="clave" name="clave" placeholder="Clave" type="password" class="form-control" aria-describedby="claveHelpBlock" required="required">
      </div> 
    </div>
  </div> 
  <div class="form-group row">
    <div class="offset-4 col-8">
      <button name="submit" type="submit" class="btn btn-primary">Ingresar</button>
    </div>
  </div>
</div>
</form>
</body>
</html>
