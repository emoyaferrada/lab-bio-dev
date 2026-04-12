<?


$rut = $_GET['rut'];

require_once("../../model/analisis.model.php");
require_once("../../controller/analisis.controller.php");



$analisis = new analisisModel();
$controller=new analisisController();

$datos_originales=$analisis->obtiene_seguimiento_cliente($rut);
//echo $controller->imprimir_array($datos_originales);

//trasponer el array para mostrar por codigo de analisis para el cliente
$datos=$controller->transponer_analisis2($analisis->obtiene_seguimiento_cliente($rut));

echo $controller->imprimir_array($datos);
exit;


?>

<!DOCTYPE html>
<html lang="es">
<head>
 <meta charset="UTF-8">
 <title>Listado de Análisis</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">

<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/datatables/datatables.min.js"></script>  
</head>
<body>
 <h2>Listado de Análisis</h2>
 <table id="tabla" class="display" style="width:100%">
	 <thead>
	 <tr>
	 <th>Fecha</th>
	 <th>Predio</th>
	 <th>Zona</th>
	 <th>Usuario Muestra</th>
	 <th>Responsable</th>
	 <th>Profundidad Análisis</th>
	 <th>Profundidad</th>
	 <th>Nombre Variable</th>
	 <th>Unidad</th>
	 <th>Valor</th>
	 <th>Rango</th>
	 </tr>
	 </thead>
 </table>

 <script>
 $(document).ready(function() {
	 var dataSet = <?echo $datos;?>;
	 	console.log(dataSet);

	 $('#tabla').DataTable({
		 data: dataSet,
		 columns: [
		 { data: 'fecha_analisis_ok' },
		 { data: 'nombre_predio' },
		 { data: 'nombre_zona' },
		 { data: 'usuario_toma_muestra' },
		 { data: 'responsable_muestra' },
		 { data: 'profundidad_analisis' },
		 { data: 'profundidad' },
		 { data: 'nombre' },
		 { data: 'unidad' },
		 { data: 'valor' },
		 { data: 'rango' }
		 ]
	 });
 });
 </script>
</body>
</html>
