<?php

/**
 * Archivo: www/PICEL-master/PICEL-master/main/act-main.php
 *
 * Descripción:
 * Este archivo PHP maneja la visualización y definición de actividades para usuarios autenticados.
 * Dependiendo del tipo de usuario (Admin o Docente), se muestran diferentes opciones y datos.
 *
 * Archivos requeridos:
 * - ../system/conexion.php: Conexión a la base de datos.
 * - menu.php: Menú de navegación.
 * - ../main/JS/act-main.php: Archivo JavaScript para manejar la lógica de la página.
 * - ../system/sm-activity.php: Archivo PHP para manejar el registro de actividades.
 * - footer.php: Pie de página.
 *
 * Variables principales:
 * - $_SESSION['id_User']: ID del usuario autenticado.
 * - $_SESSION['tipo_us']: Tipo de usuario (Admin o Docente).
 * - $link: Conexión a la base de datos.
 * - $id_Docente: ID del docente asociado al usuario autenticado.
 * - $id_Evento: ID del evento asociado a una actividad.
 * - $row_d0: Array que contiene los datos de cada actividad.
 * - $opciones: Opciones de estudiantes para el formulario de definición de actividades.
 *
 * Funciones JavaScript:
 * - load(int): Carga la vista de registros o definición de actividades.
 * - enviarDatos(int, int, int): Envía datos de una actividad específica.
 * - modal(int, string): Muestra el modal de confirmación de eliminación.
 *
 * Modales:
 * - deleteModal: Modal para confirmar la eliminación de una actividad.
 * - ModalMensaje: Modal para mostrar mensajes al usuario.
 *
 * Notas:
 * - La página redirige a la página de inicio si el usuario no está autenticado.
 * - Se utiliza Chart.js para mostrar gráficos de porcentaje de tareas entregadas.
 */
