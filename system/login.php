<?php

/**
 * Este archivo maneja el proceso de inicio de sesión para usuarios docentes y estudiantes.
 * 
 * Variables principales:
 * - $_POST['usuario']: Nombre de usuario ingresado por el usuario.
 * - $_POST['pass']: Contraseña ingresada por el usuario.
 * - $keyend: Clave codificada en base64 utilizada en el proceso.
 * - $usuario: Nombre de usuario después de aplicar la función antiscript.
 * - $pass: Contraseña después de aplicar la función antiscript.
 * - $usenc: Nombre de usuario codificado en hexadecimal.
 * - $sql: Consulta SQL para verificar los datos del usuario docente.
 * - $sql2: Consulta SQL para verificar los datos del usuario estudiante.
 * - $complet: Resultado de la consulta SQL para docentes.
 * - $complet2: Resultado de la consulta SQL para estudiantes.
 * - $error3: Mensaje de error codificado en base64 para datos incorrectos.
 * - $error2: Mensaje de error codificado en base64 para campos vacíos.
 * 
 * Funciones principales:
 * - antiscript($data): Función que limpia los datos de entrada para prevenir ataques XSS.
 * 
 * Flujo principal:
 * 1. Verifica si los campos 'usuario' y 'pass' están establecidos y no están vacíos.
 * 2. Limpia los datos de entrada utilizando la función antiscript.
 * 3. Codifica el nombre de usuario en hexadecimal.
 * 4. Ejecuta consultas SQL para verificar si el usuario es un docente o un estudiante.
 * 5. Si las credenciales son correctas, inicia una sesión y redirige al usuario a la página principal.
 * 6. Si las credenciales son incorrectas, redirige al usuario a la página de inicio con un mensaje de error.
 * 7. Si los campos están vacíos, redirige al usuario a la página de inicio con un mensaje de error.
 */
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
            $_SESSION['num_control'] = $f['num_control'];

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
