<?php
include("../system/encryptPSA.php");
include("../system/conexion.php");
function antiscript($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if(isset($_POST['accion']))
{
    try {
        //echo($_POST['accion'].' '.$_POST['id_registro']);
        $BORRAR_DOCENTE = "DELETE FROM docentes WHERE id_Docente =".$_POST['id_registro'];

        if($link->query($BORRAR_DOCENTE)) {
            echo json_encode(array("success" => true));
        } else {
            echo('ERRO AL BORRAR DOCENTE');
        }
} catch (Exception $e) {
    // Manejo de excepciones
    echo "Error: " . $e->getMessage();
} 
}


//docente adminsitrador
if (
    isset($_POST['nombre']) && !empty($_POST['nombre']) &&
    isset($_POST['apellidoP']) && !empty($_POST['apellidoP']) &&
    isset($_POST['apellidoM']) && !empty($_POST['apellidoM']) &&
    isset($_POST['carrera']) && !empty($_POST['carrera']) &&
    isset($_POST['correo']) && !empty($_POST['correo']) &&
    isset($_POST['telefono']) && !empty($_POST['telefono']) &&
    isset($_POST['usuario']) && !empty($_POST['usuario']) &&
    isset($_POST['tipo']) && !empty($_POST['tipo']) &&
    isset($_POST['password']) && !empty($_POST['password'])
) {
    $nombre = antiscript($_POST['nombre']);
    $apellidoP = antiscript($_POST['apellidoP']);
    $apellidoM = antiscript($_POST['apellidoM']);
    $carrera = antiscript($_POST['carrera']);
    $correo = antiscript($_POST['correo']);
    $num_celular = antiscript($_POST['telefono']);
    $usuario = antiscript($_POST['usuario']);
    $tipo = antiscript($_POST['tipo']);
    $password = antiscript($_POST['password']);
    $sql = "SELECT * FROM docentes WHERE correo = '$correo'";
    $complet = $link->query($sql);
    if (mysqli_num_rows($complet) == 0) {
        var_dump($_POST['tipo']);
        var_dump($_POST['nombre']);
        if ($tipo == "1") {
            $insert1 = "INSERT INTO usuarios(usuario, contrasena, tipo_us) 
            VALUES ('$usuario','$password','Docente')";
            if ($link->query($insert1)) {
                $insert2 = "INSERT INTO docentes(id_User,nombre, apellidoP, apellidoM, correo, carrera, num_celular) 
                VALUES ( (SELECT id_User FROM USUARIOS
                ORDER BY id_User DESC LIMIT 1 ),
                '$nombre','$apellidoP','$apellidoM','$correo','$carrera','$num_celular')";
                //var_dump($insert2);
                var_dump($link->query($insert2));
                if ($link->query($insert2)) {
                    var_dump("ENTRO");
                    $error2 = base64_encode("Se ha hecho el registrado correctamente");
                    echo "<script>location.href='../main/rd-main.php?r=$error2'</script>";
                } else {
                    var_dump("NO ENTRO");
                }
            }
        } else {
            $insert1 = "INSERT INTO usuarios(usuario, contrasena, tipo_us) 
            VALUES ('$usuario','$password','Admin')";
            if ($link->query($insert1)) {
                $insert2 = "INSERT INTO docentes(id_User,nombre, apellidoP, apellidoM, correo, carrera, num_celular) 
                VALUES ( (SELECT id_User FROM USUARIOS
                ORDER BY id_User DESC
                LIMIT 1 ),'$nombre','$apellidoP','$apellidoM','$correo','$carrera','$num_celular')";
                if ($link->query($insert2)) {
                    $error2 = base64_encode("Se ha registrado correctamente");
                    echo "<script>location.href='../main/rd-main.php?r=$error2'</script>";
                }
            }
        }
    } else {
        $error2 = base64_encode("Este docente ya existe");
        echo "<script>location.href='../main/rd-main.php?r=$error2'</script>";
    }
} else {
    $error2 = base64_encode("No dejes campos vacíos");
    echo "<script>location.href='../main/rd-main.php?r=$error2'</script>";
}
