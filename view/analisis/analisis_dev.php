<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="LAB-BIO">
  <meta name="author" content="Erick Moya">
  <title>LAB-BIO</title>
   <link src="../assets/vendor/bootstrap/dist/css/bootstrap.min.js">
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
  
</head>
<body>

  <div class="">
   
</div>
<div class="" style="margin-top: 10px; margin-bottom: 10px !important";>
<ul class="nav nav-pills nav-fill" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false" onclick="cargar_tabla('tabla_ingresados')">Ingresados DEV</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false" onclick="cargar_tabla('tabla_en_proceso')">En proceso DEV</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" onclick="alert('rer'); cargar_tabla('tabla_ingresados_por_revisar')">Por Revisar adsasdasdsa</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="contact2-tab" data-toggle="tab" href="#contact2" role="tab" aria-controls="contact2" aria-selected="false" onclick="cargar_tabla('tabla_enviados')">Enviados</a>
  </li>    
  <li class="nav-item">
    <a class="nav-link" id="contact3-tab" data-toggle="tab" href="#contact3" role="tab" aria-controls="contact3" aria-selected="false" onclick="cargar_tabla('tabla_rechazados')">Rechazados</a>
  </li>  
</ul>
</div>
<!-- Tabla de analisis -->
<div class="tab-content" id="myTabContent" style="width:100%">
      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Estado análisis ingresados</h3>
                </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table table-dark" id="estado_analisis">
               
              </table>
            </div>
          </div>
        </div>
      </div>
      </div>
<!-- Tabla de analisis por revisar -->
      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Análisis por revisar</h3>
                </div>
            </div>

            <form method="POST" action="../view/analisis/reporte-analisis.php"> 
	            <div class="table-responsive">
	            	<?php echo $this->model->tabla_reporte_cliente();?>
	            </div>
				<div class="form-group row">
					<button type="button" id="hacer_reporte" name="hacer_reporte" value="1" class="btn btn-primary" data-toggle="modal" data-target="#nuevo_resultado_modal">Configurar Reporte</button>
				</div>
            </form>
          </div>
        </div>
      </div>
    </div>
      <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Análisis en proceso</h3>
                </div>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Tipo</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Fecha ingreso</th>
                    <th scope="col">Codigo muestra</th>
                    <th scope="col">Avance</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php echo $this->model->tabla_revision_3();?>
                </tbody> 
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
<!-- Tabla de analisis ingresados y rechazados -->
      <div class="tab-pane fade" id="contact2" role="tabpanel" aria-labelledby="contact2-tab">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Análisis Aprobados y Enviados</h3>
                </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Tipo</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">F. envío</th>
                    <th scope="col">Predio</th>
                  </tr>
                </thead>
                <tbody>
                  <?php echo $this->model->tabla_enviados();?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
   </div>
   <div class="tab-pane fade" id="contact3" role="tabpanel" aria-labelledby="contact3-tab">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Análisis Rechazados</h3>
                </div>
            </div>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Tipo</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">F. Rechazo</th>
                    <th scope="col">Predio</th>
                    <th scope="col">Motivo</th>
                  </tr>
                </thead>
                <tbody>
                  <?php echo $this->model->tabla_rechazados();?>
                </tbody> 
              </table>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
    <!-- Modal nuevo análisis -->
    <div class="modal fade" id="nuevo_analisis_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Ingreso Nuevo Análisis</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					    <span aria-hidden="true">&times;</span>
					</button>
				</div>
    			<iframe src='../view/analisis/ingreso-nuevo.php' width="800" height="700" frameborder="0" id="nuevo_analisis2"></iframe>
    		</div>
    	</div>
    </div>

    <!-- Modal nuevo cliente -->
    <div class="modal fade" id="nuevo_cliente_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ingreso Nuevo Cliente</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <iframe src='../view/Cliente/ingreso-nuevo-cliente.php' frameborder="0" height="800" id="nuevo_analisis2"></iframe>
        </div>
      </div>
    </div>    

    <!-- Modal resultado analisis -->
    <div class="modal fade" id="nuevo_resultado_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Configurar reporte</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <iframe src='../view/analisis/reporte-analisis.php' frameborder="0" height="800" id="nuevo_resultado"></iframe>
        </div>
      </div>
    </div>  

    <!-- Modal nuevo batch -->
    <div class="modal fade" id="nuevo_batch_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ingreso trabajos análisis</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <iframe src='../view/analisis/nuevo-batch.php' frameborder="0" height="800" id="iframe_nuevo-batch"></iframe>
        </div>
      </div>
    </div>  

    <!-- Modal ingreso_batch_modal -->
    <div class="modal fade" id="ingreso_batch_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ingreso Resultados</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <iframe src='' frameborder="0" height="800" id="iframe_ingreso-batch"></iframe>
        </div>
      </div>
    </div>  

  <?
    $listado=json_encode($this->model->listar_todos());
    //echo ("que hay ".$this->Guardar());
  ?>
  <script>
    $( document ).ready(function() {

        //funcion global que cierra el modal
        window.closeModal = function(){
          $('#nuevo_cliente_modal').modal('hide');
          location.reload();  
        };
        window.close_modal_revisar_analisis = function(){
          $('#revisar_modal').modal('hide');
          location.reload();  
        };
                
        var dataSet = <?echo json_decode($listado);?>;
        //console.log(dataSet);

        //$('#tabla_por_revisar').DataTable();
        new DataTable('#tabla_por_revisar');
        console.log("y la tabla");



        $('#estado_analisis').DataTable({
            language: {
				emptyTable: "No hay información",
				search: "Buscar",
				info: "Mostrando _START_ a _END_ de _TOTAL_ Registros",
				lengthMenu: "Mostrar _MENU_ ",
				paginate: {
				  	next: '<i class="fas fa-angle-right"></i>',
			   	  	previous: '<i class="fas fa-angle-left"></i>'				   
				}
      				//"url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
      			},
            data: dataSet,
            columns: [
            { title: "ID<br> Analisis" },
            { title: "Fecha ingreso<br>" },
            { title: "Cliente<br>" },
            { title: "Tipo análisis<br>" },
            { title: "Estado<br>" },
            { title: "<strong>Días<br> desde<br> Ingreso<strong>" }
            ]
        });
		$('#hacer_reporte').on('click', function(){
			//$("#contenido").printElement();
			var checked = []
			$("input[name='sel[]']:checked").each(function ()
			{
			    checked.push(parseInt($(this).val()));
			});

			var sel=JSON.stringify(checked);

			$('#nuevo_resultado').attr('src','../view/analisis/reporte-analisis.php?sel='+sel)

		})        
    
    $('#btn_ingreso_batch_modal').on('click', function(){
      $('#iframe_ingreso-batch').attr('src','../view/analisis/ingreso-batch.php');
    });
    
    
  });
  function revisar(id){
      $("#iframe_revisar_analisis").attr("src","../view/analisis/revisar-analisis.php?id=" + id);
       $("#revisar_modal").modal('show');
  }

  function cargar_tabla(tipo){
      alert (tipo);

        $.ajax({
          'url':'https://198.251.64.144/sada-util/lab-bio-dev/controller/analisis.controller.php?traer_tabla='+tipo,
          'type': 'GET',
          'dataType': 'text',
          success: function(html_result){
            console.log(tabla);
            $("div_tabla_"+tipo).empty();
            $("div_tabla_"+tipo).html(html_result);

          }

        })
  }
  </script>    
</body>