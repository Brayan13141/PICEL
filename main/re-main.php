<?php

/**
 * Archivo: /c:/wamp64/www/PICEL-master/PICEL-master/main/re-main.php
 *
 * Este archivo maneja la interfaz de administración y registro de estudiantes.
 *
 * Variables de sesión:
 * - $_SESSION['tipo_us']: Tipo de usuario (Admin o Docente).
 * - $_SESSION['id_User']: ID del usuario.
 * - $_SESSION['editar']: Variable de sesión para editar.
 * - $_SESSION['IdEditar']: ID del estudiante a editar.
 *
 * Archivos requeridos:
 * - ../system/conexion.php: Archivo de conexión a la base de datos.
 * - menu.php: Archivo que contiene el menú de navegación.
 * - ../css/bootstrap.min.css: Archivo CSS de Bootstrap.
 * - ../css/estilo.css: Archivo CSS personalizado.
 * - ../js/jquery-3.6.0.min.js: Archivo JavaScript de jQuery.
 * - ../js/bootstrap.min.js: Archivo JavaScript de Bootstrap.
 * - ../main/JS/re-main.js: Archivo JavaScript personalizado.
 * - ../system/sm_re.php: Archivo que maneja el registro de estudiantes.
 * - footer.php: Archivo que contiene el pie de página.
 *
 * Funcionalidades:
 * - Ver registros de estudiantes.
 * - Registrar nuevos estudiantes.
 * - Editar y eliminar estudiantes.
 * - Mostrar mensajes de confirmación y errores.
 *
 * Estructura HTML:
 * - Contenedor principal con botones para ver registros y registrar estudiantes.
 * - Tabla para mostrar los registros de estudiantes.
 * - Formulario para registrar nuevos estudiantes.
 * - Modal para confirmar la eliminación de estudiantes.
 * - Modal para mostrar mensajes.
 *
 * Redirección:
 * - Si el usuario no es Admin o Docente, se redirige a index.php.
 */
session_start();
if (!isset($_SESSION['tipo_us']) || ($_SESSION['tipo_us'] != "Admin" && $_SESSION['tipo_us'] != "Docente")) {
    header('Location: ../main/index.php');
    exit();
}

