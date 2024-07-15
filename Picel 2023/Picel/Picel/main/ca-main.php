<?php
    session_start();
    if (isset($_SESSION['id_user'])) {
      echo'JALO';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>PICEL ~ CA</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="../ico/logo.ico">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/estilo.css?v=<?php echo time();?>" rel="stylesheet">
        <script src="https://kit.fontawesome.com/2a6b22e937.js" crossorigin="anonymous"></script>
        
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-picel">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin: auto;">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="index.php">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="ca-main.php">Control de acceso</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="rd-main.php">Registro de docentes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="re-main.php">Registro de estudiantes</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="exit-main.php">Cerrar Sesión</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
   <div class="container">
     <div class="row">
       <div class="col text-center">
            <h3>Control de acceso</h3>
       </div>
     </div>
     <div class="row">
          <div class="card card-main" onclick="location.href='ca-main.php';" style="width: 18rem;">
            <img src="../imgs/users.svg" class="card-img-top" alt="...">
            <div class="card-body"><h4><strong>Control de acceso</strong></h4>
              
            </div>
        </div>
     </div>
   </div>


   <footer style="position: absolute;width: 100%;bottom: 0;">
<div class="d-flex flex-column flex-md-row text-center text-md-start justify-content-between py-4 px-4 px-xl-5  bg-picel">
            <div class="text-white mb-3 mb-md-0">
            Copyright 2022 © Experiencias Literarías
            </div>         
        </div> 
</footer>
    </body>
</html>
<?php
} else {
    ?>
<HTML>
<HEAD>
<TITLE>Picel</TITLE>

</HEAD>
<body BGCOLOR="black">
    <script>
location.href ="../";
 </script>
</body>
<?php
}
?>
