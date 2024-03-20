<?php
include("../system/conexion.php");
function antiscript($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}    
//estudiante administrador
if(isset($_POST['numerocontrol']) && !empty($_POST['numerocontrol']) && 
isset($_POST['nombre']) && !empty($_POST['nombre']) && 
isset($_POST['apellidoP']) && !empty($_POST['apellidoP']) && 
isset($_POST['apellidoM']) && !empty($_POST['apellidoM']) &&
isset($_POST['semestre']) && !empty($_POST['semestre']) &&
isset($_POST['correoinstituto']) && !empty($_POST['correoinstituto']) &&
isset($_POST['carrera']) && !empty($_POST['carrera']) &&
isset($_POST['telefono']) && !empty($_POST['telefono'])&&
isset($_POST['usuario']) && !empty($_POST['usuario']) && 
isset($_POST['password']) && !empty($_POST['password']))
{
    $numerocontrol = antiscript($_POST['numerocontrol']);
    $nombre= antiscript($_POST['nombre']);
    $apellidoP= antiscript($_POST['apellidoP']);
    $apellidoM= antiscript($_POST['apellidoM']);
    $semestre= antiscript($_POST['semestre']);
    $carrera= antiscript($_POST['carrera']);
    $telefono= antiscript($_POST['telefono']);
    $correo= antiscript($_POST['correoinstituto']);
    $usuario= antiscript($_POST['usuario']);
    $password= antiscript($_POST['password']);
    $sql = "SELECT * FROM estudiantes WHERE num_control = '$numerocontrol'";
    $complet = $link->query($sql);
    if(mysqli_num_rows($complet)==0){
        $insert1 = "INSERT INTO usuarios(usuario, contrasena, tipo_us) 
        VALUES ('$usuario','$password','Estudiante')";
        if($link->query($insert1)){
            $insert2 = "INSERT INTO estudiantes(id_User,num_control, nombre,apellidoP,apellidoM, correo, carrera, semestre, num_celular) 
            VALUES ( (SELECT id_User FROM USUARIOS
            ORDER BY id_User DESC
            LIMIT 1 ),'$numerocontrol', '$nombre', '$apellidoP', '$apellidoM', '$correo', '$carrera', '$semestre', '$telefono')";
            if( $link->query($insert2)){
                $error2 = base64_encode("Se ha hecho el registrado correctamente");
                echo "<script>location.href='../main/re-main.php?r=$error2'</script>";
            }
        }
    }else{
        $error2 = base64_encode("Este estudiante ya existe");
            echo "<script>location.href='../main/re-main.php?r=$error2'</script>";
    }
}
else{
    $error2 = base64_encode("No dejes campos vacíos");
    echo "<script>location.href='../main/re-main.php?r=$error2'</script>";
}
?>