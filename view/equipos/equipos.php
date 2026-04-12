
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

</head>
<body>
  <div class="">
    <!-- Header -->
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Equipos</h6>
            </div>
            <div class="col-lg-6 col-5 text-right">
              <a href="#" class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#nuevo_equipo_modal"> + Nuevo equipo</a>           
            </div>
          </div>
        </div>
      </div>
    </div>

<!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-12">
          <div class="card">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Base de datos equipos</h3>
                </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table" id="estado_equipos">
                <thead class="thead-light">

                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>

    <!-- Modal nuevo equipo -->
    <div class="modal fade" id="nuevo_equipo_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    	<div class="modal-dialog modal-lg" role="document">
    		<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Ingreso Nuevo Equipo</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
					    <span aria-hidden="true">&times;</span>
					</button>
				</div>
    			<iframe src='../view/analisis/ingreso-nuevo.php' width="700" height="500" frameborder="0" id="nuevo_equipo"></iframe>
    		</div>
    	</div>
    </div>


  <script src="../assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
  <script src="../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/vendor/datatables/datatables.min.js"></script>  
  <?
    $listado_equipos=json_encode($this->model->listar_todos2());
  ?>

  <script>
    $( document ).ready(function() {
        
        var dataSet = <?echo json_decode($listado_equipos);?>;
        console.log("resultado:",dataSet);

        $('#estado_equipos').DataTable({
            data: dataSet,
            columns: [
            { title: "Id" },
            { title: "Nombre" },
            { title: "Marca" },
            { title: "Modelo" },
            { title: "n_serie" },
            { title: "Proveedor" }
            ]
        });
    });

  </script>    
</body>