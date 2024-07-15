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
    //----------------------------------CANCELAR-ACTUALIZACION--------------------------------
    if (isset($_POST['cancel']) && $_POST['cancel'] = true) {
        $_SESSION['editar'] = '';
        $_SESSION['IdEditar'] = '';
        header('Location: ../main/rd-main.php');
        exit();
    }

    //----------------------------------ACTUALIZAR-ELIMINAR--------------------------------
    if (
        isset($_POST['accion'])  && isset($_POST['id_registro']) &&
        (isset($_POST['accion']) == "eliminar_registro" || isset($_POST['accion']) == "editar_registro")
    ) {
        try {

            if ($_POST['accion'] == "eliminar_registro") {
                $id_registro = antiscript($_POST['id_registro']);
                $BORRAR_ALUMNO = "DELETE FROM estudiantes WHERE id_estudiante=" . $id_registro;
                if ($link->query($BORRAR_ALUMNO)) {
                    echo json_encode(array("success" => true));
                } else {
                    header('Location: ../main/re-main.php?mensaje=ERROR AL BORRAR EL ESTUDIANTE');
                }
            } else {
                $id_registro = antiscript($_POST['id_registro']);
                $RESULTADO_ALUMNO = "SELECT * FROM estudiantes   
                JOIN usuarios ON estudiantes.id_User = usuarios.id_User 
                WHERE estudiantes.id_Estudiante =" . $id_registro;
                if ($resultado = $link->query($RESULTADO_ALUMNO)) {
                    $ALUMNO = $resultado->fetch_assoc();
                    $_SESSION['editar'] = true;
                    $_SESSION['idEditar'] =  $id_registro;
                    echo json_encode(array("success" => true, "ESTUDIANTE" => $ALUMNO));
                } else {
                    header('Location: ../main/re-main.php?mensaje=ERROR AL ACTUALIZAR');
                }
            }
        } catch (Exception $e) {
            // Manejo de excepciones
            echo "Error: " . $e->getMessage();
        }
    }

    //----------------------------------INSERTAR-ACTUALIZAR--------------------------------
    if (
        isset($_POST['numerocontrol']) && !empty($_POST['numerocontrol']) &&
        isset($_POST['nombre']) && !empty($_POST['nombre']) &&
        isset($_POST['apellidoP']) && !empty($_POST['apellidoP']) &&
        isset($_POST['apellidoM']) && !empty($_POST['apellidoM']) &&
        isset($_POST['semestre']) && !empty($_POST['semestre']) &&
        isset($_POST['correoinstituto']) && !empty($_POST['correoinstituto']) &&
        isset($_POST['carrera']) && !empty($_POST['carrera']) &&
        isset($_POST['telefono']) && !empty($_POST['telefono']) &&
        isset($_POST['usuario']) && !empty($_POST['usuario']) &&
        isset($_POST['password']) && !empty($_POST['password'])
    ) {
        try {
            $numerocontrol = antiscript($_POST['numerocontrol']);
            $nombre = antiscript($_POST['nombre']);
            $apellidoP = antiscript($_POST['apellidoP']);
            $apellidoM = antiscript($_POST['apellidoM']);
            $semestre = antiscript($_POST['semestre']);
            $carrera = antiscript($_POST['carrera']);
            $telefono = antiscript($_POST['telefono']);
            $correo = antiscript($_POST['correoinstituto']);
            $usuario = antiscript($_POST['usuario']);
            $password = antiscript($_POST['password']);
            
            $sql = "SELECT * FROM estudiantes WHERE num_control = '$numerocontrol'";
            $complet = $link->query($sql);

            $row = $complet->fetch_assoc();
            $id_User = $row['id_User'];


            if (mysqli_num_rows($complet) == 0 && is_null(isset($_SESSION['editar'])) && is_null(isset($_SESSION['idEditar']))) {
                $insert1 = "INSERT INTO usuarios(usuario, contrasena, tipo_us) 
            VALUES ('$usuario','$password','Estudiante')";
                if ($link->query($insert1)) {
                    $insert2 = "INSERT INTO estudiantes(id_User,num_control, nombre,apellidoP,apellidoM, correo, carrera, semestre, num_celular) 
                VALUES ( (SELECT id_User FROM USUARIOS
                ORDER BY id_User DESC
                LIMIT 1 ),'$numerocontrol', '$nombre', '$apellidoP', '$apellidoM', '$correo', '$carrera', '$semestre', '$telefono')";
                    if ($link->query($insert2)) {
                        header('Location: ../main/re-main.php?mensaje=ESTUDIANTE REGISTRADO');
                    } else {
                        header('Location: ../main/re-main.php?mensaje=ERROR AL REGISTRAR');
                    }
                }
            } else if (isset($_SESSION['editar']) &&  $_SESSION['editar'] = true && isset($_SESSION['idEditar'])) {
                try {
                    $update = "UPDATE estudiantes SET num_control = '$numerocontrol', nombre = '$nombre', apellidoP = '$apellidoP', apellidoM = '$apellidoM', correo = '$correo', carrera = '$carrera', semestre = '$semestre', num_celular = '$telefono' WHERE id_Estudiante = " .  $_SESSION['idEditar'];

                    $updateUser = "UPDATE usuarios SET usuario = '$usuario', contrasena = '$password' WHERE id_User = " . $id_User;

                    if ($link->query($update)) {
                        if ($link->query($updateUser)) {
                            $_SESSION['editar'] = '';
                            $_SESSION['idEditar'] = '';
                            header('Location: ../main/re-main.php?mensaje=SE HA ACTUALIZADO CORRECTAMENTE EL ESTUDIANTE');
                            exit();
                        } else {
                            header('Location: ../main/re-main.php?mensaje=ERROR AL ACTUALIZAR EL ESTUDIANTE(USUARIO U CONTRASEÑA)');
                        }
                    } else {
                        header('Location: ../main/re-main.php?mensaje=ERROR AL ACTUALIZAR EL ESTUDIANTE(ALUMNOS)');
                        exit();
                    }
                } catch (Exception $e) {
                    header('Location: ../main/re-main.php?mensaje=ERROR AL ACTUALIZAR EL ESTUDIANTE(ERROR)');
                }
            } else {
                header('Location: ../main/re-main.php?mensaje=ESTE ESTUDIANTE YA EXISTE');

                exit();
            }
        } catch (Exception $e) {
            header('Location: ../main/re-main.php?mensaje=ERROR: ' . $e->getMessage());
        }
    } else {
        if (
            isset($_POST['accion'])  && isset($_POST['id_registro']) &&
            (isset($_POST['accion']) == "eliminar_registro" || isset($_POST['accion']) == "editar_registro")
        ) {
            //NO SE REALIZA NADA
        } else {
            header('Location: ../main/re-main.php?mensaje=NO DEJES CAMPOS VACIOS');
            exit();
        }
    }
