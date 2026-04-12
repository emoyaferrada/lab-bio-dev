<?php
require_once '../model/analisis.model.php';
session_start();
class analisisController{
    
    private $model;
    
    public function __CONSTRUCT(){
        $this->model = new analisisModel();
    }
    
    public function Index(){
        require_once '../view/analisis/analisis.php';
    }
    public function Index_dev(){
        require_once '../view/analisis/analisis_dev.php';
    }

    public function nuevo_batch(){
        require_once '../view/analisis/nuevo-batch.php';
    }
    public function nuevo_analisis_view(){
        require_once '../view/analisis/ingreso-nuevo.php';
    }
    
    public function transponer_analisis($datos) {
        $clavePivot="nombre";

        foreach ($datos as $fila) {
            if (!isset($fila[$clavePivot])) {
                continue; // O lanzar una excepción si prefieres
            }

            $pivot = $fila[$clavePivot];
            foreach ($fila as $clave => $valor) {
                if ($clave !== $clavePivot) {
                    $resultado[$clave][$pivot] = $valor;
                }
            }
        }

        return $resultado;
    }

    public function transponer_analisis2($datos) {
        $clavePivot="codigo_muestra";
        //imprimir encabezado
        $json_titulos[]="Fecha";
        $json_titulos[]="Código";
        
        foreach ($datos as $fila) {
            $json_resultado="";
            $datos_json=json_decode($fila["variables_resultado"]);

            foreach ($datos_json as $key => $value) {
                $json_titulos[]=$value->nombre;
            }
            break;
        }

        foreach ($datos as $fila) {
            $json_resultado="";
            $datos_json=json_decode($fila["variables_resultado"]);

            foreach ($datos_json as $key => $value) {
                $json_valor[$value->nombre]=$value->valor;        
            }
            $salida[]=array_merge(array("Fecha"=>$fila["fecha_analisis_ok"],"Codigo"=>$fila["codigo"]),$json_valor);
        }
       
        return array_merge($salida);

    }


    public function imprimir_array(array $arrayTranspuesto){
        // Obtener los encabezados (claves internas del array)
        foreach ($arrayTranspuesto as $campo => $valores) {
            foreach ($valores as $clave => $valor) {
                $encabezados[$clave] = true;
            }
        }
        $encabezados = array_keys($encabezados);

        echo "<table border='1' cellpadding='5' cellspacing='0'>";

        // Encabezado de la tabla
        echo "<thead><tr><th>Campo</th>";
        foreach ($encabezados as $col) {
            echo "<th>{$col}</th>";
        }
        echo "</tr></thead>";

        // Cuerpo de la tabla
        echo "<tbody>";
        foreach ($arrayTranspuesto as $campo => $valores) {
            echo "<tr><td><strong>{$campo}</strong></td>";
            foreach ($encabezados as $col) {
                $valor = $valores[$col];
                echo "<td>{$valor}</td>";
            }
            echo "</tr>";
        }
        echo "</tbody>";

        echo "</table>";
    }


    
}


if ($_POST["guardar_datos_suelo"]){
    
    $valores=$_POST["valor"];
    $factor_estandar=$_POST["factor"];
    $factor_dilucion=$_POST["factor2"];

    $id_muestra=$_POST["id_muestra"];
    $observaciones=$_POST["observaciones"];



    //Anlális de Suelo
    //traer los factores para multiplicar los valores ingresados
    $analisis=new analisisModel;
    $instancia=$analisis->obtener_analisis($id_muestra);

    $factores=$analisis->variable_tipo_analisis2($instancia->tipo_analisis_id);
    //Realizar lso calculos entre variables y guardar
    $json_salida="";
    //var_dump($factores);
    foreach ($valores as $key => $value) {
        $resultado[$key][4]=round(($value * $factor_estandar[$key] * $factor_dilucion[$key]),$factores[$key][4]);
        $valores[$key] = round(($value * $factor_estandar[$key] * $factor_dilucion[$key]),$factores[$key][4]);
        $json_ingreso[$key]=array('valor'=>$value,'factor_estandar'=>$factor_estandar[$key],'factor_dilucion'=>$factor_dilucion[$key]);      
    }
    print_r($resultado);
    print_r($valores);
    //campos calculados
    //Potasio disponible
    $valores[88] = ($json_ingreso[89]["valor"] * $json_ingreso[88]["factor_estandar"]);

    //Suma de Bases
    $suma_de_bases=round(($resultado[83][4] + $resultado[90][4] + $resultado[91][4] + $resultado[100][4]),2);

    $valores[103] = $suma_de_bases;
    //CICE
    $CICE=round(($suma_de_bases + $resultado[101][4]),2);
    $valores[104] = $CICE;
    //Saturación de Aluminio
    $saturacion_de_aluminio=round((($resultado[101][4]/$CICE)*100),1);
    $valores[105] = $saturacion_de_aluminio;
    //Saturación de Potasio
    $saturacion_de_potasio=round((($resultado[89][4]/$CICE)*100),1);
    $valores[106] = $saturacion_de_potasio;
    //Saturación de Calcio
    $saturacion_de_calcio=round((($resultado[90][4]/$CICE)*100),1);
    $valores[107] = $saturacion_de_calcio;
    //Saturación de Magnesio
    $saturacion_de_magnesio=round((($resultado[91][4]/$CICE)*100),1);
    $valores[108] = $saturacion_de_magnesio;
    //Relación Calcio - Magnesio
    $relacion_calcio_magnesio=round(($resultado[90][4]/$resultado[91][4]),1);
    $valores[109] = $relacion_calcio_magnesio;
    //Relación Potasio - Magnesio
    $relacion_potasio_magnesio=round(($resultado[89][4]/$resultado[91][4]),1);
    $valores[110] = $relacion_potasio_magnesio;
    
    ksort($valores);
    
    foreach ($valores as $key => $value) {
        //armar el json para guardar los valores
        $json_resultado[$key]=array('id'=>$key,'nombre'=>$factores[$key][1],'valor'=>round($value,$factores[$key][4]),'rango'=>$factores[$key][5]);
    }
    print_r($valores);
    print_r($json_resultado);
    exit;
    //Update a la tabla analisis con el id_muestra y los nuevos valores incluido el json de datos de entrada y el de datos de salida
    if  ($analisis->guardar_resultado_analisis($id_muestra,json_encode($json_ingreso),json_encode($json_resultado),$observaciones) == 1){
        echo $analisis->actualizar_estado_analisis($id_muestra);
        //exit();
        header("location:../view/analisis/resultado-ingreso-analisis.php?id=".$id_muestra);
    } 
    else{
        echo '<h3>ERROR al guardar registro</h3>';        
    }
    exit();
}

