<?
  require_once("../../model/cliente.model.php");
  require_once("../../model/analisis.model.php");
  require_once("../../model/usuario.model.php");
  $cliente = new clienteModel();
  $analisis = new analisisModel();
  $usuario = new usuarioModel();

  $nuevo_id=($analisis->obtener_ultimo_id()) + 1;

?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script language="javascript">
function eliminardato(num){	
	alert("entra");
		 var variables="";
		 var nom_tipo= "";
		 var contenido= "";		 
		 var cont=parseInt(document.getElementById("cont").value);
		 var id_inicial=parseInt(document.getElementById("idinicial").value);	
	     var contenido_tabla=document.getElementById("tabla_content");
		 var i=0;		 	 
		 var tabla="";
		 var vari="";
		 var id=id_inicial;
		 for(var c=0; c< cont; c=c+1){	
			 	vari="";	
				if(c!=num){
					if(document.getElementById("tipo"+c).value==1){
						nom_tipo="Foliar";
						vari = '<td><input type="hidden" id="info1'+i+'" name="info1'+i+'" value="'+document.getElementById("info1"+c).value+'">'+document.getElementById("info1"+c).value+'</td><td><input type="hidden" id="info2'+c+'" name="info2'+i+'" value="'+document.getElementById("info2"+c).value+'">'+document.getElementById("info2"+c).value+'</td><td><input type="hidden" id="info3'+c+'" name="info3'+c+'" value="'+document.getElementById("info3"+c).value+'">'+document.getElementById("info3"+c).value+'</td><td>&nbsp;</td>';
					}else if(document.getElementById("tipo"+c).value==2){
						nom_tipo="Agua";
						vari = '<td><input type="hidden" id="info1A1'+i+'" name="info1A1'+i+'" value="'+document.getElementById("info1A1"+c).value+'">'+document.getElementById("info1A1"+c).value+'<input type="hidden" id="info1A2'+i+'" name="info1A2'+i+'" value="'+document.getElementById("info1A2"+c).value+'">-'+document.getElementById("info1A2"+c).value+'<input type="hidden" id="info1A3'+i+'" name="info1A3'+i+'" value="'+document.getElementById("info1A3"+c).value+'">-'+document.getElementById("info1A3"+c).value+'</td><td><input type="hidden" id="info2A1'+i+'" name="info2A1'+i+'" value="'+document.getElementById("info2A1"+c).value+'">'+document.getElementById("info2A1"+c).value+'<input type="hidden" id="info2A2'+i+'" name="info2A2'+i+'" value="'+document.getElementById("info2A2"+c).value+'">-'+document.getElementById("info2A2"+c).value+'<input type="hidden" id="info2A3'+i+'" name="info2A3'+i+'" value="'+document.getElementById("info2A3"+c).value+'">-'+document.getElementById("info2A3"+c).value+'</td><td><input type="hidden" id="info3A1'+i+'" name="info3A1'+i+'" value="'+document.getElementById("info3A1"+c).value+'">'+document.getElementById("info3A1"+c).value+'<input type="hidden" id="info3A2'+i+'" name="info3A2'+i+'" value="'+document.getElementById("info3A2"+c).value+'">-'+document.getElementById("info3A2"+c).value+'<input type="hidden" id="info3A3'+i+'" name="info3A3'+i+'" value="'+document.getElementById("info3A3"+c).value+'">-'+document.getElementById("info3A3"+c).value+'</td><td><input type="hidden" id="info4A1'+i+'" name="info4A1'+i+'" value="'+document.getElementById("info4A1"+c).value+'">'+document.getElementById("info4A1"+c).value+'<input type="hidden" id="info4A2'+i+'" name="info4A2'+i+'" value="'+document.getElementById("info4A2"+c).value+'">-'+document.getElementById("info4A2"+c).value+'<input type="hidden" id="info4A3'+i+'" name="info4A3'+i+'" value="'+document.getElementById("info4A3"+c).value+'">-'+document.getElementById("info4A3"+c).value+'</td>';
					}else if(document.getElementById("tipo"+c).value==3){
						nom_tipo="Suelo";
						vari = '<td><input type="hidden" id="info1'+i+'" name="info1'+i+'" value="'+document.getElementById("info1"+c).value+'">'+document.getElementById("info1"+c).value+'</td><td><input type="hidden" id="info2'+i+'" name="info2'+i+'" value="'+document.getElementById("info2"+c).value+'">'+document.getElementById("info2"+c).value+'</td><td><input type="hidden" id="info3'+i+'" name="info3'+i+'" value="'+document.getElementById("info3"+c).value+'">'+document.getElementById("info3"+c).value+'</td><td><input type="hidden" id="info4'+i+'" name="info4'+i+'" value="'+document.getElementById("info4"+c).value+'">'+document.getElementById("info4"+c).value+'</td>';
					}else if(document.getElementById("tipo"+c).value==4){
						nom_tipo="Fertilizante";
						vari = '<td><input type="hidden" id="info1'+i+'" name="info1'+i+'" value="'+document.getElementById("info1"+c).value+'">'+document.getElementById("info1"+c).value+'</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
					}				

					contenido = contenido + '<tr><td><input type="hidden" name="id'+i+'" value="'+id+'">'+id+'</td><td><input type="hidden" id="fecha'+i+'" name="fecha'+i+'" value="'+document.getElementById("fecha"+c).value+'">'+document.getElementById("fecha"+c).value+'</td><td><input type="hidden" id="resp'+i+'" name="resp'+i+'" value="'+document.getElementById("resp"+c).value+'">'+document.getElementById("resp"+c).value+'</td><td><input type="hidden" id="user'+i+'" name="user'+i+'" value="'+document.getElementById("user"+c).value+'">'+document.getElementById("user"+c).value+'</td><td><input type="hidden" id="tipo'+i+'" name="tipo'+i+'" value="'+document.getElementById("tipo"+c).value+'">'+nom_tipo+'</td>'+
								vari+'<td><button name="eliminaranalisis'+i+'" id="eliminaranalisis'+i+'" type="button" onclick(eliminardato('+i+')) class="btn btn-primary">Eliminar</button></td>';
					id = id+1;
					i=i+1;					
				}
			}
 			 tabla ='<table id="tabla_datos" width="100%" border="0" cellspacing="0" cellpadding="0">'+
			 '<tbody><tr><td>ID</td><td>Fecha</td><td>Responsable</td><td>Usuario</td><td>Tipo An&aacute;lisis</td><td>Dato 1</td><td>Dato 2</td><td>Dato 3</td><td>Dato 4</td><td>Elimina</td></tr></tbody>'+contenido+'</table>';
			//$('#tabla_datos').remove();
			//$('#tabla_content').html(tabla);
	//alert(tabla);
			contenido_tabla.innerHTML = tabla;
			 document.getElementById("cont").value=c;
			
		 
	 } // fin funcion
	
