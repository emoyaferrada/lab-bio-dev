<?
if ($_GET<>"") {
	$_POST["sel"]=json_decode($_GET["sel"]);
	$sel=$_GET["sel"];
	$_POST["hacer_reporte"]=1;
}
if (strlen($sel)==2){
	echo "<h4>No se seleccionaron análisis</h4>";
	exit;
}

require_once("../../model/cliente.model.php");
require_once("../../model/analisis.model.php");
include_once '../../assets/vendor/html2pdf/html2pdf.class.php';
		
//$htmlpdf = new HTML2FPDF();
$htmlpdf = new HTML2PDF('P','A4','es');


$cliente = new clienteModel();
$analisis = new analisisModel();

if ($_POST["aprobar"]==1){
	//var_dump($_POST);
	//guardar el reporte para el cliente y predio con el html
	//$analisis->guarda_reporte_cliente($_POST);
	/*foreach (json_decode($_POST["id_analisis"]) as $key => $value) {
		$res=$analisis->aprobar_analisis($value);
	}*/
	$res=1;
	if($res==1){
		
		$email_default="ana.jorquera.saez@gmail.com";
		$contenido_html = '<h3>  '.$_POST["tipo_info"].'</h3><br />Cliente: '.$_POST["cliente_nom"].'<br />Rut: '.$_POST["cliente_rut"].'<br />Fecha: '.date("d-m-Y");	
		
		//redactando  mensaje
		$asunto = "Reporte Aprobado";

		$to = $email_default;
		$target = "Sistema Web";
		$cabeceras = "MIME-Version: 1.0\r\n";
		$cabeceras.= "Content-type: text; charset=iso-8859-1\r\n";
		$cabeceras.= "From: no@reply <no@reply>\r\n";
		$nombre_pdf='reporte'.$_POST["cliente_rut"].'cliente'.$_POST["cliente_nom"].'.pdf';
		
		$content =$_POST["reporte_html"];		

		
		/*$htmlpdf->AddPage();
		$htmlpdf->WriteHTML($content);
		$htmlpdf->Output($nombre_pdf,'D');*/
		
		
		$htmlpdf->WriteHTML($content);
		$htmlpdf->Output($nombre_pdf,'D');		
		
		$attachments= array();
		$attachments['0']['file']=$nombre_pdf;
		
		$sendit = new AttachmentEmail($to, $asunto, $contenido_html, $nombre_pdf, $nombre_pdf);
		//$sendit -> mail();		
		
		echo '<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
		
	}	
		
	exit;
}
if ($_POST["rechazar_analisis"]==1){
	//echo "RECHAZO ... ";
	foreach (json_decode($_POST["id_analisis"]) as $key => $value) {
		$res=$analisis->rechazar_analisis($value,$_POST["motivo_rechazo"]);
	}
	exit;	
}