if ($_POST["guardar_datos_agua"]){
    
    //$valores=$_POST["valor"];
    $valores1=$_POST["valor"];
    $factor_estandar=$_POST["factor"];
    $factor_dilucion=$_POST["factor2"];

    $id_muestra=$_POST["id_muestra"];
    $observaciones=$_POST["observaciones"];

	//Calcular los valores multiplicados
	foreach ($valores1 as $key => $value) {
		$valores[$key] = ($value * $factor_estandar[$key] * $factor_dilucion[$key]);
        $json_ingreso[$key]=array('valor'=>$value,'factor_estandar'=>$factor_estandar[$key],'factor_dilucion'=>$factor_dilucion[$key]);
    }

	//calcular los campos con formulas
	$valores[113]=round($valores[112] * 640,0);
	$valores[115]=round((($valores[119]*2.5) + ($valores[120]*4.15)) / 10, 2);
	$valores[126]=round(($valores[116] / 14), 2);
	$valores[127]=round(($valores[117] / 14), 2);
	$valores[128]=round(($valores[118] / 39.1), 2);
	$valores[129]=round(($valores[119] / 20.04), 2);
	$valores[130]=round(($valores[120] / 12.16), 2);
	$valores[131]=round(($valores[121] / 23), 2);
	$valores[134]=round(($valores[124] / 48), 2);
	$valores[114]=round($valores[131] / (sqrt(($valores[129] + $valores[130]) / 2)), 2);
	$valores[122]=round(($valores[132] * 50), 2);
	$valores[123]=round(($valores[133] * 61), 2);
    $valores[125]=round(($valores[125] * 0.01 * 35450)/5, 2);
    $valores[135]=round(($valores[125] / 35), 2);
    
	//var_export($valores);

    $analisis=new analisisModel;
    $instancia=$analisis->obtener_analisis($id_muestra);
    $factores=$analisis->variable_tipo_analisis2($instancia->tipo_analisis_id);

    ksort($valores);
    $salida="<table class='table'>";
    foreach ($valores as $key => $value) {
        $salida.='<tr><td>'.$factores[$key][1].'</td><td>'.$factores[$key][3].'</td><td>'.round($value,$factores[$key][4]).'</td><td>'.$factores[$key][5].'</td></tr>';
        //armar el json para guardar los valores
        $json_resultado[$key]=array('id'=>$key,'nombre'=>$factores[$key][1],'valor'=>round($value,$factores[$key][4]),'rango'=>$factores[$key][5]);
    }
    $salida.="<table>";
    //echo $salida;

    if  ($analisis->guardar_resultado_analisis($id_muestra,json_encode($json_ingreso),json_encode($json_resultado),$observaciones) ==1){
        echo $analisis->actualizar_estado_analisis($id_muestra);
        //exit();
        header("location:../view/analisis/resultado-ingreso-analisis.php?id=".$id_muestra);
    } 
    else{
        
        echo '<h3>ERROR al guardar registro</h3>';        
    }

    //Mostrar el resultado para boton de guardar
    //Verificar estado de el analisis con sus submuestras
	exit;
}


