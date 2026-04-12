  <?
  require_once("../model/usuario.model.php");
  $usuario = new usuarioModel();
  ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="LAB-BIO">
  <meta name="author" content="Erick Moya">
  <title>LAB-BIO</title>

  <!-- Favicon -->
  <link rel="icon" href="../assets/img/brand/favicon.png" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="../assets/vendor/nucleo/css/nucleo.css" type="text/css">
  <link rel="stylesheet" href="../assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
  <!-- Page plugins -->
  <!-- Argon CSS -->
  <link rel="stylesheet" href="../assets/css/argon.css?v=1.2.0" type="text/css">
  <!-- Modal CSS -->
  <script src="../assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/datatables/datatables.min.js"></script>  

  <link href='../assets/agenda/css/fullcalendar.css' rel='stylesheet' />
  <link href='../assets/agenda/css/fullcalendar.print.css' rel='stylesheet' media='print' />
  <script src='../assets/agenda/js/fullcalendar.js' type="text/javascript"></script>

</head>
<body>
  <div class="">
    <!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Planificaci&oacute;n de trabajos</h6>
            </div>
            <div class="col-lg-6 col-5 text-right">
              <a href="#" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#nuevo_planificacion"> + Nuevo Ingreso </a>              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>  
  <div class="" style="margin-top: 10px; margin-bottom: 10px !important";>
  	<ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="tabla-tab" data-toggle="tab" href="#tabla" role="tab" aria-controls="tabla" aria-selected="false">Ultimos ingresados</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="calendario-tab" data-toggle="tab" href="#calendario" role="tab" aria-controls="calendario" aria-selected="false">Calendario</a>
      </li>  
      <li class="nav-item">
        <a class="nav-link" id="mapa-tab" data-toggle="tab" href="#mapa" role="tab" aria-controls="mapa" aria-selected="false">Mapa</a>
      </li>        
    </ul>
  </div>
	<div class="tab-content" id="myTabContent" style="width:100%">
	  <div class="tab-pane fade show active" id="tabla" role="tabpanel" aria-labelledby="tabla-tab">
	    <div class="col-xl-12">
	      <div class="card">
	        <div class="card-header border-0">cdscdcs
	        <!---- filtro cliente y año --->	
	          <div class="row align-items-center">dwdwqd
					<div class="table-responsive">dwqdqwdwqd
						<table class="table" id="ultimos_ingresados">
						  <thead class="thead-light">
						  </thead>
						</table>
					</div>
	          </div>
	        </div>
	      </div>
	  	</div>
	  </div>

	  <div class="tab-pane fade" id="mapa" role="tabpanel" aria-labelledby="mapa-tab">
	    <div class="col-xl-12">
	      <div class="card">
	        <div class="card-header border-0">
	          <div class="row align-items-center">
	            <div class="col">
	              <div class="form-group row">
	              	<h3 class="mb-0">Mapa predios programados</h3>
				  			</div>
	              <div class="form-group row">
	              	<div class="col">
		              	<label class="col-form-label" for="fecha_inicio_mapa">Fecha Inicio</label> 
	         			<input type="date" id="fecha_inicio_mapa">
		              	<label class="col-form-label" for="fecha_fin_mapa">Fecha Fin</label> 
	         			<input type="date" id="fecha_fin_mapa">
	         			<label for="responsable_muestra">Responsable</label> 
              	        <select id="responsable_muestra" name="responsable_muestra">
				          <option value="0">Usuario</option>
				          <? echo $usuario->usuario_todos_select();?>
				        </select>
        				<button type="button" id="filtrar_mapa" class="btn btn-primary" >Filtrar</button>
	              	</div>
				  </div>
	              <div id="map_id" align="center" style="{height: 600px;width: 1000px;}">
	              	<iframe src="../view/planificacion/vista-mapa.php" height="600" width="100%" frameborder="0" id="iframe_mapa"></iframe>
	              </div>
	            </div>
	          </div>
	          	
	        </div>
	      </div>
	    </div>
	  </div>
	  <div class="tab-pane fade active" id="calendario" role="tabpanel" aria-labelledby="calendario-tab">
	    <div class="col-xl-12">
	      <div class="card">
	        <div class="card-header border-0">
	          <div class="row align-items-center">
	            <div class="col">
	              <h3 class="mb-0">Calendario Visitas Programadas</h3>
	              <button class="btn btn-primary" id="btn_actualiza_calendario">Actualizar</button>
	            </div>
	          </div>
	          <div class="table-responsive">
	            <div id='calendar'></div>
	            <div style='clear:both'></div>  
	          </div>
	        </div>
	      </div>
	    </div>
	  </div>

	</div>
    <!-- Modal nuevo planificacion -->
    <div class="modal fade" id="nuevo_planificacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    	<div class="modal-dialog modal-xl" role="document">
    		<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Ingreso planificacion de trabajos</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					    <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
    			<iframe src='../view/planificacion/ingreso-nuevo-dev.php' width="100%" height="1000" frameborder="0" id="nuevo_equipo"></iframe>
    			</div>
			</div>
    	</div>
    </div>
	<div class="modal fade" id="modal-event" tabindex="-1" role="dialog" aria-labelledby="modal-eventLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="event-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row" style="display:none">
                    	<div id="">ID: <input type="text" value="" id="event-id" name="event-id" readonly> </div>
        			</div>
					<div class="form-group row">
                    	<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>
                    </div>
					<div class="form-group row">
                    	<div id="event-description"></div>
					</div>
					<div class="form-group row">
						<div class="col">
						  <input id="nueva_fecha" name="nueva_fecha" type="date" class="form-control">
						  <button type="button" id="mover_fecha" class="btn btn-secondary" >Mover a esta fecha</button>
						</div>
					</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modal_ver_detalle" tabindex="-1" role="dialog" aria-labelledby="modal-eventLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="event-title">Editar Planificación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe src="" id="ver_detalle" width="100%" height="1000" frameborder="0"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
  <script src="../assets/vendor/datatables/datatables.min.js"></script>  

  <script>
    $( document ).ready(function() {
        $("#alerta").hide();
 		var r=actualiza_calendario(<? echo $this->model->obtener_eventos_calendario();?>);
        
		var dataSet = <?echo $this->model->listar_todos();?>;
		
		$('#ultimos_ingresados').DataTable({
		    language: {"url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"},
		    data: dataSet,
		    columns: [
      				{ title: "Fecha" },
      				{ title: "Comuna" },
      				{ title: "Cliente" },
      				{ title: "Predio" },				
      				{ title: "ver" }
      				]
		});
        
        function actualiza_calendario(eventos_calendario){
		    //Opciones Calendario
		    //llamar los eventos desde ajax
		    
		    var calendar =  $('#calendar').fullCalendar({
		      header: {
		        left: 'title',
		        center: 'agendaDay,agendaWeek,month',
		        right: 'prev,next today'
		      },
		      editable: false,
		      firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
		      selectable: true,
		      defaultView: 'month',

		      axisFormat: 'h:mm',
		      columnFormat: {
		                month: 'ddd',    // Mon
		                week: 'ddd d', // Mon 7
		                day: 'dddd M/d',  // Monday 9/7
		                agendaDay: 'dddd d'
		            },
		            titleFormat: {
		                month: 'MMMM yyyy', // September 2009
		                week: "MMMM yyyy", // September 2009
		                day: 'MMMM yyyy'                  // Tuesday, Sep 8, 2009
		            },
		      allDaySlot: false,
		      selectHelper: true,
		      select: function(start, end, allDay) {
		        var title = prompt('Event Title:');
		        if (title) {
		          calendar.fullCalendar('renderEvent',
		            {
		              title: title,
		              description,
		              start: start,
		              end: end,
		              allDay: allDay
		            },
		            true // make the event "stick"
		          );
		        }
		        calendar.fullCalendar('unselect');
		      },
		      droppable: false, // this allows things to be dropped onto the calendar !!!
		      drop: function(date, allDay) { // this function is called when something is dropped

		        // retrieve the dropped element's stored Event Object
		        var originalEventObject = $(this).data('eventObject');

		        // we need to copy it, so that multiple events don't have a reference to the same object
		        var copiedEventObject = $.extend({}, originalEventObject);

		        // assign it the date that was reported
		        copiedEventObject.start = date;
		        copiedEventObject.allDay = allDay;

		        // render the event on the calendar
		        // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
		        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

		        // is the "remove after drop" checkbox checked?
		        if ($('#drop-remove').is(':checked')) {
		          // if so, remove the element from the "Draggable Events" list
		          $(this).remove();
		        }

		      },
		      monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		      monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		      dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		      dayNamesShort: ['Dom','Lun','Mar','Mir','Jue','Vie','Sab'],
		      buttonText: {
		        prev: "<span class='fc-text-arrow'>&lsaquo;</span>",
		        next: "<span class='fc-text-arrow'>&rsaquo;</span>",
		        prevYear: "<span class='fc-text-arrow'>&laquo;</span>",
		        nextYear: "<span class='fc-text-arrow'>&raquo;</span>",
		        today: 'Hoy',
		        month: 'Mes',
		        week: 'Semana',
		        day: 'D&iacute;a'
		      },

		      dayClick: function (date, jsEvent, view) {
		              alert('Has hecho click en: ');
		          }, 
		      eventClick: function (calEvent, jsEvent, view) {
		        $('#event-id').val(calEvent.id);
		        $('#event-title').text(calEvent.title);
		        $('#event-description').html(calEvent.description);
		        $('#modal-event').modal();
		      },  
		      events: eventos_calendario
		      //<? echo $this->model->obtener_eventos_calendario();?>
		      //events: eventos_calendario
		    });
        }

    $("#mover_fecha").on('click', function(){
    	alert("Seguro desea cambiar evento de fecha " + $("#nueva_fecha").val() + "ID: " + $("#event-id").val());
    	//hacer el cambio con ajax
	    $.ajax({
	        url: '../controller/planificacion.controller.php',
	        type: 'post',
	        data: { "modificar_fecha_programacion": "1", "nueva_fecha": $("#nueva_fecha").val(), "programacion_id": $("#event-id").val() },
	        success: function(response) { 
	        	$("#alerta").show();
        	    $("#alerta").delay(4000).slideUp(200, function() {
			        $(this).alert('close');
			    });
	        }
	    });    	
    });

    //boton ver detalle de tabla programacion
    $("#btn_detalle").on('click',function(){
    	alert (" data-id=" + ("#btn_detalle").data("data-id"));
    });

    $("#filtrar_mapa").on('click',function(){
    	var fecha1=$("#fecha_inicio_mapa").val();
		var fecha2=$("#fecha_fin_mapa").val();
		var resp=$("#responsable_muestra").val();
		
    	//alert ("Desde: " + fecha1 + " Hasta: " + fecha2);
    	$("#iframe_mapa").attr("src","../view/planificacion/vista-mapa.php?desde="+fecha1+"&hasta="+fecha2 +  "&resp="+ resp);
    });

    $("#btn_actualiza_calendario").on('click',function(){
	    $.ajax({
	        url: '../controller/planificacion.controller.php',
	        //url: '../view/planificacion/planificacion_ajax.php',
	        type: 'post',
	        //dataType:'json',
	        data: {obtener_eventos_calendario:1},
	        success: function(response) { 
	        	actualiza_calendario(JSON.parse(response));
	        },
	        error: function(jqXHR, textStatus, errorThrown) {
                        alert('Error: ' + textStatus + ' ' + errorThrown);
            }	        
	    })
	 });

})
    
    function abre_detalles(id){
    	//alert ("data-id=" + id);
    	$("#ver_detalle").attr("src","../view/planificacion/ingreso-plan.php?id="+id);
    	$('#modal_ver_detalle').modal('show');

    }
  </script>    
</body>
</html>