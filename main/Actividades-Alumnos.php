<?php
session_start();
if (isset($_SESSION['tipo_us']) && isset($_SESSION['id_User'])) {
    include('../system/conexion.php');
    // Obtener el ID del estudiante desde la base de datos
    $id_Estudiante = null;
    if (isset($_SESSION['num_control'])) {
        $queryEstudiante = "SELECT id_Estudiante FROM estudiantes WHERE num_control = '" . $link->real_escape_string($_SESSION['num_control']) . "'";
        $resultEstudiante = $link->query($queryEstudiante);
        if ($resultEstudiante && $rowEstudiante = $resultEstudiante->fetch_assoc()) {
            $id_Estudiante = $rowEstudiante['id_Estudiante'];
        }
    }
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
        <link rel="stylesheet" href="../main/CSS/Actividades-Alumno.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </head>

    <body>
        <?php require('menu.php'); ?>
        <div class="container margen">
            <div class="row" id="registros" style="display:block; visibility:visible;">
                <div class="col-12 pt-2 text-center">
                    <h3>Actividades</h3>
                </div>
                <div class="grid mx-auto col-md-12">
                    <?php
                    $query = "SELECT DISTINCT t.id_Tarea, t.Nombre, t.descripcion,t.Estatus,
                              CONCAT(d.nombre,' ',d.apellidoP,' ',d.apellidoM) AS nombre_completo, 
                              t.anotaciones
                              FROM tarea t
                              INNER JOIN actividades_asignadas a ON a.id_Actividades = t.id_Actividad
                              INNER JOIN docentes d ON a.id_Docente = d.id_Docente
                              WHERE t.id_Estudiante = '$id_Estudiante'";

                    if ($result = $link->query($query)) {
                        while ($row = $result->fetch_assoc()) {
                            $id_tarea      = $row["id_Tarea"];
                            $nombreTarea   = $row["Nombre"];
                            $descripcion   = $row["descripcion"];
                            $nombreDocente = $row["nombre_completo"];
                            $anotaciones   = $row["anotaciones"];
                            $estatus = $row["Estatus"];
                            // Consultar en la tabla "entregas" para ver si el alumno ya entregó la tarea
                            $queryEntrega = "SELECT * FROM entregas WHERE id_Tarea = '$id_tarea'";
                            $resultEntrega = $link->query($queryEntrega);

                            if ($resultEntrega && $rowEntrega = $resultEntrega->fetch_assoc()) {
                                // Tarea ya entregada: mostrar preview del archivo entregado
                                $archivo_entregado = $rowEntrega['archivo'];
                                $ext = strtolower(pathinfo($archivo_entregado, PATHINFO_EXTENSION));
                                if (in_array($ext, array('jpg', 'jpeg', 'png', 'gif'))) {
                                    $previewHTML = '<img src="' . $archivo_entregado . '" alt="Preview" class="img-fluid" style="max-width:200px;">';
                                } else {
                                    $previewHTML = '<a href="' . $archivo_entregado . '" target="_blank">Ver Archivo Entregado</a>';
                                }
                                $Archivo = $previewHTML;
                            } else {
                                // Tarea pendiente: se muestra input para subir archivo, preview centrado y botón de envío
                                $Archivo = '
                                    <div>
                                    <input type="file"  id="customFile' . $id_tarea . '" name="archivo" required onchange="previewFile(this, \'preview' . $id_tarea . '\', \'submitBtn' . $id_tarea . '\')">
                                    <button type="submit" class="btn btn-success" disabled id="submitBtn' . $id_tarea . '">ENVIAR</button>
                                    </div>
                                    <div id="preview' . $id_tarea . '" class="text-center" style="margin-top:10px;"></div>';
                            }
                            if ($estatus) {
                                $estatusBadge = '<span class="badge badge-entregado">Entregado</span>';
                            } else {
                                $estatusBadge = '<span class="badge badge-pendiente">Pendiente</span>';
                            }


                            echo '<div class="card actividad-card">
                                    <div class="Status">' . $estatusBadge . '</div>
                                    <div class="card-header actividad-header">' . htmlspecialchars($nombreTarea) . '</div>
                                    <div class="card-body actividad-body">
                                        <div class="D_D">
                                            <p><strong>Docente:</strong> ' . htmlspecialchars($nombreDocente) . '</p>
                                            <p><strong>Descripción:</strong> ' . htmlspecialchars($descripcion) . '</p>
                                        </div>
                                        <p><strong>Anotaciones:</strong> ' . htmlspecialchars($anotaciones) . '</p>
                                    </div>
                                    <div class="card-footer actividad-footer">';

                            if (!isset($rowEntrega)) {
                                // Mostrar el formulario para entregar la tarea
                                echo '<form action="../system/Actividades_Alumno-system.php" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" name="id_tarea" value="' . $id_tarea . '">
                                        <div class="form-group">
                                            <div class="D_D">
                                                ' . $Archivo . '
                                            </div>
                                        </div>
                                      </form>';
                            } else {
                                // Si ya se entregó, mostrar únicamente la preview centrada
                                echo '<div class="text-center">' . $Archivo . '</div>';
                            }

                            echo '</div>
                                  </div>';
                        }
                        $result->free();
                    }
                    ?>
                </div>
            </div>
        </div>

        <?php
        if (isset($_GET['mensaje'])) {
        ?>
            <div class="modal fade" id="ModalMensaje" style="border: 1px solid black;" tabindex="-3">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #5fbc18; position: relative;">
                            <div style="position: absolute; left: 10px;">
                                <img src="../imgs/logorecortado.png" class="img-fluid" alt="PICEL" width="55">
                            </div>
                            <h5 class="modal-title w-100 text-center">MENSAJE</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p id="LabelModal"><?php echo isset($_GET['mensaje']) ? $_GET['mensaje'] : ""; ?></p>
                        </div>
                        <div class="modal-footer" style=" background-color: #5fbc18;">
                            <h6>Da clic fuera del mensaje para continuar</h6>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var modal = new bootstrap.Modal(document.getElementById("ModalMensaje"));
                modal.show();
            });
        </script>';
        ?>
        <footer>
            <?php include('footer.php'); ?>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../main/JS/Actividades_Alumnos.js"></script>
        <!-- Función JavaScript para la preview del archivo -->
        <script>
            function previewFile(input, previewId, submitBtnId) {
                var file = input.files[0];
                var preview = document.getElementById(previewId);
                var submitBtn = document.getElementById(submitBtnId);
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        if (file.type.match('image.*')) {
                            preview.innerHTML = '<img src="' + e.target.result +
                                '" alt="Preview" class="img-fluid" style="max-width:200px;">';
                        } else {
                            preview.innerHTML = '<p>Archivo seleccionado: ' + file.name + '</p>';
                        }
                        submitBtn.disabled = false;
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.innerHTML = '';
                    submitBtn.disabled = true;
                }
            }
        </script>
    </body>

    </html>
<?php
} // Fin de verificación de sesión
?>