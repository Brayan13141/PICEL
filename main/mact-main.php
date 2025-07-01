<?php

/**
 * Archivo: /c:/wamp64/www/PICEL-master/PICEL-master/main/mact-main.php
 *
 * Este archivo PHP se encarga de mostrar una página de monitoreo de actividades
 * para usuarios autenticados. Dependiendo del tipo de usuario (Admin o Docente),
 * se ejecutan diferentes consultas SQL para obtener los datos necesarios.
 *
 * Variables principales:
 * - $_SESSION['id_User']: ID del usuario autenticado.
 * - $_SESSION['tipo_us']: Tipo de usuario (Admin o Docente).
 * - $_GET['id_E']: ID del evento.
 * - $_GET['id_D']: ID del docente.
 * - $_GET['id_A']: ID de la actividad.
 * - $link: Conexión a la base de datos.
 * - $query3: Consulta SQL para obtener los datos de las tareas y estudiantes.
 * - $query2: Consulta SQL para obtener el ID del docente basado en el ID del usuario.
 * - $result: Resultado de la consulta SQL.
 * - $row: Fila de datos obtenida de la consulta SQL.
 * - $Nombreact: Nombre de la actividad.
 * - $name_completo: Nombre completo del estudiante.
 * - $descripcion: Descripción de la tarea.
 * - $estatus: Estado de la tarea (Entregado o Pendiente).
 * - $anotaciones: Anotaciones de la tarea.
 *
 * Archivos requeridos:
 * - ../system/conexion.php: Archivo de conexión a la base de datos.
 * - menu.php: Archivo que contiene el menú de navegación.
 * - footer.php: Archivo que contiene el pie de página.
 * - ../ico/logo.ico: Icono de la página.
 * - ../css/bootstrap.min.css: Estilos de Bootstrap.
 * - ../css/estilo.css: Estilos personalizados.
 * - ../js/jquery-3.6.0.min.js: Biblioteca jQuery.
 * - ../js/bootstrap.min.js: Biblioteca JavaScript de Bootstrap.
 */
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
        <!-- Usamos el bundle de Bootstrap para que se incluya Popper y se defina "bootstrap" -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <link href="../css/estilo.css?v=<?php echo time(); ?>" rel="stylesheet">
    </head>

    <body>
        <?php require('menu.php'); ?>
        <div class="container margen">
            <div class="row mt-3">
                <div class="col">
                    <!-- Botón para regresar -->
                    <a href="act-main.php" class="btn btn-success">Regresar</a>
                </div>
            </div>

            <div class="row" id="monitoreo">
                <?php
                // Verifica que existan los parámetros necesarios en la URL
                if (
                    isset($_GET['id_E']) && !empty($_GET['id_E']) &&
                    isset($_GET['id_D']) && !empty($_GET['id_D']) &&
                    isset($_GET['id_A']) && !empty($_GET['id_A'])
                ) {
                    $id_Evento  = $_GET['id_E'];
                    $id_Docente = $_GET['id_D'];
                    $id_Act     = $_GET['id_A'];

                    // Lógica de consultas según tipo de usuario
                    if ($_SESSION['tipo_us'] == "Admin") {
                        $query3 = "
                        SELECT DISTINCT
                            CONCAT(e.nombre,' ',e.apellidoP,' ',e.apellidoM) AS nombre_completo,
                            t.id_Tarea,
                            t.nombre,
                            t.Estatus AS estado,
                            t.descripcion,
                            t.Anotaciones,  (
                                SELECT en.archivo
                                FROM entregas en
                                WHERE en.id_Tarea = t.id_Tarea
                                LIMIT 1
                            ) AS direccion_archivo
                        FROM tarea t
                            JOIN actividades_asignadas a ON a.id_Actividades = t.id_Actividad
                            JOIN estudiantes e ON t.id_Estudiante = e.id_Estudiante
                            JOIN docentes d
                        WHERE a.id_Evento = '$id_Evento'
                            AND a.id_Docente = '$id_Docente'
                            AND a.id_Actividades = '$id_Act' 
                    ";
                    }

                    if ($_SESSION['tipo_us'] == "Docente") {
                        // Primero obtenemos el id_Docente correspondiente al usuario actual
                        $query2 = "SELECT id_Docente FROM docentes WHERE id_User = '" . $_SESSION['id_User'] . "'";
                        if ($result = $link->query($query2)) {
                            while ($row = $result->fetch_assoc()) {
                                $id_Docente = $row["id_Docente"];
                            }
                            $result->free();
                        }

                        $query3 = "
                        SELECT DISTINCT
                            CONCAT(est.nombre, ' ', est.apellidoP, ' ', est.apellidoM) AS nombre_completo,
                            t.id_Tarea,
                            t.nombre,
                            t.Estatus AS estado,
                            CONCAT(est.nombre, ' ', est.apellidoP, ' ', est.apellidoM) AS nombre_completo,
                            t.id_Tarea,
                            t.nombre,
                            t.validado,
                            t.descripcion,
                            t.Anotaciones,
                            (
                                SELECT en.archivo
                                FROM entregas en
                                WHERE en.id_Tarea = t.id_Tarea
                                LIMIT 1
                            ) AS direccion_archivo
                        FROM tarea t
                            JOIN actividades_asignadas a ON a.id_Actividades = t.id_Actividad
                            JOIN estudiantes est ON t.id_Estudiante = est.id_Estudiante
                            JOIN docentes d
                        WHERE a.id_Evento = '$id_Evento'
                            AND a.id_Docente = '$id_Docente'
                            AND a.id_Actividades = '$id_Act'
                    ";
                    }

                    echo '<div class="col-12 pt-2 text-center">
                        <h3>Monitorear Actividad</h3>
                      </div>';

                    echo '
                <table class="table table-hover mt-3" border="0" cellspacing="2" cellpadding="2"> 
                    <tr> 
                        <td><strong>Alumno</strong></td> 
                        <td><strong>Tarea</strong></td> 
                        <td><strong>Descripción</strong></td> 
                        <td><strong>Estado</strong></td> 
                        <td><strong>Evidencia</strong></td> 
                        <td><strong>Anotaciones</strong></td>
                        <td><strong>Acciones</strong></td>
                    </tr>';

                    if ($result = $link->query($query3)) {
                        while ($row = $result->fetch_assoc()) {
                            $idTarea          = $row["id_Tarea"];
                            $Nombreact        = $row["nombre"];
                            $name_completo    = $row["nombre_completo"];
                            $descripcion      = $row["descripcion"];

                            $entregado    = ! empty($row["direccion_archivo"]);
                            $validado     = (bool) $row["validado"];

                            $anotaciones      = $row["Anotaciones"];
                            $direccion_archivo = $row["direccion_archivo"] ?? "SIN ENTREGA";
                            if ($validado) {
                                $estadoLabel = '<span class="badge bg-success">Validado</span>';
                            } elseif ($entregado) {
                                $estadoLabel = '<span class="badge bg-warning text-dark">Entregado</span>';
                            } else {
                                $estadoLabel = '<span class="badge bg-secondary">Pendiente</span>';
                            }
                            echo '<tr>
                                <td>' . htmlspecialchars($name_completo) . '</td>
                                <td>' . htmlspecialchars($Nombreact) . '</td>
                                <td>' . htmlspecialchars($descripcion) . '</td>
                                <td>' . $estadoLabel . '</td>
                                <td>
                                    <div class="text-center">';
                            // Mostrar preview y descarga si está "Entregado" y existe archivo
                            if ($entregado && !empty($direccion_archivo)) {
                                $extension = strtolower(pathinfo($direccion_archivo, PATHINFO_EXTENSION));
                                // Imagen
                                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    echo '<img src="' . htmlspecialchars($direccion_archivo) . '" width="200" height="150" alt="Vista previa"><br>';
                                }
                                // PDF
                                elseif ($extension === 'pdf') {
                                    echo '<iframe src="' . htmlspecialchars($direccion_archivo) . '" width="200" height="150" frameborder="0"></iframe><br>';
                                }
                                // Otros formatos: enlace de visualización
                                else {
                                    echo '<a href="' . htmlspecialchars($direccion_archivo) . '" target="_blank">Ver Archivo</a><br>';
                                }
                                // Botón para descargar
                                echo '<a href="' . htmlspecialchars($direccion_archivo) . '" download class="btn btn-primary btn-sm mt-2">Descargar</a>';
                            } else {
                                echo 'No entregado';
                            }
                            echo '      </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <!-- Formulario para guardar anotaciones -->
                                        <form method="POST" action="../system/mact-main.php" onsubmit="return confirmAndSubmit(event, \'¿DESEA GUARDAR LAS ANOTACIONES?\');">
                                            <input type="hidden" name="id_tarea" value="' . intval($idTarea) . '">
                                            <input type="hidden" name="id_E" value="' . htmlspecialchars($id_Evento) . '">
                                            <input type="hidden" name="id_D" value="' . htmlspecialchars($id_Docente) . '">
                                            <input type="hidden" name="id_A" value="' . htmlspecialchars($id_Act) . '">
                                            <textarea class="form-control" rows="2" name="anotaciones" placeholder="Escribe tus anotaciones...">' . htmlspecialchars($anotaciones) . '</textarea>
                                            <button type="submit" class="btn btn-success btn-sm mt-2">Guardar Anotaciones</button>
                                        </form>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">';
                            // Si la tarea está entregada, se muestran los formularios para validar y denegar
                            // Mostrar botones solo si hay entrega y aún NO está validada
                            if ($entregado && ! $validado) {
                                echo '
                                <form method="POST" action="../system/mact-main.php" style="display:inline;" onsubmit="return confirmAndSubmit(event, \'¿DESEAS VALIDAR ESTA TAREA?\');">
                                    <input type="hidden" name="id_tarea" value="' . intval($idTarea) . '">
                                    <input type="hidden" name="accion" value="validar">
                                    <input type="hidden" name="id_E" value="' . htmlspecialchars($id_Evento) . '">
                                    <input type="hidden" name="id_D" value="' . htmlspecialchars($id_Docente) . '">
                                    <input type="hidden" name="id_A" value="' . htmlspecialchars($id_Act) . '">
                                    <button type="submit" class="btn btn-success btn-sm mr-1">Validar</button>
                                </form>
                                <form method="POST" action="../system/mact-main.php" style="display:inline;" onsubmit="return confirmAndSubmit(event, \'¿DESEA DENEGAR ESTA TAREA Y MARCARLA COMO PENDIENTE?\');">
                                    <input type="hidden" name="id_tarea" value="' . intval($idTarea) . '">
                                    <input type="hidden" name="accion" value="denegar">
                                    <input type="hidden" name="id_E" value="' . htmlspecialchars($id_Evento) . '">
                                    <input type="hidden" name="id_D" value="' . htmlspecialchars($id_Docente) . '">
                                    <input type="hidden" name="id_A" value="' . htmlspecialchars($id_Act) . '">
                                    <button type="submit" class="btn btn-danger btn-sm">Denegar</button>
                                </form>';
                            }

                            echo '      </div>
                                </td>
                              </tr>';
                        }
                        echo "</table>";
                        $result->free();
                    } else {
                        echo '<div class="col-12 pt-2 text-center">
                            <h3>No hay registros</h3>
                          </div>';
                    }
                }
                ?>
            </div>
        </div>

        <!-- Modal para mostrar mensajes de redirección (si $_GET["mensaje"] está definido) -->
        <?php
        if (isset($_GET['mensaje'])) {
            echo '<div class="modal fade" id="ModalMensaje" tabindex="-3">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success" style="position: relative;">
                            <div style="position: absolute; left: 10px;">
                                <img src="../imgs/logorecortado.png" class="img-fluid" alt="PICEL" width="55">
                            </div>
                            <h5 class="modal-title w-100 text-center">MENSAJE</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p id="LabelModal">' . htmlspecialchars($_GET['mensaje']) . '</p>
                        </div>
                        <div class="modal-footer bg-success">
                            <h6>Da clic fuera del mensaje para continuar</h6>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var modal = new bootstrap.Modal(document.getElementById("ModalMensaje"));
                    modal.show();
                });
            </script>';
        }
        ?>

        <!-- Nuevo modal de confirmación para acciones de submit -->
        <div class="modal fade" id="ConfirmModal" tabindex="-1" aria-labelledby="ConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #5fbc18; position: relative;">
                        <h5 class="modal-title" id="ConfirmModalLabel">Confirmación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p id="modalConfirmMessage"></p>
                    </div>

                    <div class="modal-footer" style=" background-color: #5fbc18;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="submitPendingForm()">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let pendingForm = null;

            // Esta función se llama en el onsubmit de cada formulario.
            function confirmAndSubmit(e, message) {
                e.preventDefault();
                pendingForm = e.target;
                document.getElementById('modalConfirmMessage').innerText = message;
                var confirmModal = new bootstrap.Modal(document.getElementById('ConfirmModal'));
                confirmModal.show();
                return false;
            }

            function submitPendingForm() {
                if (pendingForm) {
                    pendingForm.submit();
                }
            }
        </script>

        <?php include('footer.php'); ?>
    </body>

    </html>
<?php
} else {
?>
    <html>

    <head>
        <title>Picel</title>
    </head>

    <body bgcolor="black">
        <script>
            location.href = "../";
        </script>
    </body>

    </html>
<?php
}
?>