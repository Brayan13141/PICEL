<?php
include("conexion.php");
function antiscript($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if (
    isset($_POST['usuario']) && !empty($_POST['usuario']) &&
    isset($_POST['pass']) && !empty($_POST['pass'])
) {

    $keyend = base64_encode("PICEL");
    $usuario = antiscript($_POST['usuario']);
    $pass = antiscript($_POST['pass']);
    $usenc = bin2hex($usuario);

    $sql = "SELECT * FROM docentes d, usuarios u WHERE d.id_user = u.id_user and u.usuario= UNHEX('$usenc')";
    $complet = $link->query($sql);

    $sql2 = "SELECT * FROM estudiantes e, usuarios u WHERE e.id_user = u.id_user and u.usuario= UNHEX('$usenc')";
    $complet2 = $link->query($sql2);
    
    if ($f = $complet->fetch_array()) {
        if ($pass == $f['contrasena']) {
            session_start();
            $_SESSION['id_User'] = $f['id_User'];
            $_SESSION['correo'] = $f['correo'];
            $_SESSION['nombre'] = $f['nombre'];
            $_SESSION['apellidoP'] = $f['apellidoP'];
            $_SESSION['apellidoM'] = $f['apellidoM'];
            $_SESSION['tipo_us'] = $f['tipo_us'];
            header("Location: ../main/");
        } else {
            $error3 = base64_encode("Datos incorrectos");
            echo "<script>location.href='../?error=$error3'</script>";
        }
    } else if ($f = $complet2->fetch_array()) {
        if ($pass == $f['contrasena']) {
            session_start();
            $_SESSION['id_User'] = $f['id_User'];
            $_SESSION['correo'] = $f['correo'];
            $_SESSION['nombre'] = $f['nombre'];
            $_SESSION['apellidoP'] = $f['apellidoP'];
            $_SESSION['apellidoM'] = $f['apellidoM'];
            $_SESSION['tipo_us'] = $f['tipo_us'];
            /*$sql2 = "UPDATE usuarios SET time_log='$time_regis' WHERE usuario='$username'";
            $link->query($sql2);
            $sql3 = "UPDATE usuarios SET ip_log='$ipend' WHERE usuario='$username'";
            $link->query($sql3);
            $sql3 = "UPDATE usuarios SET activo='1' WHERE usuario='$username'";
            $link->query($sql3);*/
            header("Location: ../main/");
        } else {
            $error3 = base64_encode("Datos incorrectos");
            echo "<script>location.href='../?error=$error3'</script>";
        }
    } else {
        $error3 = base64_encode("Datos incorrectos");
        echo "<script>location.href='../?error=$error3'</script>";
    }
} else {
    $error2 = base64_encode("Campos vacios");
    echo "<script>location.href='../?error=$error2'</script>";
}
