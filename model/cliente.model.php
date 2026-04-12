<?php
class clienteModel
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

	public function cliente_select($rut)
	{
		try
		{
			$salida="";
			$sql="select rut,razon_social from cliente order by nombre;";
			$stm = $this->pdo->prepare($sql); 
			$stm->execute();
			foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {				
				if($rut==$r->rut){
					$salida.="<option value='".$r->rut."' selected='selected'>".$r->razon_social."</option>";
				}else{
					$salida.="<option value='".$r->rut."'>".$r->razon_social."</option>";
				}
			}
			return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function cliente_select_reporte()
	{
		try
		{
			$salida="";
			$sql = "SELECT DISTINCT rut,nombre 
					FROM cliente,analisis
					WHERE cliente.rut=analisis.cliente_id
					AND analisis.estado_trabajo=7 
					ORDER BY nombre;";
			$stm = $this->pdo->prepare($sql); 
			$stm->execute();
			foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
				$salida.="<option value='".$r->rut."'>".$r->nombre."</option>";
			}
			return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function predio_select()
	{
		try
		{
			$salida="";
			$array_predios="";
			$sql="select cliente_id,id,nombre from predio_cliente;";
			$stm = $this->pdo->prepare($sql); 
			$stm->execute();
			foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
				//$salida.="<option value='".$r->id."'>".$r->nombre."</option>";
				$array_predios[]=array($r->cliente_id,$r->id,$r->nombre);
			}
			return $array_predios;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function option_comunas($defecto)
	{
		try
		{
			$result = array();

			$stm = $this->pdo->prepare("SELECT nombre_comuna, id FROM public.comuna order by nombre_comuna;");
			$stm->execute();
			$salida="<option value='0'>Seleccione comuna</option>";
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				if ($r->id==$defecto) $salida.="<option value='".$r->id."' selected='selected'>".$r->nombre_comuna."</option>";
				else $salida.="<option value='".$r->id."'>".$r->nombre_comuna."</option>";
			}
			return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function lista_predios_cliente($rut_cliente)
	{
		try
		{
			
			$stm = $this->pdo->prepare("SELECT nombre,sector, comuna.nombre_comuna as comuna, predio_cliente.id as id_predio 
										FROM predio_cliente,comuna 
										WHERE predio_cliente.comuna_id=comuna.id
										AND cliente_id='".$rut_cliente."';");
			$stm->execute();
			$salida="<table class='table'>
						<thead>
							<th>Nombre</th>
							<th>Sector</th>
							<th>Comuna</th>
						</thead>
						<tbody>";
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$salida.="<tr><td>".$r->nombre
						."</td><td>".$r->sector
						."</td><td>".$r->comuna
						."</td><td><a href='ingreso-zona-cuartel.php?predio_id=".$r->id_predio."&cliente_id=".$rut_cliente."' class='btn btn-primary'>Cuarteles</a>"
						."</td></tr>";
			}
			$salida.="</tbody></table>";
			return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}	

	public function lista_cuartel_predio($predio_id)
	{
		try
		{
			
			$stm = $this->pdo->prepare("SELECT predio_cuartel.nombre as nombre ,predio_cuartel.descripcion as descripcion,predio_zona.nombre as nombre_zona,predio_cuartel.id, predio_zona.id as id_zona
										FROM predio_cuartel,predio_zona 
										WHERE predio_cuartel.predio_zona_id=predio_zona.id 
										AND predio_cuartel.predio_id=".$predio_id.";");
			$stm->execute();
			$salida="<table class='table'>
						<thead>
							<th>Zona</th>
							<th>Cuartel</th>
							<th>Descripción</th>
						</thead>
						<tbody>";
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$salida.="<tr><td>".$r->nombre_zona
						."</td><td>".$r->nombre
						."</td><td>".$r->descripcion
						."</td><td><a href='ingreso-zona-cuartel.php?accion=del&cuartel_id=".$r->id."&predio_id=".$_POST["predio_id"]."&cliente_id=".$_POST["cliente_id"]."&zona_id=".$r->id_zona."'>Eliminar</a></td></tr>";
			}
			$salida.="</tbody></table>";
			return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}	

	public function listado_clientes()
	{
		try
		{
			
			$stm = $this->pdo->prepare("SELECT * FROM cliente;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r){
				$detalle2='<button class="btn btn-sm btn-neutral" id="btn_detalle" onClick="abre_detalles('.$r->rut.')">Ver detalle</button>';
				$result[]=array($r->rut,$r->nombre,$r->razon_social,$r->direccion,$r->telefono_1,$r->telefono_2,$r->email_1,$r->email_2,$detalle2);
			}
			return json_encode($result);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}	

	public function lista_predio_zona($predio_id,$zona_id)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT predio_cuartel.nombre
											,predio_cuartel.descripcion
											,predio_zona_id
											,predio_cuartel.id
										FROM predio_cuartel
										WHERE predio_zona_id=".$zona_id."
										AND predio_cuartel.predio_id=".$predio_id);
			$stm->execute();
			$salida="<table class='table'>
						<thead>
							<th>Zona de riego</th>
							<th>Cuartel</th>
							<th>Descripcion</th>
						</thead>
						<tbody>";
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$salida.="<tr><td>Zona ".$r->predio_zona_id
						."</td><td>".$r->nombre
						."</td><td>".$r->descripcion
						."</td></tr>";
			}
			$salida.="</tbody></table>";
			return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}	
	public function listado_zonas($predio_id)
	{
		try
		{
			$stm = $this->pdo->prepare("SELECT min(id) as id, nombre 
										FROM predio_zona 
										WHERE predio_id=".$predio_id." 
										GROUP BY nombre 
										ORDER BY nombre;");
			$stm->execute();
			$arr=array();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$arr[$r->id]=$r->nombre;
			}
			return json_encode($arr);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}	

	public function guardar_nuevo($data)
	{
		try 
		{
		$sql = "INSERT INTO cliente (rut, nombre,direccion,comuna_id,razon_social,telefono_1,telefono_2,email_1,giro)
		        VALUES (?, ?, ?, ?, ?, ?,?,?,?)";
		$this->pdo->prepare($sql)
		     ->execute(array($data["rut"],$data["nombre"],$data["direccion"],$data["comuna"],$data["razon_social"],$data["telefono_1"],$data["telefono_2"],$data["email"],$data["giro"]));
		return 1;
		}

		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
	public function modificar_cliente($data)
	{
		try 
		{
		$sql = "UPDATE cliente 
				SET nombre='".$data["nombre"]."',
					direccion='".$data["direccion"]."',
					comuna_id=".$data["comuna"].",
					razon_social='".$data["razon_social"]."',
					telefono_1='".$data["telefono_1"]."',
					telefono_2='".$data["telefono_2"]."',
					email_1='".$data["email"]."',
					giro='".$data["giro"]."'
					where rut='".$data["rut"]."'";
		$stm=$this->pdo->prepare($sql);
		$stm->execute();
		return 1;
		}

		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
	public function guardar_nuevo_predio($data)
	{
		try 
		{
		$sql = "INSERT INTO predio_cliente (nombre,sector,comuna_id,cliente_id)
		        VALUES (?, ?, ?, ?)";
		$this->pdo->prepare($sql)
		     ->execute(array($data["nombre_predio"],$data["sector_predio"],$data["comuna_predio"],$data["rut"]));
		return 1;
		}

		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}

	public function guardar_nuevo_cuartel($data)
	{
		try 
		{
		
		
		$sql = "INSERT INTO predio_zona (nombre, predio_id) 
				VALUES (?, ?) ON CONFLICT (id) DO NOTHING;";
		$this->pdo->prepare($sql)
		     ->execute(array($data["zona"],$data["predio_id"]));
		$salida="<br>Datos predio_zona: nombre->".$data["zona"]." predio_id->".$data["predio_id"];
		
		//obtener el id de la nueva zona

		$stm = $this->pdo->prepare("SELECT max(id) as max_id FROM predio_zona;");
		$stm->execute();
		$r=$stm->fetch(PDO::FETCH_OBJ);
		$id_nueva_zona = $r->max_id;

		
		$sql = "INSERT INTO predio_cuartel (nombre,descripcion,predio_zona_id,predio_id)
		        VALUES (?, ?, ?, ?)";
		$this->pdo->prepare($sql)
		     ->execute(array($data["nombre"],$data["descripcion"],$id_nueva_zona,$data["predio_id"]));
		
		$salida.="<br>Datos predio_cuartel: nombre->".$data["nombre"]." descripcion->".$data["descripcion"]." id_nueva_zona->".$id_nueva_zona." predio_id->".$data["predio_id"];

		return 1;
		}

		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}

	public function eliminar_cuartel($cuartel_id,$zona_id)
	{
		try 
		{
		$sql = "DELETE FROM predio_cuartel WHERE id=?;";
		$this->pdo->prepare($sql)
		     ->execute(array($cuartel_id));
		
		$sql = "DELETE FROM predio_zona WHERE id=?;";
		$this->pdo->prepare($sql)
		     ->execute(array($zona_id));

		return 1;
		}

		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
	public function trae_form($rut)
	{
		try 
		{
		$sql = "SELECT * FROM cliente 
				WHERE rut='".$rut."'";
		$stm=$this->pdo->prepare($sql);
		$stm->execute();
		$r=$stm->fetch(PDO::FETCH_OBJ);
		$salida["rut"]=$r->rut;
		$salida["nombre"]=$r->nombre;
		$salida["razon_social"]=$r->razon_social;
		$salida["giro"]=$r->giro;
		$salida["direccion"]=$r->direccion;
		$salida["telefono_1"]=$r->telefono_1;
		$salida["telefono_2"]=$r->telefono_2;
		$salida["email"]=$r->email_1;
		$salida["comuna"]=$r->comuna_id;

		return $salida;
		}

		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}	
}