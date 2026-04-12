<?php
class analisisModel
{
	private $pdo;
	private $analisis;

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
	public function listar_todos()
	{
		try
		{
			$result = array();
			$actual=date("Y-m-d");
			$stm = $this->pdo->prepare("SELECT analisis.id as id, fecha_ingreso, cliente.razon_social as nombre_cliente, tipo_analisis.nombre as tipo_analisis, estado_trabajo.nombre as estado_trabajo, DATE_PART('day',NOW() - fecha_ingreso::timestamp) as dias, tipo_analisis.descripcion as descripcion
				FROM analisis
				INNER JOIN cliente ON cliente.rut=analisis.cliente_id
				INNER JOIN predio_cliente ON predio_cliente.id= analisis.predio_cliente_id
				INNER JOIN estado_trabajo ON estado_trabajo.id=analisis.estado_trabajo and analisis.estado_trabajo=3
				INNER JOIN tipo_analisis ON tipo_analisis.id=analisis.tipo_analisis_id

				ORDER BY fecha_ingreso DESC;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->id,$r->fecha_ingreso,$r->nombre_cliente,$r->tipo_analisis."|".$r->descripcion,$r->estado_trabajo,$r->dias);
			}

    		return json_encode($result);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function tipo_analisis_select()
	{
		try
		{
			$salida = array();

			$stm = $this->pdo->prepare("SELECT id, nombre, descripcion FROM public.tipo_analisis WHERE analisis_custom=false order by id;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$salida.="<option value='".$r->id."'>".$r->nombre." | ".$r->descripcion."</option>";
			}
    		return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function tipo_analisis_dinamico_select()
	{
		try
		{
			$salida = array();

			$stm = $this->pdo->prepare("SELECT id, nombre, descripcion FROM public.tipo_analisis WHERE analisis_custom=true order by id;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$salida.="<option value='".$r->id."'>".$r->nombre." | ".$r->descripcion."</option>";
			}
    		return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function guarda_analisis_custom($tipo_analisis)
	{
	try 
		{
		$fecha=date("Y/m/d");
		$tipo_custom=true;
		
		
		$sql = "INSERT INTO tipo_analisis (nombre, analisis_custom) 
				VALUES (?,?)";
		$this->pdo->prepare($sql)
		     ->execute(array($tipo_analisis,$tipo_custom));
		return "Exito";
		}
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
	}	

	public function guarda_variable_analisis_custom($ultimo_id,$variables_analisis)
	{
	try 
		{
		$fecha=date("Y/m/d");
		$tipo_custom=true;
		
		foreach ($variables_analisis as $key=>$value){
			
			//traer la variable desde la de la tabla
			$sql="SELECT formula, valor_minimo, valor_maximo, numero_decimales, nivel_suficiencia
				FROM public.variable_tipo_analisis
				WERE variable_id=$value 
				LIMIT 1";
			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			
			//hacer el insert de cada variable
			$sql_insert="INSERT INTO variable_tipo_analisis(formula,valor_minimo,valor_maximo, tipo_analisis_id,variable_id,numero_decimales,nivel_suficiencia) 
				VALUES (?,?,?,?,?,?,?)";
			$this->pdo->prepare($sql)
		     ->execute(array($r->formula,$r->valor_minimo,$r->valor_maximo,$ultimo_id,$value,$r->numero_decimales,$r->nivel_suficiencia));	
		}
		}
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
	}	


	public function leer_analisis_custom()
	{
	try 
		{
			$result = array();
			$stm = $this->pdo->prepare("SELECT id, nombre FROM public.tipo_analisis WHERE analisis_custom=true;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->id,$r->nombre);
			}
	    return $result;
	  }
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
	}	

	public function tipo_especie_select()
	{
		try
		{
			$salida = array();

			$stm = $this->pdo->prepare("SELECT id, nombre FROM public.especie order by nombre;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$salida.="<option value='".$r->id."'>".$r->nombre."</option>";
			}
    		return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function estado_fenologico_select()
	{
		try
		{
			$result = array();
			$stm = $this->pdo->prepare("SELECT distinct public.variable_tipo_analisis.especie_id as id,public.estado_fenologico.nombre as nombre,  public.estado_fenologico.id as id_estado
				from public.variable_tipo_analisis,public.estado_fenologico 
				where public.variable_tipo_analisis.estado_fenologico_id=public.estado_fenologico.id 
				order by public.estado_fenologico.id;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->id,$r->nombre,$r->id_estado);
			}
    		return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function guardar_analisis($data,$n_muestras)
	{
		try 
		{
		if (!$data["estado_trabajo"]) $data["estado_trabajo"]=3;
		$sql = "INSERT INTO analisis (id,fecha_toma_muestra,fecha_ingreso,tipo_analisis_id,predio_cliente_id,estado_trabajo,usuario_ingreso,usuario_toma_muestra,cliente_id,observacion, total_muestras, total_muestras_ingresadas,predio_zona_id,programacion_id,profundidad_analisis)
		        VALUES (?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?)";
		$this->pdo->prepare($sql)
		     ->execute(array($data["id_analisis1"],$data["fecha_toma_muestra"],$data["fecha_ingreso"],$data["tipo_analisis"],$data["predio_cliente_id"],$data["estado_trabajo"],$_SESSION["rut"],$data["nombre_muestra"],$data["cliente_id"],$data["observaciones"],$n_muestras,0,$data["zona"],$data["ultimo_id_programacion"],$data["profundidad_1"]));
		$salida_sql = $data["id_analisis1"]." | ".$data["fecha_toma_muestra"]." | ".$data["fecha_ingreso"]." | ".$data["tipo_analisis"]." | ".$data["predio_cliente_id"]." | 3 | ".$_SESSION["rut"]." | ".$data["nombre_muestra"]." | ".$data["cliente_id"]." | ".$data["observaciones"]." | ".$n_muestras." | 0 | ".$data["zona"]." | ".$data["ultimo_id_programacion"];
		return $salida_sql;
		}
		
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
	public function guarda_muestra_foliar($id, $especie,$variedad,$estado_fenologico,$nombre_muestra)
	{
		$codigo="FQ-".$id;

		try 
		{
		$sql = "INSERT INTO analisis_muestra (codigo,especie,variedad,estado_fenologico, analisis_id,nombre)
		        VALUES (?, ?, ?, ?, ?,?)";
		$this->pdo->prepare($sql)
		     ->execute(array($codigo,$especie,$variedad,$estado_fenologico, $id,$nombre_muestra));
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
	public function guarda_muestra_agua($id, $nombre,$origen,$emisor)
	{
		$codigo="AQ-".$id;
		try 
		{
		$sql = "INSERT INTO analisis_muestra (codigo,nombre,origen,emisor,analisis_id)
		        VALUES (?, ?, ?, ?, ?)";
		$this->pdo->prepare($sql)
		     ->execute(array($codigo, $nombre,$origen,$emisor,$id));
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}

		
		//Actualizar el numero de muestras de la tabla analisis
		try 
		{
		$sql = "UPDATE analisis set total_muestras=total_muestras+1 WHERE id=(select distinct analisis_id from analisis_muestra WHERE id=?)";
		$this->pdo->prepare($sql)
		     ->execute(array($id));
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
	public function guarda_muestra_suelo($id, $nombre,$profundidad,$nombre_muestra)
	{
		$codigo="SQC-".$id;
		try 
		{
			/*
			if ($nombre_muestra<>"") 
				$nombre=$nombre_muestra;
			else
				$nombre=$nombre;
			*/
			$sql = "INSERT INTO analisis_muestra (codigo,nombre,profundidad,analisis_id)
			        VALUES (?, ?, ?, ?)";
			$this->pdo->prepare($sql)
			     ->execute(array($codigo, $nombre,$profundidad,$id));
			
			//Actualizar el numero de muestras de la tabla analisis
			$sql = "UPDATE analisis set total_muestras=total_muestras+1 WHERE id=(select distinct analisis_id from analisis_muestra WHERE id=?)";
			$this->pdo->prepare($sql)
			     ->execute(array($id));
			return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
	public function guarda_muestra_fertilizante($id, $fuente)
	{
		$codigo="fer-".$id;
		try 
		{
		$sql = "INSERT INTO analisis_muestra (codigo,fuente,analisis_id)
		        VALUES (?, ?, ?)";
		$this->pdo->prepare($sql)
		     ->execute(array($codigo,$fuente,$id));
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}

	public function guarda_muestra_emisor($id, $fuente)
	{
		$codigo="AQ-".$id;
		try 
		{
		$sql = "INSERT INTO analisis_muestra (codigo,fuente,analisis_id)
		        VALUES (?, ?, ?)";
		$this->pdo->prepare($sql)
		     ->execute(array($codigo,$fuente,$id));
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}

	public function guarda_muestra_analisis($id)
	{
		$codigo="mues-".$id;
		try 
		{
		$sql = "INSERT INTO analisis_muestra (codigo,analisis_id)
		        VALUES (?, ?)";
		$this->pdo->prepare($sql)
		     ->execute(array($codigo,$id));
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}

	public function guardar_resultado_analisis($id_analisis,$datos_resultado,$observaciones,$reprocesado)
	{
		try 
		{
		
			//Ingresar el registro de muestra de analisis
			$fecha_ingreso_analisis=date("Y/m/d");
			$json_datos_resultado=json_encode($datos_resultado);

			//eliminar ingresos anteriores para el mismo id_analisis

				$sql="DELETE FROM analisis_muestra_variable WHERE id_analisis_variable=?";
				$this->pdo->prepare($sql)
			  		 ->execute(array($id_analisis));			
			
			//recorrer el array y guardar en tabla analisis_muestra_variables todas las variables asociadas al id
			foreach ($datos_resultado as $key => $value) {
				$id_variable=$value["id"];
				$nombre=$value["nombre"];
				$unidad=$value["unidad"];
				$valor=$value["valor"];
				$rango=$value["rango"];
				$obs=$value["obs"];
				$valor_factor_1=$value["factor_1"];
				$valor_ingreso=$value["valor_ingreso"];
				$orden=$value["orden"];
				
				$sql="INSERT INTO analisis_muestra_variable (id_analisis_variable, id_variable, nombre, unidad, valor, rango, obs,valor_factor_1,valor_ingreso, orden)
							VALUES (?,?,?,?,?,?,?,?,?,?)";
				$this->pdo->prepare($sql)
			  		 ->execute(array($id_analisis,$id_variable, $nombre, $unidad, $valor, $rango, $obs,$valor_factor_1,$valor_ingreso,$orden));	
			}

		}
		catch (Exception $e) 
		{
			die($e->getMessage());
			return $e->getMessage();
		}

		try 
		{		
		// actualizar el registro de muestra, con la fecha de ingreso y el json de resultados  
		$sql = "UPDATE analisis_muestra set fecha_ingreso_resultados = ?, variables_resultado=?, observaciones=? WHERE id=?";
		$this->pdo->prepare($sql)
		     ->execute(array($fecha_ingreso_analisis,$json_datos_resultado,$observaciones,$id_analisis));
		
		//echo " <br>UPDATE analisis_muestra set fecha_ingreso_resultados = $fecha_ingreso_analisis, variables_resultado=$json_datos_resultado, observaciones=? WHERE id=$id_analisis<br >";
		//exit;
		}

		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
		
		//Actualizar el numero de muestras de la tabla analisis
		if ($reprocesado==1) 
			$reprocesado=" (** REPROCESADO **)";
		else 
			$reprocesado="";

		try 
		{
		$sql = "UPDATE analisis 
				SET total_muestras_ingresadas=total_muestras_ingresadas+1
				,fecha_analisis_ok=?,observacion=? 
				WHERE id=(select distinct analisis_id from analisis_muestra WHERE id=?)";
		$this->pdo->prepare($sql)
		     ->execute(array($fecha_ingreso_analisis,$reprocesado,$id_analisis));
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}

	public function obtener_ultimo_id()
	{
		try {
			//$sql = "select max(id) from analisis;";
			$stm = $this->pdo->prepare("select max(id) as id from analisis;");
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			return $r->id;
		} 
		catch (Exception $e){
			die($e->getMessage());
		}
	}

	public function obtener_ultimo_batch()
	{
		try {
			//$sql = "select max(id) from analisis;";
			$stm = $this->pdo->prepare("select max(id) as id from batch_ingreso;");
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			return $r->id;
		} 
		catch (Exception $e){
			die($e->getMessage());
		}
	}	
	public function nombre_tipo_analisis($id)
	{
		try {
			//$sql = "select max(id) from analisis;";
			$stm = $this->pdo->prepare("select nombre,descripcion from tipo_analisis where id=".$id.";");
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			return $r->nombre." | ".$r->descripcion;
		} 
		catch (Exception $e){
			die($e->getMessage());
		}
	}	


	public function obtener_analisis($id_muestra)
	{
		try {
			//$sql = "select max(id) from analisis;";
			$stm = $this->pdo->prepare("select * from analisis where id = (select analisis_id from analisis_muestra where id=".$id_muestra.");");
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			return $r;
		} 
		catch (Exception $e){
			die($e->getMessage());
		}
	}


	public function variable_tipo_analisis($id_tipo_analisis,$especie_id,$estado_fenologico_id)
	{
		$restriccion=" ORDER BY orden2";
		if ($id_tipo_analisis=="3") $restriccion=" AND variable.id < 103 AND variable.id <> 88  ORDER BY orden2";
		if ($id_tipo_analisis=="5") $restriccion=" AND variable.id < 103 AND variable.id <> 88  ORDER BY orden2";
		if ($id_tipo_analisis=="6") $restriccion=" AND variable.id < 103 AND variable.id <> 88  ORDER BY orden2";
		if ($id_tipo_analisis=="7") $restriccion=" AND variable.id < 103 AND variable.id <> 88  ORDER BY orden2";
		if ($id_tipo_analisis=="8") $restriccion=" AND (variable.id < 103 OR variable.id >= 210)  AND variable.id <> 88 ORDER BY orden2";
		
		if ($id_tipo_analisis=="2") $restriccion=" AND variable.id not in(113,114,115,126,127,128,129,130,131,132,133,134,135)  ORDER BY orden2";
		//if ($id_tipo_analisis=="1") $restriccion=" ORDER BY variable.orden";
		
		if ($id_tipo_analisis=="19") $restriccion=" AND variable.id not in(216,217,128,129,130,131,126,127,134,135,164) ORDER BY orden2 ";
				
		try
		{
			$result = array();
			if ($especie_id > 0)
				//llamar las variables con especie y estado_fenologico
				$sql="SELECT distinct variable_id as id, variable.nombre as nombre , variable_tipo_analisis.formula as formula, variable.unidad 		as unidad, numero_decimales as numero_decimales, nivel_suficiencia as nivel_suficiencia, variable_tipo_analisis.orden2 as orden
							FROM variable_tipo_analisis, variable
							WHERE variable_tipo_analisis.variable_id = variable.id
							AND tipo_analisis_id=$id_tipo_analisis
							AND especie_id=$especie_id
						  AND estado_fenologico_id=$estado_fenologico_id
							AND variable_tipo_analisis.variable_id = variable.id ".$restriccion;
			else
				$sql="SELECT distinct variable_id as id, variable.nombre as nombre , variable_tipo_analisis.formula as formula, variable.unidad as unidad, numero_decimales as numero_decimales, nivel_suficiencia as nivel_suficiencia, variable_tipo_analisis.orden2 as orden
							FROM variable_tipo_analisis, variable
							WHERE tipo_analisis_id=$id_tipo_analisis 
							AND variable_tipo_analisis.variable_id = variable.id ".$restriccion;
			//echo $sql;

			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[$r->id]=array($r->id,$r->nombre,$r->formula,$r->unidad,$r->numero_decimales,$r->nivel_suficiencia,$r->orden);
			}

    		return ($result);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function obtener_variable_batch($id_analisis_muestra){
		$sql="select tipo_analisis_id from analisis where id= (SELECT analisis_id from analisis_muestra where id=".$id_analisis_muestra.")";

		$stm = $this->pdo->prepare($sql);
		$stm->execute();
		$r=$stm->fetch(PDO::FETCH_OBJ);
		return $r->tipo_analisis_id;
	}

	public function variable_tipo_analisis2($id_tipo_analisis)
	{
		try
		{
			$result = array();
			$orden=" ORDER BY orden2 ";

			$stm = $this->pdo->prepare("SELECT distinct variable_id as id, variable.nombre as nombre , variable_tipo_analisis.formula as formula, variable.unidad as unidad, numero_decimales as numero_decimales, nivel_suficiencia as nivel_suficiencia, orden2 as orden
				FROM variable_tipo_analisis, variable
				WHERE tipo_analisis_id=".$id_tipo_analisis." 
				AND variable_tipo_analisis.variable_id = variable.id 
				".$restriccion." ".$orden);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[$r->id]=array($r->id,$r->nombre,$r->formula,$r->unidad,$r->numero_decimales,$r->nivel_suficiencia,$r->orden);
			}

    		return ($result);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function tabla_revision()
	{
		try
		{
			$result = "";
			$stm = $this->pdo->prepare("SELECT analisis.id as id_analisis,tipo_analisis.nombre as nombre_tipo, cliente.nombre as cliente, TO_CHAR(analisis.fecha_ingreso, 'dd/mm/yyyy') as fecha_ingreso,  TO_CHAR(analisis.fecha_analisis_ok, 'dd/mm/yyyy') as fecha_analisis, DATE_PART('day',NOW() - analisis.fecha_ingreso::timestamp) as dias_ingreso, DATE_PART('day',NOW() - analisis.fecha_analisis_ok::timestamp) as dias_analisis
				FROM analisis,tipo_analisis,cliente 
				WHERE analisis.tipo_analisis_id=tipo_analisis.id 
				AND cliente.rut=analisis.cliente_id 
				AND analisis.estado_trabajo = 6;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result.="<tr><td>".$r->nombre_tipo."</td><td>".$r->cliente."</td><td>".$r->fecha_ingreso." (".$r->dias_ingreso.")</td><td>".$r->fecha_analisis." (".$r->dias_analisis.")</td><td><button type='button' class='btn btn-sm btn-neutral' id='revisar' onclick='revisar(".$r->id_analisis.")'>Revisar</button></td></tr>";
			}

			//$results = $stm->fetchAll(PDO::FETCH_ASSOC);
    		return ($result);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function tabla_revision_2()
	{
		try
		{
			$result = "";
			$stm = $this->pdo->prepare("SELECT analisis.id as id_analisis
										,tipo_analisis.nombre as nombre_tipo
										,cliente.nombre as cliente
										,TO_CHAR(analisis.fecha_ingreso, 'dd/mm/yyyy') as fecha_ingreso
										,DATE_PART('day',NOW() - analisis.fecha_ingreso::timestamp) as dias_ingreso
										,analisis.total_muestras as total_muestras
										,analisis.total_muestras_ingresadas as total_muestras_ingresadas
				FROM analisis,tipo_analisis,cliente 
				WHERE analisis.tipo_analisis_id=tipo_analisis.id 
				AND cliente.rut=analisis.cliente_id 
				AND analisis.total_muestras > analisis.total_muestras_ingresadas
				AND analisis.total_muestras_ingresadas <> 0 ;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result.="<tr><td>".$r->nombre_tipo."</td><td>".$r->cliente."</td><td>".$r->fecha_ingreso." (".$r->dias_ingreso.")</td><td>".$r->total_muestras_ingresadas." de ".$r->total_muestras."</td></tr>";
			}

			//$results = $stm->fetchAll(PDO::FETCH_ASSOC);
    		return ($result);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function tabla_revision_3()
	{
		try
		{
			$result = "";
			$stm = $this->pdo->prepare("SELECT DISTINCT analisis.id as id_analisis
										,tipo_analisis.nombre as nombre_tipo
										,tipo_analisis.descripcion as descripcion
										,cliente.nombre as cliente
										,TO_CHAR(analisis.fecha_ingreso, 'dd/mm/yyyy') as fecha_ingreso
										,DATE_PART('day',NOW() - analisis.fecha_ingreso::timestamp) as dias_ingreso
										,analisis_muestra.codigo as codigo
										,analisis_muestra.id as id_analisis_muestra
										,analisis_muestra.completado as completado
										,analisis_muestra.batch_id as batch
				FROM analisis,tipo_analisis,cliente,analisis_muestra 
				WHERE analisis.tipo_analisis_id=tipo_analisis.id 
				AND cliente.rut=analisis.cliente_id 
				AND analisis_muestra.analisis_id=analisis.id
				AND analisis_muestra.completado is not null
				AND analisis.estado_trabajo<6;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				if ($r->completado < 34) $color="bg-danger";
				elseif ($r->completado < 67) $color="bg-warning";
				else $color="bg-success";


				$result.="<tr><td>".$r->nombre_tipo."</td><td>".$r->cliente."</td><td>".$r->fecha_ingreso." (".$r->dias_ingreso.")</td><td>".$r->codigo."-".$r->id_analisis_muestra
					."</td><td><div class='progress' style='height: 20px;'>
                            	<div class='progress-bar ".$color."' role='progressbar' aria-valuenow='".$r->completado."' aria-valuemin='0' aria-valuemax='100' style='width: ".$r->completado."%;'>".$r->completado."%</div>
                            	</div></td>
                            	<td><a href='../view/analisis/ingreso-detalle-batch.php?id=".$r->batch."' class='btn btn-primary'>Detalle</a>
                            	</tr>";
                            	
			}
			//$results = $stm->fetchAll(PDO::FETCH_ASSOC);
    		return ($result);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}		
	public function tabla_enviados()
	{
		try
		{
			$result = "";
			$stm = $this->pdo->prepare("SELECT analisis.id as id_analisis
										,tipo_analisis.nombre as nombre_tipo
										,cliente.nombre as cliente
										,TO_CHAR(analisis.fecha_revision, 'dd/mm/yyyy') as fecha_revision
										,DATE_PART('day',NOW() - analisis.fecha_revision::timestamp) as dias_revision
										,predio_cliente.nombre as predio
										,tipo_analisis.descripcion as descripcion
				FROM analisis,tipo_analisis,cliente,predio_cliente 
				WHERE analisis.tipo_analisis_id=tipo_analisis.id 
				AND cliente.rut=analisis.cliente_id 
				AND analisis.predio_cliente_id=predio_cliente.id
				AND analisis.estado_trabajo=7;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result.="<tr><td>".$r->nombre_tipo."<br>".$r->descripcion."</td><td>".$r->cliente."</td><td>".$r->fecha_revision." (".$r->dias_revision.")</td><td>".$r->predio."</td></tr>";
			}
    		return ($result);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}	
	public function tabla_rechazados()
	{
		try
		{
			$result = "";
			$stm = $this->pdo->prepare("SELECT analisis.id as id_analisis
										,tipo_analisis.nombre as nombre_tipo
										,cliente.nombre as cliente
										,TO_CHAR(analisis.fecha_revision, 'dd/mm/yyyy') as fecha_revision
										,DATE_PART('day',NOW() - analisis.fecha_revision::timestamp) as dias_revision
										,predio_cliente.nombre as predio
										,motivo_rechazo
										,tipo_analisis.descripcion as descripcion
				FROM analisis,tipo_analisis,cliente,predio_cliente 
				WHERE analisis.tipo_analisis_id=tipo_analisis.id 
				AND cliente.rut=analisis.cliente_id 
				AND analisis.predio_cliente_id=predio_cliente.id
				AND analisis.estado_trabajo=8;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result.="<tr><td>".$r->nombre_tipo."<br>".$r->descripcion."</td><td>".$r->cliente."</td><td>".$r->fecha_revision." (".$r->dias_revision.")</td><td>".$r->predio."</td><td>".$r->motivo_rechazo."</td></tr>";
			}

			//$results = $stm->fetchAll(PDO::FETCH_ASSOC);
    		return ($result);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function obtener_analisis_revision($id)
	{
		try
		{
			$result = "";

			$stm = $this->pdo->prepare("SELECT analisis.id as id_analisis,tipo_analisis.nombre as nombre_tipo, cliente.nombre as cliente, TO_CHAR(analisis.fecha_ingreso, 'dd/mm/yyyy') as fecha_ingreso,  TO_CHAR(analisis.fecha_analisis_ok, 'dd/mm/yyyy') as fecha_analisis, DATE_PART('day',NOW() - analisis.fecha_ingreso::timestamp) as dias_ingreso, DATE_PART('day',NOW() - analisis.fecha_analisis_ok::timestamp) as dias_analisis
				FROM analisis,tipo_analisis,cliente
				WHERE analisis.tipo_analisis_id=tipo_analisis.id 
				AND cliente.rut=analisis.cliente_id 
				AND analisis.id=".$id);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			return $r;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}	
	public function obtener_encabezado_reporte($id)
	{
		try
		{
			$result = "";

			$stm = $this->pdo->prepare("SELECT 
						cliente.nombre nombre_cliente
						,cliente.razon_social as razon_social
						,cliente.rut as rut
						,cliente.email_1 as email
						,analisis.id as id_analisis
						,tipo_analisis.nombre as nombre_tipo
						,predio_cliente.nombre as nombre_predio
						,analisis.usuario_toma_muestra as responsable_muestra
						,TO_CHAR(analisis.fecha_ingreso, 'dd/mm/yyyy') as fecha_ingreso
						,TO_CHAR(analisis.fecha_analisis_ok, 'dd/mm/yyyy') as fecha_analisis
						,DATE_PART('day',NOW() - analisis.fecha_ingreso::timestamp) as dias_ingreso
						,DATE_PART('day',NOW() - analisis.fecha_analisis_ok::timestamp) as dias_analisis	
						,analisis.predio_cliente_id
						,analisis.cliente_id
						,analisis.tipo_analisis_id
						,TO_CHAR(analisis.fecha_toma_muestra, 'dd/mm/yyyy') as fecha_toma_muestra
						,analisis.profundidad_analisis as profundidad_analisis
				FROM analisis,tipo_analisis,cliente,predio_cliente
				WHERE analisis.tipo_analisis_id=tipo_analisis.id 
				AND cliente.rut=analisis.cliente_id 
				and predio_cliente.id=analisis.predio_cliente_id
				AND analisis.id=".$id);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			return $r;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function obtener_cuarteles($id)
	{
		try
		{
			$result = "";

			$stm = $this->pdo->prepare("select nombre from predio_zona where id=
					  (select predio_zona_id from analisis where id=".$id.")");
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			return $r->nombre;

			/*
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result.=$r->nombre.", ";
			}
			return rtrim($result, ", ");
			*/
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function obtener_nombre_muestra($id)
	{
		try
		{
			$result = "";

			$stm = $this->pdo->prepare("select nombre from analisis_muestra where analisis_id=".$id);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			
			if ($r->nombre<>"")
				return $r->nombre;
			else{
				$stm = $this->pdo->prepare("select nombre from predio_zona where id=
						  (select predio_zona_id from analisis where id=".$id.")");
				$stm->execute();
				$r=$stm->fetch(PDO::FETCH_OBJ);
				return $r->nombre;
			}

			
			/*
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result.=$r->nombre.", ";
			}
			return rtrim($result, ", ");
			*/
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function obtener_analisis_muestra($id)
	{
		try
		{
			$result = "";

			$stm = $this->pdo->prepare("select * from analisis_muestra where analisis_id=".$id);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			return $r;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function obtener_tipo_analisis($id)
	{
		try
		{
			$result = "";

			$stm = $this->pdo->prepare("select tipo_analisis_id from analisis where id=".$id);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			return $r->tipo_analisis_id;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function obtener_json($id)
	{
		try
		{
			$result = "";

			$stm = $this->pdo->prepare("SELECT nombre,variables_resultado
				FROM analisis_muestra 
				WHERE analisis_id=".$id." ORDER BY id");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->nombre,$r->variables_resultado);
			}
			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function obtener_json_ingreso($id)
	{
		try
		{
			$result = "";

			$stm = $this->pdo->prepare("SELECT id,nombre,variables_ingreso
				FROM analisis_muestra 
				WHERE batch_id=".$id." ORDER BY id");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->id,$r->nombre,$r->variables_ingreso);
			}
			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}	
	public function obtener_analisis_revision2($id)
	{
		try
		{
			$result = "";

			$stm = $this->pdo->prepare("SELECT analisis.id as id_analisis,tipo_analisis.nombre as nombre_tipo, cliente.nombre as cliente, TO_CHAR(analisis.fecha_ingreso, 'dd/mm/yyyy') as fecha_ingreso,  TO_CHAR(analisis.fecha_analisis, 'dd/mm/yyyy') as fecha_analisis, DATE_PART('day',NOW() - analisis.fecha_ingreso::timestamp) as dias_ingreso, DATE_PART('day',NOW() - analisis.fecha_analisis::timestamp) as dias_analisis
				FROM analisis,tipo_analisis,cliente
				WHERE analisis.tipo_analisis_id=tipo_analisis.id 
				AND cliente.rut=analisis.cliente_id 
				AND analisis.id= (select analisis_id from analisis_muestra WHERE analisis_muestra.id=".$id.")");
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			return $r;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}	
	
	public function obtener_resultados_muestra($id)
	{
		try
		{
			$result = "";

			$stm = $this->pdo->prepare("SELECT analisis_muestra.id,analisis_muestra.nombre as nombre_muestra,id_variable,analisis_muestra_variable.nombre as nombre_variable,analisis_muestra_variable.unidad,analisis_muestra_variable.valor, analisis_muestra_variable.rango,obs,rango_min,rango_max
				FROM analisis_muestra,analisis_muestra_variable,variable 
				WHERE analisis_muestra.id=analisis_muestra_variable.id_analisis_variable
				AND  analisis_muestra_variable.id_variable=variable.id
				AND analisis_muestra.analisis_id=".$id." ORDER BY orden");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->nombre_muestra,$r->nombre_variable,$r->unidad,$r->valor,$r->rango,$r->rango_min,$r->rango_max,$r->obs);
			}
			return $result;

		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}	

	public function obtener_json2($id)
	{
		try
		{
			$result = "";

			$stm = $this->pdo->prepare("SELECT nombre,variables_resultado,variables_ingreso, variables_factor_dilucion
				FROM analisis_muestra 
				WHERE id=".$id);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->nombre,$r->variables_resultado,$r->variables_ingreso,$r->variables_factor_dilucion);
			}
			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function obtener_resultado_reporte($id)
	{
		try
		{
			$result = "";
			/*
			$tipo_analisis_id=$this->obtener_id_tipo_analisis($id);
			echo "SELECT analisis_muestra_variable.nombre,analisis_muestra_variable.unidad, analisis_muestra_variable.valor_ingreso, analisis_muestra_variable.valor, analisis_muestra_variable.rango, analisis_muestra_variable.obs, analisis_muestra_variable.valor_factor_1 
																		FROM analisis_muestra, analisis_muestra_variable
																		WHERE analisis_muestra.id= analisis_muestra_variable.id_analisis_variable
																		AND id_analisis_variable=".$id."
																		ORDER BY orden";
			*/
																		
			$stm = $this->pdo->prepare("SELECT analisis_muestra_variable.nombre,analisis_muestra_variable.unidad, analisis_muestra_variable.valor_ingreso, analisis_muestra_variable.valor, analisis_muestra_variable.rango, analisis_muestra_variable.obs, analisis_muestra_variable.valor_factor_1 
																		FROM analisis_muestra, analisis_muestra_variable
																		WHERE analisis_muestra.id= analisis_muestra_variable.id_analisis_variable
																		AND id_analisis_variable=".$id."
																		ORDER BY orden");
			

			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->nombre,$r->unidad,$r->valor_ingreso,$r->valor,$r->rango,$r->obs,$r->valor_factor_1);
			}
			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function actualizar_estado_analisis($id)
	{
		try
		{
			$result = "";
			$fecha_resultado=date("Y-m-d");
			$sql = "UPDATE analisis SET estado_trabajo =?, fecha_analisis_ok=? 
					WHERE id=(select distinct analisis_id from analisis_muestra WHERE id=?)";
			$this->pdo->prepare($sql)->execute(array(6,$fecha_resultado,$id));
		return $sql;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	
	/*public function envia_reporte_cliente($id_analisis)
	{
		$fecha=date("Y-m-d");
		$fecha2=date("d-m-Y");

		try 
		{
			//obtener email del cliente del analisis
			$stm = $this->pdo->prepare("
				SELECT * 
				FROM cliente 
				WHERE rut = (SELECT cliente_id FROM analisis where id = ".$id_analisis.")"
			);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);

			$email1=$r->email1;
			$email2=$r->email2;
			
			//preparar el email
			require '../../../lib/phpmailer/src/Exception.php';
			require '../../../lib/phpmailer/src/PHPMailer.php';
			require '../../../lib/phpmailer/src/SMTP.php';
			
			$mail = new PHPMailer(true);
			try {
			    //Server settings
			    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
			    $mail->isSMTP();                                            //Send using SMTP
				$mail->SMTPDebug  = 0;
				//Ahora definimos gmail como servidor que aloja nuestro SMTP
				$mail->Host       = 'smtp.gmail.com';
				//El puerto será el 587 ya que usamos encriptación TLS
				$mail->Port       = 587;
				//Definmos la seguridad como TLS
				$mail->SMTPSecure = 'tls';
				//Tenemos que usar gmail autenticados, así que esto a TRUE
				$mail->SMTPAuth   = true;
				//Definimos la cuenta que vamos a usar. Dirección completa de la misma
				$mail->Username   = "emoyaferrada@gmail.com";
				//Introducimos nuestra contraseña de gmail
				$mail->Password   = "pacsp0000";
			    //Recipients
			    $mail->setFrom('emoyaferrada@gmail.com', 'LAB-BIO');
			    $mail->addAddress($email1);     //Add a recipient
			    $mail->addAddress($email2);               //Name is optional
			    //$mail->addCC('cc@example.com');
			    //$mail->addBCC('bcc@example.com');

			    //Attachments
			    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
			    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

			    //Content
			    $mail->isHTML(true);                                  //Set email format to HTML
			    $mail->Subject = 'LAB-BIO Nuevo informe ';
			    $mail->Body    = 'Se ha liberado un nuevo resultado de análisis de LAB-BIO con fecha '.$fecha2.'.
			    				<br/> Para descargar el reporte ingresar al siguiente enlace
			    				<br/> <a href="http://198.251.64.144/sada-util/lab-bio-dev/view/ver-reporte-cliente.php?id='.$id_analisis.'"><< Ver Reporte >></a>';

			    $mail->send();
			    echo 'Message has been sent';
			} catch (Exception $e) {
			    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
			}

			return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
	*/	

	public function aprobar_analisis($id)
	{
	try 
		{
		$fecha_revision=date("Y/m/d");
		
		$sql = "UPDATE analisis set estado_trabajo=7, fecha_revision = ?
				WHERE id=?";
		$this->pdo->prepare($sql)
		     ->execute(array($fecha_revision,$id));
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
		//enviar correo electrónico del cliente
		//$res_email = $this->envia_reporte_cliente($id);
		return 1;

	}
	public function rechazar_analisis($id,$motivo_rechazo)
	{
	try 
		{
		$fecha_revision=date("Y/m/d");
		
		$sql = "UPDATE analisis set estado_trabajo=8, fecha_revision=?, motivo_rechazo=?
				WHERE id=?";
		$this->pdo->prepare($sql)
		     ->execute(array($fecha_revision,$motivo_rechazo,$id));
		return $sql.$id.$motivo_rechazo;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
	public function actualiza_ingreso_parcial($id_muestra,$json_ingreso,$completado)
	{
	try 
		{
		$sql = "UPDATE analisis_muestra set variables_ingreso=?, completado=? 
				WHERE id=?";
		$this->pdo->prepare($sql)
		     ->execute(array($json_ingreso,$completado,$id_muestra));
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}	
	public function muestras_para_batch()
	{
		try
		{
			$result = "";
			$usuario_id=$_SESSION["rut"];
			
			$sql="SELECT analisis_muestra.id as id, analisis_muestra.codigo as codigo, TO_CHAR(analisis.fecha_ingreso, 'dd/mm/yyyy') as fecha_ingreso,tipo_analisis.nombre as tipo,tipo_analisis.descripcion as descripcion,tipo_analisis.id as analisis_id
				FROM analisis_muestra, analisis, tipo_analisis
				WHERE analisis_muestra.analisis_id=analisis.id
				AND analisis_muestra.batch_id isnull
				AND analisis.tipo_analisis_id=tipo_analisis.id ORDER BY tipo_analisis.nombre ASC, analisis.fecha_ingreso ASC";

			$stm=$this->pdo->prepare($sql);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->id,$r->codigo,$r->fecha_ingreso,$r->tipo,$r->descripcion,$r->analisis_id);
			}
			
			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function analisis_para_batch($array_muestras)
	{
		$tabla_resultado="<div class='alert alert-warning' id='importante_2' role=''><strong>Importante: </strong> seleccionar para el batch los análisis del mismo tipo</div>
											<table class='table'>
		                  <thead>
		                    <th>Sel.</th>
		                    <th>Tipo</th>
		                    <th>Detalle</th>
		                    <th>Fecha Ingreso</th>
		                    <th>Id</th>
		                  </thead>
		                  <tbody>";
		if ($array_muestras[0]){
		  $sin_datos=false;

		  foreach ($array_muestras as $value) {
		    $tabla_resultado.="<tr><td><input type='checkbox' name='sel[]' value='".$value[0].";".$value[5].";".$value[3].";".$value[1].";".$value[4]."'>"
		                    ."</td><td>".$value[3]
		                    ."</td><td>".$value[4]
		                    ."</td><td>".$value[2]
		                    ."</td><td>".$value[1]
		                    ."</td></tr>";
		  }
	  $tabla_resultado.="</tbody></table>
	            				</div>
      								<div>
	  									 <button type='submit' id='guardar_batch' name='guardar_batch' value='1' class='btn btn-primary'>Guardar Batch</button>";  
		}
		else { 
			$tabla_resultado="<div class='alert alert-danger' id='alerta' role='alert'>No se encontraron registros</div>
			</div>
			<div>
			 <button type='button' id='salir_batch' name='salir_batch' value='0' class='btn btn-primary'>Salir</button>";
		}

		return $tabla_resultado;
	}

	public function analisis_para_modificar($array_muestras,$id_batch)
  {
  	$tabla_resultado="<div class='alert alert-warning' id='importante_2' role=''><strong>Importante: </strong> La muestra se desvinculará del batch actual y quedará disponible para ingresar en otro batch posterior</div>
	                  <table class='table'>
	                    <thead>
	                      <th>ID</th>
	                      <th>Tipo</th>
	                      <th>Detalle</th>
	                      <th>Código</th>
	                      <th></th>
	                    </thead>
	                    <tbody>";
	  if ($array_muestras[0]){
	    $sin_datos=false;
	    foreach ($array_muestras as $value) {
	      $tabla_resultado.="</td><td>".$value[0]
	                      ."</td><td>".$value[1]
	                      ."</td><td>".$value[2]
	                      ."</td><td>".$value[3]
	                      ."</td><td><a href='../../controller/analisis.controller.php?accion=eliminar_analisis_batch&id_eliminar=".$value[0]."&id_batch=".$id_batch."' class='btn btn-primary'>Desvincular</a></td></tr>";
	    }
			$tabla_resultado.="</tbody></table>
	                       </div>
	                        <div>";  
	  }
	  else { 
	      $tabla_resultado="<div class='alert alert-danger' id='alerta' role='alert'>No se encontraron registros</div>
	      </div>
	      <div>";
	  }

	  return $tabla_resultado;
  }

	public function desvincular_de_batch($id)
	{
	try 
		{
		$sql = "UPDATE analisis_muestra set batch_id=null 
				WHERE id=?";
		$this->pdo->prepare($sql)
		     ->execute(array($id));
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
	public function eliminar_batch($id)
	{
	try 
		{
		//desvincular analisis_muestra desde el batch y despues eliminar el batch
		$sql = "UPDATE analisis_muestra set batch_id=null WHERE batch_id=?";
		$this->pdo->prepare($sql)
			->execute(array($id));
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}

	try 
		{
		//deaspues eliminar el batch
		$sql2 = "DELETE FROM batch_ingreso WHERE id=?";
		$this->pdo->prepare($sql2)
			->execute(array($id));
		
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}

	}

	public function actualiza_batch_muestra($id_muestra,$id_batch)
	{
	try 
		{
		$sql = "UPDATE analisis_muestra set batch_id=? 
				WHERE id=?";
		$this->pdo->prepare($sql)
		     ->execute(array($id_batch,$id_muestra));
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}	

	public function actualiza_estado_batch($id_batch)
	{
	try 
		{
		$sql = "UPDATE batch_ingreso set estado=1 
				WHERE id=?";
		$this->pdo->prepare($sql)
		     ->execute(array($id_batch));
		//TO DO actualizar estado de analisis a 5 fin analisis o 6 En revisión 
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}




	public function crear_nuevo_batch($id_batch,$usuario_id,$tipo_id,$tipo_analisis_texto,$tipo_analisis_descripcion)
	{
	try 
		{
		$fecha=date("Y/m/d");
		$estado=0;
		//$usuario_id=$_SESSION["rut"];
		
		$sql = "INSERT INTO batch_ingreso (id,fecha,estado,usuario_id,tipo_id,tipo_analisis_texto,tipo_analisis_descripcion) 
				VALUES (?,?,?,?,?,?,?)";
		$this->pdo->prepare($sql)
		     ->execute(array($id_batch,$fecha,$estado,$usuario_id,$tipo_id,$tipo_analisis_texto,$tipo_analisis_descripcion));
		
		return 1;
		}
		catch (Exception $e) 
		{
			return $e->getMessage();
		}
	}	
	public function obtener_batch_ingreso($id_batch)
	{
		try
		{
			$result = "";

			$stm = $this->pdo->prepare("SELECT id,codigo 
				FROM analisis_muestra
				WHERE analisis_muestra.batch_id=".$id_batch."
				ORDER BY analisis_muestra.id ASC");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->id,$r->codigo);
			}
			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function obtener_batch_usuario($rut)
	{
		try
		{
			$result = "";
			$sql="SELECT id,TO_CHAR(fecha, 'dd/mm/yyyy') as fecha, tipo_analisis_texto 
				FROM batch_ingreso
				WHERE estado=0
				AND tipo_analisis_texto<>'RECHAZADO' 
				AND usuario_id='".$rut."' 
				ORDER BY id ASC;";

			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->id,$r->fecha,$r->tipo_analisis_texto);
			}
			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function obtener_batch_rechazados()
	{
		try
		{
			$result = "";
			$sql="SELECT id,TO_CHAR(fecha, 'dd/mm/yyyy') as fecha, tipo_analisis_texto 
				FROM batch_ingreso
				WHERE estado=0
				AND tipo_analisis_texto<>'RECHAZADO' 
				AND usuario_id='".$rut."' 
				ORDER BY id ASC;";

			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->id,$r->fecha,$r->tipo_analisis_texto);
			}
			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function obtener_datos_fenologicos($tipo_analisis_id)
	{
		try
		{
			$result = "";
			$sql="SELECT especie,estado_fenologico
						FROM analisis,analisis_muestra
						WHERE analisis.id=analisis_muestra.analisis_id
						AND tipo_analisis_id=".$tipo_analisis_id;

			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->especie,$r->estado_fenologico);
			}
			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function obtener_id_tipo_analisis($id)
	{
		try
		{
			$result = "";
			$sql="SELECT tipo_analisis_id from analisis,analisis_muestra
						WHERE analisis.id=analisis_muestra.analisis_id
						AND analisis_muestra.id=".$id.";";

			//echo $sql;

			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			
			return $r->tipo_analisis_id;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function obtener_id_tipo_batch($id_batch)
	{
		try
		{
			$result = "";
			$sql="SELECT tipo_id FROM public.batch_ingreso
						WHERE id=".$id_batch.";";

			//echo $sql;

			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			
			return $r->tipo_id;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function obtener_batch_modificar($id)
	{
		try
		{
			$result = "";
			$stm = $this->pdo->prepare("SELECT analisis_muestra.id as id,tipo_analisis.nombre as nombre,tipo_analisis.descripcion as descripcion, analisis_muestra.codigo as codigo 
				FROM analisis_muestra,analisis,tipo_analisis
				WHERE analisis_muestra.analisis_id=analisis.id
				AND tipo_analisis.id=analisis.tipo_analisis_id
				AND analisis_muestra.batch_id=".$id);

			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[]=array($r->id,$r->nombre,$r->descripcion,$r->codigo);
			}
			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function obtener_batch_titulo($id)
	{
		try
		{
			$result = "";
			$sql="SELECT tipo_analisis_descripcion 
				FROM batch_ingreso
				WHERE id=".$id;

			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			
			return $r->tipo_analisis_descripcion;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function obtener_rangos_foliares($id_muestra,$id_tipo_analisis)
	{
		try
		{
			$result = "";
			//traer el estado fenologico y especie de la muestra

			$stm = $this->pdo->prepare("
				SELECT  especie,estado_fenologico 
				FROM analisis_muestra
				WHERE id=".$id_muestra
			);
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);

			$stm = $this->pdo->prepare("
				SELECT distinct variable_id as id, variable.nombre as nombre 
					,variable_tipo_analisis.id as id_variable_tipo
					,variable_tipo_analisis.formula as formula
					,variable.unidad as unidad
					,numero_decimales as numero_decimales
					,valor_minimo
					,valor_maximo
					,nivel_suficiencia as nivel_suficiencia
					,orden
				FROM variable_tipo_analisis, variable
				WHERE tipo_analisis_id=".$id_tipo_analisis."
				AND variable_tipo_analisis.variable_id = variable.id 
				AND especie_id=".$r->especie." 
				AND estado_fenologico_id=".$r->estado_fenologico."  
				ORDER BY orden"
			);
			
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$result[$r->id]=array($r->valor_minimo,$r->valor_maximo,$r->nivel_suficiencia);
			}
			return $result;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}	
	public function tabla_reporte_cliente()
	{
		try
		{
			$result = "";
			$stm = $this->pdo->prepare("SELECT distinct analisis.id as id_analisis
										,tipo_analisis.nombre as nombre_tipo
										,cliente.razon_social as cliente
										,TO_CHAR(analisis.fecha_revision, 'dd/mm/yyyy') as fecha_revision
										,DATE_PART('day',NOW() - analisis.fecha_revision::timestamp) as dias_revision
										,TO_CHAR(analisis.fecha_ingreso, 'dd/mm/yyyy') as fecha_ingreso
										,DATE_PART('day',NOW() - analisis.fecha_ingreso::timestamp) as dias_ingreso
										,predio_cliente.nombre as predio
										,predio_zona.nombre as nombre_zona
										,analisis_muestra.nombre as nombre_muestra
										,tipo_analisis.descripcion as descripcion
										,analisis_muestra.codigo as codigo_muestra
										,analisis.observacion
				FROM analisis
					LEFT JOIN analisis_muestra on analisis.id=analisis_muestra.analisis_id 
					LEFT JOIN tipo_analisis on analisis.tipo_analisis_id=tipo_analisis.id 
					LEFT JOIN predio_zona on predio_zona.id=analisis.predio_zona_id  
					LEFT JOIN cliente on cliente.rut=analisis.cliente_id
					LEFT JOIN predio_cliente on analisis.predio_cliente_id=predio_cliente.id
				WHERE analisis.estado_trabajo=6
				ORDER BY predio_zona.nombre;");
				//ORDER BY analisis.id;");
				//ORDER BY tipo_analisis.nombre,predio_cliente.nombre,predio_zona.nombre ;");
			
			//ajustar el estado a los trabajos revisados
			//AND analisis.estado_trabajo=8;");

			$stm->execute();
			$result="<table class='table align-items-center table-flush' name='tabla_por_revisar' id='tabla_por_revisar'>
					<thead>
					<th>Sel</th>
					<th>Código</th>
					<th>Tipo de análisis</th>
					<th>Cliente</th>
					<th>Predio</th>
					<th>Nombre muestra</th>
					<th>Ingreso</th>
					<th>Observación</th>
					</thead>
					<tbody>
					";
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				if ($r->nombre_zona=="") $r->nombre_zona=$r->nombre_muestra;

				$result.="<tr><td><input type='checkbox' name='sel[]' value='".$r->id_analisis."'>"
							."</td><td>".$r->codigo_muestra
							."</td><td>".$r->nombre_tipo."<br>".$r->descripcion
							."</td><td>".$r->cliente
							."</td><td>".$r->predio
							."</td><td>".$r->nombre_zona
							."</td><td>".$r->fecha_ingreso." (".$r->dias_ingreso.")"
							."</td><td>".$r->observacion
							."</td></tr>";
			}
			$result.="</tbody></table>";
			//$results = $stm->fetchAll(PDO::FETCH_ASSOC);
    		return ($result);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function guarda_reporte_cliente($datos)
	{
		$fecha=date("Y-m-d");
		try 
		{
		$sql = "INSERT INTO reporte_cliente (cliente_id,predio_id,tipo_analisis_id,analisis_id,reporte_html,fecha_reporte)
		        VALUES (?, ?, ?, ?, ?, ?)";
		$this->pdo->prepare($sql)
		     ->execute(array($datos["cliente_id"],$datos["predio_id"],$datos["tipo_analisis_id"],json_encode($datos["id_analisis"]),$datos["reporte_html"],$fecha));
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}


	public function modificar_batch_reingreso($id_reingreso)
	{
		$fecha=date("Y-m-d");
		try 
		{
		
		$this->pdo->prepare("update batch_ingreso set estado=0 where id=?")
		     ->execute(array($id_reingreso));
		return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}

	public function obtiene_seguimiento_cliente($rut)
	{
		try
		{
			$result = "";
			$sql="
						SELECT 
						 fecha_analisis_ok,
						 predio_cliente.nombre AS nombre_predio,
						 predio_zona.nombre AS nombre_zona,
						 usuario_toma_muestra,
						 profundidad_analisis,
						 analisis_muestra.nombre AS profundidad,
						 analisis_muestra_variable.nombre,
						 analisis_muestra_variable.unidad,
						 analisis_muestra_variable.valor,
						 analisis_muestra_variable.rango,
						 analisis_muestra.codigo as codigo_muestra
						FROM analisis
						JOIN analisis_muestra ON analisis.id = analisis_muestra.analisis_id
						JOIN analisis_muestra_variable ON analisis_muestra.id = analisis_muestra_variable.id_analisis_variable
						JOIN predio_cliente ON analisis.predio_cliente_id = predio_cliente.id
						JOIN predio_zona ON analisis.predio_zona_id = predio_zona.id
						WHERE estado_trabajo = 6
						AND analisis.cliente_id='".$rut."'";
			$sql="
						SELECT 
						 analisis_muestra.codigo as codigo_muestra,
						 fecha_analisis_ok,
						 analisis_muestra_variable.nombre,
						 analisis_muestra_variable.unidad,
						 analisis_muestra_variable.valor,
						 analisis_muestra_variable.rango
						FROM analisis
						JOIN analisis_muestra ON analisis.id = analisis_muestra.analisis_id
						JOIN analisis_muestra_variable ON analisis_muestra.id = analisis_muestra_variable.id_analisis_variable
						JOIN predio_cliente ON analisis.predio_cliente_id = predio_cliente.id
						JOIN predio_zona ON analisis.predio_zona_id = predio_zona.id
						WHERE estado_trabajo = 6
						AND analisis.cliente_id='".$rut."'
						ORDER BY codigo_muestra";

			$sql="
						SELECT fecha_analisis_ok,codigo, variables_resultado 
						FROM analisis,analisis_muestra 
						WHERE analisis.id=analisis_muestra.analisis_id 
						AND  estado_trabajo = 6
						AND analisis.cliente_id='".$rut."'
						ORDER BY codigo";					

			$stm = $this->pdo->prepare($sql);
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_ASSOC) as $r){
				/*
				$data[]=array($r->fecha_analisis_ok,
						 $r->nombre_predio,
						 $r->nombre_zona,
						 $r->usuario_toma_muestra,
						 $r->profundidad_analisis,
						 $r->profundidad,
						 $r->nombre,
						 $r->unidad,
						 $r->valor,
						 $r->rango);
				*/
				$data[]=$r;
			}
			
			return ($data);

		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
}