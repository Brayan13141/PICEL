<?php
session_start();
if ($_SESSION['tipo_us'] != "Admin") {
  header("Location: index.php");
}
if ($_SESSION['id_User']) {
  include('../system/conexion.php');
  $_SESSION['ID_ACCION'] = 0
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
    <script src="../js/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../main/JS/rd-main.js"></script>
  </head>

  <body>
    <?php
    require('menu.php');
    ?>
    <div class="container margen">
      <div class="row mt-3">
        <div class="col"><button type="button" onclick="load(0);" class="mx-auto d-flex justify-content-center btn btn-success">Ver Registros</button></div>
        <div class="col"><button type="button" onclick="load(1);" class="mx-auto d-flex justify-content-center btn btn-success">Registrar Docente</button></div>
      </div>
      <div class="row" style="display:none;visibility:hidden;" id="registros">
        <div class="col-12 pt-2 text-center">
          <h3>Docentes</h3>
        </div>
        <div class="grid mx-auto col-md-12">
          <?php
          $query = "SELECT * FROM docentes";
          echo '<table class="table table-hover mt-3" border="0" cellspacing="2" cellpadding="2"> 
          <tr> 
              <td><p>Nombre</p></td> 
              <td><p>Correo</p></td> 
              <td><p>Carrera</p></td>
              <td><p>Teléfono</p></td> 
              <td><p>Opciones</p></td> 
          </tr>';
          if ($result = $link->query($query)) {
            while ($row = $result->fetch_assoc()) {
              $id_doc = $row["id_Docente"];
              $name_doc = $row["nombre"] . " " . $row["apellidoP"] . " " . $row["apellidoM"];
              $email_doc = $row["correo"];
              $carrer_doc = $row["carrera"];
              $tel_doc = $row["num_celular"];
              echo '<tr> 
                        <td>' . $name_doc . '</td> 
                        <td>' . $email_doc . '</td> 
                        <td>' . $carrer_doc . '</td> 
                        <td>' . $tel_doc . '</td>
                        <td>
                        <button type="button" class="btn btn-sm btn-primary" onclick="editarDocente(' . $id_doc . ')">Editar</button>
                        <button type="button" id="btnEliminar" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="modal(' . $id_doc . ' \'' . addslashes($name_doc) . '\')">Eliminar</button>
                       </td> 
                    </tr>';
            }
            echo "</table>";
            $result->free();
          }
          ?>
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
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
            <label>   Esta acción no se puede deshacer.</label>

          </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row" style="display:none;visibility:hidden;" id="registrar">
        <div class="col-12 text-center pt-2 pb-4">
          <h3>Registro de Docentes</h3>
        </div>
        <div class="grid mx-auto col-md-8" style="--bs-columns: 2;">
            <form method="POST" action="../system/sm_rd.php" novalidate id="frm-rd">
            <div class="row">
              <div class="mb-3 col-4">
              <label for="nombre-c" class="form-label">Nombre</label>
              <input type="text" class="form-control" id="nombre-c" name="nombre" required>
              </div>
              <div class="mb-3 col-4">
              <label for="apellidoP-c" class="form-label">Apellido Paterno</label>
              <input type="text" class="form-control" id="apellidoP-c" name="apellidoP" required>
              </div>
              <div class="mb-3 col-4">
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
              <input type="email" class="form-control" id="correo-c" name="correo" required>
              </div>
            </div>
            <div class="row">
              <div class="mb-3 col-6">
              <label for="telefono-c" class="form-label">Teléfono</label>
              <input type="text" class="form-control" id="telefono-c" name="telefono" required>
              </div>
              <div class="mb-3 col-6">
              <label for="tipoDocente" class="form-label">Tipo de Docente</label>
              <select id="tipoDocente" name="tipo" class="form-control" required>
                <option value="1" selected>Normal</option>
                <option value="2">Administrador</option>
              </select>
              </div>
            </div>
            <div class="row" id="usuarioDocente">
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
    </div>
    <?php
    if (isset($_GET['mensaje'])) {
    ?>
      <div class="modal fade" id="ModalMensaje" tabindex="-3">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Mensaje</h5>
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
            <div class="modal-footer">
              <h6>Da clic fuera del mensaje para continuar</h6>
            </div>
          </div>
        </div>
      </div>
    <?php
      echo '
     <script>
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
          <?php $_SESSION['ID_ACCION'] = 0 ?>
          $('#nombre-c').val("");
          $('#apellidoP-c').val("");
          $('#apellidoM-c').val("");
          $('#carrera-c').val("");
          $('#correo-c').val("");
          $('#telefono-c').val("");
          $('#usuario-c').val("");
          $('#password-c').val("");
          document.getElementById("registrar").style = "display:block;visibility:visible;";
          document.getElementById("registros").style = "display:none;visibility:hidden;";
        }
      }
    </script>

  </body>

  </html>
<?php
}
?>