if ($_POST["guardar_nuevo"]==1){
    //obtener campos POST Para guardar el registro de Análisis
    if ($_POST["tipo_analisis"] == 18) {
        if ($_POST["nombre_1"]<>"") $n_muestras=1;
        if ($_POST["nombre_2"]<>"") $n_muestras=2;
        if ($_POST["nombre_3"]<>"") $n_muestras=3;
        if ($_POST["nombre_4"]<>"") $n_muestras=4;        
    }elseif ($_POST["tipo_analisis"] == 3) {
        if ($_POST["profundidad_1"]<>"") $n_muestras=1;
        if ($_POST["profundidad_2"]<>"") $n_muestras=2;
        if ($_POST["profundidad_3"]<>"") $n_muestras=3;
        if ($_POST["profundidad_4"]<>"") $n_muestras=4;
    }else
        $n_muestras=1;

    $analisis=new analisisModel();
    echo $analisis->guardar_analisis($_POST,$n_muestras);
    
    //revisar por casos el ingreso de variables de suelo, agua, foliar y fertilizante
    switch ($_POST["tipo_analisis"]) {
        case '1':
            //analisis foliar
            //guardar datos de foliar  
            $analisis->guarda_muestra_foliar($_POST["id_analisis1"],$_POST["especie"],$_POST["variedad"],$_POST["estado_fenologico"]);
            break;
        case '18':
            //analisis de agua revisar cuantos fuentes
            if ($_POST["nombre_1"]<>"")  $analisis->guarda_muestra_agua($_POST["id_analisis1"],$_POST["nombre_1"],$_POST["origen_1"],$_POST["emisor_1"]);
            if ($_POST["nombre_2"]<>"")  $analisis->guarda_muestra_agua($_POST["id_analisis1"],$_POST["nombre_2"],$_POST["origen_2"],$_POST["emisor_2"]);
            if ($_POST["nombre_3"]<>"")  $analisis->guarda_muestra_agua($_POST["id_analisis1"],$_POST["nombre_3"],$_POST["origen_3"],$_POST["emisor_3"]);
            if ($_POST["nombre_4"]<>"")  $analisis->guarda_muestra_agua($_POST["id_analisis1"],$_POST["nombre_4"],$_POST["origen_4"],$_POST["emisor_4"]);
            break;

        case '3':
            //analisis de suelo validar profundidades   
            if ($_POST["profundidad_1"]<>"") $analisis->guarda_muestra_suelo($_POST["id_analisis1"],"Profundidad_1:".$_POST["profundidad_1"],$_POST["profundidad_1"]);
            if ($_POST["profundidad_2"]<>"") $analisis->guarda_muestra_suelo($_POST["id_analisis1"],"Profundidad_2:".$_POST["profundidad_2"],$_POST["profundidad_2"]);
            if ($_POST["profundidad_3"]<>"") $analisis->guarda_muestra_suelo($_POST["id_analisis1"],"Profundidad_3:".$_POST["profundidad_3"],$_POST["profundidad_3"]);
            if ($_POST["profundidad_4"]<>"") $analisis->guarda_muestra_suelo($_POST["id_analisis1"],"Profundidad_4:".$_POST["profundidad_4"],$_POST["profundidad_4"]);

            break;
        case '4':
            //analisis fertilizante    
            //ingresar tipo de fertilizante
            $analisis->guarda_muestra_fertilizante($_POST["id_analisis1"],$_POST["fuente"]);
            break;
    }
    header("location:../view/analisis/ingreso-nuevo.php?id=exito");
}
if ($_POST["aprobar_analisis"]==1){
    $analisis=new analisisModel();
    $analisis->aprobar_analisis($_POST["id_analisis"]);
    header("location:../view/analisis/revisar-analisis.php?id=".$_POST["id_analisis"]."&estado=exito");
}
 
if ($_POST["rechazar_analisis"]==1){
    $analisis=new analisisModel();      
    $analisis->rechazar_analisis($_POST["id_analisis"],$_POST["motivo_rechazo"]);
    header("location:../view/analisis/revisar-analisis.php?id=".$_POST["id_analisis"]."&estado=rechazo");
}

if ($_POST["guardar_batch"]==1){
    //echo "procesando guardar batch";
    //recorrer por tipo de analisis
    
    $tipo_analisis_texto="";
    $analisis=new analisisModel();      
    $id_batch=$analisis->obtener_ultimo_batch()+1;


    foreach ($_POST["sel"] as $value) {
        $value_2=split(";", $value);
        $tipo_analisis_texto=$tipo_analisis_texto.$value_2[2]."(".$value_2[3].")</br>";
        $tipo_analisis_descripcion=$tipo_analisis_descripcion.$value_2[4]."(".$value_2[3].")</br>";
        $id_tipo_analisis=$value_2[1];

        $res=$analisis->actualiza_batch_muestra($value_2[0],$id_batch);
    }

    //crear el nuevo batch
    $analisis->crear_nuevo_batch($id_batch,$_SESSION["rut"],$id_tipo_analisis, $tipo_analisis_texto,$tipo_analisis_descripcion);

    header("location:../view/analisis/nuevo-batch.php?id=exito&tipo_analisis=".$id_tipo_analisis);
}

if ($_GET["accion"]=="eliminar_analisis_batch"){
    //echo "procesando guardar batch";
    //recorrer por tipo de analisis
    
    $analisis=new analisisModel();      
    $id_batch=$analisis->desvincular_de_batch($_GET["id_eliminar"]);
    if ($id_batch==1) $res="exito"; 
    else $res="error";
    header("location:../view/analisis/modifica-detalle-batch.php?res=".$res."&id=".$_GET["id_batch"]);
    exit;
}

if ($_GET["accion"]=="elimina_batch"){
    //recorrer por tipo de analisis
    
    $analisis=new analisisModel();      
    $id_batch=$analisis->eliminar_batch($_GET["id_eliminar"]);
    if ($id_batch==1) $res="exito_elimina"; 
    else $res="error";
    header("location:../view/analisis/ingreso-batch.php?res=".$res);
    exit;
}

