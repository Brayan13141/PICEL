<?php
include("../system/conexion.php");
function antiscript($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
} 
$pasable=0;
$termino=0;
//validar que los campos de tarea estén llenos de acuerdo a la cantidad de estudiantes
for($can = 1; $can<=$_POST['canEstudiantes']; $can++){
  $campo='tarea'.$can;
  if(isset($_POST[$campo]) && !empty($_POST[$campo])){
    $pasable++;
  }else{
    $error2 = base64_encode("No dejes campos vacíos");
    echo "<script>location.href='../main/act-main.php?r=$error2'</script>";
    break;
  }
}
//sentencia para ver si existe una actividad del evento seleccionado
$id_Evento= antiscript($_POST['evento']);
$sql = "SELECT * FROM actividades_asignadas WHERE id_Evento = '$id_Evento'";
$complet = $link->query($sql);
if(mysqli_num_rows($complet)!=0){
  $error2 = base64_encode("Ya hay una actividad para ese evento");
  echo "<script>location.href='../main/act-main.php?r=$error2'</script>";
}
else if($pasable==$_POST['canEstudiantes']){
  //ciclo que insertará la actividad n veces en relación a la cantidad de estudiantes
  for($can = 1; $can<=$_POST['canEstudiantes']; $can++){
    $campoT='tarea'.$can;
    $campoA='alumno'.$can;
    //obtener valores
    $tarea= antiscript($_POST[$campoT]);
    $id_Evento= antiscript($_POST['evento']);
    $id_Estudiante= antiscript($_POST[$campoA]);
    $fechai= antiscript($_POST['fechai']);  
    $fechat= antiscript($_POST['fechat']);
    
    //intertar tarea
    $insert1 = "INSERT INTO tarea(nombre) VALUES('$tarea')";
    if($link->query($insert1)){
      //insertar actividad
      $insert2 = "INSERT INTO actividades_asignadas(id_estudiante, id_evento, id_Tarea, fecha_ini, fecha_fin) 
      VALUES('$id_Estudiante','$id_Evento',last_insert_id(),'$fechai','$fechat')";
      if($link->query($insert2)){
        $termino++;
      }
    }
  }
  //ver si todo se insertó
  if($termino==$_POST['canEstudiantes']){
    $error2 = base64_encode("Se ha hecho el registrado correctamente");
    echo "<script>location.href='../main/act-main.php?r=$error2'</script>";
  }
}
?>