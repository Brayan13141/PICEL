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
        <link rel="stylesheet" href="../main/CSS/Actividades-Alumno.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

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
                    $query = "SELECT DISTINCT t.id_Tarea,t.Nombre,t.descripcion,concat(d.nombre,' ',d.apellidoP,' ',d.apellidoM) 
                    AS nombre_completo, t.estatus, t.anotaciones
                    FROM tarea t, actividades_asignadas a, docentes d
                    WHERE a.id_Actividades = t.id_Actividad
                    AND a.id_Docente = d.id_Docente
                    AND t.id_Estudiante = '" . $_SESSION['num_control'] . "'";

                    if ($result = $link->query($query)) {
                        while ($row = $result->fetch_assoc()) {
                            $id_tarea = $row["id_Tarea"];
                            $nombreTarea = $row["Nombre"];
                            $descripcion = $row["descripcion"];
                            $nombreDocente = $row["nombre_completo"];
                            $estatus = $row["estatus"];
                            $anotaciones = $row["anotaciones"];

                            // Determinar el estado de la tarea
                            $estatusBadge = $estatus == 0 ? '<span class="badge badge-pendiente">Pendiente</span>' : '<span class="badge badge-entregado">Entregado</span>';

                            if ($estatus == 0) {
                                $Archivo = ' 
                                                <input type="file" class="custom-file-input" id="customFile">
                                                
                                            </div>
                                                 <button type="submit" class="btn btn-success" disabled>ENVIAR</button>
                                            </div>
                                            ';
                            } else if ($estatus == 1) {
                                $Archivo = '</div> 
                                            </div>';
                            }


                            echo '<div class="card actividad-card">
                                    <div class="Status">
                                                ' . $estatusBadge . '
                                    </div>
                                    <div class="card-header actividad-header">
                                          ' . htmlspecialchars($nombreTarea) . '
                                    </div>
                                    <div class="card-body actividad-body">
                                       <div class="D_D">
                                        <p><strong>Docente:</strong> ' . htmlspecialchars($nombreDocente) . '</p>
                                        <p><strong>Descripción:</strong> ' . htmlspecialchars($descripcion) . '</p>
                                       </div>
                                        <p><strong>Anotaciones:</strong> ' . htmlspecialchars($anotaciones) . '</p>
                                    </div>
                                    <div class="card-footer actividad-footer">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="id_tarea" value="' . $id_tarea . '">
                                            <div class="form-group D_D">
                                            <div>
                                        ' . $Archivo . '
                                           
                                        </form>
                                    </div>
                                </div>';

                            echo '';
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    </body>

    </html>
<?php
}
?>