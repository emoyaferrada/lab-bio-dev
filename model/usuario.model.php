<?php
class usuarioModel
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

	public function existe_usuario($rut,$clave)
	{
		try
		{
			$result = false;

			//$stm = $this->pdo->prepare("SELECT * FROM public.usuario where rut= '".$rut."' and clave= '".$clave."';");
			$sql="select * from usuario where rut='".$rut."' and clave='".$clave."';";
			$stm = $this->pdo->prepare($sql); 
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);

			if ($stm->rowCount() > 0) 
				return $r;
			else 
				return 0;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function usuario_select()
	{
		try
		{
			$salida="";
			$sql="select rut,nombre_completo from usuario where tipo_usuario=1 order by nombre_completo;";
			$stm = $this->pdo->prepare($sql); 
			$stm->execute();
			foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
				$salida.="<option value='".$r->rut."'>".$r->nombre_completo."</option>";
			}
			return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function usuario_externo_select()
	{
		try
		{
			$salida="";
			$sql="select rut,nombre_completo from usuario  where tipo_usuario=2 order by nombre_completo;";
			$stm = $this->pdo->prepare($sql); 
			$stm->execute();
			foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
				$salida.="<option value='".$r->rut."'>".$r->nombre_completo."</option>";
			}
			return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function usuario_todos_select()
	{
		try
		{
			$salida="";
			$sql="select rut,nombre_completo from usuario order by nombre_completo;";
			$stm = $this->pdo->prepare($sql); 
			$stm->execute();
			foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
				$salida.="<option value='".$r->rut."'>".$r->nombre_completo."</option>";
			}
			return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
}