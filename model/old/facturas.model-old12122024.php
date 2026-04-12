<?php
class facturasModel
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
	public function guardar_factura($pagada,$num_cuotas,$fecha,$cliente,$monto,$num_factura,$foma_pago,$obs,$valor_cuota,$ges,$num_orden,$fecha_orden,$valor_orden,$archivo_orden,$tipo_pago)/// guardar factura de pago
	{
		try 
		{
			$sql = "INSERT INTO factura(fecha, numero, pagada, fecha_factura, monto, numero_cuota, forma_pago, observacion, valor_cuota, cliente_id, oc_ges, oc_numero, oc_fecha, oc_valor, oc_achivo, tipo_pago)
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$this->pdo->prepare($sql)
				 ->execute(array(date("Y/m/d"), $num_factura, $pagada, $fecha, $monto, $num_cuotas, $foma_pago, $obs, $valor_cuota, $cliente, $ges, $num_orden, $fecha_orden, $valor_orden, $archivo_orden, $tipo_pago));		

			$stm = $this->pdo->prepare("select max(id) as id from factura;");
			$stm->execute();
			$r=$stm->fetch(PDO::FETCH_OBJ);
			return $r->id;			
		}
		
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
	public function guardar_analisis_fact($analisis,$factura,$valor)/// guardar analisis segun factura
	{
		try 
		{
			if (!$data["estado_trabajo"]) $data["estado_trabajo"]=3;
			$sql = "INSERT INTO factura_trabajo(analisis_id, factura_id, valor)
					VALUES (?, ?, ?)";
			$this->pdo->prepare($sql)
				 ->execute(array($analisis, $factura, $valor));
			return 1;			
		}
		
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}		
	
	public function guardar_pago_cuota($id_fact,$fecha_pago,$numero_factura,$fecha_factura,$monto_pagado,$forma_pago,$num_cuotas,$pagada) /// pago de cuota
	{
		try 
		{		
			$sql = "INSERT INTO pago_cuotas(cantidad_cuotas, fecha_pago, numero_factura, fecha_factura, monto_pagado, factura_id, forma_pago) VALUES (?, ?, ?, ?, ?, ?, ?)";
			$this->pdo->prepare($sql)->execute(array($num_cuotas, $fecha_pago, $numero_factura, $fecha_factura, $monto_pagado, $id_fact, $forma_pago));
			
			//return "INSERT INTO pago_cuotas(cantidad_cuotas, fecha_pago, numero_factura, fecha_factura, monto_pagado, factura_id, forma_pago) VALUES ({$num_cuotas},{$fecha_pago},{$numero_factura},{$fecha_factura},{$monto_pagado},{$id_fact},{$forma_pago})";
			
			
			if($pagada==true){
				$sqlup = "UPDATE factura SET pagada='true' WHERE id=".$id_fact.";";
				$this->pdo->prepare($sqlup)->execute();
			}		
			return 1;
		}		
		catch (Exception $e) 
		{			
			return $e->getMessage();
		}
	}		
	public function listar_analisis($rut)
	{
		try
		{
			$i=0;
			$result = array();
			$actual=date("Y-m-d");
			$stm = $this->pdo->prepare("SELECT analisis.id as id, TO_CHAR(fecha_ingreso, 'dd/mm/yyyy') as fecha_ingreso,
				TO_CHAR(fecha_toma_muestra, 'dd/mm/yyyy') as fecha_toma_muestra, cliente.nombre as nombre_cliente, tipo_analisis.nombre as tipo_analisis, estado_trabajo.nombre as estado_trabajo, tipo_analisis.descripcion as descripcion
				FROM analisis, cliente, estado_trabajo, tipo_analisis 
				WHERE cliente.rut=analisis.cliente_id 
				AND cliente.rut='".$rut."'					
				AND estado_trabajo.id=analisis.estado_trabajo 
				AND analisis.estado_trabajo=3				
				AND tipo_analisis.id=analisis.tipo_analisis_id
				AND analisis.id NOT IN (SELECT factura_trabajo.analisis_id FROM factura_trabajo)
				ORDER BY fecha_ingreso DESC;");
			
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$i++;
				$tabla_contenido.='<tr>						
				        <td>'.$r->id.'<input type="hidden" name="id_analisis'.$i.'" value="'.$r->id.'"></td>
						<td>'.$r->nombre_cliente.'<input type="hidden" name="id_cliente'.$i.'" value="'.$rut.'"></td>
						<td>'.$r->fecha_ingreso.'</td>
						<td>'.$r->fecha_toma_muestra.'</td>
						<td>'.$r->descripcion.'</td>
						<td><input type="text" class="col-4 form-control" onchange="sumar('.$i.');" name="valor'.$i.'" id="valor'.$i.'"></td>             
					  </tr> ';				
			}
			
			if($i==0){
				$tabla='NO HAY ANALISIS PARA FACTURAR<input type="hidden" name="contador" id="contador" value="0">';
			}else{			
				$tabla='<table class="table">
						<thead>
						  <th>Id</th>
						  <th>Cliente</th>
						  <th>Fecha Ingreso</th>
						  <th>Fecha Muestra</th>
						  <th>Nombre analisis</th>					 
						  <th>Valor</th>          
						</thead>
						<tbody>			
						  '.$tabla_contenido.'
						  <tr>
							<td><input type="hidden" name="contador" name="contador" value="'.$i.'"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td align="right">Neto</td>
							<td><div id="neto"></div></td>             
						  </tr>  
						  <tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td align="right">Iva</td>
							<td><div id="iva"></div></td>             
						  </tr> 
						  <tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td align="right">Total</td>
							<td><div id="total"></div></td>             
						  </tr>  
						</tbody>
					  </table>';		
				}
    		return $tabla;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function listar_deudas($rut)
	{
		try
		{
			$i=0;
			$result = array();
			$actual=date("Y-m-d");
			$stm = $this->pdo->prepare("SELECT f.id as id, TO_CHAR(f.fecha, 'dd/mm/yyyy') as fecha, TO_CHAR(f.fecha_factura, 'dd/mm/yyyy') as fecha_factura, c.nombre as nombre_cliente, f.monto, f.numero_cuota, f.valor_cuota, f.numero
				FROM factura AS f, cliente AS c WHERE c.rut=f.cliente_id AND c.rut='".$rut."' AND f.pagada='0' ORDER BY f.fecha DESC;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$monto_pagado="";
				$cuotas_pagadas="";
				$stm2 = $this->pdo->prepare("SELECT SUM(monto_pagado) AS monto_pagado, SUM(cantidad_cuotas) AS cuotas_pagadas FROM pago_cuotas WHERE factura_id=".$r->id);
				$stm2->execute();
				foreach($stm2->fetchAll(PDO::FETCH_OBJ) as $r2)
				{
					$monto_pagado=$r2->monto_pagado;
					$cuotas_pagadas=$r2->cuotas_pagadas;
				}
				
				$i++;
				$tabla_contenido.='<tr>
						<td><input type="checkbox" value="'.$i.'" id="facturach'.$i.'" name="facturach" onchange="seleccionarchek('.$i.')" /></td>
				        <td>'.$r->id.'<input type="hidden" name="id_factura'.$i.'" value="'.$r->id.'"></td>
						<td>'.$r->nombre_cliente.'<input type="hidden" name="id_cliente'.$i.'" value="'.$rut.'"></td>
						<td>'.$r->fecha.'</td>
						<td>'.$r->fecha_factura.'</td>
						<td>'.$r->numero.'</td>
						<td>'.$r->monto.'</td>
						<td>'.$monto_pagado.'</td>
						<td>'.$r->numero_cuota.'<input type="hidden" id="num_cuota'.$i.'" name="num_cuota'.$i.'" value="'.$r->numero_cuota.'"></td>
						<td>'.$r->valor_cuota.'<input type="hidden" id="valor_cuota'.$i.'" name="valor_cuota'.$i.'" value="'.$r->valor_cuota.'"></td>
						<td>'.$cuotas_pagadas.'<input type="hidden" id="cuotas_pagadas'.$i.'" name="cuotas_pagadas'.$i.'" value="'.$cuotas_pagadas.'"></td>             
					  </tr> ';				
			}
					
			$tabla = '<table class="table">
					<thead>
					  <th></th>
					  <th>Id</th>
					  <th>Cliente</th>					  
					  <th>Fecha Ingreso</th>
					  <th>Fecha Factura</th>
					  <th>Numero Factura</th>
					  <th>Monto Total</th>
					  <th>Monto Pagado</th>
					  <th>Total Cuotas</th>
					  <th>Valor Cuatos</th>	
					  <th>Cuotas Pagadas<input type="hidden" id="cont_fact" name="cont_fact" value="'.$i.'"></th>
					</thead>
					<tbody>			
					  '.$tabla_contenido.'					  
					</tbody>
				  </table>';		

    		return $tabla;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function clientes_deuda()
	{
		try
		{
			$salida="";
			$sql="SELECT DISTINCT c.rut, c.nombre FROM Cliente AS c, factura AS f WHERE c.rut=f.cliente_id AND f.pagada='false' ORDER BY c.nombre;";
			$stm = $this->pdo->prepare($sql); 
			$stm->execute();
			foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
				$salida.="<option value='".$r->rut."'>".$r->nombre."</option>";
			}
			if($salida==""){
				$salida.="<option value='1'>No se encuentran Deudores</option>";
			}
			return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function facturas_pendientes()
	{
		try
		{
			$salida="";
			$sql="SELECT f.id, f.numero, f.monto, f.fecha, f.numero_cuota, c.rut, c.nombre FROM Cliente AS c, factura AS f WHERE c.rut=f.cliente_id AND f.pagada='false' ORDER BY c.nombre;";
			$stm = $this->pdo->prepare($sql); 
			$stm->execute();
			foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {
				
				$cuotas_pagadas=0;
				$stm2 = $this->pdo->prepare("SELECT SUM(cantidad_cuotas) AS cuotas_pagadas FROM pago_cuotas WHERE factura_id=".$r->id);
				$stm2->execute();
				foreach($stm2->fetchAll(PDO::FETCH_OBJ) as $r2)
				{
					$cuotas_pagadas=$r2->cuotas_pagadas;
				}
				if($cuotas_pagadas==0){
					$color="bg-danger";
				}else{
					$porc = round(($cuotas_pagadas*100)/$r->numero_cuota);
					if ($porc < 50){ $color="bg-danger";}
					else{$color="bg-warning";}
				}
				
				$salida.="<tr><td>".$r->numero."</td><td>".$r->nombre."</td><td>".$r->fecha."</td><td>$ ".$r->monto."</td><td>".$r->numero_cuota."</td><td>".$cuotas_pagadas."</td><td><div class='progress' style='height: 20px;'><div class='progress-bar ".$color."' role='progressbar' aria-valuenow='".$porc."' aria-valuemin='0' aria-valuemax='100' style='width: ".$porc."%;'>".$porc."%</div></div></td></tr>";
			}
			if($salida==""){
				$salida.="<option value='1'>No se encuentran Facturas Pendientes</option>";
			}
			return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	function listar_facturas($rut,$fechai,$fechaf){
		try
		{
			$filtro="";
			if($rut !=0 || $rut !=""){
				$filtro.= " AND c.rut='".$rut."'";
			}
			if($fechai !="" && $fechaf !=""){
				$filtro.= " AND f.fecha>='".$fechai."' AND f.fecha<='".$fechaf."'";
			}
						
			$i = 0;			
			$stm = $this->pdo->prepare("SELECT f.id as id, TO_CHAR(f.fecha, 'dd/mm/yyyy') as fecha, TO_CHAR(f.fecha_factura, 'dd/mm/yyyy') as fecha_factura, c.nombre as nombre_cliente, f.monto, f.numero_cuota, f.valor_cuota, f.numero, f.pagada
				FROM factura AS f, cliente AS c WHERE c.rut=f.cliente_id".$filtro." ORDER BY f.fecha DESC;");		
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$monto_pagado="";
				$cuotas_pagadas="";
				$stm2 = $this->pdo->prepare("SELECT SUM(monto_pagado) AS monto_pagado, SUM(cantidad_cuotas) AS cuotas_pagadas FROM pago_cuotas WHERE factura_id=".$r->id);
				$stm2->execute();
				foreach($stm2->fetchAll(PDO::FETCH_OBJ) as $r2)
				{
					$monto_pagado=$r2->monto_pagado;
					$cuotas_pagadas=$r2->cuotas_pagadas;
				}
				
				if($r->pagada == "true"){$estado="Pagado";}else{$estado="Pendiente";}
				
				$i++;
				$tabla_contenido.='<tr>						
				        <td>'.$r->id.'</td>
						<td>'.$r->nombre_cliente.'<input type="hidden" name="id_cliente'.$i.'" value="'.$rut.'"></td>
						<td>'.$estado.'</td>
						<td>'.$r->fecha.'</td>
						<td>'.$r->fecha_factura.'</td>
						<td>'.$r->numero.'</td>
						<td>'.$r->monto.'</td>
						<td>'.$r->numero_cuota.'</td>
						<td>'.$cuotas_pagadas.'<input type="hidden" id="cuotas_pagadas'.$i.'" name="cuotas_pagadas'.$i.'" value="'.$cuotas_pagadas.'"></td>
						<td>'.$monto_pagado.'</td>
						<td><button name="ver'.$i.'" id="ver'.$i.'" value="'.$r->id.'" onClick="abre_detalles('.$r->id.');" type="button" class="btn btn-primary">Ver Detalle</button></td>
					  </tr> ';				
			}
					
			$tabla = '<table class="table">
					<thead>
					  <th>Id</th>					 
					  <th>Cliente</th>
					  <th>Estado</th>	
					  <th>Fecha Ingreso</th>
					  <th>Fecha Factura</th>
					  <th>Numero Factura</th>
					  <th>Monto Total</th>
					  <th>Total Cuotas</th>
					  <th>Cuotas Pagadas<input type="hidden" id="cont_fact" name="cont_fact" value="'.$i.'"></th>
					  <th>Monto Pagado</th>	
					  <th>&nbsp;</th>	
					</thead>
					<tbody>			
					  '.$tabla_contenido.'	
					</tbody>
				  </table>';		

    		return $tabla;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}		
	}
	function ver_detalle($id){
		try
		{
			$stm = $this->pdo->prepare("SELECT f.id as id, TO_CHAR(f.fecha, 'dd/mm/yyyy') as fecha, TO_CHAR(f.fecha_factura, 'dd/mm/yyyy') as fecha_factura, c.nombre, f.monto, f.numero_cuota, f.valor_cuota, f.numero, f.forma_pago, f.observacion, f.pagada
				FROM factura AS f, cliente AS c WHERE c.rut=f.cliente_id AND f.id=".$id.";");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				if($r->pagada == "true"){$estado="Pagado";}else{$estado="Pendiente";}
				$contenidof.='<tr>				        
						<td>'.$estado.'</td>
						<td>'.$r->nombre.'</td>
						<td>'.$r->fecha.'</td>
						<td>'.$r->fecha_factura.'</td>
						<td>'.$r->numero.'</td>
						<td>'.$r->monto.'</td>
						<td>'.$r->numero_cuota.'</td>
						<td>'.$r->forma_pago.'</td>
						<td>'.$r->observacion.'</td>
					  </tr>';				
			}					
			$tablaf = '<table class="table" style="width: 800px;">
					<thead>	
					  <th>Estado</th>	
					  <th>Cliente</th>					  
					  <th>Fecha Ingreso</th>
					  <th>Fecha Factura</th>
					  <th>Numero Factura</th>					 
					  <th>Monto Pagar</th>
					  <th>Cantidad Cuotas</th>
					  <th>Forma Pago</th>
					  <th>Observacion</th>				  
					</thead>
					<tbody>			
					  '.$contenidof.'					  
					</tbody>
				  </table>';
			$total  = 0;			
			$stma = $this->pdo->prepare("SELECT ta.descripcion, ft.valor FROM analisis AS a, factura AS f, factura_trabajo AS ft, tipo_analisis AS ta
				WHERE ta.id=a.tipo_analisis_id
				AND a.id = ft.analisis_id
				AND f.id = ft.factura_id AND f.id=".$id."; ");
			$stma->execute();
			foreach($stma->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$contenidoa.='<tr>
						<td>'.$r->descripcion.'</td>
						<td>'.$r->valor.'</td>		
					  </tr> ';				
				$total = $total + $r->valor;
			}					
			$tablaA = '<table class="table">
					<thead>
					  <th>Nombre Analisis</th>
					  <th>Valor</th>
					</thead>
					<tbody>			
					  '.$contenidoa.'
					  <tr>				        
						<td>Total</td>
						<td>'.$total.'</td>	
					  </tr>
					</tbody>
				  </table>';
			$total = 0;
			$cuotas = 0;
			$stmc = $this->pdo->prepare("SELECT * FROM pago_cuotas WHERE factura_id=".$id.";");
			$stmc->execute();
			foreach($stmc->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$contenidoc.='<tr>
						<td>'.$r->fecha_pago.'</td>
						<td>'.$r->cantidad_cuotas.'</td><td>'.$r->monto_pagado.'</td>
						<td>'.$r->fecha_factura.'</td>
						<td>'.$r->numero_factura.'</td>
						<td>'.$r->forma_pago.'</td></tr>';
				
				$total = $total + $r->monto_pagado;
				$cuotas = $cuotas  + $r->cantidad_cuotas;
			}					
			$tablac = '<table class="table">
					<thead>
					  <th>Fecha Pago</th>
					  <th>Cantidad Cuotas</th>
					  <th>Monto Pagado</th>
					  <th>Fecha Factura</th>
					  <th>Numero de Factura</th>
					  <th>Forma Pago</th>
					</thead>
					<tbody>
					  '.$contenidoc.'
					  <tr>
					    <td>Total</td>
						<td>'.$cuotas.'</td>
						<td>'.$total.'</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					  </tr>
					</tbody>
				  </table>';
			
			$contenido='<h4>ID : '.$id.'</h4><h4>Datos Factura</h4><div class="row">'.$tablaf.'</div><h4>Analisis</h4><div class="row">'.$tablaA.'</div><h4>Pagos de Cuotas</h4><div class="row">'.$tablac.'</div>';

    		return $contenido;
			
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}		
	}

}