if ($_POST["guardar_parcial"]==1){
    //recorrer POST para cada id de muestra y armar el json para actualizar del registro
    $datos=$_POST["valores_ingreso"];
    $factor_dilucion=$_POST["factor_dilucion"];
    $obs=$_POST["observacion"];
    $resultado=$_POST["resultado"];
    
    $analisis = new analisisModel();

    foreach ($datos as $key => $value){
        $arr=array();
        $todos=0;
        $parcial=0;
        $completado=0;
        foreach ($value as $key3 => $dato){
            $todos++;
            if ($datos[$key][$key3]<>"") $parcial++;

            $arr[]=array($key3,$datos[$key][$key3],$factor_dilucion[$key][$key3],$obs[$key][$key3],$resultado[$key][$key3]);
            
            echo "<br/>".$key." ".$key3." ".$datos[$key][$key3]." ".$factor_dilucion[$key][$key3]." ".$resultado[$key][$key3]." ".$obs[$key][$key3];
        }
        $completado=round(($parcial/$todos)*100);
        $resultado_guardar=$analisis->actualiza_ingreso_parcial($key,json_encode($arr),$completado);
    }

    header("location:../view/analisis/ingreso-batch.php?res=exito");
    /*
    foreach ($datos as $key => $value) {
        $keys[]=$key;
        $arr=array();
        $todos=0;
        $parcial=0;
        $completado=0;
        foreach ($variables as $key2 => $value2) {
            $todos++;
            if ($value[$key2]<>"") $parcial++;
            $arr[]=array($key2,$value[$key2],$factor_dilucion[$key][$key2],$obs[$key][$key2]);
        }
        $completado=round(($parcial/$todos)*100);
        $analisis->actualiza_ingreso_parcial($key,json_encode($arr),$completado);
    }
    header("location:../view/analisis/ingreso-batch.php?id=exito");
    */
}