if ($_POST["buscar"]==1){
	$tabla = $analisis->tabla_reporte_cliente($_POST["cliente_id"]);
}
if ($_POST["hacer_reporte"]==1){

	//print_r($_POST['sel']);
	//exit;

	$cantidad_rep=count($_POST["sel"]);
	$i = 0;
	$cont = 0;
	$table_encabezado="";
	$table_encabezadopdf="";
	
  	foreach ($_POST["sel"] as $key => $value){
		$i++;
		
		if($i==1){
			$encabezado=$analisis->obtener_encabezado_reporte($value);
	  		$cuarteles_predio=$analisis->obtener_cuarteles($value);
			$nombre_analisis=$analisis->obtener_nombre_muestra($value);
			
			$table_encabezado = "<div align='center'><h2>Informe de ".$encabezado->nombre_tipo."</h2></div>
	  					<table class='table'>
	  					<tr><th>Razón Social/Nombre:</th><td>".$encabezado->razon_social."</td>
	  						<th>RUT:</th><td>".$encabezado->rut."</td>
						</tr>
	  					<tr><th>Persona de Contacto:</th><td>".$encabezado->nombre_cliente."</td>
	  						<th>Fecha Muestreo:</th><td>".$encabezado->fecha_toma_muestra."</td>
						</tr>
	  					<tr><th>Email:</th><td>".$encabezado->email."</td>
	  						<th>Fecha Ingreso Laboratorio:</th><td>".$encabezado->fecha_ingreso."</td>
						</tr>
	  					<tr><th>Muestreador:</th><td>".$encabezado->responsable_muestra."</td>
	  						<th>Fecha Salida Resultados:</th><td>".$encabezado->fecha_analisis."</td>
						</tr>
	  					<tr><th>Predio:</th><td>".$encabezado->nombre_predio."</td>
	  							<th>Cuarteles:</th><td>".$cuarteles_predio."</td>
						</tr>
	  					<tr><th>Profundidad:</th><td>".$encabezado->profundidad_analisis."</td>
							<th>&nbsp;</th><td>&nbsp;</td>
						</tr>
	  					</table>";
			
			$table_encabezadopdf = "<div><h2 style='color: #103b93;' align='center'>Informe de ".$encabezado->nombre_tipo."</h2></div>
	  					<table align='center' style='background-color: #103b93;  color: #ffffff;'>
	  					<tr><th style='padding-left: 8px; padding-right: 8px;'>Razón Social/Nombre:</th>
							<td style='padding-left: 8px; padding-right: 8px;'>".$encabezado->razon_social."</td>
	  						<th style='padding-left: 8px; padding-right: 8px;'>RUT:</th>
							<td style='padding-left: 8px; padding-right: 8px;'>".$encabezado->rut."</td>
						</tr>
	  					<tr><th style='padding-left: 8px; padding-right: 8px;'>Persona de Contacto:</th>
							<td style='padding-left: 8px; padding-right: 8px;'>".$encabezado->nombre_cliente."</td>
	  						<th style='padding-left: 8px; padding-right: 8px;'>Fecha Muestreo:</th>
							<td style='padding-left: 8px; padding-right: 8px;'>".$encabezado->fecha_toma_muestra."</td>
						</tr>
	  					<tr><th style='padding-left: 8px; padding-right: 8px;'>Email:</th>
							<td style='padding-left: 8px; padding-right: 8px;'>".$encabezado->email."</td>
	  						<th style='padding-left: 8px; padding-right: 8px;'>Fecha Ingreso Laboratorio:</th>
							<td style='padding-left: 8px; padding-right: 8px;'>".$encabezado->fecha_ingreso."</td>
						</tr>
	  					<tr><th style='padding-left: 8px; padding-right: 8px;'>Muestreador:</th>
							<td style='padding-left: 8px; padding-right: 8px;'>".$encabezado->responsable_muestra."</td>
	  						<th style='padding-left: 8px; padding-right: 8px;'>Fecha Salida Resultados:</th>
							<td style='padding-left: 8px; padding-right: 8px;'>".$encabezado->fecha_analisis."</td>
						</tr>
	  					<tr><th style='padding-left: 8px; padding-right: 8px;'>Predio:</th>
							<td style='padding-left: 8px; padding-right: 8px;'>".$encabezado->nombre_predio."</td>
	  						<th style='padding-left: 8px; padding-right: 8px;'>Cuarteles:</th>
							<td style='padding-left: 8px; padding-right: 8px;'>".$cuarteles_predio."</td>
						</tr>
	  					<tr><th style='padding-left: 8px; padding-right: 8px;'>Profundidad:</th>
							<td style='padding-left: 8px; padding-right: 8px;'>".$encabezado->profundidad_analisis."</td>
							<th>&nbsp;</th><td>&nbsp;</td>
						</tr>
	  					</table>";
			
			//obtener la salida desde la tabla analisis_muestra_variable con el id_analisis_variable
			$variables_resultado = $analisis->obtener_resultados_muestra($value);	
			
			foreach ($variables_resultado as $valuedato) {
				$datos[0][$cont] = $valuedato[1];
				$datos[1][$cont] = $valuedato[2];
				$datos[2][$cont] = $valuedato[4];
				$cont++;
			}		
		}
		
		$datosenc[0][$i] = $cuarteles_predio;
		$datosenc[1][$i] = $nombre_analisis;
		$cont = 0;		
		$variables_resultado = $analisis->obtener_resultados_muestra($value);
		foreach ($variables_resultado as $valuedat){
			
			$datosvpdf[$i][$cont] = $valuedat[3];
			
			if($valuedat[5]!="" && $valuedat[3]<$valuedat[5] )
			{
				$datosv[$i][$cont] = '<font color="#FF0000">'.$valuedat[3].'</font>';
				$datos[3][$cont]='<font color="#FF0000">Fuera de Rango</font>';
			}
			else if($valuedat[6]!="" && $valuedat[3]>$valuedat[6])
			{
				$datosv[$i][$cont] = '<font color="#FF0000">'.$valuedat[3].'</font>';
				$datos[3][$cont]='<font color="#FF0000">Fuera de Rango</font>';
			}
			else{
				$datosv[$i][$cont] = $valuedat[3];
			}
			$cont++;
		}

	} 

	$table3="<h4>Detalle</h4><table class='table'>";
	$tablapdf="<br><table style='border-collapse: collapse; width: 100%; border: 1px solid #103b93;' align='center'>";
	$table3.="<tr><th> Variable</th>
	              <th> Unidad</th>";
	
	$tablapdf.= "<tr><th style='border: 1px solid #103b93; padding: 5px; text-align:left;'>Cuartel:</th>
	              <th style='border: 1px solid #103b93; padding: 5px; text-align:center;'> </th>";

	
	for($j=1; $j<=$i; $j++){
		//$table3.="<th>".$analisis->obtener_nombre_muestra($datosenc[$j])."</th>";
		$table3.="<th>".$analisis->obtener_nombre_muestra($value)."</th>";

		//$tablapdf.="<th style='border: 1px solid #103b93; padding: 5px; text-align:center;'>".$analisis->obtener_nombre_muestra($datosenc[0][$j])."</th>";
	}	
	
		
	$table3.="<th>Nivel de Suficiencia</th></tr>";
	$tablapdf.="<th style='border: 1px solid #103b93; padding: 5px; text-align:center; background-color: #5e72e4;' rowspan='3' >Nivel de <br>Suficiencia</th></tr>";
	
	$tablapdf.= "<tr><th style='border: 1px solid #103b93; padding: 5px; text-align:left;'>Nombre:</th>
	              <th style='border: 1px solid #103b93; padding: 5px; text-align:center;'> </th>";
	
	for($j=1; $j<=$i; $j++){		
		$tablapdf.="<th style='border: 1px solid #103b93; padding: 5px; text-align:center;'>".$datosenc[1][$j]."</th>";
	}	
	$tablapdf.="</tr>";
	
	$tablapdf.= "<tr><th style='border: 1px solid #103b93; padding: 5px; text-align:left;'></th>
	              <th style='border: 1px solid #103b93; padding: 5px; text-align:center;'>Unidad</th>";
	
	for($j=1; $j<=$i; $j++){		
		$tablapdf.="<th style='border: 1px solid #103b93; padding: 5px; text-align:center;'></th>";
	}	
	$tablapdf.="</tr>";
	
	
	
	for($j=0; $j<$cont; $j++){		
		$table3.="<tr><td>".$datos[0][$j]."</td>
					  <td>".$datos[1][$j]."</td>";
		$tablapdf.="<tr>
					  <td style='border: 1px solid #103b93; padding: 5px; text-align:left;'>".$datos[0][$j]."</td>
					  <td style='border: 1px solid #103b93; padding: 5px; text-align:center;'>".$datos[1][$j]."</td>";
		
		for($k=1; $k<=$i; $k++){
			$table3.="<td>".$datosv[$k][$j]."</td>";
			$tablapdf.="<td style='border: 1px solid #103b93; padding: 5px; text-align:center;'>".$datosvpdf[$k][$j]."</td>";
		}
		
	   	$table3.="<td>".$datos[2][$j]."</td><td>".$datos[3][$j]."</td></tr>";	
		$tablapdf.="<td style='border: 1px solid #103b93; padding: 5px; text-align:center; background-color: #5e72e4;'>".$datos[2][$j]."</td></tr>";
	}		
	
	$table3.="</table>";	  
	$tablapdf.="</table>";	
	
	$inicio="<div id='contenido'>";
	$encabezadoimp="<div align='right' class='d-print-none'><a href='#' id='imprimir'><h4><i class='fa fa-print' aria-hidden='true'></h4></i></a></div>";
	$final="<div style='page-break-after: always;'></div></div>";
	
	$reporte_html = $table_encabezadopdf.$tablapdf;
	
	$reporte = $inicio.$encabezadoimp.$table_encabezado.$table3.$final;
	$tipo_info = "Informe de ".$encabezado->nombre_tipo;
	$cliente_nom=$encabezado->razon_social;
	$cliente_rut=$encabezado->rut;
	
	echo $reporte;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="container">
<form method="POST" action="reporte-analisis.php"> 
    <div class="form-group row" id="div_rechazo">
      <div class="col">
      <label for="motivo_rechazo" class="col-form-label">Motivo del Rechazo</label> 
        <input type="text" class="form-control" name="motivo_rechazo" id="motivo_rechazo">
        <button name="rechazar_analisis" id="rechazar_analisis" value="1" type="submit" class="btn btn-danger">Rechazar</button>
      </div> 
    </div> 

  <div class="form-group row">
  	<input type="hidden" name="id_analisis" value="<?echo $sel;?>">
  	<input type="hidden" name="reporte_html" value="<?echo $reporte_html;?>">
  	<input type="hidden" name="cliente_id" value="<?echo $cliente_id;?>">
  	<input type="hidden" name="predio_id" value="<?echo $predio_id;?>">
	<input type="hidden" name="tipo_analisis_id" value="<?echo $tipo_analisis_id;?>">
    <input type="hidden" name="cliente_nom" value="<?echo $cliente_nom;?>">
  	<input type="hidden" name="cliente_rut" value="<?echo $cliente_rut;?>">
	<input type="hidden" name="tipo_info" value="<?echo $$tipo_info;?>">
	
    <button type="submit" id="aprobar" name="aprobar" value="1" class="btn btn-primary">Aprobar Reporte</button>

    <button name="rechazar" id="rechazar" type="button" class="btn btn-danger">Rechazar</button>
  </div>
  </form>
</div>
</body>

<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
$( document ).ready(function(){
	$('#imprimir').on('click', function(){
		//$("#contenido").printElement();
		window.print();
	});

	$("#div_rechazo").hide();
  	$("#rechazar").on("click", function(){
      $("#div_rechazo").show();
      $("#botones").hide();
  	});
})
</script>
</html>

<?
class AttachmentEmail {
	private $from = 'no-reply@labbio.cl';
	private $from_name = 'Laboratorio lab-bio';
	private $reply_to = 'no-reply@labbio.cl';
	private $to = '';
	private $subject = '';
	private $message = '';
	private $attachment = '';
	private $attachment_filename = '';

	public function __construct($to, $subject, $message, $attachment='', $attachment_filename='') {
		$this -> to = $to;
		$this -> subject = $subject;
		$this -> message = $message;
		$this -> attachment = $attachment;
		$this -> attachment_filename = $attachment_filename;
	}
	public function mail() {
		if (!empty($this -> attachment)) {
			$filename = empty($this -> attachment_filename) ? basename($this -> attachment) : $this -> attachment_filename ;
			$path = dirname($this -> attachment);
			$mailto = $this -> to;
			$from_mail = $this -> from;
			$from_name = $this -> from_name;
			$replyto = $this -> reply_to;
			$subject = $this -> subject;
			$message = $this -> message;
			$eol = PHP_EOL;
			$file = $path.'/'.$filename;
			$file_size = filesize($file);
			$handle = fopen($file, "r");
			$content = fread($handle, $file_size);
			fclose($handle);
			$content = chunk_split(base64_encode($content));
			$uid = md5(uniqid(time()));
			$name = basename($file);
			$header = "From: ".$from_name." <".$from_mail.">".$eol;
			$header.= "Reply-To: ".$replyto.$eol;
			$header.= "MIME-Version: 1.0\r\n";
			$header.= "Content-Type: multipart/mixed; boundary=\"".$uid."\"".$eol;
			$header.= "This is a multi-part message in MIME format.".$eol;
			$hmensage = "--".$uid."\r\n";
			$hmensage.= "Content-type:text/plain; charset=iso-8859-1".$eol;
			$hmensage.= "Content-Transfer-Encoding: 8bit".$eol.$eol;
			$hmensage.= strip_tags(str_replace("", "\n", substr($message, (strpos($message, "<body>")+6)))).$eol.$eol;
			$hmensage.= "--".$uid."\r\n";
			$hmensage.= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol; // use diff. tyoes here
			$hmensage.= "Content-Transfer-Encoding: base64".$eol;
			$hmensage.= "Content-Disposition: attachment; filename=\"".$filename."\"".$eol.$eol;
			$hmensage.= $content.$eol.$eol;
			$hmensage.= "--".$uid."--";

			if(mail($mailto, $subject, $hmensage, $header)){
				return true;
			}else{
				return false;
			}
		}else{
			$header = "From: ".($this -> from_name)." <".($this -> from).">\r\n";
			$header.= "Reply-To: ".($this -> reply_to)."\r\n";
			if(mail($this -> to, $this -> subject, $this -> message, $header)){
				return true;
			}else{
				return false;
			}
		}
	}
}


?>

