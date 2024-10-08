<?php
session_start();
/*if (isset($_SESSION['tipo_us']) != "Admin" || isset($_SESSION['tipo_us']) != "Docente") {
  header('Location: ../main/index.php');
}*/
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
                    $query = "SELECT DISTINCT t.Nombre,t.descripcion,concat(d.nombre,' ',d.apellidoP,' ',d.apellidoM) 
                    AS nombre_completo, t.estatus, t.anotaciones
                    FROM tarea t, actividades_asignadas a, docentes d
                    WHERE a.id_Actividades = t.id_Actividad
                    AND a.id_Docente = d.id_Docente
                    AND t.id_Estudiante = '" . $_SESSION['num_control'] . "'";



                    echo '<table id="tabReg" class="table table-hover mt-3" border="0" cellspacing="2" cellpadding="2"> 
                    <tr> 
                        <td><strong><p>Tarea</p></strong></td> 
                        <td><strong><p>Descripcion</p></strong></td> 
                        <td><strong><p>Docente</p></strong></td>
                        <td><strong><p>Estatus</p></strong></td>
                        <td><strong><p>Anotaciones</p></strong></td>
                        
                    </tr>';

                    if ($result = $link->query($query)) {
                        while ($row = $result->fetch_assoc()) {
                            $field1name = $row["Nombre"];
                            $field2name = $row["descripcion"];
                            $field3name = $row["nombre_completo"];
                            $field4name = $row["estatus"];
                            $field5name = $row["anotaciones"];
                            echo' <tr>
                                <td><p>' . $field1name . '</p></td>
                                <td><p>' . $field2name . '</p></td>
                                <td><p>' . $field3name . '</p></td>
                                <td><p>' . $field4name . '</p></td>
                                <td><p>' . $field5name . '</p></td>
                            </tr>';
                        }
                        $result->free();
                    }
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