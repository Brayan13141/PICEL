<?php
include("../system/conexion.php");
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
    for ($can = 1; $can <= $_POST['canEstudiantes']; $can++) {
      $campoT = 'tarea' . $can;
      $campoA = 'alumno' . $can;
      //obtener valores
      $tarea = antiscript($_POST[$campoT]);
      $id_Evento = antiscript($_POST['evento']);
      $id_Estudiante = antiscript($_POST[$campoA]);
      $fechai = antiscript($_POST['fechai']);
      $NombreA = antiscript($_POST['NombreA']);

      //intertar tarea
      $insert1 = "INSERT INTO tarea(nombre) VALUES('$tarea')";
      if ($link->query($insert1)) {
        //insertar actividad

        $insert2 = "INSERT INTO actividades_asignadas(id_estudiante, id_evento, id_Tarea, fecha_ini, Nombre) 
            VALUES((SELECT id_Estudiante FROM estudiantes WHERE CONCAT(nombre, ' ', apellidoP)= '$id_Estudiante'), '$id_Evento', last_insert_id(), '$fechai', '$NombreA')";
        if ($link->query($insert2)) {
          $termino++;
        }
      }
    }
    //ver si todo se insertó
    if ($termino == $_POST['canEstudiantes']) {
      // echo "<script>location.href='../main/act-main.php?mensaje=REGISTRO EXITOSO'</script>";
    }
  }
} else {
  echo "<script>location.href='../main/act-main.php?mensaje=COMPLETA TODOS LOS CAMPOS'</script>";
}
