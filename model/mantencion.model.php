<?php
class mantencionModel
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

	
	public function eventos()
	{
		try
		{
			$salida = array();
			//echo "entro al evento";
			$stmcount = $this->pdo->prepare("SELECT count(*) as cant FROM public.equipos WHERE estado=1;");
			$stmcount->execute();
			foreach($stmcount->fetchAll(PDO::FETCH_OBJ) as $rc)
			{
				$cantregistros=$rc->cant;
			}
			$datos_eventos="events: [	";
			$cont = 0;
			$stm  = $this->pdo->prepare("SELECT * FROM public.equipos WHERE estado=1 order by nombre;");
			$stm->execute();
			
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$cont++;
				
				list($y,$m,$d)=explode("-",$r->fecha_proxima_mantencion);
				//list($yc,$mc,$dc)=explode("-",$r->fecha_proxima_calibracion);
				
				if($r->estado==1){					
					$estado="important";
				}else{
					$estado="info";
				}
				
				$descripcion_mant =  '<div class="form-group row\">'.$r->fecha_proxima_mantencion.'</div>'.								
								'<div class=\"form-group row\">Marca: '.$r->marca.'</div>'.
								'<div class=\"form-group row\">Modelo: '.$r->modelo.'</div>'.
								'<div class=\"form-group row\">Año: '.$r->ano.'</div>'.
								'<div class=\"form-group row\">Serie: '.$r->n_serie.'</div>'.
								'<div class=\"form-group row\">Proveedor: '.$r->proveedor.'</div>';
				if($cont < $cantregistros){
					$mes=abs($m);
					$datos_eventos.="{
								
								title: '".$r->nombre."',
								description: '".$descripcion_mant."',
								start: '".$y."-".$mes."-".$d."',
								className: '".$estado."'
							},";
				}else{
					$datos_eventos.="{
								
								title: '".$r->nombre."',
								description: '".$descripcion_mant."',
								start: '".$y."-".$mes."-".$d."',
								className: '".$estado."'
							}";
					
				}
				
 
			/*	if($r->fecha_proxima_calibracion){
					$descripcion_cali =  '<div class="form-group row">'.$r->fecha_proxima_calibracion.'</div>'.
								'<div class="form-group row">Nombre: '.$r->nombre.'</div>'.
  								'<div class="form-group row">Marca: '.$r->marca.'</div>'.
  								'<div class="form-group row">Modelo: '.$r->modelo.'</div>'.
  								'<div class="form-group row">Año: '.$r->ano.'</div>'.
								'<div class="form-group row">Serie: '.$r->n_serie.'</div>'.
  								'<div class="form-group row">Proveedor'.$r->proveedor.'</div>'.
								'<div class="form-group row">Valor Calibración'.$r->valor.'</div>';
					 
					$datos_eventos.="{
							id: ".$r->id.",
							title: '".$r->nombre."',
							description: '".$descripcion_cali."',
							start: new Date(".$yc.", ".$mc.", ".$dc.", 16, 0),
							allDay: false,
							className: '".$estado."'
						},";
					
				}	*/		
			}
			
    		$datos_eventos.=" ],";
			
			return $datos_eventos;
			
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

			$stm = $this->pdo->prepare("SELECT id, nombre, marca, modelo, TO_CHAR(fecha_proxima_mantencion, 'dd/mm/yyyy') as fecha_mantencion,n_serie,proveedor FROM equipos WHERE estado < 3 ORDER BY fecha_proxima_mantencion DESC;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$id = $r->id;
				$editar='<a href="#" class="btn btn-sm btn-neutral" data-id="'.$id.'" data-toggle="modal" data-target="#editar_equipo_modal">EDITAR</a>';
				$eliminar='<a href="#" class="btn btn-sm btn-neutral" data-id="'.$id.'" data-toggle="modal" data-target="#eliminar_equipo_modal">ELIMINAR</a>';				
				$result[]=array($r->nombre,$r->marca,$r->modelo,$r->n_serie,$r->fecha_mantencion,$r->proveedor,$editar,$eliminar);
			}

			//$results = $stm->fetchAll(PDO::FETCH_ASSOC);
    		return json_encode($result);
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}

	public function guardar_datos($data)
	{   
		try 
		{			
			$sql = "INSERT INTO public.equipos (nombre,marca,modelo,ano,meses_alerta,estado,fecha_proxima_mantencion,fecha_alta,motivo_baja,observacion,n_serie,proveedor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$this->pdo->prepare($sql)
				 ->execute(array($data["nombre"],$data["marca"],$data["modelo"],$data["year"],$data["tiempo_alerta"],1,$data["fecha_mantencion"],$data["fecha_alta"],'','',$data["serie"],$data["proveedor"]));
			return 1;
		}
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}

			
}