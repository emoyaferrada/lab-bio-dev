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
	
	$cant = $_POST["contador"];
    $cliente = $_POST["cliente"];
	$monto = $_POST["monto"];
	
	$num_factura = $_POST["num_factura"];	
	$valor_factura = $_POST["val_factura"];
	$fecha_factura = $_POST["fecha_factura"];
	
	$ges = $_POST["ges"];
	$num_orden = $_POST["num_orden"];
	$fecha_orden = $_POST["fecha_orden"];
	$valor_orden = $_POST["valor_orden"];
	
	$obs = $_POST["observaciones"];
	
	$newFileName = "";
	if (isset($_FILES['arch_factura']) && $_FILES['arch_factura']['error'] === UPLOAD_ERR_OK) {
		$fileTmpPath = $_FILES['arch_factura']['tmp_name'];
		$fileName 	 = $_FILES['arch_factura']['name'];
		$fileSize 	 = $_FILES['arch_factura']['size'];
		$fileType 	 = $_FILES['arch_factura']['type'];
		list($nom,$ext)=explode(".", $fileName);
		$fileNameCmps = explode(".", $fileName);
		$fileExtension = strtolower(end($fileNameCmps));

		$newFileName = $num_orden.$nom.'.'.$fileExtension;//md5(time().$fileName)
		
		//$allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'xlxs', 'doc');
		//if (in_array($fileExtension, $allowedfileExtensions)) {			
			$uploadFileDir = '../archivos/facturas/';
			$dest_path = $uploadFileDir.$newFileName;
			if(move_uploaded_file($fileTmpPath, $dest_path))
			{
			  $archivo_factura = $newFileName ;
			  $message ='subio el archivo';
			}
			else
			{
			  $message = 'NO subio el archivo';
			}
		//}
	}
	
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

	$id_factura = $factura->guardar_factura($pagada, $monto, $cliente, $ges, $num_orden, $fecha_orden, $valor_orden, $archivo_orden, $tipo_pago, $valor_factura, $archivo_factura, $fecha_factura, $num_factura, $obs);	
			
	for($i=1; $i<=$cant; $i++){
		if($_POST["valor".$i]){
			$guardar = $factura->guardar_analisis_fact($_POST["id_analisis".$i],$id_factura,$_POST["valor".$i]);
		}
	}	
	
	header("location:../view/facturas/facturas.php?id=exito");
}
if ($_POST["guardar_pago"]){
	
	$diferencia = 0;
	$check = $_POST["facturach"];
	$id_fact = $_POST["id_factura".$check];
    $fecha_pago = date("Y-m-d");
	
	$monto_ingresado = $_POST["monto"];
	$tipo_pago    = $_POST["tipo_pago"];
	$forma_pago   = $_POST["forma_pago"];
	$fecha_pago   = $_POST["fecha_pago"];
	
	if($tipo_pago=="abono"){
		$monto_total  = $_POST["monto_total".$check];
		$monto_pagado = $_POST["monto_pagado".$check];
		$diferencia = $monto_total - ($monto_ingresado + $monto_pagado);
		if($diferencia == 0){ $pagada=true; }else{ $pagada=false; }
	}else{
		$pagada=true;		
	}
	$newFileName = "";
	if (isset($_FILES['arch_comprobante']) && $_FILES['arch_comprobante']['error'] === UPLOAD_ERR_OK) {
		$fileTmpPath = $_FILES['arch_comprobante']['tmp_name'];
		$fileName = $_FILES['arch_comprobante']['name'];
		$fileSize = $_FILES['arch_comprobante']['size'];
		$fileType = $_FILES['arch_comprobante']['type'];
		list($nom,$ext)=explode(".", $fileName);
		$fileNameCmps = explode(".", $fileName);
		$fileExtension = strtolower(end($fileNameCmps));

		$newFileName = $num_orden.$nom.'.'.$fileExtension;//md5(time().$fileName)
		
		//$allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'xlxs', 'doc');
		//if (in_array($fileExtension, $allowedfileExtensions)) {			
			$uploadFileDir = '../archivos/pagos/';
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

	$resp = $factura->guardar_pago($id_fact,$fecha_pago,$monto_ingresado,$forma_pago,$pagada,$tipo_pago,$archivo_comprobante); 
	
	header("location:../view/facturas/facturas-pagocuota.php?id=exito");
}


?>