if ($_POST["guardar_total"]==1){
    //recorrer POST para cada id de muestra y armar el json para actualizar del registro
    $datos=$_POST["valores_ingreso"];
    $factor_dilucion=$_POST["factor_dilucion"];
    $reprocesado=$_POST["tipo_rechazado"]; //1= reprocesado 2=no reprocesado
    
    $obs=$_POST["observacion"];
    $id_batch=$_POST["batch_id"];

    $analisis = new analisisModel();
    $tipo_muestra=$analisis->obtener_id_tipo_batch($id_batch);
    $_POST["tipo_muestra"]=$tipo_muestra;
    
    if ($tipo_muestra==9){
        //analisis foliar traer variables con especie y estado fenologico
        $variables=$analisis->variable_tipo_analisis($_POST["tipo_muestra"],$_POST["especie_id"],$_POST["estado_fenologico"]);    
    }
    else{
        $variables=$analisis->variable_tipo_analisis($_POST["tipo_muestra"],0,0);    
    }
    //$variables=$analisis->variable_tipo_analisis($tipo_muestra);

    echo "tipo_muestra:".$tipo_muestra."<br/>";
    echo "especie:".$_POST["especie_id"]."<br/>";
    echo "eestado_fenologico:".$_POST["estado_fenologico"]."<br/>";

    echo "VARIABLES:<br/>";
    var_dump($variables);
    


    foreach ($datos as $key => $value) {
        $valores=array();
        $todos=0;
        $parcial=0;
        foreach ($variables as $key2 => $value2) {
            $todos++;
            if ($value[$key2]<>"") $parcial++;
            if (!$value2[2]) $value2[2]=1;

            $arr[]=array($key2,$value[$key2],$factor_dilucion[$key][$key2],$obs[$key][$key2]);
            $valores[$key2]=($value[$key2]*$factor_dilucion[$key][$key2]*$value2[2]);
            
            //para solucion suelo guardar valor ingreso y factor 1 igual que el anterior
            $valor_ingreso[$key2]=$value[$key2];
            $valores_factor_1[$key2]=$valores[$key2];
            $orden[$key2]=$value2[6];

            $factor_std[$key2]=$value2[2];

            $observaciones[$key2]=$obs[$key][$key2];
            $valores_foliar[$key2]=$value[$key2];
            echo "<br> id:".$key2."=>".$value[$key2]."*".$factor_dilucion[$key][$key2]."*".$value2[2]."=".$valores[$key2]." ORDEN:". $orden[$key2];
        }
       
        $analisis->actualiza_ingreso_parcial($key,json_encode($arr),round(($parcial/$todos)*100));
        
        //calculos adicionales por agua, suelo y foliar
        //calcular los campos con formulas
        if (($_POST["tipo_muestra"]==1) or ($_POST["tipo_muestra"]==9) or ($_POST["tipo_muestra"]==10)  or ($_POST["tipo_muestra"]==11) or ($_POST["tipo_muestra"]==12))
            $tipo="foliar";
        elseif (($_POST["tipo_muestra"]==3) or ($_POST["tipo_muestra"]==5) or ($_POST["tipo_muestra"]==6)  or ($_POST["tipo_muestra"]==7) or ($_POST["tipo_muestra"]==8))
            $tipo="suelo";
        elseif (($_POST["tipo_muestra"]==4) or ($_POST["tipo_muestra"]==26))
            $tipo="fertilizante";
        elseif (($_POST["tipo_muestra"]==13) or ($_POST["tipo_muestra"]==14))
            $tipo="fruto";
        elseif ($_POST["tipo_muestra"]==19)
            $tipo="solucion_suelo";
        elseif ($_POST["tipo_muestra"]==18)
            $tipo="analisis_quimico_agua";
        elseif ($_POST["tipo_muestra"]==20)
            $tipo="solucion_emisor";
        else
            $tipo="otro";

        //suelo
        if ($tipo=="suelo"){
            //potasio intercambiable (89) = valor lectura * 0.0512
            //potasio disponible (88) = potasio intercambiable * 391 *** ojo quedo el valor fijo ***
            $valores[88] = ($valores[89] * 391);

            
            if ($_POST["tipo_muestra"]<> 5){
                //campos calculados
                //Potasio disponible
                //$valores[88] = ($valores[89] * $factor_std[88]);

                //Suma de Bases
                $suma_de_bases=round(($valores[89] + $valores[90] + $valores[91] + $valores[100]),2);
                $valores[103] = $suma_de_bases;
                //CICE
                $CICE=round(($suma_de_bases + $valores[101]),2);           
                $valores[104] = $CICE;

                if  (($_POST["tipo_muestra"] == 8) or ($_POST["tipo_muestra"] == 3)){
                    //Saturación de Aluminio
                    $saturacion_de_aluminio=round((($valores[101]/($valores[89] + $valores[90] + $valores[91] + $valores[100] + $valores[101]))*100),1);
                    $valores[105] = $saturacion_de_aluminio;
                    //Saturación de Potasio
                    $saturacion_de_potasio=round((($valores[89]/($valores[89] + $valores[90] + $valores[91] + $valores[100] + $valores[101]))*100),1);
                    $valores[106] = $saturacion_de_potasio;
                    //Saturación de Calcio
                    $saturacion_de_calcio=round((($valores[90]/($valores[89] + $valores[90] + $valores[91] + $valores[100] + $valores[101]))*100),1);
                    $valores[107] = $saturacion_de_calcio;
                    //Saturación de Magnesio
                    $saturacion_de_magnesio=round((($valores[91]/($valores[89] + $valores[90] + $valores[91] + $valores[100] + $valores[101]))*100),1);
                    $valores[108] = $saturacion_de_magnesio;
                    //Saturación de Sodio
                    $saturacion_de_sodio=round((($valores[100]/($valores[89] + $valores[90] + $valores[91] + $valores[100] + $valores[101]))*100),1);
                    $valores[204] = $saturacion_de_sodio;
                    //Relación Calcio - Magnesio
                    $relacion_calcio_magnesio=round(($valores[90]/$valores[91]),1);
                    $valores[109] = $relacion_calcio_magnesio;
                    //Relación Potasio - Magnesio
                    $relacion_potasio_magnesio=round(($valores[89]/$valores[91]),1);
                    $valores[110] = $relacion_potasio_magnesio;
                }
            }

            $factores=$analisis->variable_tipo_analisis2($_POST["tipo_muestra"]);

            ksort($valores);
            
            $json_resultado=array();

            foreach ($valores as $key2 => $value2) {
                $json_resultado[$key2]=array('id'=>$key2,'nombre'=>$factores[$key2][1],'unidad'=>$factores[$key2][3],'valor'=>round($value2,$factores[$key2][4]),'rango'=>$factores[$key2][5],'obs'=>$observaciones[$key2],'orden'=>$factores[$key2][6]);
            }
            //echo "<br>Valores resultado:<pre>".print_r($json_resultado);
            //exit;


            $resultado_guardar_analisis=$analisis->guardar_resultado_analisis($key,$json_resultado,$observaciones,$reprocesado);
            //echo "<br>resultado_guardar_analisis:".$resultado_guardar_analisis;

            if  ( $resultado_guardar_analisis== 1){
                echo $analisis->actualizar_estado_analisis($key);
                //exit();
                //header("location:../view/analisis/resultado-ingreso-analisis.php?id=".$id_muestra);
            } 
            else{
                echo '<h3>ERROR al guardar registro</h3>';        
            }

        }

        //foliar
        if ($tipo=="foliar"){
            //buscar los rangos de suficiencia para el analisis foliar
            $factores=$analisis->variable_tipo_analisis2($_POST["tipo_muestra"]);
            //echo $_POST["especie"],$_POST["estado_fenologico"];
            $rangos_foliar=$analisis->obtener_rangos_foliares($key,$_POST["tipo_muestra"]);
            
            foreach ($valores as $key2 => $value2) {
                //$json_resultado[$key2]=array('id'=>$key2,'nombre'=>$factores[$key2][1],'unidad'=>$factores[$key2][3],'valor'=>round($value2,$factores[$key2][4]),'min'=>$rangos_foliar[$key2][0],'max'=>$rangos_foliar[$key2][1],'rango'=>$rangos_foliar[$key2][0]." - ".$rangos_foliar[$key2][1],'obs'=>$observaciones[$key2]);

                $json_resultado[$key2]=array('id'=>$key2,'nombre'=>$factores[$key2][1],'unidad'=>$factores[$key2][3],'valor'=>round($value2,$factores[$key2][4]),'rango'=>$rangos_foliar[$key2][2],'obs'=>$observaciones[$key2],'valor_ingreso'=>$valor_ingreso[$key2], 'orden'=>$orden[$key2]);
            }
            if  ($analisis->guardar_resultado_analisis($key,$json_resultado,$observaciones,$reprocesado) == 1){
                echo $analisis->actualizar_estado_analisis($key);
                //header("location:../view/analisis/resultado-ingreso-analisis.php?id=".$id_muestra);
            } 
            else{
                echo '<h3>ERROR al guardar registro</h3>';        
            }
        }      

        if ($tipo=="solucion_suelo"){
            
            //valores respuesta
            $valores[164]=round($valores[117] + $valores[116],0);
            $valores_factor_1[164]=round($valores[117] + $valores[116],0);

            $valores[128]=round(($valores[118] / 39.1), 2);
            $valores[129]=round(($valores[119] / 20.04), 2);
            $valores[130]=round(($valores[120] / 12.16), 2);
            $valores[131]=round(($valores[121] / 23), 2);
            
            $valores[126]=round(($valores[116] / 14), 2);
            $valores[127]=round(($valores[117] / 14), 2);
            
            $valores[134]=round(($valores[74] / 48), 2);
            $valores[135]=round(($valores[125] / 35), 2);
            
            $valores[132]=round(($valores[132]), 2);
            $valores[133]=round(($valores[133]), 2);
            

            //***** sumar los miliequivalentes colocar "BALANCE" en Factor 1
            //carbonato y bicarbonato se ingresa

            //suma de cationes Potasio+calcio+magnesio+sodio+amonio
            $valores[216]=round(($valores[128]+$valores[129]+$valores[130]+$valores[131]+$valores[126]),2);
            $valores_factor_1[216]=round(($valores[128]+$valores[129]+$valores[130]+$valores[131]+$valores[126]),2);
            
            //suma de aniones Nitrato+azufre+cloruro+carbonato+bicarbonato
            $valores[217]=round(($valores[127]+$valores[134]+$valores[135]+$valores[132]+$valores[133]),2);
            $valores_factor_1[217]=round(($valores[127]+$valores[134]+$valores[135]+$valores[132]+$valores[133]),2);




            // valores internos
            // calcular el valor ingreso por el factor 1 interno
            $valores_factor_1[213]=$valor_ingreso[213]*1;
            $valores_factor_1[214]=$valor_ingreso[214]*1;
            $valores_factor_1[112]=$valor_ingreso[112]*0.001;
            $valores_factor_1[117]=$valor_ingreso[117]*1;
            $valores_factor_1[116]=$valor_ingreso[116]*1;
            $valores_factor_1[164]=$valor_ingreso[117]+$valor_ingreso[116];
            $valores_factor_1[215]=$valor_ingreso[215]*1;
            $valores_factor_1[118]=$valor_ingreso[118]*5;
            $valores_factor_1[119]=$valor_ingreso[119]*5;
            $valores_factor_1[120]=$valor_ingreso[120]*5;
            $valores_factor_1[74]=$valor_ingreso[74]*1;
            $valores_factor_1[125]=$valor_ingreso[125]*1;
            $valores_factor_1[121]=$valor_ingreso[121]*5;
            $valores_factor_1[137]=$valor_ingreso[137]*1;
            $valores_factor_1[138]=$valor_ingreso[138]*1;
            $valores_factor_1[139]=$valor_ingreso[139]*1;
            $valores_factor_1[140]=$valor_ingreso[140]*1;
            $valores_factor_1[141]=$valor_ingreso[141]*1;
            $valores_factor_1[132]=$valor_ingreso[132]*1;
            $valores_factor_1[133]=$valor_ingreso[133]*1;

            //valores calculados con factor 1
            $valores_factor_1[128]=round(($valores_factor_1[118] / 39.1), 2);
            $valores_factor_1[129]=round(($valores_factor_1[119] / 20.04), 2);
            $valores_factor_1[130]=round(($valores_factor_1[120] / 12.16), 2);
            $valores_factor_1[131]=round(($valores_factor_1[121] / 23), 2);
            $valores_factor_1[126]=round(($valores_factor_1[116] / 14), 2);
            $valores_factor_1[127]=round(($valores_factor_1[117] / 14), 2);
            $valores_factor_1[134]=round(($valores_factor_1[74] / 48), 2);
            $valores_factor_1[135]=round(($valores_factor_1[125] / 35), 2);
            $valores_factor_1[132]=round(($valores_factor_1[132]), 2);
            $valores_factor_1[133]=round(($valores_factor_1[133]), 2);
            
            //suma de cationes Potasio+calcio+magnesio+sodio+amonio
            $valores_factor_1[216]=round(($valores_factor_1[128]+$valores_factor_1[129]+$valores_factor_1[130]+$valores_factor_1[131]+$valores_factor_1[126]),2);
            
            //suma de aniones Nitrato+azufre+cloruro+carbonato+bicarbonato
            $valores_factor_1[217]=round(($valores_factor_1[127]+$valores_factor_1[134]+$valores_factor_1[135]+$valores_factor_1[132]+$valores_factor_1[133]),2);

/*
            foreach ($valores_factor_1 as $key4 => $value4) {
                echo "<br>factor 1 ".$key4." - ".$valor_ingreso[$key4]." - ".$value4." - ".$valores[$key4];
            }

            exit;
*/
            $factores=$analisis->variable_tipo_analisis2($_POST["tipo_muestra"]);

            ksort($valores);
            $json_resultado=array();

            foreach ($valores as $key2 => $value2) {
                $json_resultado[$key2]=array('id'=>$key2,'nombre'=>$factores[$key2][1],'unidad'=>$factores[$key2][3],'valor'=>round($value2,$factores[$key2][4]),'rango'=>$factores[$key2][5],'obs'=>$observaciones[$key2],'factor_1'=>$valores_factor_1[$key2],'valor_ingreso'=>$valor_ingreso[$key2], 'orden'=>$factores[$key2][6]);
            }
            //7print_r($json_resultado);
            //exit;

            if  ($analisis->guardar_resultado_analisis($key,$json_resultado,$observaciones,$reprocesado) == 1){
                echo $analisis->actualizar_estado_analisis($key);
                //exit();
                //header("location:../view/analisis/resultado-ingreso-analisis.php?id=".$id_muestra);
            } 
            else{
                
                echo '<h3>ERROR al guardar registro</h3>';        
            }
            

        }
        
        if ($tipo=="analisis_quimico_agua"){
            //Potacsio y calcio
            //$valores[118]=$valores[118]*$valores[119];
             
            //analisis de agua

            $valores[113]=round($valores[112] * 640,0);
            $valores[115]=round((($valores[119]*2.5) + ($valores[120]*4.15)) / 10, 2);
            $valores[126]=round(($valores[116] / 14), 2);
            $valores[127]=round(($valores[117] / 14), 2);
            $valores[128]=round(($valores[118] / 39.1), 2);
            $valores[129]=round(($valores[119] / 20.04), 2);
            $valores[130]=round(($valores[120] / 12.16), 2);
            $valores[131]=round(($valores[121] / 23), 2);
            $valores[134]=round(($valores[124] / 48), 2);
            $valores[114]=round($valores[131] / (sqrt(($valores[129] + $valores[130]) / 2)), 2);
            $valores[122]=round(($valores[132] * 50), 2);
            $valores[123]=round(($valores[133] * 61), 2);
            $valores[125]=round(($valores[125] * 0.01 * 35450)/5, 2);
            $valores[135]=round(($valores[125] / 35), 2);
            
            $factores=$analisis->variable_tipo_analisis2($_POST["tipo_muestra"]);
/*            
            echo "analisis solucion riego<br>".$_POST["tipo_muestra"];
            print_r($valores);
            exit;
*/
            ksort($valores);
            $json_resultado=array();

            foreach ($valores as $key2 => $value2) {
                $json_resultado[$key2]=array('id'=>$key2,'nombre'=>$factores[$key2][1],'unidad'=>$factores[$key2][3],'valor'=>round($value2,$factores[$key2][4]),'rango'=>$factores[$key2][5],'obs'=>$observaciones[$key2]);
            }
            //print_r($json_resultado);
            //exit;

            if  ($analisis->guardar_resultado_analisis($key,$json_resultado,$observaciones,$reprocesado) == 1){
                echo $analisis->actualizar_estado_analisis($key);
                //exit();
                //header("location:../view/analisis/resultado-ingreso-analisis.php?id=".$id_muestra);
            } 
            else{
                
                echo '<h3>ERROR al guardar registro</h3>';        
            }
        }

        if ($tipo=="solucion_emisor"){
            //analisis solucion riego 

            $factores=$analisis->variable_tipo_analisis2($_POST["tipo_muestra"]);

            ksort($valores);
            $json_resultado=array();

            foreach ($valores as $key2 => $value2) {
                $json_resultado[$key2]=array('id'=>$key2,'nombre'=>$factores[$key2][1],'unidad'=>$factores[$key2][3],'valor'=>round($value2,$factores[$key2][4]),'rango'=>$factores[$key2][5],'obs'=>$observaciones[$key2]);
            }
            //print_r($json_resultado);
            //exit;

            if  ($analisis->guardar_resultado_analisis($key,$json_resultado,$observaciones,$reprocesado) == 1){
                echo $analisis->actualizar_estado_analisis($key);
                //exit();
                //header("location:../view/analisis/resultado-ingreso-analisis.php?id=".$id_muestra);
            } 
            else{
                
                echo '<h3>ERROR al guardar registro</h3>';        
            }
        }

        //fruto
        if ($tipo=="fruto"){
            $factores=$analisis->variable_tipo_analisis2($_POST["tipo_muestra"]);
            ksort($valores);
            $json_resultado=array();

            //Multiplicar el resultado de variable por el valor de materia seca
            $valor_materia_seca=$valores[148];
            foreach ($valores as $key2 => $value2) {
                echo "<br>". $key2 ."=>". $value2;
                $valor_resultado=$value2;
                if (($key2==146) or ($key2==147) or ($key2==148) or ($key2==149)){
                    $valor_resultado=$value2;
                }
                else{
                    $valor_resultado=($value2*$valor_materia_seca);
                }

                $json_resultado[$key2]=array('id'=>$key2,'nombre'=>$factores[$key2][1],'unidad'=>$factores[$key2][3],'valor'=>round($valor_resultado,$factores[$key2][4]),'rango'=>$factores[$key2][5],'obs'=>$observaciones[$key2],'valor_materia_seca'=>$valor_materia_seca);
            }
            //print_r($json_resultado);
            //exit();

            if  ($analisis->guardar_resultado_analisis($key,json_encode($json_resultado),$observaciones,$reprocesado) == 1){
                echo $analisis->actualizar_estado_analisis($key);
                //exit();
                //header("location:../view/analisis/resultado-ingreso-analisis.php?id=".$id_muestra);
            } 
            else{
                echo '<h3>ERROR al guardar registro</h3>';        
            }
            
        }    

        if ($tipo=="otro"){
            //Calcular solo lo que corresponde
            $factores=$analisis->variable_tipo_analisis2($_POST["tipo_muestra"]);

            foreach ($valores as $key2 => $value2) {
                $json_resultado[$key2]=array('id'=>$key2,'nombre'=>$factores[$key2][1],'unidad'=>$factores[$key2][3],'valor'=>round($value2,$factores[$key2][4]),'min'=>$rangos_foliar[$key2][0],'max'=>$rangos_foliar[$key2][1],'rango'=>$rangos_foliar[$key2][0]." - ".$rangos_foliar[$key2][1],'obs'=>$observaciones[$key2]);
            }            
        }
    }

    //print_r($json_resultado);
    //exit();
    
    //cambiar estado al batch
    $analisis->actualiza_estado_batch($_POST["batch_id"]);

    header("location:../view/analisis/resultado-detalle-batch-v3.php?id=".$_POST["batch_id"]);
}

