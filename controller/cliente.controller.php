<?php
require_once '../model/cliente.model.php';

class equipoController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new clienteModel();
    }
}

if ($_POST["ajax_cuarteles"]==1){
    //traer los datos y la tabla de salida
    $cliente=new clienteModel();
    echo $cliente->lista_predio_zona($_POST["predio_id"],$_POST["zona_id"]); 
}
if ($_POST["cuarteles_predio"]==1){
    //traer los datos y la tabla de salida
    $cliente=new clienteModel();
    echo $cliente->listado_zonas($_POST["predio_id"]); 
}