</script>
</head>
<body>

<div class="container">
<?
if ($_GET["id"]=="exito"){
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}

?>

  <form  method="post" action="../../controller/analisis.controller.php">

    <h4>Datos cliente dsapijdoisa cds</h4>
    <div class="form-group row">
      <div class="col">
        <label for="cliente">Cliente</label> 
        <select id="cliente_id" name="cliente_id" class="custom-select">
          <option value="0">Seleccione cliente</option>
          <? echo $cliente->cliente_select();?>
        </select>
      </div>
      <div class="col">
        <label for="predio">Predio</label> 
          <select id="predio_cliente_id" name="predio_cliente_id" class="custom-select">
            <option value="0">Seleccione Predio</option>
          </select>
      </div>
      <div class="col">
        <label for="zona">Zona de riego</label> 
          <select name="zona" id="zona" class="custom-select">
            <option>Seleccione Zona</option>
          </select>
      </div>    
    </div>


    <h4>Datos Ingreso</h4>
    <div class="form-group row">
      <div class="col">
        <label for="fecha_ingreso" class="col-form-label">Fecha programación</label> 
        <input type="date" class="form-control" name="fecha_ingreso" access="false" id="fecha_ingreso" required="required" aria-required="true">
      </div>
      <div class="col">
		<label for="tipo_analisis" class="col-form-label">Tipo de análisis</label> 
        <select id="tipo_analisis" name="tipo_analisis" class="custom-select">
          <option value=0>Seleccione</option>
          <? echo $analisis->tipo_analisis_select();?>
        </select>
	  </div>
      <div class="col">
        <label for="resp_muestra">Responsable muestra</label> 
        <select id="resp_muestra" name="resp_muestra" class="custom-select">
          <option>1. Propietario</option>
          <option>2. LB-TRACK</option>
          <option>3. Otro</option>
        </select>    
	  </div>
      <div class="col">
        <label for="usuario_toma_muestra">Usuario toma muestra</label> 
        <input type="text" class="form-control" id="nombre_muestra" name="nombre_muestra">
        <select id="usuario_toma_muestra" name="usuario_toma_muestra" class="custom-select">
        	<option value="0">Usuario</option>
          <? echo $usuario->usuario_select();?>
        </select>
      </div>
    </div>
    
    <h4>Detalles</h4> 
    <div class="form-group row" id="div_suelo">
      <label for="prof1" class="col-4 col-form-label">Profundidad muestra 1</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad_1" id="profundidad_1">
      </div>
      <label for="prof2" class="col-4 col-form-label">Profundidad muestra 2</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad_2" id="profundidad_2">
      </div>
      <label for="prof3" class="col-4 col-form-label">Profundidad muestra 3</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad_3" id="profundidad_3">
      </div>
      <label for="prof4" class="col-4 col-form-label">Profundidad muestra 4</label> 
      <div class="col-8">
        <input type="text" class="form-control" name="profundidad_4" id="profundidad_4">
      </div>
    </div>
    
    <div class="form-group row" id="div_agua">
      <table class="table">
        <thead>
          <th>#</th>
          <th>Nombre origen</th>
          <th>Tipo origen</th>
          <th>Tipo emisor</th>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td><input type="text" class="col-4 form-control" name="nombre_1" id="nombre_1"></td>
            <td>
              <select id="origen_1" name="origen_1" class="custom-select">
                <option value="1">Pozo</option>
                <option value="2">Canal</option>
                <option value="3">Tranque</option>
              </select>
            </td>
            <td>
              <select id="emisor_1" name="emisor_1" class="custom-select">
                <option value="1">Riego goteo</option>
                <option value="2">Aspersor</option>
                <option value="3">Pivote central</option>
              </select>     
            </td>
          </tr>
          <tr>
            <td>2</td>
            <td><input type="text" class="col-4 form-control" name="nombre_2" id="nombre_2"></td>
            <td>
              <select id="origen_2" name="origen_2" class="custom-select">
                <option value="1">Pozo</option>
                <option value="2">Canal</option>
                <option value="3">Tranque</option>
              </select>
            </td>
            <td>
              <select id="emisor_2" name="emisor_2" class="custom-select">
                <option value="1">Riego goteo</option>
                <option value="2">Aspersor</option>
                <option value="3">Pivote central</option>
              </select>     
            </td>
          </tr>          
          <tr>
            <td>3</td>
            <td><input type="text" class="col-4 form-control" name="nombre_3" id="nombre_3"></td>
            <td>
              <select id="origen_3" name="origen_3" class="custom-select">
                <option value="1">Pozo</option>
                <option value="2">Canal</option>
                <option value="3">Tranque</option>
              </select>
            </td>
            <td>
              <select id="emisor_3" name="emisor_3" class="custom-select">
                <option value="1">Riego goteo</option>
                <option value="2">Aspersor</option>
                <option value="3">Pivote central</option>
              </select>     
            </td>
          </tr>
          <tr>
            <td>4</td>
            <td><input type="text" class="col-4 form-control" name="nombre_4" id="nombre_4"></td>
            <td>
              <select id="origen_4" name="origen_4" class="custom-select">
                <option value="1">Pozo</option>
                <option value="2">Canal</option>
                <option value="3">Tranque</option>
              </select>
            </td>
            <td>
              <select id="emisor_4" name="emisor_4" class="custom-select">
                <option value="1">Riego goteo</option>
                <option value="2">Aspersor</option>
                <option value="3">Pivote central</option>
              </select>     
            </td>
          </tr>
        </tbody>
      </table>    
    </div>     

    <div class="form-group row" id="div_fertilizante">
      <div class="col">
        <label for="fuente" class="col-form-label">Fuente (tipo fertilizante)</label> 
        <input type="text" class="form-control" name="fuente" id="fuente">
      </div>
    </div>

    <div class="form-group row" id="div_foliar">
      <div class="col">
        <label for="especie">Especie</label> 
        <select id="especie" name="especie" class="custom-select">
          <option value="0">Especie</option>
          <? echo $analisis->tipo_especie_select();?>
        </select>
      </div>
      <div class="col">
        <label for="variedad">Variedad</label> 
        <select id="variedad" name="variedad" class="custom-select">
          <option value="0">Variedad</option>
          <? //echo $analisis->variedad_select();?>
        </select>
      </div>
      <div class="col">
        <label for="estado_fenologico">Estado Fenologico</label> 
        <select id="estado_fenologico" name="estado_fenologico" class="custom-select">
          <option value="0">Estado fenológico</option> 
          <? //echo $analisis->estado_fenologico_select();?>
        </select>
      </div>
    </div>
	  
   <div class="form-group row">
      <div class="offset-4 col-8">
        <button name="agregaranalisis" id="agregaranalisis" value="1" type="button" class="btn btn-primary" >Agregar</button>
      </div>    
    </div>
    <div class="form-group row">
      <div class="col">
		 <input type="hidden" id="cont" name="cont" value="0">
		 <input id="idinicial" name="idinicial" type="hidden" value="<? echo $nuevo_id;?>" readonly />
		 <div id="tabla_content">
         <table id="tabla_datos" width="100%" border="0" cellspacing="0" cellpadding="0">
			<tbody><tr><td>ID</td><td>Fecha</td>
				<td>Responsable</td>
				<td>Usuario</td>
				<td>Tipo An&aacute;lisis</td>
				<td>Dato 1</td>
				<td>Dato 2</td>
				<td>Dato 3</td>
				<td>Dato 4</td>
				<td>Elimina</td></tr>
				</tbody></table>	  
		</div> 
      </div>  
	</div> 
	
 
	  
    <div class="form-group row">
      <div class="col">
        <label for="observaciones" class="col-form-label">Observaciones</label> 
        <input type="text" class="form-control" name="observaciones" id="observaciones">
      </div>
    </div>  
    <div class="form-group row">
      <div class="offset-4 col-8">
        <button name="guardar_nuevo" id="guardar_nuevo" value="1" type="submit" class="btn btn-primary">Guardar</button>
      </div>    
    </div>
  </form>
