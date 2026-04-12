<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jsBarcode/JsBarcode.all.min.js"></script>

</head>
<body>


<div id="svg_barcode"><svg id="barcode"></svg></div>


<script type="text/javascript">
	//Creación del codigo de Barras al pinchar el botón de codigo
	console.log("GET ID:",<?echo $_GET["id"];?>);
    $('#barcode').JsBarcode(<?echo $_GET["id"];?>);
    //<script src="../../assets/vendor/jsBarcode/JsBarcode.code128.min.js"></script>

</script>
</body>
</html>
