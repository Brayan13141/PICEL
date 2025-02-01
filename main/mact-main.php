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
        <link href="../css/estilo.css?v=<?php echo time(); ?>" rel="stylesheet">
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </head>

    <body>
        <?php
        require('menu.php');
        ?>
        <div class="container margen">
            <div class="row mt-3">
                <div class="col"><button type="button" onclick="regresar();"
                        class="mx-auto btn btn-success">Regresar</button></div>
            </div>

            <div class="row" id="monitoreo">
                <?php

                if (isset($_GET['id_E']) && !empty($_GET['id_E']) && isset($_GET['id_D']) && !empty($_GET['id_D']) && isset($_GET['id_A']) && !empty($_GET['id_A'])) {
                    $id_Evento = $_GET['id_E'];
                    $id_Docente = $_GET['id_D'];
                    $id_Act = $_GET['id_A'];
                    if ($_SESSION['tipo_us'] == "Admin") {

                        $query3 = "SELECT DISTINCT  concat(e.nombre,' ',e.apellidoP,' ',e.apellidoM) AS nombre_completo ,
                         t.nombre,t.Estatus as estado,t.descripcion,t.Anotaciones 
                        FROM tarea t, actividades_asignadas a, estudiantes e, docentes d
                        WHERE t.id_Estudiante = e.id_Estudiante
                        AND  a.id_Evento = '$id_Evento' AND '$id_Docente' = a.id_Docente 
                        AND a.id_Actividades = '$id_Act'
                        AND a.id_Actividades = t.id_Actividad";
                    }
                    if ($_SESSION['tipo_us'] == "Docente") {

                        $query2 = "SELECT id_Docente FROM docentes WHERE id_User = '" . $_SESSION['id_User'] . "'";
                        if ($result = $link->query($query2)) {
                            while ($row = $result->fetch_assoc()) {
                                $id_Docente = $row["id_Docente"];
                            }
                            $result->free();
                        }

                        $query3 = "SELECT DISTINCT concat(e.nombre,' ',e.apellidoP,' ',e.apellidoM) AS nombre_completo,
                        t.nombre,t.Estatus as estado,t.descripcion,t.Anotaciones 
                        FROM tarea t, actividades_asignadas a, estudiantes e, docentes d
                        WHERE t.id_Estudiante = e.id_Estudiante
                        AND  a.id_Evento = '$id_Evento' AND '$id_Docente' = a.id_Docente 
                        AND a.id_Actividades = '$id_Act'
                        AND a.id_Actividades = t.id_Actividad";
                    }



                    echo '<div class="col-12 pt-2 text-center">
                        <h3>Monitorear Actividad</h3>
                          </div>';
                    echo '
                    <table class="table table-hover mt-3" border="0" cellspacing="2" cellpadding="2"> 
                        <tr> 
                         <td><strong><p>Alumno</p></strong></td> 
                         <td><strong><p>Tarea</p></strong></td> 
                         <td><strong><p>Descripcion</p></strong></td> 
                         <td><strong><p>Estado</p></strong></td> 
                         <td><strong><p>Evidencia</p></strong></td> 
                         <td><strong><p>Anotaciones</p></strong></td> 

                          
                        </tr>';
                    if ($result = $link->query($query3)) {
                        while ($row = $result->fetch_assoc()) {
                            $Nombreact = $row["nombre"];
                            $name_completo = $row["nombre_completo"];
                            $descripcion = $row["descripcion"];
                            $estatus = $row["estado"] ? "Entregado" : "Pendiente";
                            $anotaciones = $row["Anotaciones"];

                            echo '<tr> 
                                    <td>' . $name_completo . '</td> 
                                    <td>' . $Nombreact . '</td> 
                                    <td>' . $descripcion . '</td> 
                                    <td >' . $estatus  . '</td>
                                    <td>Nada</td>
                                    <td>' . $anotaciones . '</td>
                                </tr>';
                        }
                        echo "</table>";
                        $result->free();
                    } else {
                        echo '<div class="col-12 pt-2 text-center">
                            <h3>No hay registros</h3>';
                    }
                }
                ?>
            </div>
        </div>

        <?php
        include('footer.php');
        ?>
    </body>
    <script>
        function regresar() {
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
            location.href = "../";
        </script>
    </body>
<?php
}
?>