</div>
<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
  $( document ).ready(function(){
	  
	 var fecha_prog = $('#fecha_ingreso').val();
	 var tipo_analisis= $('#tipo_analisis').val();
	 var resp_muestra = $('#resp_muestra').val();
	 var nom_muestra= $('#usuario_toma_muestra').val();	
	 var id = Number($('#idinicial').val()); 
	 
	

    var lista_predios= <?echo json_encode($cliente->predio_select());?>;
    var lista_estados_fenologicos= <?echo json_encode($analisis->estado_fenologico_select());?>;
    var predios=$('#predio_cliente_id');
    var estado_fenologico=$('#estado_fenologico');
    //Creación del codigo de Barras al pinchar el botón de codigo
    
    $('#div_foliar').hide();
    $('#div_suelo').hide();
    $('#div_agua').hide();
    $('#div_fertilizante').hide();
    //$("#cliente").attr("disabled", true);

    $("#alerta").delay(4000).slideUp(200, function() {
        $(this).alert('close');
    });

    //Cambiar options del select de predios de clinte
    $('#cliente_id').on( 'change', function() {
      predios.empty();
      var valor=this.value;
      predios.append($("<option></option>").attr("value", 0).text("Seleccione Predio"));
      //console.log(lista_predios);
      $.each(lista_predios, function(key, value) {  
        //console.log(valor,value[0]);
        if (valor==value[0]) predios.append($("<option></option>").attr("value", value[1]).text(value[2]));
      });
    });
    

    //Cambiar options del select de estados fenologicos por especie
    $('#especie').on( 'change', function() {
      estado_fenologico.empty();
      var valor=this.value;
      console.log(lista_estados_fenologicos);
      $.each(lista_estados_fenologicos, function(key, value) {  
        //console.log(valor,value[0]);
        if (valor==value[0]) estado_fenologico.append($("<option></option>").attr("value", value[2]).text(value[1]));
      });
    });
    
	$('#tipo_analisis').on( 'change', function() {
		if (this.value==1){
			$('#div_foliar').show();
			$('#div_suelo').hide();
			$('#div_agua').hide();
			$('#div_fertilizante').hide();
		}
		if (this.value==2){
			$('#div_foliar').hide();
			$('#div_suelo').hide();
			$('#div_agua').show();
			$('#div_fertilizante').hide();
		}
		if (this.value==3){
			$('#div_foliar').hide();
			$('#div_suelo').show();
			$('#div_agua').hide();
			$('#div_fertilizante').hide();
		}
		if (this.value==4){
			$('#div_foliar').hide();
			$('#div_suelo').hide();
			$('#div_agua').hide();
			$('#div_fertilizante').show();
		}
	})
    $('#zona').on( 'change', function() {
      //ajax para traer tabla de cuarteles de la zona
      var predio_id=$("#predio_cliente_id").val();
      var zona_id=$("#zona").val();
      if (zona_id != "Seleccione zona"){
        $.ajax({
            url:"../../controller/cliente.controller.php",    //the page containing php script
            type: "post",    //request type,
            data: {ajax_cuarteles: 1, predio_id: predio_id, zona_id:zona_id },
            success:function(result){
                console.log(result);
                //poner en el div
                $("#tabla_cuarteles").html(result);
            }
        });      
      }
    })
    $('#predio_cliente_id').on( 'change', function() {
      //ajax para traer tabla de cuarteles de la zona
      var predio_id=$("#predio_cliente_id").val();
      if (predio_id != ""){
        $.ajax({
            url:"../../controller/cliente.controller.php",    //the page containing php script
            type: "post",    //request type,
            data: {cuarteles_predio: 1, predio_id: predio_id},
            success:function(result){
                var arr=JSON.parse(result);
                var zona=$("#zona");
                zona.empty();
                zona.append($("<option></option>").attr("value", 0).text("Seleccione Zona"));
                $.each(arr, function(key, value) {  
                  //console.log(key,value);
                  zona.append($("<option></option>").attr("value", value).text(value));
                });
            }
        });      
      }
    })
	  
	 $('#agregaranalisis').click(function() {	
		 var e=0;
		 var variables="";
		 var nom_tipo= "";
		 var contenido= "";
		 e=parseInt($('#cont').val());
		
		  
		 fecha_prog = $('#fecha_ingreso').val();
		 tipo_analisis= $('#tipo_analisis').val();
		 resp_muestra = $('#resp_muestra').val();
		 nom_muestra= $('#usuario_toma_muestra').val();	
		
		 
		 var vari="";
		
		 
		 if(tipo_analisis==1){ //foliar
			var especie = $('#especie').val();
		 	var variedad = $('#variedad').val();
		 	var estado = $('#estado_fenologico').val();
			contenido = contenido + '<td><input type="hidden" id="info1'+e+'" name="info1'+e+'" value="'+especie+'">'+especie+'</td>'+
						'<td><input type="hidden" id="info2'+e+'" name="info2'+e+'" value="'+variedad+'">'+variedad+'</td>'+
						'<td><input type="hidden" id="info3'+e+'" name="info3'+e+'" value="'+estado+'">'+estado+'</td>'+
						'<td>&nbsp;</td>';
		 }else if(tipo_analisis==2){//agua
			var nombre1 = $('#nombre_1').val();
		 	var origen1 = $('#origen_1').val();
		 	var emisor1 = $('#emisor_1').val();
			contenido =	contenido + '<td><input type="hidden" id="info1A1'+e+'" name="info1A1'+e+'" value="'+nombre1+'">'+nombre1+
							'<input type="hidden" id="info1A2'+e+'" name="info1A2'+e+'" value="'+origen1+'">-'+origen1+
							'<input type="hidden" id="info1A3'+e+'" name="info1A3'+e+'" value="'+emisor1+'">-'+emisor1+'</td>';	
			if($('#nombre_2').val()){
				var nombre2 = $('#nombre_2').val();
		 		var origen2 = $('#origen_2').val();
		 		var emisor2 = $('#emisor_2').val();
				contenido = contenido + '<td><input type="hidden" id="info2A1'+e+'" name="info2A1'+e+'" value="'+nombre2+'">'+nombre2+
							'<input type="hidden" id="info2A2'+e+'" name="info2A2'+e+'" value="'+origen2+'">-'+origen2+
							'<input type="hidden" id="info2A3'+e+'" name="info2A3'+e+'" value="'+emisor2+'">-'+emisor2+'</td>';			
			}else{
				contenido = contenido + '<td>&nbsp;</td>';
			}
			if($('#nombre_3').val()){
				var nombre3 = $('#nombre_3').val();
		 		var origen3 = $('#origen_3').val();
		 		var emisor3 = $('#emisor_3').val();
				contenido = contenido + '<td><input type="hidden" id="info3A1'+e+'" name="info3A1'+e+'" value="'+nombre3+'">'+nombre3+
								'<input type="hidden" id="info3A2'+e+'" name="info3A2'+e+'" value="'+origen3+'">-'+origen3+
								'<input type="hidden" id="info3A3'+e+'" name="info3A3'+e+'" value="'+emisor3+'">-'+emisor3+'</td>';			
			}else{
				contenido =	contenido + '<td>&nbsp;</td>';
			}
			if($('#nombre_4').val()){
				var nombre4 = $('#nombre_4').val();
		 		var origen4 = $('#origen_4').val();
		 		var emisor4 = $('#emisor_4').val();
			contenido =	contenido + '<td><input type="hidden" id="info4A1'+e+'" name="info4A1'+e+'" value="'+nombre4+'">'+nombre4+
							'<input type="hidden" id="info4A2'+e+'" name="info4A2'+e+'" value="'+origen4+'">-'+origen4+
							'<input type="hidden" id="info4A3'+e+'" name="info4A3'+e+'" value="'+emisor4+'">-'+emisor4+'</td>';			
			}else{
				contenido =	contenido + '<td>&nbsp;</td>';
			}
		 }else if(tipo_analisis==3){ // suelo
			var prof1 = $('#profundidad_1').val();
			var prof2 = $('#profundidad_2').val();
			var prof3 = $('#profundidad_3').val();
			var prof4 = $('#profundidad_4').val();	
			contenido =contenido + '<td><input type="hidden" id="info1'+e+'" name="info1'+e+'" value="'+prof1+'">'+prof1+'</td>'+
						'<td><input type="hidden" id="info2'+e+'" name="info2'+e+'" value="'+prof2+'">'+prof2+'</td>'+
						'<td><input type="hidden" id="info3'+e+'" name="info3'+e+'" value="'+prof3+'">'+prof3+'</td>'+
						'<td><input type="hidden" id="info4'+e+'" name="info4'+e+'" value="'+prof4+'">'+prof4+'</td>'; 
			
			 
		}else if(tipo_analisis==3){ // fertilizante 
			contenido = contenido + '<td><input type="hidden" id="info1'+e+'" name="info1'+e+'" value="'+fuente+'">'+fuente+'</td>';
		}	 
		 var tabla='<tr><td><input type="hidden" name="id'+e+'" value="'+id+'">'+id+'</td><td><input type="hidden" id="fecha'+e+'" name="fecha'+e+'" value="'+fecha_prog+'">'+fecha_prog+'</td><td><input type="hidden" id="resp'+e+'" name="resp'+e+'" value="'+resp_muestra+'">'+resp_muestra+'</td><td><input type="hidden" id="user'+e+'" name="user'+e+'" value="'+nom_muestra+'">'+nom_muestra+'</td><td><input type="hidden" id="tipo'+e+'" name="tipo'+e+'" value="'+tipo_analisis+'">'+tipo_analisis+'</td>'+contenido+'<td><button name="eliminaranalisis'+e+'" id="eliminaranalisis'+e+'" onclick="eliminardato('+e+')" type="button" class="btn btn-primary">Eliminar</button></td></tr>';	 
		 id = id+1;
		 e = e+1;
		 $('#tabla_datos').append(tabla);	
		 $('#cont').val(e);	
		
	 }) // fin funcion agregar
	  
	 
	 
  });
</script>
</body>
</html>
