<?php
class planificacionModel
{
	private $pdo;
	
	public function __CONSTRUCT()
	{
		try
		{
			$pdo_options[PDO::ATTR_ERRMODE]=PDO::ERRMODE_EXCEPTION;
			$this->pdo = new PDO('pgsql:host=localhost;dbname=laboratorio','labo_user','R3$L4B0',$pdo_options);
			//$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		        
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function obtener_ultimo_id()
	{
		try {
			//$sql = "select max(id) from analisis;";
			$stm = $this->pdo->prepare("select max(id) as id from programacion;");
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			return $r->id;
		} 
		catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function obtiene_tabla_id($id)
	{
		try {
			//$sql = "select max(id) from analisis;";
			$stm = $this->pdo->prepare("select programacion.id as id_programacion,fecha_programada, TO_CHAR(fecha_programada, 'dd/mm/yyyy') as fecha, cliente.nombre as nombre_cliente, predio_cliente.nombre as nombre_predio, comuna.nombre_comuna as nombre_comuna, predio_zona.nombre as nombre_zona, programacion.observaciones_toma as observacion
										from programacion,cliente,predio_cliente,comuna,predio_zona
										where programacion.cliente_id=cliente.rut
										and programacion.predio_cliente_id=predio_cliente.id
										and comuna.id=predio_cliente.comuna_id
										and predio_zona.id=programacion.predio_zona_id 
										and programacion.id=".$id.";");
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			$salida="<table class='table'>
						<tr><td><strong>Fecha Programación</strong></td><td>".$r->fecha."</td></tr>
						<tr><td><strong>Cliente</strong></td><td>".$r->nombre_cliente."</td></tr>
						<tr><td><strong>Predio</strong></td><td>".$r->nombre_predio."</td></tr>
						<tr><td><strong>Zona</strong></td><td>".$r->nombre_zona."</td></tr>
						
						<tr><td><strong>Comuna</strong></td><td>".$r->nombre_comuna."</td></tr>
						<tr><td><strong>Observaciones</strong></td><td>".$r->observacion."</td></tr>					
					</table>";
			return $salida;
		} 
		catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function obtiene_tabla_detalle($id)
	{
		$fecha_actual=date("Y-m-d");
		try {
			$stm = $this->pdo->prepare("SELECT analisis.id as id, 
										tipo_analisis.nombre as nombre, 
										tipo_analisis.descripcion as descripcion,
										programacion.nombre_responsable as usuario,
										TO_CHAR(analisis.fecha_toma_muestra, 'dd/mm/yyyy') as fecha_programada,
										analisis.estado_trabajo as estado_trabajo,
										estado_trabajo.nombre as estado_trabajo_nombre
										
										FROM analisis, tipo_analisis, programacion, estado_trabajo 
										
										WHERE analisis.tipo_analisis_id = tipo_analisis.id
										AND analisis.estado_trabajo = estado_trabajo.id
										AND programacion.id= analisis.programacion_id
										AND programacion_id=".$id." ORDER BY analisis.fecha_toma_muestra ASC;");
			$stm->execute();
			$salida="<table class='table' id='tabla_detalle_analisis'>
					<th>ID</th>
					<th>Tipo análisis</th>
					<th>Responsable</th>
					<th>Fecha programada</th>
					<th>Fecha de ingreso</th>
					<th>Selecciona</th>
			";
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
				$salida.="<tr>
							<td>".$r->id."</td>
							<td>".$r->nombre."<br>".$r->descripcion."</td>
							<td>".$r->usuario."</td>
							<td>".$r->fecha_programada."</td>";
				if ($r->estado_trabajo > 1)
							$salida.= "<td>".$r->estado_trabajo_nombre."</td></tr>";
				else
							$salida.= "
							<td><input type='date' class='form-control' name='fecha[".$r->id."]' value='".$fecha_actual."' id='".$r->id."' required='required' aria-required='true'></td>
							<td class='text-center'><input class='form-check-input' type='checkbox' name='chk_sel[".$r->id."'] id='chk_sel[".$r->id."'] checked></td>
						  </tr>";
			}

			$salida.="</table>";

			return $salida;
		} 
		catch (Exception $e){
			die($e->getMessage());
		}
	}	
	public function guardar_nuevo($data){
		$usuario_id=$_SESSION["rut"];
		if ($data["resp_muestra"] == 0) $id_responsable=$data["nombre_muestra"];
		if ($data["resp_muestra"] == 1) $id_responsable=$data["usuario_toma_muestra"];
		if ($data["resp_muestra"] == 2) $id_responsable=$data["tercero_toma_muestra"];

		try 
		{
			$sql = "INSERT INTO programacion (fecha_programada, observaciones_toma, usuario_id, cliente_id, predio_cliente_id, predio_zona_id, tipo_responsable,nombre_responsable, estado_programacion)
			        VALUES (?, ?, ?, ?, ?, ?, ?, ?,?)";
			$this->pdo->prepare($sql)
			     ->execute(array($data["fecha_ingreso"], $data["observaciones"], $id_responsable, $data["cliente_id"],$data["predio_cliente_id"],$data["zona"],$data["resp_muestra"],$id_responsable,1));
			//return "Guardado ...".$sql;
		}

		catch (Exception $e) 
		{
			die($e->getMessage());
			//return $e->getMessage();
		}
	}

	public function obtener_eventos_calendario()
	{
		try {
			//$sql = "select max(id) from analisis;";
			$stm = $this->pdo->prepare("select programacion.id as id_programacion, fecha_programada, TO_CHAR(fecha_programada, 'dd/mm/yyyy') as fecha, cliente.nombre as nombre_cliente, predio_cliente.nombre as nombre_predio, comuna.nombre_comuna as nombre_comuna 
										from programacion,cliente,predio_cliente,comuna
										where programacion.cliente_id=cliente.rut
										and programacion.predio_cliente_id=predio_cliente.id
										and comuna.id=predio_cliente.comuna_id;");
			$stm->execute();
			$eventos="";
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
				$title = "{id: ".$r->id_programacion.", title:'(".$r->id_programacion.")". $r->nombre_comuna." - ".$r->nombre_cliente."',";
				$descripcion =  ' description:\'<br>Fecha: '.$r->fecha.								
								'<br>Cliente: '.$r->nombre_cliente.				
								'<br>Predio: '.$r->nombre_predio.
								'<br>Comuna: '.$r->nombre_comuna.
								'<br>Responsable: LB-TRACK / tercero '.
								'<br>Encargado: Nombre del encargado'.'\',';
				$start = " start:'".$r->fecha_programada."'},";
				$eventos.= $title.$descripcion.$start;
			}
			$salida = "[".substr($eventos, 0, -1)."],";
			return $salida;
		} 
		catch (Exception $e){
			die($e->getMessage());
		}
	}
	
	public function obtener_eventos_calendario_2()
	{
		try {
			//$sql = "select max(id) from analisis;";
			$stm = $this->pdo->prepare("select programacion.id as id_programacion, fecha_programada, TO_CHAR(fecha_programada, 'dd/mm/yyyy') as fecha, cliente.nombre as nombre_cliente, predio_cliente.nombre as nombre_predio, comuna.nombre_comuna as nombre_comuna 
										from programacion,cliente,predio_cliente,comuna
										where programacion.cliente_id=cliente.rut
										and programacion.predio_cliente_id=predio_cliente.id
										and comuna.id=predio_cliente.comuna_id;");
			$stm->execute();
			$eventos="";
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
				$salida[]=array("id"=>"$r->id_programacion",
								"title"=>"($r->id_programacion)$r->nombre_comuna -$r->nombre_cliente",
								"description"=>"<br>Fecha: $r->fecha
												<br>Cliente: $r->nombre_cliente
												<br>Predio:  $r->nombre_predio
												<br>Comuna:$r->nombre_comuna",
								"start"=>"$r->fecha_programada");
			}
			return $salida;
		} 
		catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function listar_todos()
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("select programacion.id as id_programacion,fecha_programada, TO_CHAR(fecha_programada, 'dd/mm/yyyy') as fecha, cliente.nombre as nombre_cliente, predio_cliente.nombre as nombre_predio, comuna.nombre_comuna as nombre_comuna 
										from programacion,cliente,predio_cliente,comuna
										where programacion.cliente_id=cliente.rut
										and programacion.predio_cliente_id=predio_cliente.id
										and comuna.id=predio_cliente.comuna_id");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$id = $r->id_programacion;
				$detalle='<a href="#" class="btn btn-sm btn-neutral" data-id="'.$id.'" data-toggle="modal" data-target="#modal_ver_detalle" >Ver detalle</a>';
				$detalle2='<button class="btn btn-sm btn-neutral" id="btn_detalle" onClick="abre_detalles('.$id.')">Ver detalle</button>';
				$editar='<a href="#" class="btn btn-sm btn-neutral" data-id="'.$id.'" data-toggle="modal" data-target="#editar_equipo_modal">EDITAR</a>';
				$eliminar='<a href="#" class="btn btn-sm btn-neutral" data-id="'.$id.'" data-toggle="modal" data-target="#eliminar_equipo_modal">ELIMINAR</a>';				
				$result[]=array($r->fecha,$r->nombre_comuna,$r->nombre_cliente,$r->nombre_predio,$detalle2);
			}

			//$results = $stm->fetchAll(PDO::FETCH_ASSOC);
    		return json_encode($result);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function modificar_fecha_programacion($programacion_id,$nueva_fecha){
		$fecha_actual=date("Y-m-d");
		try 
		{
			$sql = "update programacion set fecha_programada= ?, fecha_reprogramacion=? where id=?;";
			$this->pdo->prepare($sql)
			     ->execute(array($nueva_fecha,$fecha_actual,$programacion_id));
			return $sql;
		}

		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
		//modificar la fecha de todos los tipos de analisis

		try 
		{
			$sql = "update analisis set fecha_programada= ? where programacion_id=?;";
			$this->pdo->prepare($sql)
			     ->execute(array($nueva_fecha,$programacion_id));
			return $sql;
		}

		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
			
	public function obtener_marcadores_mapa($fecha_desde,$fecha_hasta,$responsable)
	{
		$filtro="";
		if ($fecha_desde<>""){
			$filtro.=" AND fecha_programada >='".$fecha_desde."'";
		}
		if ($fecha_hasta<>""){
			$filtro.=" AND fecha_programada <='".$fecha_hasta."'";
		}
		if ($responsable<>""){
			//$filtro.=" AND responsable ='".$responsable."'";
		}		

		try
		{
			$result = array();
			$sql="select programacion.id as id_programacion,fecha_programada, TO_CHAR(fecha_programada, 'dd/mm/yyyy') as fecha, cliente.nombre as nombre_cliente, predio_cliente.nombre as nombre_predio, comuna.nombre_comuna as nombre_comuna, predio_cliente.lat as lat,predio_cliente.lon as lon, usuario.nombre_completo as nombre_completo 
										from programacion,cliente,predio_cliente,comuna, usuario
										where programacion.cliente_id=cliente.rut
										and usuario.rut=programacion.usuario_id 
										and programacion.predio_cliente_id=predio_cliente.id
										and comuna.id=predio_cliente.comuna_id ".$filtro;
			
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{		
				$result[]=array($r->fecha,$r->nombre_comuna,$r->nombre_cliente,$r->nombre_predio,$r->lat,$r->lon,$r->nombre_completo);
			}

			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function actualiza_ingreso_analisis($id,$fecha){
		try 
		{
			$sql = "update analisis set fecha_ingreso= ?, estado_trabajo=3 where id=?;";
			$this->pdo->prepare($sql)
			     ->execute(array($fecha,$id));
			return "exito ".$id." ".$fecha;
		}

		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}		

	}
	
	

}