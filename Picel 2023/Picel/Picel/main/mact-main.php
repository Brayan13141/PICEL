<?php
    session_start();
    if ($_SESSION['id_User']) {
      include('../system/conexion.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <title>PICEL ~ RD</title>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="icon" href="../ico/logo.ico">
      <link href="../css/bootstrap.min.css" rel="stylesheet">
      <link href="../css/estilo.css?v=<?php echo time();?>" rel="stylesheet">
      <script src="../js/jquery-3.6.0.min.js"></script>
      <script src="../js/bootstrap.min.js"></script>
  </head>
  <body>
    <?php
      require('menu.php');
    ?>
<div class="container margen">
    <div class="row mt-3">
        <div class="col"><button type="button" onclick="regresar();" class="mx-auto btn btn-success">Regresar</button></div>
    </div>

      <div class="row" id="monitoreo">
          <?php
            $id = $_GET['act'];
            $query3 = "SELECT t.nombre, concat(e.nombre,' ',e.apellidoP,' ',e.apellidoM) 
            AS nombre_completo FROM tarea t, actividades_asignadas a, estudiantes e 
            WHERE t.id_Tarea = a.id_Tarea AND a.id_Estudiante = e.id_Estudiante
            AND a.id_Evento = '$id'";
            echo'<div class="col-12 pt-2 text-center">
                <h3>Monitorear Actividad</h3>
            </div>';
            echo'
            <table class="table table-hover mt-3" border="0" cellspacing="2" cellpadding="2"> 
                <tr> 
                    <td><p>Alumno</p></td> 
                    <td><p>Tarea</p></td> 
                    <td><p>Estado</p></td> 
                    <td><p>Evidencia</p></td> 
                    <td><p>Anotaciones</p></td>  
                </tr>';
            if ($result = $link->query($query3)) {
                while ($row = $result->fetch_assoc()) {
                  $name_completo = $row["nombre_completo"];  
                  $name_t = $row["nombre"];

                    echo '<tr> 
                              <td>'.$name_completo.'</td> 
                              <td>'.$name_t.'</td> 
                              <td>0%</td> 
                              <td>Nada</td> 
                              <td>Incompleta</td>
                          </tr>';
                }
                echo "</table>";
                $result->free();
            } 
          ?>
      </div>
</div>

<?php
include('footer.php');
?>
</body>
<script>
    function regresar(){
        window.location.href = 'act-main.php';
    }
</script>

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
