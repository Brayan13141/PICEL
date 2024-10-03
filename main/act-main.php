<?php
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

  </head>

  <body>
    <?php
    require('menu.php');
    ?>
    <div class="container margen">
      <div class="row mt-3">
        <div class="col"><button type="button" onclick="load(0);"
            class="mx-auto d-flex justify-content-center btn btn-success">Ver Registros</button></div>
        <?php
        if ($_SESSION['tipo_us'] == "Admin") {
        ?>
          <div class="col"><button type="button" onclick="load(1);"
              class="mx-auto d-flex justify-content-center btn btn-success">Definir Actividad</button></div>
        <?php
        }
        ?>
      </div>
      <div class="row" style="display:none;visibility:hidden;" id="registros">
        <div class="col-12 pt-2 text-center">
          <h3>Actividades</h3>
        </div>
        <div class="grid mx-auto col-md-12">
          <?php
          $query = "SELECT DISTINCT id_Evento FROM actividades_asignadas";
          echo '<table id="tabReg" class="table table-hover mt-3" border="0" cellspacing="2" cellpadding="2"> 
              <tr> 
                  <td><strong><p>Evento</p></strong></td> 
                  <td><strong><p>No. Estudiantes</p></strong></td> 
                  <td><strong><p>Docente</p></strong></td> 
                  <td><strong><p>Estatus</p></strong></td> 
                  <td><strong><p>Detalles</p></strong></td>  
              </tr>';

          if ($result = $link->query($query)) {
            while ($row = $result->fetch_assoc()) {
              $id_Evento = $row["id_Evento"];
              $query_d0 = "SELECT DISTINCT ev.nombre, 
                  (SELECT count(*) FROM evento ev1 JOIN actividades_asignadas a1 ON ev1.id_Evento = a1.id_Evento
                  WHERE ev1.id_Evento = a.id_Evento), 
                  concat(d.nombre,' ',d.apellidoP,' ',d.apellidoM) 
                  FROM actividades_asignadas a, estudiantes e, evento ev, docentes d 
                  WHERE a.id_Estudiante = e.id_Estudiante AND a.id_Evento = ev.id_Evento 
                  AND ev.id_Docente = d.id_Docente AND a.id_Evento = '$id_Evento'";
              $result_d0 = $link->query($query_d0);
              $row_d0 = $result_d0->fetch_array(MYSQLI_NUM);
              echo '<tr> 
                            <td>' . (!empty($row_d0[0]) ? $row_d0[0] : '') . '</td> 
                            <td>' . (!empty($row_d0[1]) ? $row_d0[1] : '') . '</td> 
                            <td>' . (!empty($row_d0[2]) ? $row_d0[2] : '') . '</td> 
                            <td>0%</td>
                            <td><button type="button" id="btnMonitoreo' . $id_Evento . '" value="' . $id_Evento . '" class="mx-auto btn btn-success">+</button></td>
                            <td><button  class="mx-auto btn btn-success">ELIMINAR</button></td>
                        </tr>';
            }
            echo "</table>";
          }
          ?>
        </div>
      </div>

      <div class="row" style="display:none;visibility:hidden;" id="definir">
        <div class="col-12 pt-2 text-center">
          <h2>Definición de Actividades</h2>
        </div>
        <div class="grid mx-auto col-md-8" style="--bs-columns: 2;">
          <form method="POST" id="frm_act" action="../system/sm-activity.php?caso=1" novalidate>
            <div class="row">
              <div class="col-12 pt-2 text-center">
                <h3>Nombre de la actividad</h3>
                <input type="text" name="NombreA" id="NombreA" class="form-control">
              </div>
              <div class="mb-3 col-6">
                <label for="evento-c" class="form-label">Evento</label>
                <?php
                $query2 = "SELECT * FROM evento";
                echo '<select name="evento" id="cmbEvento" class="form-control" required>';
                if ($result = $link->query($query2)) {
                  while ($row = $result->fetch_assoc()) {
                    $name = $row["nombre"];
                    $id = $row["id_Evento"];
                    echo '<option value="' . $id . '">' . $name . '</option>';
                  }
                  $result->free();
                }
                echo ' </select>';
                ?>
              </div>
              <div class="mb-3 col-6">
                <label for="canEstudiantes-c" class="form-label">Cantidad de estudiantes</label>
                <?php
                $query2 = "SELECT count(*) FROM Estudiantes";
                $query3 = "SELECT * FROM Estudiantes";
                $opciones = '';
                if ($result = $link->query($query2)) {
                  $row = $result->fetch_assoc();
                  $count = $row['count(*)'];
                  echo '<select name="canEstudiantes" id="noAlumnos" class="form-control" required>';
                  for ($i = 1; $i <= $count; $i++) {
                    echo '<option value="' . $i . '">' . $i . '</option>';
                  }
                  echo '</select>';

                  if ($result2 = $link->query($query3)) {
                    while ($row2 = $result2->fetch_assoc()) {
                      $name = $row2["nombre"];
                      $apellido = $row2["apellidoP"];
                      $opciones .= '<option value="'  . $name . ' ' . $apellido . '">' . $name . ' ' . $apellido . '</option>';
                    }
                    $result2->free();
                    $_SESSION['opciones'] = $opciones;
                  }
                  $result->free();
                }
                ?>
              </div>
            </div>

            <!--ALUMNOS-->
            <div class="contenedor">
              <div id="contenedorAlumnos">

              </div>
            </div>
            <div class="row">
              <div class="mb-3 col-6">
                <label for="nombre-c" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" name="fechai" value="2023-01-15" min="2020-01-01"
                  max="2030-12-31" required>
              </div>

            </div>
            <div class="pt-2 d-flex justify-content-center">
              <button type="submit" class="btn btn-picel">Registrar</button>
            </div>
          </form>
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

    include('../main/JS/act-main.php');
    ?>
    <script>

    </script>
  </body>

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