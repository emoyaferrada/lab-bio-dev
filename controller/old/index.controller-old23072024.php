<?php
function mostrar_menu($rol){
  $salida_top='
    <!DOCTYPE html>
    <html>

    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
      <meta name="author" content="Creative Tim">
      <title>LAB-BIO</title>
      <!-- Favicon -->
      <link rel="icon" href="assets/img/brand/favicon.png" type="image/png">
      <!-- Fonts -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
      <!-- Icons -->
      <link rel="stylesheet" href="assets/vendor/nucleo/css/nucleo.css" type="text/css">
      <link rel="stylesheet" href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
      <!-- Page plugins -->
      <!-- Argon CSS -->
      <link rel="stylesheet" href="assets/css/argon.css?v=1.2.0" type="text/css">
    </head>';


  if ($rol==1){
    $salida_menu='
      <body class="w-100 p-3">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">
          <img src="assets/img/brand/lab-bio.jpg" width="250" class="" alt="LAB-BIO">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="button" href="#" onClick="muestra(\'dashboard\')">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" onClick="muestra(\'analisis\')">
                <i class="ni ni-chart-bar-32 text-orange"></i>
                <span class="button">Análisis</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" onClick="muestra(\'planificacion\')">
                <i class="ni ni-folder-17 text-primary"></i>
                <span class="nav-link-text">Planificación</span>
              </a>
            </li>  
            <li class="nav-item">
              <a class="nav-link" onClick="muestra(\'facturas\')">
                <i class="ni ni-single-copy-04 text-primary"></i>
                <span class="nav-link-text">Facturas</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onClick="muestra(\'cliente\')">Administración Clientes</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onClick="muestra(\'batch\')">Crear Nuevo Batch</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" onClick="muestra(\'ingreso_batch\')">Ingresar datos Batch</a>
            </li>
          </ul> 

          <span class="navbar-text">
            <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
              <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <div class="media align-items-center">
                    <div class="media-body  ml-2  d-none d-lg-block">
                      <span class="mb-0 text-sm  font-weight-bold"><? echo $_SESSION["nombre"]; ?></span>
                    </div>
                  </div>
                </a>
                <div class="dropdown-menu  dropdown-menu-right ">
                  <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Welcome!</h6>
                  </div>
                  <a href="#!" class="dropdown-item">
                    <i class="ni ni-single-02"></i>
                    <span>Logout</span>
                  </a> 
                 </div>
              </li>
            </ul>

          </span>
        </div>  
      </nav>';
  }
  elseif ($rol==2){
    $salida_menu='
      <body class="w-100 p-3">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">
          <img src="assets/img/brand/lab-bio.jpg" width="250" class="" alt="LAB-BIO">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#" onClick="muestra(\'dashboard\')">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" onClick="muestra(\'planificacion\')">
                <i class="ni ni-folder-17 text-primary"></i>
                <span class="nav-link-text">Planificación</span>
              </a>
            </li>  
          </ul>
          <span class="navbar-text">
            <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
              <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <div class="media align-items-center">
                    <div class="media-body  ml-2  d-none d-lg-block">
                      <span class="mb-0 text-sm  font-weight-bold"><? echo $_SESSION["nombre"]; ?></span>
                    </div>
                  </div>
                </a>
                <div class="dropdown-menu  dropdown-menu-right ">
                  <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Welcome!</h6>
                  </div>
                  <a href="#!" class="dropdown-item">
                    <i class="ni ni-single-02"></i>
                    <span>Logout</span>
                  </a> 
                 </div>
              </li>
            </ul>

          </span>
        </div>  
      </nav>';
  }
  elseif ($rol==4){
    $salida_menu='
      <body class="w-100 p-3">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">
          <img src="assets/img/brand/lab-bio.jpg" width="250" class="" alt="LAB-BIO">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#" onClick="muestra(\'ingreso_batch\')">Crear Nuevo Batch</a>
            </li>
          </ul>
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#" onClick="muestra(\'ingreso_batch\')">Ingresar datos Batch</a>
            </li>
          </ul>          
          <span class="navbar-text">
            <ul class="navbar-nav align-items-center  ml-auto ml-md-0 ">
              <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <div class="media align-items-center">
                    <div class="media-body  ml-2  d-none d-lg-block">
                      <span class="mb-0 text-sm  font-weight-bold"><? echo $_SESSION["nombre"]; ?></span>
                    </div>
                  </div>
                </a>
                <div class="dropdown-menu  dropdown-menu-right ">
                  <div class="dropdown-header noti-title">
                    <h6 class="text-overflow m-0">Welcome!</h6>
                  </div>
                  <a href="#!" class="dropdown-item">
                    <i class="ni ni-single-02"></i>
                    <span>Logout</span>
                  </a> 
                 </div>
              </li>
            </ul>
          </span>
        </div>  
      </nav>';
  }
  $salida_scripts='
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="assets/vendor/jquery/dist/jquery.min.js"></script>
  <script src="assets/vendor/jquery/dist/jquery-ui.min.js"></script>
  <script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/js-cookie/js.cookie.js"></script>
  <script src="assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
  <script src="assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
  <!-- Optional JS -->
  <script src="assets/vendor/chart.js/dist/Chart.min.js"></script>
  <script src="assets/vendor/chart.js/dist/Chart.extension.js"></script>
  <!-- Argon JS -->
  <script src="assets/js/argon.js?v=1.2.0"></script>';



  return $salida_top.$salida_menu.$salida_scripts;

}





