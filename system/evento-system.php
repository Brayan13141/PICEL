<?php

/**
 * Archivo: /c:/wamp64/www/PICEL-master/PICEL-master/system/evento-system.php
 *
 * Este archivo maneja las operaciones CRUD (Crear, Leer, Actualizar, Eliminar) para los eventos en el sistema.
 *
 * Variables principales:
 * - $link: Conexión a la base de datos.
 * - $_SESSION['editar']: Indica si se está en modo de edición.
 * - $_SESSION['idEditar']: Almacena el ID del evento que se está editando.
 * - $_SESSION['id_User']: ID del usuario actual.
 *
 * Funciones principales:
 * - antiscript($data): Limpia los datos de entrada para prevenir ataques XSS.
 *
 * Operaciones principales:
 * - Cancelar actualización: Si se recibe una solicitud de cancelación, se restablecen las variables de sesión y se redirige a la página principal de eventos.
 * - Actualizar o eliminar: Dependiendo de la acción recibida (eliminar_registro o editar_registro), se elimina un evento o se prepara para editarlo.
 * - Insertar o actualizar: Si se reciben los datos necesarios (nombre, periodo, descripcion), se inserta un nuevo evento o se actualiza uno existente.
 *
 * Manejo de errores:
 * - Se utilizan bloques try-catch para manejar excepciones y redirigir con mensajes de error en caso de fallos.
 */
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
if (isset($_POST['cancel']) && $_POST['cancel'] == true) {
    $_SESSION['editar'] = '';
    $_SESSION['idEditar'] = '';
    header('Location: ../main/evento-main.php');
    exit();
}

//----------------------------------ACTUALIZAR-ELIMINAR--------------------------------
if (
    isset($_POST['accion']) && isset($_POST['id_registro']) &&
    ($_POST['accion'] == "eliminar_registro" || $_POST['accion'] == "editar_registro")
) {
    try {
        if ($_POST['accion'] == "eliminar_registro") {
            $id_registro = antiscript($_POST['id_registro']);
            $BORRAR_EVENTO = "DELETE FROM evento WHERE id_evento=" . $id_registro;
            if ($link->query($BORRAR_EVENTO)) {
                echo json_encode(array("success" => true));
            } else {
                header('Location: ../main/evento-main.php?mensaje=ERROR AL BORRAR EL EVENTO');
            }
        } else {
            $id_registro = antiscript($_POST['id_registro']);
            $RESULTADO_EVENTO = "SELECT * FROM evento WHERE id_evento =" . $id_registro;
            if ($resultado = $link->query($RESULTADO_EVENTO)) {
                $EVENTO = $resultado->fetch_assoc();
                $_SESSION['editar'] = true;
                $_SESSION['idEditar'] =  $id_registro;
                echo json_encode(array("success" => true, "EVENTO" => $EVENTO));
            } else {
                header('Location: ../main/evento-main.php?mensaje=ERROR AL ACTUALIZAR');
            }
        }
    } catch (Exception $e) {
        // Manejo de excepciones
        echo "Error: " . $e->getMessage();
    }
}
//----------------------------------INSERTAR-ACTUALIZAR--------------------------------
if (
    isset($_POST['nombre']) && !empty($_POST['nombre']) &&
    isset($_POST['periodo']) && !empty($_POST['periodo']) &&
    isset($_POST['descripcion']) && !empty($_POST['descripcion']) &&
    isset($_POST['id_Docente']) && !empty($_POST['id_Docente']) // Nueva validación
) {

    try {
        $nombre = antiscript($_POST['nombre']);
        $periodo = antiscript($_POST['periodo']);
        $descripcion = antiscript($_POST['descripcion']);
        $id_Doc = antiscript($_POST['id_Docente']); // Obtenemos el ID del formulario

        // Validación adicional
        if (!is_numeric($id_Doc)) {
            header('Location: ../main/evento-main.php?mensaje=SELECCIONE UN DOCENTE VÁLIDO');
            exit();
        }

        $sql = "SELECT * FROM evento WHERE nombre = '$nombre' AND periodo = '$periodo'";
        $complet = $link->query($sql);
        if (mysqli_num_rows($complet) == 0 && isset($_SESSION['editar'])) {
            $insert = "INSERT INTO evento(id_Docente, nombre, periodo, descripcion) 
                      VALUES ('$id_Doc', '$nombre', '$periodo', '$descripcion')";
            if ($link->query($insert)) {

                header('Location: ../main/evento-main.php?mensaje=EVENTO REGISTRADO');
            } else {
                header('Location:../main/evento-main.php?mensaje=ERROR AL REGISTRAR EL EVENTO');
            }
        } else if (isset($_SESSION['editar']) && $_SESSION['editar'] == true && isset($_SESSION['idEditar'])) {
            try {
                $update = "UPDATE evento SET 
                          id_Docente = '$id_Doc',
                          nombre = '$nombre', 
                          periodo = '$periodo', 
                          descripcion = '$descripcion' 
                          WHERE id_Evento = " . $_SESSION['idEditar'];

                if ($link->query($update)) {
                    $_SESSION['editar'] = '';
                    $_SESSION['idEditar'] = '';
                    header('Location: ../main/evento-main.php?mensaje=SE HA ACTUALIZADO CORRECTAMENTE');
                    exit();
                } else {
                    header('Location: ../main/evento-main.php?mensaje=ERROR AL ACTUALIZAR EL EVENTO');
                    exit();
                }
            } catch (Exception $e) {
                header('Location: ../main/evento-main.php?mensaje=' . $e->getMessage());
            }
        }
    } catch (Exception $e) {
        header('Location: ../main/evento-main.php?mensaje=ERROR: ' . $e->getMessage());
    }
} else {
    // Validación modificada
    if (
        isset($_POST['accion']) && isset($_POST['id_registro']) &&
        ($_POST['accion'] == "eliminar_registro" || $_POST['accion'] == "editar_registro")
    ) {
        // No se realiza nada
    } else {
        header('Location: ../main/evento-main.php?mensaje=COMPLETE TODOS LOS CAMPOS');
        exit();
    }
}