session_start();
if (isset($_SESSION['id_User'])) {
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
    <link href="../css/estilo.css?v=<?php echo time(); ?>" rel="stylesheet">
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>
    <?php
        require('menu.php');
        include('../main/JS/act-main.php');
        ?>
    <div class="container margen">
        <div class="row mt-3">
            <div class="col"><button type="button" onclick="load(0);"
                    class="mx-auto d-flex justify-content-center btn btn-success">Ver Registros</button></div>
            <div class="col"><button type="button" onclick="load(1);"
                    class="mx-auto d-flex justify-content-center btn btn-success">Definir Actividad</button></div>
        </div>
        <div class="row" style="display:none;visibility:hidden;" id="registros">
            <div class="col-12 pt-2 text-center">
                <h3>Actividades</h3>
            </div>
            <div class="grid mx-auto col-md-12">
                <?php

                    $consulta = "SELECT id_Docente FROM docentes WHERE id_User = '" . $_SESSION['id_User'] . "'";

                    $id_Docente = 0;

                    if ($result = $link->query($consulta)) {
                        while ($row = $result->fetch_assoc()) {
                            $id_D = $row["id_Docente"];
                            $id_Docente = $id_D;
                        }
                        $result->free();
                    }

                    echo '<table id="tabReg" class="table table-hover mt-3" border="0" cellspacing="2" cellpadding="2"> 
                <tr> 
                    <td><strong><p>Actividad</p></strong></td> 
                    <td><strong><p>Evento</p></strong></td> 
                    <td><strong><p>No. Estudiantes</p></strong></td> 
                    <td><strong><p>Docente</p></strong></td> 
                    <td><strong><p>Estatus</p></strong></td> 
                    <td><strong><p>Detalles</p></strong></td>  
                </tr>';
                    //OBTENEMOS LOS ID DE LOS EVENTOS QUE ESTAN EN LAS ACTIVIDADES ASIGNADAS
                    $query = "SELECT DISTINCT id_Evento FROM actividades_asignadas";
                    if ($_SESSION['tipo_us'] == "Admin") {

                        if ($result1 = $link->query($query)) {

                            while ($row = $result1->fetch_assoc()) {

                                $id_Evento = $row["id_Evento"];
                                $query_d0 = "SELECT DISTINCT 
                                        a.Nombre AS actividad, 
                                        ev.nombre AS evento, 
                                        COUNT(t.id_Estudiante) AS no_estudiantes,
                                        CONCAT(d.nombre, ' ', d.apellidoP) AS docente,
                                        d.id_Docente AS id_docente,
                                        a.id_Actividades AS id_actividad,
                                        (SELECT ROUND((SUM(CASE WHEN Estatus = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) AS porcentaje_entregadas
                                        FROM tarea 
                                        WHERE id_Actividad = a.id_Actividades
                                        GROUP BY id_Actividad) AS porcentaje_entregadas
                                        FROM 
                                            actividades_asignadas a
                                        JOIN 
                                            evento ev ON a.id_Evento = ev.id_Evento
                                        JOIN 
                                            docentes d ON a.id_Docente = d.id_Docente
                                        LEFT JOIN 
                                            tarea t ON a.id_Actividades = t.id_Actividad 
                                        LEFT JOIN 
                                            estudiantes e ON t.id_Estudiante = e.id_Estudiante 
                                        WHERE 
                                            a.id_Evento = $id_Evento
                                        GROUP BY 
                                            a.Nombre, ev.nombre, d.nombre, d.apellidoP, a.id_Actividades, d.id_Docente;";


                                if ($result_d0 = $link->query($query_d0)) {
                                    while ($row_d0 = $result_d0->fetch_array(MYSQLI_NUM)) {

                                        echo '<tr> 
                                            <td>' . (!empty($row_d0[0]) ? $row_d0[0] : '') . '</td> 
                                            <td>' . (!empty($row_d0[1]) ? $row_d0[1] : '') . '</td> 
                                            <td>' . (!empty($row_d0[2]) ? $row_d0[2] : '') . '</td>
                                            <td>' . (!empty($row_d0[3]) ? $row_d0[3] : '') . '</td>  
                                            
                                            <td>

                                            <canvas id="chart-' . $row_d0[5] . '" width="100" height="100"></canvas>
                                                <style>
                                                #chart-' . $row_d0[5] . ' {
                                                    width: 120px !important;
                                                    height: 120px !important;
                                                }
                                                </style>
                                            <div id="percentage-' . $row_d0[5] . '" style="text-align: center; font-size: 16px; margin-top: 10px;"></div>
                                            <script>
                                                var ctx = document.getElementById("chart-' . $row_d0[5] . '").getContext("2d");
                                                var percentage = ' . $row_d0[6] . ';
                                                var myChart = new Chart(ctx, {
                                                    type: "doughnut",
                                                    data: {
                                                        datasets: [{
                                                            data: [percentage, 100 - percentage],
                                                            backgroundColor: ["#4caf50", "#ffeb3b"]
                                                        }],
                                                        labels: ["Entregado", "Pendiente"]
                                                    },
                                                    options: {
                                                        cutout: "70%",
                                                        responsive: false,
                                                        plugins: {
                                                            tooltip: {
                                                                callbacks: {
                                                                    label: function(context) {
                                                                        return context.label + ": " + context.raw + "%";
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                });
                                                // Mostrar el porcentaje debajo del gráfico
                                                document.getElementById("percentage-' . $row_d0[5] . '").innerHTML = percentage + "% Entregado";
                                            </script>
                                            </td>

                                            <td>
                                            <button type="button" onclick="enviarDatos(' . $id_Evento . ',' . $row_d0[4] . ',' . $row_d0[5] . ');" 
                                            class="mx-auto btn btn-success">DETALLES</button>
                                            <button class="mx-auto btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" 
                                            onclick="modal(' . $row_d0[5] . ',\'' . $row_d0[1] . '\')">ELIMINAR</button>
                                            </td>
                                            </tr>';
                                    }
                                }
                            }
                            echo "</table>";
                        }
                    } else if ($_SESSION['tipo_us'] == "Docente") {

                        if ($result1 = $link->query($query)) {

                            while ($row = $result1->fetch_assoc()) {

                                $id_Evento = $row["id_Evento"];
                                $query_d0 = "SELECT DISTINCT 
                                        a.Nombre AS actividad, 
                                        ev.nombre AS evento, 
                                        COUNT(t.id_Estudiante) AS no_estudiantes,
                                        CONCAT(d.nombre, ' ', d.apellidoP) AS docente,
                                        d.id_Docente AS id_docente,
                                        a.id_Actividades AS id_actividad,
                                        (SELECT ROUND((SUM(CASE WHEN Estatus = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) AS porcentaje_entregadas
                                        FROM tarea 
                                        WHERE id_Actividad = a.id_Actividades
                                        GROUP BY id_Actividad) AS porcentaje_entregadas
                                        FROM 
                                            actividades_asignadas a
                                        JOIN 
                                            evento ev ON a.id_Evento = ev.id_Evento
                                        JOIN 
                                            docentes d ON a.id_Docente = d.id_Docente
                                        LEFT JOIN 
                                            tarea t ON a.id_Actividades = t.id_Actividad -- unir con las tareas para obtener estudiantes
                                        LEFT JOIN 
                                            estudiantes e ON t.id_Estudiante = e.id_Estudiante -- obtener información de los estudiantes
                                        WHERE 
                                            a.id_Docente = $id_Docente AND  a.id_Evento = $id_Evento
                                        GROUP BY 
                                            a.Nombre, ev.nombre, d.nombre, d.apellidoP, a.id_Actividades, d.id_Docente;";
                                $result_d0 = $link->query($query_d0);

                                if ($result_d0->num_rows > 0) {

                                    while ($row_d0 = $result_d0->fetch_array(MYSQLI_NUM)) {
                                        echo '<tr> 
                                            <td>' . (!empty($row_d0[0]) ? $row_d0[0] : '') . '</td> 
                                            <td>' . (!empty($row_d0[1]) ? $row_d0[1] : '') . '</td> 
                                            <td>' . (!empty($row_d0[2]) ? $row_d0[2] : '') . '</td>
                                            <td>' . (!empty($row_d0[3]) ? $row_d0[3] : '') . '</td>   
                                             <td>
                                            <canvas id="chart-' . $row_d0[5] . '" width="100" height="100"></canvas>
                                            <style>
                                            #chart-' . $row_d0[5] . ' {
                                                width: 120px !important;
                                                height: 120px !important;
                                            }
                                            </style>
                                            <div id="percentage-' . $row_d0[5] . '" style="text-align: center; font-size: 16px; margin-top: 10px;"></div>
                                            <script>
                                                var ctx = document.getElementById("chart-' . $row_d0[5] . '").getContext("2d");
                                                var percentage = ' . $row_d0[6] . ';//ERROR
                                                var myChart = new Chart(ctx, {
                                                    type: "doughnut",
                                                    data: {
                                                        datasets: [{
                                                            data: [percentage, 100 - percentage],
                                                            backgroundColor: ["#4caf50", "#ffeb3b"]
                                                        }],
                                                        labels: ["Entregado", "Pendiente"]
                                                    },
                                                    options: {
                                                        cutout: "70%",
                                                        responsive: false,
                                                        plugins: {
                                                            tooltip: {
                                                                callbacks: {
                                                                    label: function(context) {
                                                                        return context.label + ": " + context.raw + "%";
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                });
                                                
                                                // Mostrar el porcentaje debajo del gráfico
                                                document.getElementById("percentage-' . $row_d0[5] . '").innerHTML = percentage + "% Entregado";
                                            </script>
                                            </td>

                                            <td>
                                            <button type="button" onclick="enviarDatos(' . $id_Evento . ',' . $row_d0[4] . ',' . $row_d0[5] . ');" 
                                            class="mx-auto btn btn-success">DETALLES</button>
                                            <button  class="mx-auto btn btn-danger" onclick="modal(' . $row_d0[5] . ',' . ' \'' . addslashes($row_d0[1]) . '\')">ELIMINAR</button>
                                            </td>
                                            </tr>';
                                    }
                                }
                            }
                            echo "</table>";
                        }
                    }


                    ?>
            </div>
        </div>

        <div class="row" style="display:none;visibility:hidden;" id="definir">
            <div class="col-12 pt-2 text-center">
                <h2>Definición de Actividades</h2>
            </div>
            <div class="grid mx-auto col-md-8" style="--bs-columns: 2;">
                <form method="POST" id="frm_act" action="../system/act-main.php" novalidate>
                    <div class="row">
                        <!-- Nombre de la Actividad -->
                        <div class="col-12 pt-2 text-center">
                            <h3>Nombre de la actividad</h3>
                            <input type="text" name="NombreA" id="NombreA" class="form-control" required>
                        </div>

                        <!-- Selección de Evento -->
                        <div class="mb-3 col-6">
                            <label for="evento-c" class="form-label">Evento</label>
                            <?php
                                // Obtiene eventos del docente actual
                                $query2 = "SELECT * FROM evento WHERE id_Docente = $id_Docente";
                                echo '<select name="evento" id="cmbEvento" class="form-control" required>';
                                if ($result = $link->query($query2)) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row["id_Evento"] . '">' . $row["nombre"] . '</option>';
                                    }
                                    $result->free();
                                }
                                echo '</select>';
                                ?>
                        </div>

                        <!-- Cantidad de Estudiantes -->
                        <div class="mb-3 col-6">
                            <label for="canEstudiantes-c" class="form-label">Cantidad de estudiantes</label>
                            <?php
                                // Calcula cantidad de estudiantes disponibles
                                $query3 = "SELECT COUNT(*) as total FROM Estudiantes WHERE id_Docente = $id_Docente";
                                $result3 = $link->query($query3);
                                $count = $result3->fetch_assoc()['total'];

                                echo '<select name="canEstudiantes" id="noAlumnos" class="form-control" required>';
                                for ($i = 1; $i <= $count; $i++) {
                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                }
                                echo '</select>';

                                // Genera opciones de estudiantes para JS
                                $query4 = "SELECT * FROM Estudiantes WHERE id_Docente = $id_Docente";
                                $result4 = $link->query($query4);
                                $opciones = '';
                                while ($row = $result4->fetch_assoc()) {
                                    $nc = $row["num_control"];
                                    $nombre = $row["nombre"]; // o como tengas el nombre en BD
                                    $opciones .= "<option value=\"{$nc}\">{$nombre} - {$nc}</option>";
                                }
                                $_SESSION['opciones'] = $opciones;
                                ?>
                        </div>
                    </div>

                    <!-- Contenedor Dinámico para Estudiantes -->
                    <div class="contenedor">
                        <div id="contenedorAlumnos"></div> <!-- Aquí se inyectarán los campos -->
                    </div>

                    <!-- Fecha de Inicio -->
                    <div class="row">
                        <div class="mb-3 col-6">
                            <label for="nombre-c" class="form-label">Fecha Inicio</label>
                            <input type="date" class="form-control" name="fechai" value="<?= date('Y-m-d') ?>"
                                min="2020-01-01" max="2030-12-31" required>
                        </div>
                    </div>

                    <!-- Botón de Registro -->
                    <div class="pt-2 d-flex justify-content-center">
                        <button type="submit" class="btn btn-picel">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true"
        style="border: 1px solid black;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #5fbc18; position: relative;">
                    <div style="position: absolute; left: 10px;">
                        <img src="../imgs/logorecortado.png" class="img-fluid" alt="PICEL" width="55">
                    </div>
                    <h5 class="modal-title w-100 text-center" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        ¿Estás seguro de que deseas eliminar a
                        <strong>
                            <label id="Nombre-Modal">
                            </label>
                        </strong>
                        ?
                    </p>
                    <label>Esta acción no se puede deshacer.</label>
                </div>
                <div class="modal-footer" style=" background-color: #5fbc18;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="border: 1px solid black;">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete"
                        style="border: 1px solid black; ">Eliminar</button>
                </div>
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
                    <p id="LabelModal"><?php
                                                if (isset($_GET['mensaje'])) {
                                                    echo $_GET['mensaje'];
                                                } else {
                                                    echo "";
                                                }
                                                ?></p>
                </div>
                <div class="modal-footer" style=" background-color: #5fbc18;">
                    <h6>Da clic fuera del mensaje para continuar</h6>
                </div>
            </div>
        </div>
    </div>
    <?php
            echo '   <script> 
            document.addEventListener("DOMContentLoaded", function() {
            // Seleccionamos el modal
            var modal = new bootstrap.Modal(document.getElementById("ModalMensaje"));
            // Mostramos el modal
            modal.show();
            });
        </script>';
        }
        ?>

    <?php
        include('footer.php');

        ?>
</body>
<style>
table {
    width: 100%;
}

td {
    text-align: center;
    vertical-align: middle;
}

td button {
    display: flex !important;
    flex-direction: column !important;
}
</style>

</html>
<?php
} else {
?>
<script>
alert('No tienes autorización para ingresar a esta página');
window.location.href = "../index.php";
</script>
<?php
}
?>