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
	public function guardar_venta($monto, $cliente, $num_hes, $archivo_hes, $num_orden, $archivo_orden, $tipo_pago, $valor_factura, $archivo_factura, $fecha_factura, $num_factura, $obs, $archivo_coti)/// guardar factura de pago
	{
		try 
		{
			$fecha=date("Y/m/d");
			$estado="";
			if($tipo_pago=="abono"){$estado="a"; /*abierta*/}else{$estado="c"; /*cerrada*/}
			
			$sql = "INSERT INTO ventas(fecha, tipo_pago, monto_total, estado, cliente_id) VALUES (?, ?, ?, ?, ?)";
			$this->pdo->prepare($sql)->execute(array($fecha, $tipo_pago, $monto, $estado, $cliente));		

			$stm = $this->pdo->prepare("SELECT MAX(id) as id FROM ventas");
			$stm->execute();
			$r = $stm->fetch(PDO::FETCH_OBJ);
			
			$sql2 = "INSERT INTO factura(fecha, pagada, cliente_id, hes_numero, oc_archivo, factura_valor, factura_archivo, factura_fecha, factura_numero, observacion, ventas_id, hes_archivo, oc_numero, cotizacion_archivo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$this->pdo->prepare($sql2)->execute(array($fecha, 'false', $cliente, $num_hes, $archivo_orden, $valor_factura, $archivo_factura, $fecha_factura, $num_factura, $obs, $r->id, $archivo_hes, $num_orden, $archivo_coti));			
			
			return $r->id;		
		}		
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return error_log($e->getMessage());
		}
	}
	public function guardar_factura($cliente,$num_hes,$archivo_hes,$num_orden,$fecha_orden,$valor_orden,$archivo_orden,$valor_factura,$archivo_factura,$fecha_factura,$num_factura,$obs,$venta_id)/// guardar factura de pago
	{
		try 
		{			
			$sql = "INSERT INTO factura(fecha, pagada, cliente_id, hes_numero, oc_archivo, factura_valor, factura_archivo, factura_fecha, factura_numero, observacion, ventas_id, hes_archivo, oc_numero) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$this->pdo->prepare($sql)->execute(array(date("Y/m/d"), 'false', $cliente, $num_hes, $archivo_orden, $valor_factura, $archivo_factura, $fecha_factura, $num_factura, $obs, $venta_id, $archivo_hes, $num_orden));			
			
			return 1;		
		}		
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}
	public function guardar_analisis_fact($analisis,$venta,$valor)/// guardar analisis segun venta
	{
		try 
		{
			$sql = "INSERT INTO factura_trabajo(analisis_id, ventas_id, valor)
					VALUES (?, ?, ?)";
			$this->pdo->prepare($sql)
				 ->execute(array($analisis, $venta, $valor));

			$sqlup = "UPDATE analisis SET ventas_id = ".$venta." WHERE id=".$analisis.";";
			$this->pdo->prepare($sqlup)->execute();
			
			return 1;			
		}		
		catch (Exception $e) 
		{
			//die($e->getMessage());
			return $e->getMessage();
		}
	}		
	
	public function guardar_pago($id_fact,$fecha_pago,$monto_pagado,$forma_pago,$comprobante) /// pago				
	{
		try 
		{		
			$sql = "INSERT INTO pagos(fecha_pago, monto_pagado, factura_id, forma_pago, comprobante) VALUES (?, ?, ?, ?, ?)";
			$resp= $this->pdo->prepare($sql)->execute(array($fecha_pago, $monto_pagado, $id_fact, $forma_pago, $comprobante));
			
			if($resp){
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
			$actual = date("Y-m-d");
			$stm = $this->pdo->prepare("SELECT analisis.id as id, TO_CHAR( analisis.fecha_ingreso, 'dd/mm/yyyy') as fecha_ingreso,
				TO_CHAR( analisis.fecha_toma_muestra, 'dd/mm/yyyy') as fecha_toma_muestra, cliente.nombre as nombre_cliente, tipo_analisis.precio as precio, tipo_analisis.nombre as tipo_analisis, tipo_analisis.descripcion as descripcion
				FROM analisis, cliente, tipo_analisis 
				WHERE cliente.rut=analisis.cliente_id
				AND cliente.rut='".$rut."'
				AND analisis.ventas_id=0
				AND tipo_analisis.id=analisis.tipo_analisis_id
				ORDER BY analisis.fecha_ingreso DESC;");
			
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$i++;
				$tabla_contenido.='<tr>	
						<td><input type="checkbox" value="'.$i.'" id="facturach'.$i.'" name="facturach" onchange="seleccionarchek('.$i.')" /></td>
				        <td>'.$r->id.'<input type="hidden" name="id_analisis'.$i.'" value="'.$r->id.'"></td>
						<td>'.$r->nombre_cliente.'<input type="hidden" name="id_cliente'.$i.'" value="'.$rut.'"></td>
						<td>'.$r->fecha_ingreso.'</td>
						<td>'.$r->fecha_toma_muestra.'</td>
						<td>'.$r->descripcion.'</td>
						<td><input type="text" class="col-4 form-control" name="valor'.$i.'" id="valor'.$i.'" value="'.$r->precio.'" onchange="actualizavalor('.$i.')"></td>             
					  </tr> ';
			}
			
			if($i==0){
				$tabla='NO HAY ANALISIS PARA FACTURAR<input type="hidden" name="contador" id="contador" value="0">';
			}else{			
				$i=$i+1;
				$tabla='<table class="table">
						<thead>
						  <th>Incluir</th>
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
						  	<td><input type="checkbox" value="'.$i.'" id="facturach'.$i.'" name="facturach" onchange="seleccionarchek('.$i.')" /></td>
							<td>Adicionales</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td><input type="text" class="col-4 form-control" name="valor'.$i.'" id="valor'.$i.'" value=""></td>             
						  </tr>  
						  
						  <tr>
						  	<td>&nbsp;</td>
							<td><input type="hidden" name="contador" id="contador" value="'.$i.'"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td align="right">Neto</td>
							<td><div id="neto"></div><input type="hidden" name="total_neto" id="total_neto" value="0"></td>             
						  </tr>  
						  <tr>
						  	<td>&nbsp;</td>
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
	public function listar_ventas($rut,$idventa)
	{
		try
		{
			if($idventa !=0){
				$op=" AND v.id=".$idventa;
				$chek= ' checked="true"';
			}else{
				$op="";
				$chek= '';
			}
			$i=0;
			$result = array();
			$actual=date("Y-m-d");
			$stm = $this->pdo->prepare("SELECT v.id, TO_CHAR(v.fecha, 'dd/mm/yyyy') as fecha, v.tipo_pago, v.monto_total, v.estado
				FROM ventas as v, cliente as c WHERE c.rut=v.cliente_id AND c.rut='".$rut."' AND v.estado='a'".$op." ORDER BY v.fecha DESC;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{
				$i++;
				$stm2 = $this->pdo->prepare("SELECT SUM(factura_valor) AS valor_factura FROM factura WHERE ventas_id=".$r->id);
				$stm2->execute();
				foreach($stm2->fetchAll(PDO::FETCH_OBJ) as $r2)
				{
					$monto_pagado = $r2->valor_factura;				
					$monto_pendiente = $r->monto_total - $monto_pagado;				
				}		
				if($monto_pendiente!=0){	
					$tabla_contenido.='<tr>
						<td><input type="checkbox" value="'.$i.'" id="ventach'.$i.'" name="ventach" onchange="seleccionarchek('.$i.')"'.$chek.' /></td>
						<td>'.$r->fecha.'<input type="hidden" name="id_venta'.$i.'" id="id_venta'.$i.'" value="'.$r->id.'"></td>				
						<td>'.$r->tipo_pago.'<input type="hidden" id="tipo_pago'.$i.'" name="tipo_pago'.$i.'" value="'.$r->tipo_pago.'"></td>
						<td>'.$r->monto_total.'<input type="hidden" name="monto_total'.$i.'" id="monto_total'.$i.'" value="'.$r->monto_total.'"></td>
						<td>'.$monto_pagado.'<input type="hidden" name="estado'.$i.'" id="estado'.$i.'" value="'.$r->estado.'"></td>
						<td>'.$monto_pendiente.'<input type="hidden" id="monto_pendi'.$i.'"  name="monto_pendi'.$i.'" value="'.$monto_pendiente.'"></td>									             
					</tr> ';	
				}			
			}
			$tabla = '<table class="table">
					<thead>
					  <th></th>
					  <th>Fecha</th>
					  <th>Tipo Pago</th>					  
					  <th>Monto Total</th> 	
					  <th>Monto Facturado</th> 
					  <th>Monto Pendiente<input type="hidden" id="contventa" name="contventa" value="'.$i.'"></th>					  
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
	public function listar_deudas($rut,$fact)
	{
		try
		{
			$opcion="";
			if($rut !=0 && $fact !=0){
				$opcion = " AND f.id='".$fact."'";
			}	
			
			$i=0;
			$result = array();
			$actual=date("Y-m-d");
			$stm = $this->pdo->prepare("SELECT f.id as id, TO_CHAR(f.fecha, 'dd/mm/yyyy') as fecha, TO_CHAR(f.factura_fecha, 'dd/mm/yyyy') as fecha_factura, c.nombre as nombre_cliente, f.factura_valor, f.factura_numero FROM factura AS f, cliente AS c WHERE c.rut=f.cliente_id AND c.rut='".$rut."' AND f.pagada='false'".$opcion." ORDER BY f.fecha DESC;");
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{						
				$i++;				
				$tabla_contenido.='<tr>
					<td><input type="checkbox" value="'.$i.'" id="facturach'.$i.'" name="facturach" onchange="seleccionarchek('.$i.')" /></td>
					<td>'.$r->id.'<input type="hidden" name="id_factura'.$i.'" value="'.$r->id.'"></td>
					<td>'.$r->nombre_cliente.'</td>
					<td>'.$rut.'<input type="hidden" name="id_cliente'.$i.'" value="'.$rut.'"></td>
					<td>'.$r->fecha.'</td>
					<td>'.$r->factura_numero.'<input type="hidden" name="num_factura'.$i.'" value="'.$r->factura_numero.'"></td>
					<td>'.$r->fecha_factura.'</td>						
					<td>'.$r->factura_valor.'<input type="hidden" name="monto'.$i.'" id="monto'.$i.'" value="'.$r->factura_valor.'"></td>					
				  </tr>';				
			}
			$tabla = '<table class="table">
					<thead>
					  <th></th>
					  <th>Id</th>
					  <th>Cliente</th>
					  <th>Rut</th>
					  <th>Fecha Ingreso</th>
					  <th>Numero Factura</th>
					  <th>Fecha Factura</th>				  				 				  
					  <th>Valor Factura<input type="hidden" id="cont_fact" name="cont_fact" value="'.$i.'"></th>						  
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
			$cuerpo="";
			$sql="SELECT f.id, f.factura_fecha, f.factura_numero, f.factura_valor, c.rut, c.nombre FROM cliente AS c, factura AS f WHERE c.rut=f.cliente_id AND f.pagada='false' ORDER BY f.factura_fecha DESC;";
			$stm = $this->pdo->prepare($sql); 
			$stm->execute();
			$i=0;
			foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) {				
				$cuerpo.="<tr>				
				<td>".$r->nombre."</td>
				<td>".$r->rut."</td>				
				<td>".$r->factura_numero."</td>	
				<td>".$r->factura_fecha."</td>	
				<td>$ ".$r->factura_valor."</td>			
				<td><button name='ver".$i."' id='ver".$i."' value='".$r->id."' onClick='abre_detalles_pago(".$r->rut.",".$r->id.");' type='button' class='btn btn-primary'>Pagar</button></td></tr>";
				$i++;
			}
			if($cuerpo==""){
				$salida="NO SE ENCUENTRAN FACTURAS PENDIENTE DE PAGO";
			}else{			
				$salida='<table class="table align-items-center table-flush">
					<thead class="thead-light">
					  <tr>
						<th scope="col">Nombre</th>
						<th scope="col">RUT</th>
						<th scope="col">Numero</th>
						<th scope="col">Fecha</th>					
						<th scope="col">Monto</th>                   
						<th scope="col">Pagar</th>
					  </tr>
					</thead>
					<tbody>'.$cuerpo.'</tbody>
				  </table>';
			}			
			return $salida;
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}
	public function ventas_pendientes()
	{
		try
		{
			$i=0;
			$salida="";
			$cuerpo="";
			$sql="SELECT v.id, v.fecha, v.tipo_pago, v.monto_total, c.rut, c.nombre FROM cliente AS c, ventas AS v WHERE c.rut=v.cliente_id AND v.estado='a' ORDER BY v.fecha DESC;";
			$stm = $this->pdo->prepare($sql); 
			$stm->execute();
			foreach ($stm->fetchAll(PDO::FETCH_OBJ) as $r) 
			{				
				$monto_pagado = 0;
				$porc = 0;
				$color ="";
				$monto_total = $r->monto_total;
				$stmf = $this->pdo->prepare("SELECT SUM(factura_valor) AS monto_pagado FROM factura WHERE pagada='true' AND ventas_id=".$r->id);
				$stmf->execute();
				foreach($stmf->fetchAll(PDO::FETCH_OBJ) as $r2)
				{
					$monto_pagado=$r2->monto_pagado;
				}							
				$stmp = $this->pdo->prepare("SELECT SUM(factura_valor) AS monto_pendiente FROM factura WHERE pagada='false' AND ventas_id=".$r->id);
				$stmp->execute();
				foreach($stmp->fetchAll(PDO::FETCH_OBJ) as $rp)
				{
					$monto_facturado=$rp->monto_pendiente;
				}
				$pendiente = $monto_total- $monto_pagado;
				
				if($monto_pagado==0){
					$color="bg-danger";
				}else{
					$porc = round(($monto_pagado*100)/$monto_total);
					if ($porc < 50){ $color="bg-danger";}
					else{$color="bg-warning";}
				}		
				
				$cuerpo.="<tr>				
				<td>".$r->nombre."</td>
				<td>".$r->rut."</td>
				<td>".$r->tipo_pago."</td>				
				<td>".$r->fecha."</td>
				<td>$ ".$monto_total."</td>				
				<td>$ ".$monto_pagado."</td>				
				<td>$ ".$pendiente."</td>
				<td>$ ".$monto_facturado."</td>				
				<td><div class='progress' style='height: 20px;'><div class='progress-bar ".$color."' role='progressbar' aria-valuenow='".$porc."' aria-valuemin='0' aria-valuemax='100' style='width: ".$porc."%;'>".$porc."%</div></div></td>
				<td><button name='ver".$i."' id='ver".$i."' value='".$r->id."' onClick='abre_detalles_pago(".$r->rut.",".$r->id.");' type='button' class='btn btn-primary'>Realizar Factura</button></td></tr>";
				$i++;
			}
			if($cuerpo==""){
				$salida="NO SE ENCUENTRAN VENTAS PENDIENTES";
			}else{
				$salida='<table class="table align-items-center table-flush">
					<thead class="thead-light">
					  <tr>
						<th scope="col">Nombre</th>
						<th scope="col">RUT</th>
						<th scope="col">Tipo Pago</th>
						<th scope="col">Fecha Venta</th>
						<th scope="col">Monto Total</th>
						<th scope="col">Monto Pagado</th>           
						<th scope="col">Monto Pendiente</th>
						<th scope="col">Monto Facturado No Pagado</th>
						<th scope="col">Avance</th>
						<th scope="col">Facturar Abono</th>
					  </tr>
					</thead>
					<tbody>
					 '.$cuerpo.'
					</tbody>
				  </table>';
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
			$stm = $this->pdo->prepare("SELECT f.id as id, TO_CHAR(f.fecha, 'dd/mm/yyyy') as fecha, TO_CHAR(f.factura_fecha, 'dd/mm/yyyy') as fecha_factura, c.nombre as nombre_cliente, c.rut, f.factura_numero, f.factura_valor, f.pagada FROM factura AS f, cliente AS c WHERE c.rut=f.cliente_id".$filtro."  ORDER BY f.fecha DESC;");		
			$stm->execute();
			foreach($stm->fetchAll(PDO::FETCH_OBJ) as $r)
			{	
				
				$stmp = $this->pdo->prepare("SELECT * FROM pagos WHERE factura_id=".$r->id);
				$stmp->execute();
				foreach($stmp->fetchAll(PDO::FETCH_OBJ) as $rp)
				{
					$fecha_pago = $rp->fecha_pago;
					$forma_pago = $rp->forma_pago;
				}
				
				$i++;
				$tabla_contenido.='<tr>						
				        <td>'.$r->id.'</td>
						<td>'.$r->nombre_cliente.'<input type="hidden" name="id_cliente'.$i.'" value="'.$rut.'"></td>
						<td>'.$r->rut.'</td>
						<td>'.$r->fecha.'</td>
						<td>'.$estado.'</td>
						<td>'.$r->fecha_factura.'</td>
						<td>'.$r->factura_numero.'</td>						
						<td>'.$r->factura_valor.'</td>
						<td><button name="verif'.$i.'" id="verif'.$i.'" value="'.$r->id.'" onClick="abre_detalles('.$r->id.',\'ifa\');" type="button" class="btn btn-primary">Ver</button></td>						
						<td>'.$r->hes_numero.'</td>
						<td><button name="verih'.$i.'" id="verih'.$i.'" value="'.$r->id.'" onClick="abre_detalles('.$r->id.',\'ihes\');" type="button" class="btn btn-primary">Ver</button></td>						
						<td>'.$r->oc_numero.'</td>
						<td><button name="verioc'.$i.'" id="verioc'.$i.'" value="'.$r->id.'" onClick="abre_detalles('.$r->id.',\'ioc\');" type="button" class="btn btn-primary">Ver</button></td>						
						<td>'.$fecha_pago.'</td>
						<td>'.$forma_pago.'</td>
						<td><button name="verc'.$i.'" id="verc'.$i.'" value="'.$r->id.'" onClick="abre_detalles('.$r->id.',\'ic\');" type="button" class="btn btn-primary">Ver</button></td>
					  </tr> ';				
			}
					
			$tabla = '<table class="table">
					<thead>
					  <th>Id<input type="hidden" id="cont_fact" name="cont_fact" value="'.$i.'"></th>					 
					  <th>Cliente</th>
					  <th>RUT</th>	
					  <th>Fecha Ingreso</th>
					  <th>Estado</th>					  
					  <th>Fecha Factura</th>
					  <th>Numero Factura</th>
					  <th>Valor Factura</th>					  
					  <th>Imagen Factura</th>
					  <th>Numero HES</th>
					  <th>Imagen HES</th>
					  <th>Numero OC</th>
					  <th>Imagen OC</th>
					  <th>Fecha Pago</th>					 
					  <th>Forma Pago</th>
					  <th>Comprobante</th>					 
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
	function ver_detalle($id,$tipo){
		try
		{
			if($tipo=="ifa"){
				$stma = $this->pdo->prepare("SELECT factura_archivo FROM factura WHERE id=".$id."; ");
				$stma->execute();
				foreach($stma->fetchAll(PDO::FETCH_OBJ) as $r)
				{					
					if($r->factura_archivo){
						$contenido='<img src="https://198.251.64.144/sada-util/lab-bio-dev/archivos/facturas/'.$r->factura_archivo.'" alt="Factura"/>';		
					}else{
						$contenido='<h2>NO HAY IMAGEN</h2>';	
					}
					
								
				}
				return $contenido;

			}else if($tipo=="ihes"){
				$stma = $this->pdo->prepare("SELECT hes_archivo FROM factura WHERE id=".$id."; ");
				$stma->execute();
				foreach($stma->fetchAll(PDO::FETCH_OBJ) as $r)
				{					
					if($r->factura_archivo){
						$contenido='<img src="https://198.251.64.144/sada-util/lab-bio-dev/archivos/hes/'.$r->hes_archivo.'" alt="HES"/>';		
					}else{
						$contenido='<h2>NO HAY IMAGEN</h2>';	
					}
					
								
				}
				return $contenido;

			}else if($tipo=="ioc"){
				$stma = $this->pdo->prepare("SELECT oc_archivo FROM factura WHERE id=".$id."; ");
				$stma->execute();
				foreach($stma->fetchAll(PDO::FETCH_OBJ) as $r)
				{					
					if($r->factura_archivo){
						$contenido='<img src="https://198.251.64.144/sada-util/lab-bio-dev/archivos/ordenes_compra/'.$r->oc_archivo.'" alt="Orden de Compra"/>';		
					}else{
						$contenido='<h2>NO HAY IMAGEN</h2>';	
					}
					
								
				}
				return $contenido;

			}else if($tipo=="ic"){
				$stma = $this->pdo->prepare("SELECT comprobante FROM pagos WHERE factura_id=".$id."; ");
				$stma->execute();
				foreach($stma->fetchAll(PDO::FETCH_OBJ) as $r)
				{			
					if($r->comprobante){
						$contenido='<img src="https://198.251.64.144/sada-util/lab-bio-dev/archivos/pagos/'.$r->comprobante.'" alt="Comprobante"/>';	
					}else{
						$contenido='<h2>NO HAY IMAGEN</h2>';	
					}										
				}
				return $contenido;

			}/*else if($tipo=="d"){
				$stma = $this->pdo->prepare("SELECT ta.descripcion, ft.valor FROM analisis AS a, factura AS f, tipo_analisis AS ta, ventas AS v, factura_trabajo AS ft
					WHERE ta.id=a.tipo_analisis_id
					AND f.venta_id=a.venta_id
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
				
				
				$contenido='<h4>ID : '.$id.'</h4><h4>Analisis Facturados</h4><div class="row">'.$tablaA.'</div>';

				return $contenido;
			}*/
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}		
	}

}