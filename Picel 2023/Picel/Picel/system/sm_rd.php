<?php 
/*
 * It handles various operations related to docentes (teachers) in the system.
 * 
 * Functions:
 * - antiscript($data): A helper function that sanitizes the input data by removing leading/trailing spaces, backslashes, and converting special characters to HTML entities.
 * 
 * Actions:
 * - Cancel: If the 'cancel' POST parameter is set to true, it clears the session variables 'editar' and 'IdEditar' and redirects to ../main/rd-main.php.
 * - Eliminar Registro: If the 'accion' and 'id_registro' POST parameters are set and 'accion' is equal to "eliminar_registro", it deletes the docente record with the specified 'id_registro' from the 'docentes' table.
 * - Editar Registro: If the 'accion' and 'id_registro' POST parameters are set and 'accion' is equal to "editar_registro", it retrieves the docente record with the specified 'id_registro' from the 'docentes' table and stores it in the session variables 'editar' and 'idEditar'.
 * - Registrar Docente: If all the required POST parameters (nombre, apellidoP, apellidoM, carrera, correo, telefono, usuario, tipo, password) are set and not empty, it inserts a new docente record into the 'docentes' table along with the corresponding user record in the 'usuarios' table.
 * - Actualizar Docente: If the session variables 'editar' and 'idEditar' are set, it updates the docente record with the specified 'idEditar' in the 'docentes' table.
 * 
 * Redirects:
 * - If an error occurs during the database operations, it redirects to ../main/rd-main.php with an appropriate error message.
 * - If the registration or update is successful, it redirects to ../main/rd-main.php with a success message.
 * - If there are empty fields in the registration form, it redirects to ../main/rd-main.php with a message indicating that fields should not be left empty.
 * - If a docente with the same correo already exists and it's not an edit operation, it redirects to ../main/rd-main.php with a message indicating that the docente already exists.
 * 
 * Note: This code assumes the existence of a database connection object named $link, which is used to perform database operations.
 */
?>

<?php 
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
    isset($_POST['accion']) && isset($_POST['id_registro']) &&
    (isset($_POST['accion']) == "eliminar_registro" || isset($_POST['accion']) == "editar_registro")
) {
    try {
        if ($_POST['accion'] == "eliminar_registro") {
            $id_registro = antiscript($_POST['id_registro']);
            $BORRAR_DOCENTE = "DELETE FROM docentes WHERE id_Docente =" . $id_registro;
            if ($link->query($BORRAR_DOCENTE)) {
                echo json_encode(array("success" => true));
            } else {
                header('Location: ../main/rd-main.php?mensaje=ERROR AL BORRAR EL DOCENTE');
                
            }
        } else {
            $id_registro = antiscript($_POST['id_registro']);
            $RESULTADO_DOCENTE = "SELECT * FROM docentes  
                      JOIN usuarios ON docentes.id_User = usuarios.id_User 
                      WHERE docentes.id_Docente =" . $id_registro;
            if ($resultado = $link->query($RESULTADO_DOCENTE)) {
                $docente = $resultado->fetch_assoc();
                $_SESSION['editar'] = true;
                $_SESSION['idEditar'] = $id_registro;
                echo json_encode(array("success" => true, "DOCENTE" => $docente));
            } else {
                header('Location: ../main/rd-main.php?mensaje=ERROR AL ACTUALIZAR');
                
            }
        }
    } catch (Exception $e) {

        header('Location: ../main/rd-main.php?mensaje='.$e->getMessage());
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
                    header('Location: ../main/rd-main.php?mensaje= REGISTRO INCORRECTO');
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
                    header('Location: ../main/rd-main.php?mensaje=SE HA REGISTRADO CORRECTAMENTE');
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
                header('Location: ../main/rd-main.php?mensaje=SE HA ACTUALIZADO CORRECTAMENTE');
                exit();
            } else {
                header('Location: ../main/rd-main.php?mensaje=ERROR AL ACTUALIZAR EL DOCENTE ');
                exit();
            }
        }catch(Exception $e)
        {
            header('Location: ../main/rd-main.php?mensaje='.$e->getMessage()  );
        }
   
    } else {
        header('Location: ../main/rd-main.php?mensaje=ESTE DOCENTE YA EXISTE'.$_SESSION['editar']);
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
