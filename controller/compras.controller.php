<?php
require_once '../model/compras.model.php';

session_start();
class comprasController{    
    private $model;		
    
    public function __CONSTRUCT(){
        $this->model = new comprasModel();		
    }
    
    public function Index(){
        require_once '../view/compras/ingreso-insumos.php';
    }
}

$compras = new comprasModel();

if ($_POST["guarda_insumo"]){
	
	$nombre  = $_POST["nombre"];
    $descrip = $_POST["descripcion"];
	$unidad  = $_POST["unidad"];
	$alerta  = $_POST["alerta"];
	$tiempo  = $_POST["tiempo"];
	$fecha   = date("Y-m-d");
	
	$newFileName = "";
	if (isset($_FILES['certificado']) && $_FILES['certificado']['error'] === UPLOAD_ERR_OK) {
		$fileTmpPath = $_FILES['certificado']['tmp_name'];
		$fileName 	 = $_FILES['certificado']['name'];
		$fileSize 	 = $_FILES['certificado']['size'];
		$fileType 	 = $_FILES['certificado']['type'];
		list($nom,$ext) = explode(".", $fileName);
		$fileNameCmps   = explode(".", $fileName);
		$fileExtension  = strtolower(end($fileNameCmps));

		$newFileName = $nom.'.'.$fileExtension;//md5(time().$fileName)
		
		//$allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'xlxs', 'doc');
		//if (in_array($fileExtension, $allowedfileExtensions)) {			
			$uploadFileDir = '../archivos/certificados/';
			$dest_path = $uploadFileDir.$newFileName;
			if(move_uploaded_file($fileTmpPath, $dest_path))
			{
			  $certificado = $newFileName ;
			  $message ='subio el archivo';
			}
			else
			{
			  $message = 'NO subio el archivo';
			}
		//}
	}
	
	$id_compras = $compras->guardar_insumo($nombre, $descrip, $unidad, $alerta, $tiempo, $fecha, $certificado);		
	
	header("location:../view/compras/ingreso-insumos.php?id=exito");
}
if ($_POST["guarda_proveedor"]){

	$nombre    = $_POST["nombre"];
	$rut       = $_POST["rut"];
	$direccion = $_POST["direccion"];
	$fono      = $_POST["$fono"];
	$correo    = $_POST["correo"];
	$contacto  = $_POST["contacto"];	

	$resp = $factura->guardar_proveedor($nombre, $rut, $direccion, $fono, $correo, $contacto); 
	
	header("location:../view/compras/ingreso-proveedores.php?id=exito");
}


?>