<?php
require_once '../../model/planificacion.model.php';
$planificacion=new planificacionModel();

$marcadores=$planificacion->obtener_marcadores_mapa($_GET["desde"],$_GET["hasta"],$_GET["resp"]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  
  
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>

  <style>
    html, body {
      height: 100%;
      margin: 0;
    }
    .leaflet-container {
      height: 1000px;
      width: 1000px;
      max-width: 100%;
      max-height: 100%;
    }
  </style>
</head>
<body>



<div id="map" style="width: 4000px; height: 1000px;"></div>
<script>
  var marcadores=<?echo json_encode($marcadores)?>;
  
  var map = L.map('map').setView([-37.462324, -72.290608], 8);

  var tiles = L.tileLayer(
    'https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
      'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1
  }).addTo(map);

  //var marker = L.marker([-37.462324, -72.290608]).addTo(map)
  // .bindPopup('<b>Hello world!</b><br />I am a popup.');
  var limites=[];
  marcadores.forEach(function(valor,indice,array){
    limites.push([valor[4],valor[5]]);

    var marker = L.marker([valor[4], valor[5]]).addTo(map)
    .bindPopup('<h2>Fecha:'+valor[0]+'<br/>Responsable:'+valor[6]+'</h2><br />Cliente:'+valor[2]+'<br />Predio:'+valor[3]+'<br />Comuna:'+valor[1]);
  });
  
  map.fitBounds(limites);


</script>



</body>
</html>