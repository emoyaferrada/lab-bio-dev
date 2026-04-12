<?php
require_once '../model/facturas.model.php';
session_start();
class facturasController{
    
    private $model;
    
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

if ($_POST["guardar_factura"]){
    
    $cant=$_POST["contador"];
    $cliente=$_POST["cliente"];
	$monto = $_POST["monto"];
	$num_factura= $_POST["num_factura"];
	$foma_pago = $_POST["forma_pago"];
	$obs = $_POST["observaciones"];
	$num_cuotas = $_POST["num_cuota"];
	$fecha = $_POST["fecha_factura"];
	$valor_cuota=$_POST["valor_cuota"];
	
	if($num_cuotas !="" || $num_cuotas !=0){ $pagada=0;	}else{ $pagada=1; }
	
	$id_factura = $factura->guardar_factura($pagada,$num_cuotas,$fecha,$cliente,$monto,$num_factura,$foma_pago,$obs,$valor_cuota);
		
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
	$numero_factura = $_POST["num_factura"];
	$fecha_factura = $_POST["fecha_factura"];
	$monto_pagado = $_POST["monto_pagar"];
	$forma_pago = $_POST["forma_pago"];
	$num_cuotas = $_POST["cuota"];
	
	$cant_total_cuotas = $num_cuotas + $_POST["cuotas_pagadas".$check];
	
	if($cant_total_cuotas == $_POST["num_cuota".$check]){ $pagada=true;	}else{ $pagada=false; }
	
	$resp = $factura->guardar_pago_cuota($id_fact,$fecha_pago,$numero_factura,$fecha_factura,$monto_pagado,$forma_pago,$num_cuotas,$pagada); 
	header("location:../view/facturas/facturas-pagocuota.php?id=exito");
}


?>