if ($_GET["traer_tabla"]=="Ingresados"){
    //echo "procesando guardar batch";
    //recorrer por tipo de analisis
    
    $analisis=new analisisModel();
    echo $analisis->tabla_revision_3();
    exit;
}
if ($_POST["ajax_variables_analisis"]==1){

    $analisis=new analisisModel();
    $valores=$analisis->variable_tipo_analisis($_POST["analisis_id"]);
 
    $salida="<table class='table'>
                <th>Sel.</th>
                <th>Nombre</th>";
    foreach ($valores as $key => $value) {
        $salida.="<tr><td><input type='checkbox' name='sel[$key]' id='sel[$key]' value='$value[0]' CHECKED>
                <td>".$value[1]."</td></tr>";
    }
    $salida.="</table>";
    echo $salida;

}

if ($_POST["ajax_analisis_custom"]==1){
    $analisis=new analisisModel();
    $result=$analisis->guarda_analisis_custom($_POST["tipo_analisis_custom"]);
    echo $result;
}
if ($_POST["ajax_guarda_variable_analisis_custom"]==1){
    $analisis=new analisisModel();

    $result=$analisis->guarda_variable_analisis_custom($analisis->obtener_ultimo_id(),$_POST["variables_analisis"]);
    echo $result;
}



if ($_POST["ajax_obtiene_variables_analisis"]==1){
    $analisis=new analisisModel();
    $result=$analisis->leer_analisis_custom();

    //echo json_encode(array_map("utf8_encode", $result));
    echo json_encode($result);

}

