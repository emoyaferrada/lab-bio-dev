<?  
  require_once("../../model/facturas.model.php");
  $facturas = new facturasModel();
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="container" >
	<? echo $facturas->ver_detalle($_GET["id"]); ?>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>