<?php

/** 
 * 
 * Este archivo se encarga de gestionar las actividades asignadas a los estudiantes.
 * 
 * Archivos requeridos:
 * - ../system/conexion.php: Archivo que contiene la conexión a la base de datos.
 * 
 * Variables principales:
 * - $_SESSION['id_User']: ID del usuario que ha iniciado sesión.
 * - $_SESSION['tipo_us']: Tipo de usuario (Admin o Docente).
 * - $_POST['evento']: ID del evento.
 * - $_POST['NombreA']: Nombre de la actividad.
 * - $_POST['canEstudiantes']: Cantidad de estudiantes.
 * 
 * Funciones principales:
 * - antiscript($data): Función que limpia los datos de entrada para evitar inyecciones de scripts.
 * - Validación de campos: Se valida que los campos de tarea y alumno estén completos.
 * - Inserción de actividades: Se insertan las actividades en la base de datos.
 * - Inserción de tareas: Se insertan las tareas correspondientes a cada estudiante.
 * - Eliminación de registros: Se eliminan registros de actividades asignadas.
 * 
 * Notas:
 * - Se utilizan múltiples redirecciones con mensajes de error o éxito.
 * - Se maneja la excepción en caso de error al registrar las tareas.
 */
include("../system/conexion.php");
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
} else {
  if (isset($_SESSION['id_User']) && ($_SESSION['tipo_us'] == "Admin" || $_SESSION['tipo_us'] == "Docente")) {
    function antiscript($data)
    {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    if (isset($_POST['evento']) && !empty($_POST['evento']) && isset($_POST['NombreA']) && !empty($_POST['NombreA']) && isset($_POST['canEstudiantes'])) {
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

                $insert1 = "INSERT INTO tarea(nombre,descripcion,id_Actividad,id_Estudiante,Estatus) VALUES('$NombreA','$tarea','$id_Act','$ALUMNO',false,'')";

                if ($link->query($insert1)) {
                  $termino++;
                  echo "<script>location.href='../main/act-main.php?mensaje=SE REGISTRO LA TAREA AL ALUMNO</script>";
                } else {
                  echo "<script>location.href='../main/act-main.php?mensaje=ERROR AL REGISTRAR LA TAREA </script>";
                }
              } catch (\Throwable $th) {
                echo "<script>location.href='../main/act-main.php?mensaje=ERROR AL REGISTRAR LAS TAREAS'" . $th . "</script>";
              }
            }
            $result->free();
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
}
