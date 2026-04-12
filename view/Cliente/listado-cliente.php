<?
  session_start();
  require_once("../../model/cliente.model.php");

  $cliente = new clienteModel();
  $listado=$cliente->listado_clientes();
  //print_r($listado);
?>
<script src="../assets/vendor/datatables/datatables.min.js"></script>  
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="container">

  <div class="col-xl-12">
    <div class="card">
      <div class="card-header border-0">
      <!---- filtro cliente y año --->  
        <div class="row align-items-center">
      <div class="table-responsive">
        <table class="table" id="lista_clientes">
          <thead class="thead-light"></thead>
        </table>
      </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/datatables/datatables.min.js"></script>  
<script type="text/javascript">
$( document ).ready(function() {
  var dataSet = <?echo $listado;?>;
    
  $('#lista_clientes').DataTable({
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
            { title: "RUT" },
            { title: "Nombre" },
            { title: "Razon Social" },
            { title: "Direccion" },        
            { title: "Telefono 1" },        
            { title: "Telefono 2" },        
            { title: "EMAIL 1" },        
            { title: "EMAIL 2" },
            { title: "" }  
            ]
  });

})  
</script>