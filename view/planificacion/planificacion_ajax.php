<?php

if ($_POST["obtener_eventos_calendario"]==1){
	require_once '../../model/planificacion.model.php';
	$planificacion=new planificacionModel();
	echo json_encode($planificacion->obtener_eventos_calendario_2());
}
?>
