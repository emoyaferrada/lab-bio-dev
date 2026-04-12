
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

<link href='../assets/agenda/css/fullcalendar.css' rel='stylesheet' />
<link href='../assets/agenda/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='../assets/agenda/js/jquery-1.10.2.js' type="text/javascript"></script>
<script src='../assets/agenda/js/jquery-ui.custom.min.js' type="text/javascript"></script>
<script src='../assets/agenda/js/fullcalendar.js' type="text/javascript"></script>
<script>
	
	$(document).ready(function() {
	   	/*  className colors
		className: default(transparent), important(red), chill(pink), success(green), info(blue)
		*/
		/* initialize the external events
		-----------------------------------------------------------------*/
	

		$('#external-events div.external-event').each(function() {

			// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
			// it doesn't need to have a start or end
			var eventObject = {
				title: $.trim($(this).text()) // use the element's text as the event title
			};

			// store the Event Object in the DOM element so we can get to it later
			$(this).data('eventObject', eventObject);

			// make the event draggable using jQuery UI
			$(this).draggable({
				zIndex: 999,
				revert: true,      // will cause the event to go back to its
				revertDuration: 0  //  original position after the drag
			});

		});


		/* initialize the calendar
		-----------------------------------------------------------------*/

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
<?php echo  $this->model->eventos();?>
			
			dayClick: function (date, jsEvent, view) {
            	alert('Has hecho click en: '+ date.format());
        	}, 
			eventClick: function (calEvent, jsEvent, view) {
				$('#event-title').text(calEvent.title);
				$('#event-description').html(calEvent.description);
				$('#modal-event').modal();
			},  
		});
		
	});

</script>
<style>

	body {		
		text-align: center;
		font-size: 14px;
		font-family: "Helvetica Nueue",Arial,Verdana,sans-serif;
		background-color: #DDDDDD;
		}

	#wrap {
		width: 1100px;
		margin: 0 auto;
		}

	#external-events {
		float: left;
		width: 150px;
		padding: 0 10px;
		text-align: left;
		}

	#external-events h4 {
		font-size: 16px;
		margin-top: 0;
		padding-top: 1em;
		}

	.external-event { /* try to mimick the look of a real event */
		margin: 10px 0;
		padding: 2px 4px;
		background: #3366CC;
		color: #fff;
		font-size: .85em;
		cursor: pointer;
		}

	#external-events p {
		margin: 1.5em 0;
		font-size: 11px;
		color: #666;
		}

	#external-events p input {
		margin: 0;
		vertical-align: middle;
		}

	#calendar {
/* 		float: right; */
        margin: 0 auto;
		width: 900px;
		background-color: #FFFFFF;
		  border-radius: 6px;
        box-shadow: 0 1px 2px #C3C3C3;
		}

</style>
</head>
<body>
  <div class="">
    <!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Mantenci&oacute;n Equipos e Instrumentos</h6>
            </div>
            <div class="col-lg-6 col-5 text-right">
              <a href="#" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#nuevo_equipo_modal"> + Nuevo </a>              
            </div>
          </div>
        </div>
      </div>
    </div>

	<!-- Agenda -->
  	 <div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">						 
						<div id='calendar'></div>
						<div style='clear:both'></div>					   
                </div>
            </div>			
		</div>
       </div>
      </div>
	<!---- Tabla --->	  
	<div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Equipos e Instrumentos</h3>
                </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table" id="tabla_equipos">
                <thead class="thead-light"></thead>
              </table>
            </div>
          </div>
        </div>
      </div>
	<!-- ventana modal de los eventos de la agenda --->	  
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
                    <div id="event-description"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>                   
                </div>
                </div>
            </div>
            </div>
		
    <!-- Modal nuevo Equipos -->
    <div class="modal fade" id="nuevo_equipo_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Ingreso Nuevo Equipo o Instrumento</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					    <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
    			<iframe src='../view/mantencion/ingreso-nuevo.php' width="700" height="500" frameborder="0" id="nuevo_equipo"></iframe>
    			</div>
			</div>
    	</div>
    </div>
	 <!-- Modal Editar -->
    <div class="modal fade" id="editar_equipo_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Editar Equipo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
          </button>
        </div>
		   <div class="modal-body">
			<script>
				$(document).ready(function() {
				$('#editar_equipo_modal').on('show.bs.modal', function (event) {
					  var button = $(event.relatedTarget); // Button that triggered the modal
					  var recipient = button.data('id'); // Extract info from data-* attributes
					  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
					  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					  document.getElementById("editar_equipo").src="../view/mantencion/editar-equipo.php?id="+recipient;
					//var modal = $(this)
					 // modal.find('.modal-title').text('New message to ' + recipient)
					 // modal.find('.modal-body input').val(recipient)
					})
				})
				
				
		  </script>  
          <iframe src='' width="800" height="500" frameborder="0" id="editar_equipo"></iframe>
        </div>
			</div>
      </div>
    </div> 

    <!-- Modal Eliminar -->
    <div class="modal fade" id="eliminar_equipo_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Eliminar Equipo</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
          </button>
        </div>
		   <div class="modal-body">
		<script>
        $(document).ready(function (e) {
  			$('#eliminar_modal').on('show.bs.modal', function(e) {    
     			var id = $(e.relatedTarget).data().id;      		
			});			   
    		document.getElementById("eliminar_equipo").src="../view/mantencion/eliminar-equipo.php";
		});
      </script>  
          <iframe src='' width="800" height="500" frameborder="0" id="eliminar_equipo"></iframe>
        </div>
			</div>
      </div>
    </div> 
<?php
    $listado=json_encode($this->model->listar_todos());
 ?>
    <script src="../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	
  <script src="../assets/vendor/datatables/datatables.min.js"></script>  

    <script>
    $( document ).ready(function() {
                        
        var dataSet = <?echo json_decode($listado);?>;
        console.log(dataSet);

        $('#tabla_equipos').DataTable({
            language: {"url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"},
            data: dataSet,
            columns: [
				{ title: "Nombre" },
				{ title: "Marca" },
				{ title: "Modelo" },
				{ title: "N&uacute;mero de Serie" },				
				{ title: "Fecha Mantenci&oacute;n" },				
				{ title: "Proveedor" },
				{ title: "EDITAR" },
				{ title: "ELIMINAR" }
				]
        });
    });
  </script>    
</body>