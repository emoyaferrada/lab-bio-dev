<?php
require_once '../model/facturas.model.php';

session_start();
class facturasController{    
    private $model;
	private $cliente;	
    
    public function __CONSTRUCT(){
        $this->model = new facturasModel();		
    }
    
    public function Index(){
        require_once '../view/facturas/facturas.php';
    }
}

$factura = new facturasModel();

if ($_POST["ajax_cliente"]){   
    echo $factura->listar_analisis($_POST["ajax_cliente"]); 
}
if ($_POST["cliente_deuda"]){   
    echo $factura->listar_deudas($_POST["cliente_deuda"]); 
}
if ($_POST["cliente_busq"] || $_POST["fechai"] || $_POST["fechaf"]){   
    echo $factura->listar_facturas($_POST["cliente_busq"],$_POST["fechai"],$_POST["fechaf"]); 
}

if ($_POST["guarda_factura"]){
	
	$pagada = "0";
    $cant = $_POST["contador"];
    $cliente = $_POST["cliente"];
	$monto = $_POST["monto"];
	$num_factura = $_POST["num_factura"];
	$foma_pago = $_POST["forma_pago"];
	$obs = $_POST["observaciones"];
	$num_cuotas = $_POST["num_cuota"];
	$fecha = $_POST["fecha_factura"];
	$valor_cuota = $_POST["valor_cuota"];
	$valor_cuotaf = $_POST["valor_cuotaf"];
	$ges = $_POST["ges"];
	$num_orden = $_POST["num_orden"];
	$fecha_orden = $_POST["fecha_orden"];
	$valor_orden = $_POST["valor_orden"];
	
	$tipo_pago = $_POST["tipo_pago"];
	
	$newFileName = "";
	if (isset($_FILES['arch_orden']) && $_FILES['arch_orden']['error'] === UPLOAD_ERR_OK) {
		$fileTmpPath = $_FILES['arch_orden']['tmp_name'];
		$fileName = $_FILES['arch_orden']['name'];
		$fileSize = $_FILES['arch_orden']['size'];
		$fileType = $_FILES['arch_orden']['type'];
		list($nom,$ext)=explode(".", $fileName);
		$fileNameCmps = explode(".", $fileName);
		$fileExtension = strtolower(end($fileNameCmps));

		$newFileName = $num_orden.$nom.'.'.$fileExtension;//md5(time().$fileName)
		
		//$allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'xlxs', 'doc');
		//if (in_array($fileExtension, $allowedfileExtensions)) {			
			$uploadFileDir = '../archivos/ordenes_compra/';
			$dest_path = $uploadFileDir.$newFileName;
			if(move_uploaded_file($fileTmpPath, $dest_path))
			{
			  $archivo_orden = $newFileName ;
			  $message ='subio el archivo';
			}
			else
			{
			  $message = 'NO subio el archivo';
			}
		//}
	}

	if($tipo_pago=="total"){ $pagada = 'true'; }else{ $pagada = 'false'; }
	if($tipo_pago=="cuota"){ $valor_cuota = $valor_cuotaf; }

	$id_factura = $factura->guardar_factura($pagada,$num_cuotas,$fecha,$cliente,$monto,$num_factura,$foma_pago,$obs,$valor_cuota,$ges,$num_orden,$fecha_orden, $valor_orden,$archivo_orden,$tipo_pago);	
			
	for($i=1; $i<=$cant; $i++){
		if($_POST["valor".$i]){
			$guardar = $factura->guardar_analisis_fact($_POST["id_analisis".$i],$id_factura,$_POST["valor".$i]);
		}
	}	
	
	header("location:../view/facturas/facturas.php?id=exito");
}
if ($_POST["guardar_cuota"]){
	
	$check = $_POST["facturach"];
	$id_fact = $_POST["id_factura".$check];
    $fecha_pago = date("Y-m-d");
	$numero_factura  = $_POST["num_factura"];
	$fecha_factura   = $_POST["fecha_factura"];
	$monto_ingresado = $_POST["monto"];
	$forma_pago   = $_POST["forma_pago"];
	$num_cuotas   = $_POST["cuota"];
	$tipo_pago    = $_POST["tipo_pago".$check];
	$monto_total  = $_POST["monto_total".$check];
	$monto_pagado = $_POST["monto_pagado".$check];
	$cuotas_pagadas = $_POST["cuotas_pagadas".$check];
	$cuotas_total   = $_POST["num_cuota".$check];
	
	if($numero_factura == ''){
		$numero_factura='0';
	}		
	
	if($fecha_factura == ''){
		$fecha_factura='0000-00-00';
	}
		
	$cant_total_cuotas = $num_cuotas + $cuotas_pagadas;
	$cant_pagada = $monto_total - ($monto_ingresado + $monto_pagado) ;
	
	if(($tipo_pago == "cuota") && ($cant_total_cuotas == $cuotas_total)){ $pagada=true; }else{ $pagada=false; }
	
	if(($tipo_pago == "abono") && ($cant_pagada == 0)){ $pagada=true; }else{ $pagada=false; }
	
	$resp = $factura->guardar_pago_cuota($id_fact,$fecha_pago,$numero_factura,$fecha_factura,$monto_ingresado,$forma_pago,$num_cuotas,$pagada,$tipo_pago); 
	
	header("location:../view/facturas/facturas-pagocuota.php?id=exito");
}


?>