if ($_POST["ajax_rechaza_muestra"]==1){
    $analisis=new analisisModel();
    $result=$analisis->rechazar_analisis($_POST["id"],$_POST["motivo_rechazo"]);
    //obtener id batch
    //guardar nuevo batch ... marcar como rechazado para poner al comienzo de la lista
    $id_batch=$analisis->obtener_ultimo_batch()+1;

    //crear el nuevo batch
    $analisis->crear_nuevo_batch($id_batch,$_SESSION["rut"],$_POST["tipo_analisis"], "RECHAZADO",$_POST["motivo_rechazo"]);
    $analisis->actualiza_batch_muestra($_POST["analisis_muestra_id"],$id_batch);    
    echo $result;
}


if ($_POST["guardar_nuevo_analisis"]==1){
    $analisis=new analisisModel();
    $detalles=json_decode(utf8_encode($_POST["detalle_analisis"]),true);
    /*
    echo "en guardar_nuevo_analisis<br>";
    print_r($detalles);
    
    foreach ($detalles as $key => $value) {
        echo "<br>".$key."=>".$value["Tipo Análisis"];
        echo "<br>DATA ----->>> ".$key."=>".$value["Zona / Nombre Muestra"];


    }
    //exit();
    */

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
        $datos_para_analisis["observaciones"]=$_POST["observaciones"];
        $datos_para_analisis["zona"]=$value["id_nombre_muestra_reporte"];
        $datos_para_analisis["ultimo_id_programacion"]=$ultimo_id_programacion;
        $datos_para_analisis["estado_trabajo"]=1;
        $datos_para_analisis["profundidad_1"]=$_POST["profundidad_1"];
        $datos_para_analisis["nombre_muestra_reporte"]=$value["Zona / Nombre Muestra"];
        
        
        
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
        if ($_POST["tipo_analisis"]==20)
            $tipo="emisor";
               
        switch ($tipo) {
            case 'foliar':
                //analisis foliar
                //guardar datos de foliar  

                $analisis->guarda_muestra_foliar($ultimo_id_analisis,$value["d1"],$value["d2"],$value["d3"],$value["id_nombre_muestra_reporte"]);
                break;
            case 'agua':
                //analisis de agua revisar cuantos fuentes
                if ($_POST["nombre_1"]<>"")  $analisis->guarda_muestra_agua($ultimo_id_analisis,$_POST["nombre_1"],$_POST["origen_1"],$_POST["emisor_1"]);
                if ($_POST["nombre_2"]<>"")  $analisis->guarda_muestra_agua($ultimo_id_analisis,$_POST["nombre_2"],$_POST["origen_2"],$_POST["emisor_2"]);
                if ($_POST["nombre_3"]<>"")  $analisis->guarda_muestra_agua($ultimo_id_analisis,$_POST["nombre_3"],$_POST["origen_3"],$_POST["emisor_3"]);
                if ($_POST["nombre_4"]<>"")  $analisis->guarda_muestra_agua($ultimo_id_analisis,$_POST["nombre_4"],$_POST["origen_4"],$_POST["emisor_4"]);
                break;

            case 'suelo':
                //analisis de suelo validar profundidades   
                if ($value["d1"]<>"") $analisis->guarda_muestra_suelo($ultimo_id_analisis,$value["Zona / Nombre Muestra"],$value["d1"],$value["id_nombre_muestra_reporte"]);
                if ($value["d2"]<>"") $analisis->guarda_muestra_suelo($ultimo_id_analisis,$value["Zona / Nombre Muestra"],$value["d2"],$_POST["nombre_muestra_reporte"]);
                if ($value["d3"]<>"") $analisis->guarda_muestra_suelo($ultimo_id_analisis,$value["Zona / Nombre Muestra"],$value["d3"],$_POST["nombre_muestra_reporte"]);
                if ($value["d4"]<>"") $analisis->guarda_muestra_suelo($ultimo_id_analisis,$value["Zona / Nombre Muestra"],$value["d4"],$_POST["nombre_muestra_reporte"]);
                break;
            case 'fertilizante':
                //analisis fertilizante    
                //ingresar tipo de fertilizante
                $analisis->guarda_muestra_fertilizante($ultimo_id_analisis,$value["d1"]);
                break;
            case 'emisor':
                //analisis fertilizante    
                //ingresar tipo de fertilizante
                $analisis->guarda_muestra_emisor($ultimo_id_analisis,$value["d1"]);
                break;                
            default:
                //analisis todos
                $analisis->guarda_muestra_analisis($ultimo_id_analisis);
                
                break;
        }
        $ultimo_id_analisis++;
    }
    header("location:../view/planificacion/ingreso-nuevo-dev-2.php?id=1");
}
