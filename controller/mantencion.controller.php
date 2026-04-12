<?php
require_once '../model/mantencion.model.php';

class mantencionController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new mantencionModel();
    }
    
    public function Index(){
        require_once '../view/mantencion/mantencion.php';
    }
    
    public function nuevo_equipo_view(){
        require_once '../view/mantencion/ingreso-nuevo.php';
    }    
}

if ($_POST){
    //obtener campos POST Para guardar el registro de Análisis
	
	$mantencion=new mantencionModel();
  	echo $mantencion->guardar_datos($_POST);
	
	header("location:../view/mantencion/ingreso-nuevo.php?i=1");
}


