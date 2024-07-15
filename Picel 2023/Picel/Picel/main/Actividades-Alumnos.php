<?php
session_start();
if (isset($_SESSION['tipo_us']) != "Admin" || isset($_SESSION['tipo_us']) != "Docente") {
  header('Location: ../main/index.php');
}
if (isset($_SESSION['tipo_us']) && isset($_SESSION['id_User'])) {
  include('../system/conexion.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Actividades</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../ico/logo.ico">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilo.css?v=<?php echo time(); ?>" rel="stylesheet">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</head>
<body>
    <?php
    require('menu.php');
    ?>
    <div class="container margen">
        <div class="row" style="display:block;visibility:visible;" id="registros">
            <div class="col-12 pt-2 text-center">
                <h3>Actividades</h3>
            </div>
            <div class="grid mx-auto col-md-12">
                <?php
                $query = "SELECT DISTINCT id_Evento FROM actividades_asignadas";
                echo '<table id="tabReg" class="table table-hover mt-3" border="0" cellspacing="2" cellpadding="2"> 
              <tr> 
                  <td><p>Evento</p></td> 
                  <td><p>No. Estudiantes</p></td> 
                  <td><p>Docente</p></td> 
                  <td><p>Estatus</p></td> 
                  <td><p>Detalles</p></td>  
              </tr>';
                echo "</table>";
                ?>
            </div>
        </div>
    </div>
    <footer>
        <?php
        include('footer.php');
        ?>
    </footer>
</body>

</html> 
<?php
}
?>