<?php
include("../system/conexion.php");
if (session_status() == PHP_SESSION_ACTIVE) {
} else {
  session_start();
}

if (isset($_SESSION['id_User']) && $_SESSION['tipo_us'] == "Admin" || $_SESSION['tipo_us'] == "Docente") {
  function antiscript($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
  if (isset($_POST['evento']) && !empty($_POST['evento'] && isset($_POST['NombreA']) && !empty($_POST['NombreA']) && isset($_POST['canEstudiantes']))) {
    //variables de control
    $pasable = 0;
    $termino = 0;
    //validar que los campos de tarea estén llenos de acuerdo a la cantidad de estudiantes
    for ($can = 1; $can <= $_POST['canEstudiantes']; $can++) {
      $campo = 'tarea' . $can;
      if (isset($_POST[$campo]) && !empty($_POST[$campo]) && isset($_POST['alumno' . $can]) && !empty($_POST['alumno' . $can])) {
        $pasable++;
      } else {
        echo "<script>location.href='../main/act-main.php?mensaje=COMPLETA TODOS LOS ESTUDIANTES'</script>";
        break;
      }
    }
    if ($pasable == $_POST['canEstudiantes']) {
      //ciclo que insertará la actividad n veces en relación a la cantidad de estudiantes
      $fechai = antiscript($_POST['fechai']);
      $NombreA = antiscript($_POST['NombreA']);
      $id_Evento = antiscript($_POST['evento']);

      //CREAMOS EL INSERT DE LAS ACTIVIDAES
      $insert2 = "INSERT INTO actividades_asignadas(id_evento, fecha_ini, Nombre, id_Docente)
      VALUES( 
          '$id_Evento',
          '$fechai',
          '$NombreA',
          (SELECT id_Docente FROM docentes WHERE id_User = " . $_SESSION['id_User'] . ")
      )";
      //OBTENEMOS LA ULTIMA ACTIVIDAD INSERTADA

      //insertar actividad
      if ($link->query($insert2)) {

        $sele = "SELECT LAST_INSERT_ID() as id FROM actividades_asignadas";
        if ($result = $link->query($sele)) {
          $row = $result->fetch_assoc();
          $id_Act = $row["id"];

          for ($can = 1; $can <= $_POST['canEstudiantes']; $can++) {
            $campoT = 'tarea' . $can;
            $campoA = 'alumno' . $can;
            //OBTENEMOS LOS VALORES DE LOS ALUMNOS Y DE LAS TAREAS
            $tarea = antiscript($_POST[$campoT]);
            $ALUMNO = antiscript($_POST[$campoA]);

            try {

              $insert1 = "INSERT INTO tarea(nombre,descripcion,id_Actividad,id_Estudiante,Estatus) VALUES('$NombreA','$tarea','$id_Act','$ALUMNO',false)";

              if ($link->query($insert1)) {
                $termino++;
              } else {
                echo "<script>location.href='../main/act-main.php?mensaje=ERROR AL REGISTRAR ALUMNO'</script>";
              }
              $result->free();
            } catch (\Throwable $th) {
              echo "<script>location.href='../main/act-main.php?mensaje=ERROR AL REGISTRAR'" . $th . "</script>";
            }
          }
        }
      } else {
        echo "<script>location.href='../main/act-main.php?mensaje=ERROR AL REGISTRAR LA ACTIVIDAD'</script>";
      }

      //ver si todo se insertó
      if ($termino == $_POST['canEstudiantes']) {
        echo "<script>location.href='../main/act-main.php?mensaje=REGISTRO EXITOSO'</script>";
      } else {
        echo "<script>location.href='../main/act-main.php?mensaje=NO SE COMPLETARON LOS REGISTROS'</script>";
      }
    } else {
      echo "<script>location.href='../main/act-main.php?mensaje=COMPLETA TODOS LOS CAMPOS'</script>";
    }
  }

  if (
    isset($_POST['accion'])  && isset($_POST['id_registro']) &&
    (isset($_POST['accion']) == "eliminar_registro")
  ) {
    try {
      $id_registro = antiscript($_POST['id_registro']);
      $BORRAR_ACT = "DELETE FROM actividades_asignadas WHERE id_Actividades=" . $id_registro;
      if ($link->query($BORRAR_ACT)) {
        echo json_encode(array("success" => true));
      } else {
        header('Location: ../main/act-main.php?mensaje=ERROR AL BORRAR EL ESTUDIANTE');
      }
    } catch (Exception $e) {
      // Manejo de excepciones
      echo "Error: " . $e->getMessage();
    }
  }
}
