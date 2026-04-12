<?php
require_once '../model/planificacion.model.php';
require_once '../model/analisis.model.php';

class planificacionController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new planificacionModel();
    }
    
    public function Index(){
        require_once '../view/planificacion/planificacion.php';
    }
    
    public function nuevo_planificacion_view(){
        require_once '../view/planificacion/ingreso-nuevo.php';
    }    
}


if ($_POST["guardar_nuevo"]==1){
    //obtener campos POST Para guardar el registro de planificacion
    
    //Guardar registro de programacion
    $planificacion=new planificacionModel();
  	echo $planificacion->guardar_nuevo($_POST);

    $ultimo_id_programacion=$planificacion->obtener_ultimo_id();
    
    $analisis=new analisisModel();
    $detalles=json_decode(utf8_encode($_POST["detalle_analisis"]),true);

    echo "<pre>".print_r($detalles)."</pre>";
    exit;



    foreach ($detalles as $key => $value) {
        //echo "<br>".$key."=>".$value["Tipo Análisis"];
        $ultimo_id_analisis=$analisis->obtener_ultimo_id() + 1;
        
        //Guardar registro de analisis
        //obtener campos POST Para guardar el registro de Análisis
        if ($value["id_tipo_analisis"] == 2) {
            if ($value["d1"]<>"") $n_muestras=1;
            if ($value["d4"]<>"") $n_muestras=2;
            if ($value["d7"]<>"") $n_muestras=3;
            if ($value["d10"]<>"") $n_muestras=4;        
        }elseif ($value["id_tipo_analisis"] == 3) {
            if ($value["d1"]<>"") $n_muestras=1;
            if ($value["d2"]<>"") $n_muestras=2;
            if ($value["d3"]<>"") $n_muestras=3;
            if ($value["d4"]<>"") $n_muestras=4;
        }else{
            $n_muestras=1;
        }

        $datos_para_analisis=array();
        $datos_para_analisis["id_analisis1"]=$ultimo_id_analisis;
        $datos_para_analisis["fecha_toma_muestra"]=$value["Fecha"];
        $datos_para_analisis["fecha_ingreso"]=$value["Fecha"];
        $datos_para_analisis["tipo_analisis"]=$value["id_tipo_analisis"];
        $datos_para_analisis["predio_cliente_id"]=$_POST["predio_cliente_id"];
        $datos_para_analisis["nombre_muestra"]=$value["Nombre"]; //$_POST["usuario_toma_muestra"];
        $datos_para_analisis["cliente_id"]=$_POST["cliente_id"];
        $datos_para_analisis["observaciones"]="";
        $datos_para_analisis["zona"]=$_POST["zona"];
        $datos_para_analisis["ultimo_id_programacion"]=$ultimo_id_programacion;
        $datos_para_analisis["estado_trabajo"]=1;
        $datos_para_analisis["profundidad_1"]=$_POST["profundidad_1"];
        
        
        echo "<br>guarda_analisis: ". $analisis->guardar_analisis($datos_para_analisis,$n_muestras);
         //Guardar analisis_muestra   
        //revisar por casos el ingreso de variables de suelo, agua, foliar y fertilizante

        if (($_POST["tipo_analisis"]==1) or ($_POST["tipo_analisis"]==9) or ($_POST["tipo_analisis"]==10)  or ($_POST["tipo_analisis"]==11) or ($_POST["tipo_analisis"]==12))
            $tipo="foliar";
        if (($_POST["tipo_analisis"]==2) or ($_POST["tipo_analisis"]==18))
            $tipo="agua";
        if (($_POST["tipo_analisis"]==3) or ($_POST["tipo_analisis"]==5) or ($_POST["tipo_analisis"]==6)  or ($_POST["tipo_analisis"]==7) or ($_POST["tipo_analisis"]==8) or ($_POST["tipo_analisis"]==19))
            $tipo="suelo";
        if (($_POST["tipo_analisis"]==4) or ($_POST["tipo_analisis"]==26))
            $tipo="fertilizante";
               
        switch ($tipo) {
            case 'foliar':
                //analisis foliar
                //guardar datos de foliar  

                $analisis->guarda_muestra_foliar($ultimo_id_analisis,$value["d1"],$value["d2"],$value["d3"]);
                break;
            case 'agua':
                //analisis de agua revisar cuantos fuentes
                if ($value["d1"]<>"")  $analisis->guarda_muestra_agua($ultimo_id_analisis,$value["d1"],$value["d2"],$value["d3"]);
                if ($value["d2"]<>"")  $analisis->guarda_muestra_agua($ultimo_id_analisis,$value["d4"],$value["d5"],$value["d6"]);
                if ($value["d3"]<>"")  $analisis->guarda_muestra_agua($ultimo_id_analisis,$value["d7"],$value["d8"],$value["d9"]);
                if ($value["d4"]<>"")  $analisis->guarda_muestra_agua($ultimo_id_analisis,$value["d10"],$value["d11"],$value["d12"]);
                break;

            case 'suelo':
                //analisis de suelo validar profundidades   
                if ($value["d1"]<>"") $analisis->guarda_muestra_suelo($ultimo_id_analisis,"Profundidad_1:".$value["d1"],$value["d1"]);
                if ($value["d2"]<>"") $analisis->guarda_muestra_suelo($ultimo_id_analisis,"Profundidad_2:".$value["d2"],$value["d2"]);
                if ($value["d3"]<>"") $analisis->guarda_muestra_suelo($ultimo_id_analisis,"Profundidad_3:".$value["d3"],$value["d3"]);
                if ($value["d4"]<>"") $analisis->guarda_muestra_suelo($ultimo_id_analisis,"Profundidad_4:".$value["d4"],$value["d4"]);

                break;
            case 'fertilizante':
                //analisis fertilizante    
                //ingresar tipo de fertilizante
                $analisis->guarda_muestra_fertilizante($ultimo_id_analisis,$value["d1"]);
                break;
            default:
                //analisis todos
                $analisis->guarda_muestra_analisis($ultimo_id_analisis);
                
                break;
        }
        $ultimo_id_analisis++;
    }
    header("location:../view/planificacion/ingreso-nuevo-dev.php?id=1");
}
if ($_POST["modificar_fecha_programacion"]==1){
    //modificar fecha de planificacion y devolver respuesta
    $planificacion=new planificacionModel();
    echo $planificacion->modificar_fecha_programacion($_POST["programacion_id"],$_POST["nueva_fecha"]);   
}

if ($_POST["obtener_eventos_calendario"]==1){
    $planificacion=new planificacionModel();
    echo json_encode($planificacion->obtener_eventos_calendario_2());
}

if ($_POST["ingresa_detalle_planificacion"]==1){
    $planificacion=new planificacionModel();
    
    foreach($_POST["fecha"] as $key=>$value){
        $estado=$_POST["chk_sel_".$key];
        if ($estado=="on")
            echo $planificacion->actualiza_ingreso_analisis($key, $value);
    }
    header("location:../view/planificacion/ingreso-nuevo-dev.php?id=1");
}
?>
