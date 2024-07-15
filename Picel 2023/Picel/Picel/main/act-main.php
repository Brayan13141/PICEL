<?php
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
        <div class="col"><button type="button" onclick="load(0);" class="mx-auto d-flex justify-content-center btn btn-success">Ver Registros</button></div>
        <?php
        if ($_SESSION['tipo_us'] == "Admin") {
        ?>
          <div class="col"><button type="button" onclick="load(1);" class="mx-auto d-flex justify-content-center btn btn-success">Definir Actividad</button></div>
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
                  <td><p>Evento</p></td> 
                  <td><p>No. Estudiantes</p></td> 
                  <td><p>Docente</p></td> 
                  <td><p>Estatus</p></td> 
                  <td><p>Detalles</p></td>  
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
                            <td>' . $row_d0[0] . '</td> 
                            <td>' . $row_d0[1] . '</td>  
                            <td>' . $row_d0[2] . '</td> 
                            <td>0%</td>
                            <td><button type="button" id="btnMonitoreo' . $id_Evento . '" value="' . $id_Evento . '" class="mx-auto btn btn-success">+</button></td>
                        </tr>';
            }
            echo "</table>";
          }
          ?>
        </div>
      </div>

      <div class="row" style="display:none;visibility:hidden;" id="definir">
        <div class="col-12 pt-2 text-center">
          <h3>Definición de Actividades</h3>
        </div>
        <div class="grid mx-auto col-md-8" style="--bs-columns: 2;">
          <form method="POST" action="../system/sm-activity.php?caso=1">
            <div class="row">
              <div class="mb-3 col-6">
                <label for="evento-c" class="form-label">Evento</label>
                <?php
                $query2 = "SELECT * FROM evento";
                echo '<select name="evento" id="cmbEvento" class="form-control">';
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
                <select id="noAlumnos" name="canEstudiantes" class="form-control">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
              </div>
            </div>
            <!--Alumno 1-->
            <div class="row" id="campoAlumno1">
              <div class="mb-3 col-6">
                <label for="estudiante-c" class="form-label">Estudiante</label>
                <?php
                $query2 = "SELECT * FROM estudiantes";
                echo '<select name="alumno1" id="cmbAlumno1" class="form-control">';
                if ($result = $link->query($query2)) {
                  while ($row = $result->fetch_assoc()) {
                    $name = $row["nombre"];
                    $id = $row["id_Estudiante"];
                    echo '<option value="' . $id . '">' . $name . '</option>';
                  }
                  $result->free();
                }
                echo ' </select>';
                ?>
              </div>
              <div class="mb-3 col-6">
                <label for="nombre-c" class="form-label">Tarea</label>
                <input type="text" id="txtTarea1" class="form-control" name="tarea1">
              </div>
            </div>
            <!--Alumno 2-->
            <div class="row" id="campoAlumno2" style="display:none;visibility:hidden;">
              <div class="mb-3 col-6">
                <label for="estudiante-c" class="form-label">Estudiante</label>
                <?php
                $query2 = "SELECT * FROM estudiantes";
                echo '<select name="alumno2" id="cmbAlumno2" class="form-control">';
                if ($result = $link->query($query2)) {
                  while ($row = $result->fetch_assoc()) {
                    $name = $row["nombre"];
                    $id = $row["id_Estudiante"];
                    echo '<option value="' . $id . '">' . $name . '</option>';
                  }
                  $result->free();
                }
                echo ' </select>';
                ?>
              </div>
              <div class="mb-3 col-6">
                <label for="nombre-c" class="form-label">Tarea</label>
                <input type="text" id="txtTarea2" class="form-control" name="tarea2">
              </div>
            </div>
            <!--Alumno 3-->
            <div class="row" id="campoAlumno3" style="display:none;visibility:hidden;">
              <div class="mb-3 col-6">
                <label for="estudiante-c" class="form-label">Estudiante</label>
                <?php
                $query2 = "SELECT * FROM estudiantes";
                echo '<select name="alumno3" id="cmbAlumno3" class="form-control">';
                if ($result = $link->query($query2)) {
                  while ($row = $result->fetch_assoc()) {
                    $name = $row["nombre"];
                    $id = $row["id_Estudiante"];
                    echo '<option value="' . $id . '">' . $name . '</option>';
                  }
                  $result->free();
                }
                echo ' </select>';
                ?>
              </div>
              <div class="mb-3 col-6">
                <label for="nombre-c" class="form-label">Tarea</label>
                <input type="text" id="txtTarea3" class="form-control" name="tarea3">
              </div>
            </div>
            <div class="row">
              <div class="mb-3 col-6">
                <label for="nombre-c" class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" name="fechai" value="2023-01-15" min="2020-01-01" max="2030-12-31">
              </div>
              <div class="mb-3 col-6">
                <label for="nombre-c" class="form-label">Fecha Término</label>
                <input type="date" class="form-control" name="fechat" value="2023-02-28" min="2020-01-01" max="2030-12-31">
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
    ?>
    <script>
      function load(id) {
        if (id == 0) {
          document.getElementById("registros").style = "display:block;visibility:visible;";
          document.getElementById("definir").style = "display:none;visibility:hidden;";
        } else {
          document.getElementById("definir").style = "display:block;visibility:visible;";
          document.getElementById("registros").style = "display:none;visibility:hidden;";
        }
      }
      let limite = 1,
        original = 1,
        eliminar = 0;
      document.getElementById("noAlumnos").addEventListener('change',
        () => {
          limite = document.getElementById("noAlumnos").value;
          document.getElementById("txtTarea1").value = "";
          document.getElementById("txtTarea2").value = "";
          document.getElementById("txtTarea3").value = "";
          if (limite == 3) {
            original = 3;
            eliminar = 3;
            document.getElementById("campoAlumno2").style = "visibility=visible;";
            document.getElementById("campoAlumno3").style = "visibility=visible;";
          } else if (limite == 2) {
            original = 2;
            eliminar = 2;
            document.getElementById("campoAlumno2").style = "visibility=visible;";
            document.getElementById("campoAlumno3").style = "display:none;visibility=hidden;";
          } else {
            original = 1;
            eliminar = 1;
            document.getElementById("campoAlumno2").style = "display:none;visibility=hidden;";
            document.getElementById("campoAlumno3").style = "display:none;visibility=hidden;";
          }
        });

      //Obtener el botón que se pulsó
      let tabla = document.getElementById("tabReg");
      let botones = tabla.getElementsByTagName("button");
      for (var i = 0; i < botones.length; i++) {
        botones[i].addEventListener("click", function() {
          var valueBoton = this.value;
          //alert("Se hizo clic en el botón con value: " + valueBoton);
          window.location.href = 'mact-main.php?act=' + encodeURIComponent(valueBoton);
        });
      }
    </script>
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
      location.href = "../";
    </script>
  </body>
<?php
}
?>