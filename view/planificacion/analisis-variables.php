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
</head>
<body>
<div class="container">
<?
if ($_GET["id"]==1){
    echo '<div class="alert alert-success" id="alerta" role="alert">Registro guardado exitosamente</div>';
}

?>
<form id="form_ingreso" method="post" action="../../controller/planificacion.controller.php">
<h4>Ingreso de análisis</h4>
    <div class="form-group row">
      <div class="col">
        <label for="tipo_analisis" class="col-form-label">Tipo de análisis estándar</label> 
        <select id="tipo_analisis" name="tipo_analisis" class="custom-select">
          <option value=0>Seleccione</option>
          <option value=8>Análisis de Suelo</option>
          <option value=2>Análisis Foliar</option>
        </select>
        <input type="button" class="button" id="seleccionar_analisis" value="Aceptar">
      </div>
      <div class="col">
        <label for="tipo_analisis" class="col-form-label">Tipo personalizado</label> 
        <select id="analisis_custom" name="analisis_custom" class="custom-select">
        </select>
        <input type="button" class="button" id="seleccionar_custom" value="Aceptar">
      </div>
    </div>
    <div class="form-group row">
      <div class="col">
        <label for="tipo_analisis" class="col-form-label">Nombre nuevo personalizado</label> 
        <input type="text" id="nombre_guardar">
        <input type="button" class="button" id="guardar_nuevo_dinamico" value="Guardar nuevo">
      </div>
    </div>
    <div class="form-group row">
      <div class="col">
        
        <div id="lista_variables"></div>

      </div>
    </div>
</form>
</div>

<script src="../../assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="../../assets/vendor/jquery/dist/jquery-ui.min.js"></script>
<script src="../../assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/tabletojson/jquery.tabletojson.js"></script>

<script type="text/javascript">
$( document ).ready(function(){
  
  //llamar completar el select
  completa_select_custom();

  $('#tipo_analisis').on( 'change', function() {
    //ajax para traer variables del analisis
    var tipo_analisis=$("#tipo_analisis").val();
    if (tipo_analisis != "Seleccione"){
      $.ajax({
          url:"../../controller/analisis.controller.php",    //the page containing php script
          type: "post",    //request type,
          data: {ajax_variables_analisis: 1, analisis_id: tipo_analisis},
          success:function(result){
              //poner en el div
              $("#lista_variables").html(result);
          }
      });      
    }
  })

  $('#guardar_nuevo_dinamico').on('click',function(){
      var variables_analisis = $.map($('input:checkbox:checked'), function(e,i) {
          return +e.value;
      });
      var tipo_analisis_custom=$("#nombre_guardar").val();
  
      //guardar el analisis tipo
      $.ajax({
          url:"../../controller/analisis.controller.php",    //the page containing php script
          type: "post",    
          data: {ajax_analisis_custom: 1, tipo_analisis_custom: tipo_analisis_custom},
          success:function(result){
              completa_select_custom();
          }
      })  

      //Guardar el variable_tipo_id
      $.ajax({
          url:"../../controller/analisis.controller.php",    //the page containing php script
          type: "post",    
          data: {ajax_guarda_variable_analisis_custom: 1, variables_analisis: variables_analisis},
          success:function(result){
              console.log(result);
              completa_select_custom();
              //poner en el div
          }
      })  


  })
  $('#seleccionar_analisis').on('click',function(){
      var arr = $.map($('input:checkbox:checked'), function(e,i) {
          return +e.value;
          });

      console.log(arr);

      /*
      var tipo_analisis_custom=$("#nombre_guardar").val();
      $.ajax({
          url:"../../controller/analisis.controller.php",    //the page containing php script
          type: "post",    
          data: {ajax_analisis_custom: 1, tipo_analisis_custom: tipo_analisis_custom},
          success:function(result){
              console.log(result);
              completa_select_custom();
              //poner en el div
          }
      }) */

  })  

})
function completa_select_custom(){
    var lista_analisis_custom
    $.ajax({
      url:"../../controller/analisis.controller.php",    //the page containing php script
      type: "post",    //request type,
      data: {ajax_obtiene_variables_analisis: 1},
      success:function(result){
          $('#analisis_custom').empty();
          $.each(JSON.parse(result),function(key,value){
              console.log(key,value[0] + ' -> ' + value[1]);
              var html_option='<option value='+value[0]+'>'+value[1]+'</option>';
              //console.log(html_option);
              $('#analisis_custom').append(html_option);
          }); 
      }
    });     
}
</script>