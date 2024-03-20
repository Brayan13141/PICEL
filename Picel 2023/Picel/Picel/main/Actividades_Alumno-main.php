<?php
session_start();
if ($_SESSION['id_User']) {
  include('../system/conexion.php');
}
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis actividades</title>
    <link rel="icon" href="../ico/logo.ico">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilo.css?v=<?php echo time(); ?>" rel="stylesheet">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
</head>
<body>
<?php
require('menu.php');
echo($_SESSION['id_User']);
$sql = "SELECT * FROM actividades_asignadas WHERE id_Estudiante = $_SESSION[id_User]";
$result = $link->query($sql);
echo '<table class="table table-hover mt-3" border="0" cellspacing="2" cellpadding="2"> 
<tr> 
    <td><p>Actividad</p></td> 
    <td><p>Evento</p></td> 
    <td><p>Fecha Inicio</p></td>
    <td><p>Fecha Fin</p></td>  
</tr>';
if ($result->num_rows > 0) {
    // Mostrar los resultados en una tabla
    echo "<table>";
    echo "<tr><th>ID Actividad</th><th>ID Estudiante</th><th>ID Evento</th><th>ID Tarea</th><th>Fecha Inicio</th><th>Fecha Fin</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id_Actividades"]."</td><td>".$row["id_Estudiante"]."</td><td>".$row["id_Evento"]."</td><td>".$row["id_Tarea"]."</td><td>".$row["fecha_ini"]."</td><td>".$row["fecha_fin"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron tareas asignadas para este estudiante.";
}
?>
  <?php
    require('footer.php');
  ?>
</body>
</html>
<?php

?>