if ((isset($_SESSION['id_User']) && $_SESSION['tipo_us'] == "Admin") ||
    (isset($_SESSION['id_User']) && $_SESSION['tipo_us'] == "Docente")
) {
    include('../system/conexion.php');
    $_SESSION['editar'] = null;
    $_SESSION['IdEditar'] = null;
?>
    <!DOCTYPE html>
    <html lang="en">


    <head>
        <title>ESTUDIANTES</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="../ico/logo.ico">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/estilo.css?v=<?php echo time(); ?>" rel="stylesheet">
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../main/JS/re-main.js"> </script>

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
                        class="mx-auto d-flex justify-content-center btn btn-success">Registrar Estudiante</button></div>
            </div>

            <div class="row" style="display:none;visibility:hidden;" id="registros">
                <div class="col-12 pt-2 text-center">
                    <h3>Estudiantes</h3>
                </div>
                <div class="grid mx-auto col-md-12">
                    <?php
                    echo '<table class="table table-hover mt-3" border="0" cellspacing="2" cellpadding="2"> 
        <tr> 
              <td><strong><p>No. Control</p></strong></td>
              <td><strong><p>Nombre</p></strong></td>
              <td><strong><p>Correo</p></strong></td>
              <td><strong><p>Carrera</p></strong></td>
              <td><strong><p>Semestre</p></strong></td>
              <td><strong><p>Teléfono</p></strong></td>
              <td><strong><p>Opciones</p></strong></td>
        </tr>';

                    if ($_SESSION['tipo_us'] == "Admin") {
                        $query = "SELECT * FROM estudiantes";
                    } else if ($_SESSION['tipo_us'] == "Docente") {
                        $query = "SELECT * FROM estudiantes WHERE id_Docente = (SELECT id_Docente FROM docentes WHERE id_User = " . $_SESSION['id_User'] . ")";
                    }

                    if ($result = $link->query($query)) {
                        while ($row = $result->fetch_assoc()) {
                            $ncon = $row["num_control"];
                            $name = $row["nombre"] . " " . $row["apellidoP"] . " " . $row["apellidoM"];
                            $correo = $row["correo"];
                            $carr = $row["carrera"];
                            $sem = $row["semestre"];
                            $cel = $row["num_celular"];
                            $id_E = $row["id_Estudiante"];

                            echo '<tr> 
                            <td>' . $ncon . '</td> 
                            <td>' . $name . '</td> 
                            <td>' . $correo . '</td>
                            <td>' . $carr . '</td> 
                            <td>' . $sem . '</td> 
                            <td>' . $cel . '</td> 
                       <td>
                        <button type="button" class="btn btn-sm btn-primary" onclick="editarAlumno(' . $id_E . ')">Editar</button>
                        <button type="button" id="btnEliminar" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="modal(' . $id_E . ',' . '\'' . addslashes($name) . '\')">Eliminar</button>
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
                <div class="col text-center pt-2 pb-4">
                    <h3>Registro de Estudiantes</h3>
                </div>
                <div class="grid mx-auto col-md-8" style="--bs-columns: 2;">
                    <form id="frm-re" method="POST" action="../system/sm_re.php" novalidate>
                        <div class="row">
                            <div class="mb-3 col-3">
                                <label for="nc-c" class="form-label">No. de Control</label>
                                <input type="text" class="form-control" id="nc-c" name="numerocontrol" required>
                            </div>
                            <div class="mb-3 col-3">
                                <label for="nombre-c" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre-c" name="nombre" required>
                            </div>
                            <div class="mb-3 col-3">
                                <label for="apellidoP-c" class="form-label">Apellido Paterno</label>
                                <input type="text" class="form-control" id="apellidoP-c" name="apellidoP" required>
                            </div>
                            <div class="mb-3 col-3">
                                <label for="apellidoM-c" class="form-label">Apellido Materno</label>
                                <input type="text" class="form-control" id="apellidoM-c" name="apellidoM" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label for="carrera-c" class="form-label">Carrera</label>
                                <select name="carrera" class="form-control" id="carrera-c" required>
                                    <option value="ING SISTEMAS COMPUTACIONALES">ING SISTEMAS COMPUTACIONALES</option>
                                    <option value="ING AMBIENTAL">ING AMBIENTAL</option>
                                    <option value="ING INDUSTRIAL">ING INDUSTRIAL</option>
                                    <option value="ING GESTION EMPRESARIAL">ING GESTION EMPRESARIAL</option>
                                    <option value="ING ELECTRONICA">ING ELECTRONICA</option>
                                    <option value="ING SISTEMAS AUTOMOTRICES">ING SISTEMAS AUTOMOTRICES</option>
                                    <option value="GASTRONOMIA">GASTRONOMIA</option>
                                </select>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="correo-c" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="correo-c" name="correoinstituto" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label for="semestre-c" class="form-label">Semestre</label>
                                <input type="number" class="form-control" id="semestre-c" min="1" max="16" value="1"
                                    name="semestre" required>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="telefono-c" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="telefono-c" name="telefono" required>
                            </div>
                        </div>
                        <div class="row" id="usuarioEstudiante">
                            <div class="mb-3 col-6">
                                <label for="usuario-c" class="form-label">Usuario</label>
                                <input type="text" class="form-control" id="usuario-c" name="usuario" required>
                            </div>
                            <div class="mb-3 col-6">
                                <label for="password-c" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password-c" name="password" required>
                            </div>
                        </div>
                        <div class="pt-2 d-flex justify-content-around">
                            <button id="btn-regis" type="submit" class="btn btn-picel">Registrar</button>
                            <button type="button" class="btn btn-picel-Cancel" onclick="cancel()">Cancelar</button>
                        </div>
                    </form>
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
                                style="border: 1px solid black;">Eliminar</button>
                        </div>
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
            echo '<script>
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
        <?php include('footer.php'); ?>
        </div>
    </body>

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
            location.href = "../main/index.php";
        </script>

    </body>
<?php
}
?>