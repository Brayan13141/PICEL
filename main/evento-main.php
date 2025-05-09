<?php

/**
 * Archivo: /c:/wamp64/www/PICEL-master/PICEL-master/main/evento-main.php
 *
 * Este archivo maneja la interfaz principal para la gestión de eventos en el sistema PICEL.
 *
 * Requisitos:
 * - Debe existir una sesión iniciada con un usuario de tipo "Admin".
 * - Se requiere la conexión a la base de datos a través del archivo '../system/conexion.php'.
 * - Se incluyen varios archivos CSS y JS para el diseño y funcionalidad de la página.
 * - Se requiere el archivo 'menu.php' para mostrar el menú de navegación.
 * - Se requiere el archivo 'footer.php' para mostrar el pie de página.
 *
 * Variables principales:
 * - $_SESSION['id_User']: ID del usuario que ha iniciado sesión.
 * - $_SESSION['tipo_us']: Tipo de usuario que ha iniciado sesión.
 * - $_SESSION['editar']: Variable de sesión para manejar la edición de eventos.
 * - $_SESSION['idEditar']: ID del evento que se está editando.
 * - $link: Conexión a la base de datos.
 * - $query: Consulta SQL para obtener los eventos y sus detalles.
 * - $result: Resultado de la consulta SQL.
 * - $row: Fila actual del resultado de la consulta SQL.
 * - $id_evento: ID del evento.
 * - $periodo: Periodo del evento.
 * - $evento_nombre: Nombre del evento.
 * - $descrip: Descripción del evento.
 * - $docente_nombre: Nombre del docente asociado al evento.
 *
 * Funciones JavaScript:
 * - load(id): Muestra u oculta las secciones de registros y registro de eventos según el valor de 'id'.
 * - editarEvento(id_evento): Función para editar un evento (definida en 'evento-main.js').
 * - modal(id_evento, nombre_evento): Muestra el modal de confirmación de eliminación de un evento.
 * - cancel(): Cancela la acción de registro de un evento.
 *
 * Modales:
 * - deleteModal: Modal para confirmar la eliminación de un evento.
 * - ModalMensaje: Modal para mostrar mensajes al usuario.
 *
 * Redirección:
 * - Si el usuario no tiene una sesión válida o no es un administrador, se redirige a '../main/index.php'.
 */
session_start();
if ((isset($_SESSION['id_User']) && $_SESSION['tipo_us'] == "Admin")) {
    include('../system/conexion.php');
    $_SESSION['editar'] = '';
    $_SESSION['idEditar'] = '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVENTOS</title>
    <link rel="icon" href="../ico/logo.ico">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilo.css?v=<?php echo time(); ?>" rel="stylesheet">
    <script src="../js/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../main/JS/evento-main.js"></script>
</head>

<body>
    <?php
        require('menu.php');
        ?>
    <div class="container margen">
        <div class="row mt-3">
            <div class="col"><button type="button" onclick="load(0);"
                    class="mx-auto d-flex justify-content-center btn btn-success">Ver Registros</button></div>
            <div class="col"><button type="button" onclick="load(1);"
                    class="mx-auto d-flex justify-content-center btn btn-success">Registrar Evento</button></div>
        </div>
        <div class="row" style="display:none;visibility:hidden;" id="registros">
            <div class="col-12 pt-2 text-center">
                <h3>EVENTOS</h3>
            </div>
            <div class="grid mx-auto col-md-12">
                <?php
                    $query = "SELECT evento.id_Evento, evento.periodo, evento.nombre AS evento_nombre, evento.descripcion, docentes.nombre 
                     AS docente_nombre, docentes.apellidoP FROM evento INNER JOIN docentes 
                     ON evento.id_Docente = docentes.id_Docente";
                    echo '<table class="table table-hover mt-3" border="0" cellspacing="2" cellpadding="2"> 
                    <tr> 
                        <td><strong><p>Evento</p></strong></td>
                        <td><strong><p>Periodo</p></strong></td>
                        <td><strong><p>Descripción</p></strong></td>
                        <td><strong><p>Docente</p></strong></td>
                        <td><strong><p>Opciones</p></strong></td>
                    </tr>';
                    if ($result = $link->query($query)) {
                        while ($row = $result->fetch_assoc()) {
                            $id_evento = $row["id_Evento"];
                            $periodo = $row["periodo"];
                            $evento_nombre = $row["evento_nombre"];
                            $descrip = $row["descripcion"];
                            $docente_nombre = $row["docente_nombre"] . " " . $row["apellidoP"];
                            echo '<tr> 
                <td>' . $evento_nombre . '</td> 
                <td>' . $periodo . '</td>
                <td>' . $descrip . '</td> 
                <td>' . $docente_nombre . '</td> 
                <td>
                <button type="button" class="btn btn-sm btn-primary" onclick="editarEvento(' . $id_evento . ')">Editar</button>
                <button type="button" id="btnEliminar" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="modal(' . $id_evento . ',' . ' \'' . addslashes($evento_nombre) . '\')">Eliminar</button>
                   </td> 
                </tr>';
                        }
                        echo "</table>";
                        $result->free();
                    }
                    ?>
            </div>
        </div>

        <div class="row" style="display:none;visibility:hidden;" id="registrar">
            <div class="col-12 text-center pt-2 pb-4">
                <h3>Registro de Eventos</h3> <!-- Corregí el título -->
            </div>
            <div class="grid mx-auto col-md-8" style="--bs-columns: 2;">
                <form action="../system/evento-system.php" method="POST" novalidate id="frm-revento">
                    <div class="mb-3">
                        <label for="periodo" class="form-label">Periodo</label>
                        <input type="text" class="form-control" id="periodo" name="periodo" maxlength="50" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre del Evento</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descrip" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="docente" class="form-label">Docente</label>
                        <select class="form-select" id="docente" name="id_Docente" required>
                            <option value="">Seleccione un docente</option>
                            <?php
                                $query_docentes = "SELECT id_Docente, nombre, apellidoP, apellidoM FROM docentes";
                                $result_docentes = $link->query($query_docentes);

                                while ($docente = $result_docentes->fetch_assoc()) {
                                    $nombre_completo = $docente['nombre'] . ' ' . $docente['apellidoP'] . ' ' . $docente['apellidoM'];
                                    echo '<option value="' . $docente['id_Docente'] . '">' . $nombre_completo . '</option>';
                                }
                                ?>
                        </select>
                    </div>
                    <div class="pt-2 d-flex justify-content-around">
                        <button id="btn-regis" type="submit" class="btn btn-picel">Registrar</button>
                        <button type="button" class="btn btn-picel-Cancel" onclick="cancel()">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                    <label> Esta acción no se puede deshacer.</label>

                </div>
                <div class="modal-footer" style=" background-color: #5fbc18;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
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
      // Esperamos a que el documento se haya cargado completamente
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

    <script>
    function load(id) {
        if (id == 0) {
            document.getElementById("registros").style = "display:block;visibility:visible;";
            document.getElementById("registrar").style = "display:none;visibility:hidden;";
        } else {
            document.getElementById("registrar").style = "display:block;visibility:visible;";
            document.getElementById("registros").style = "display:none;visibility:hidden;";
        }
    }
    </script>
</body>

</html>
<?php
} else {
    header('Location: ../main/index.php');
}
?>