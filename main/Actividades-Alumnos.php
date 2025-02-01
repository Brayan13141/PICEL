<?php

/**
 * Archivo: /c:/wamp64/www/PICEL-master/PICEL-master/main/Actividades-Alumnos.php
 *
 * Este archivo muestra las actividades asignadas a los alumnos.
 * Requiere que el usuario haya iniciado sesión y tenga los permisos adecuados.
 *
 * Variables principales:
 * - $_SESSION['tipo_us']: Tipo de usuario (Admin, Docente, etc.)
 * - $_SESSION['id_User']: ID del usuario
 * - $_SESSION['num_control']: Número de control del estudiante
 * - $link: Conexión a la base de datos
 * - $query: Consulta SQL para obtener las actividades asignadas
 * - $result: Resultado de la consulta SQL
 * - $row: Fila actual de los resultados de la consulta
 * - $id_tarea: ID de la tarea
 * - $nombreTarea: Nombre de la tarea
 * - $descripcion: Descripción de la tarea
 * - $nombreDocente: Nombre completo del docente
 * - $estatus: Estado de la tarea (0: Pendiente, 1: Entregado)
 * - $anotaciones: Anotaciones de la tarea
 * - $estatusBadge: Etiqueta HTML que muestra el estado de la tarea
 * - $Archivo: HTML para el archivo de la tarea
 *
 * Archivos requeridos:
 * - ../system/conexion.php: Archivo para la conexión a la base de datos
 * - menu.php: Archivo que contiene el menú de navegación
 * - footer.php: Archivo que contiene el pie de página
 * - ../css/bootstrap.min.css: Archivo CSS de Bootstrap
 * - ../css/estilo.css: Archivo CSS personalizado
 * - ../js/jquery-3.6.0.min.js: Archivo JavaScript de jQuery
 * - ../js/bootstrap.min.js: Archivo JavaScript de Bootstrap
 * - ../main/CSS/Actividades-Alumno.css: Archivo CSS específico para esta página
 * - https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css: Archivo CSS de iconos de Bootstrap
 * - https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css: Archivo CSS de Bootstrap
 * - https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js: Archivo JavaScript de Bootstrap
 */
session_start();
/*if (isset($_SESSION['tipo_us']) != "Admin" || isset($_SESSION['tipo_us']) != "Docente") {
  header('Location: ../main/index.php');
}*/
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
                    $query = "SELECT DISTINCT t.id_Tarea, t.Nombre, t.descripcion, 
                    CONCAT(d.nombre,' ',d.apellidoP,' ',d.apellidoM) AS nombre_completo, 
                    t.estatus, t.anotaciones
                    FROM tarea t
                    INNER JOIN actividades_asignadas a ON a.id_Actividades = t.id_Actividad
                    INNER JOIN docentes d ON a.id_Docente = d.id_Docente
                    WHERE t.id_Estudiante = '$id_Estudiante'";

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