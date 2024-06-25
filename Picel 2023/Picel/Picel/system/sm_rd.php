<?php
include("../system/encryptPSA.php");
include("../system/conexion.php");
session_start();

function antiscript($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if(isset($_POST['cancel']) && $_POST['cancel'] = true)
{
    $_SESSION['editar'] ='';
    $_SESSION['IdEditar'] ='';
    header('Location: ../main/rd-main.php');
    exit();
}

if (
    isset($_POST['accion'])  && isset($_POST['id_registro']) &&
    (isset($_POST['accion']) == "eliminar_registro" || isset($_POST['accion']) == "editar_registro")
) {
    try {
        if ($_POST['accion'] == "eliminar_registro") {
            $BORRAR_DOCENTE = "DELETE FROM docentes WHERE id_Docente =" . $_POST['id_registro'];
            if ($link->query($BORRAR_DOCENTE)) {
                echo json_encode(array("success" => true));
            } else {
                echo ('ERROR AL BORRAR DOCENTE');
            }
        } else {
            $RESULTADO_DOCENTE = "SELECT * FROM docentes  
                      JOIN usuarios ON docentes.id_User = usuarios.id_User 
                      WHERE docentes.id_Docente =" . $_POST['id_registro'];
            if ($resultado = $link->query($RESULTADO_DOCENTE)) {
                $docente = $resultado->fetch_assoc();
                $_SESSION['editar'] = true;
                $_SESSION['idEditar'] =  $_POST['id_registro']; 
                echo json_encode(array("success" => true, "DOCENTE" => $docente));
            } else {
                echo ('ERROR AL ACTUALIZAR');
            }
        }
    } catch (Exception $e) {
        // Manejo de excepciones
        echo "Error: " . $e->getMessage();
    }
}

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
        if ($tipo == "1") {
            $insert1 = "INSERT INTO usuarios(usuario, contrasena, tipo_us) 
            VALUES ('$usuario','$password','Docente')";
            if ($link->query($insert1)) {
                $insert2 = "INSERT INTO docentes(id_User,nombre, apellidoP, apellidoM, correo, carrera, num_celular) 
                VALUES ( (SELECT id_User FROM USUARIOS
                ORDER BY id_User DESC LIMIT 1 ),
                '$nombre','$apellidoP','$apellidoM','$correo','$carrera','$num_celular')";
                if ($link->query($insert2)) {
                    header('Location: ../main/rd-main.php?mensaje=Se ha registrado correctamente');
                    exit();
                } else {
                    header('Location: ../main/rd-main.php?mensaje= Registro incorrecto');
                    exit();
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
                    header('Location: ../main/rd-main.php?mensaje=Se ha registrado correctamente');
                    exit();
                }
            }
        }
    } else if (isset($_SESSION['editar']) &&  $_SESSION['editar'] = true && isset( $_SESSION['idEditar'])) {
        try{
            $update = "UPDATE docentes SET nombre = '$nombre', apellidoP = '$apellidoP', apellidoM = '$apellidoM', carrera = '$carrera', correo = '$correo', num_celular = '$num_celular' WHERE id_Docente = " . $_SESSION['idEditar'];
            if ($link->query($update)) {
                $_SESSION['editar'] ='';
                $_SESSION['IdEditar'] ='';
                header('Location: ../main/rd-main.php?mensaje=Se ha actualizado correctamente');
                exit();
            } else {
                header('Location: ../main/rd-main.php?mensaje=Error al actualizar el docente');
                exit();
            }
        }catch(Exception $e)
        {
            echo "Error: " . $e->getMessage();
        }
   
    } else {
        header('Location: ../main/rd-main.php?mensaje=Este docente ya existe'.$_SESSION['editar']);
        exit();
    }
} else {
    //LOS MENSAJES SE GUARDAN EN LA VARIABLE DE SESION, SE RECARGA LA PAGINA. SI HAY MENSAJE SE MUESTRA
    //VALIDACION PARA QUE NO INTERVENGA CON LA FUNCIONALIDAD DE EDITAR Y ELIMINAR
    if (
        isset($_POST['accion'])  && isset($_POST['id_registro']) &&
        (isset($_POST['accion']) == "eliminar_registro" || isset($_POST['accion']) == "editar_registro")
    ) {
        //NO SE REALIZA NADA
    } else {
        header('Location: ../main/rd-main.php?mensaje=No dejes campos vacíos');
        exit();
    }
}
