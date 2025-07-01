<?php

/**
 * Archivo: /c:/wamp64/www/PICEL-master/PICEL-master/system/sm_tarea.php
 *
 * Este archivo procesa las acciones sobre las tareas:
 * - Validar: actualiza el estado de la tarea a 1 (Validado)
 * - Denegar: actualiza el estado de la tarea a 0 (Pendiente)
 * - Guardar Anotaciones: actualiza el campo Anotaciones de la tarea
 *
 * Además, se reciben los id's del evento, docente y actividad (id_E, id_D, id_A)
 * ya sea mediante POST o GET, para usarlos en la redirección final.
 */

include("../system/conexion.php");
session_start();

/**
 * Función para sanear los datos de entrada.
 *
 * @param string $data
 * @return string
 */
function antiscript($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Obtener los id's (id_E, id_D, id_A) desde POST o GET
$id_E = isset($_POST['id_E']) ? antiscript($_POST['id_E']) : (isset($_GET['id_E']) ? antiscript($_GET['id_E']) : '');
$id_D = isset($_POST['id_D']) ? antiscript($_POST['id_D']) : (isset($_GET['id_D']) ? antiscript($_GET['id_D']) : '');
$id_A = isset($_POST['id_A']) ? antiscript($_POST['id_A']) : (isset($_GET['id_A']) ? antiscript($_GET['id_A']) : '');

// Construir la URL de redirección con los parámetros recibidos
$redirect = "../main/mact-main.php?id_E=" . $id_E . "&id_D=" . $id_D . "&id_A=" . $id_A;

//---------------------------------- PROCESAR VALIDAR O DENEGAR --------------------------------
if (isset($_POST['accion']) && isset($_POST['id_tarea'])) {
    $accion = antiscript($_POST['accion']);
    $id_tarea = antiscript($_POST['id_tarea']);

    try {
        if ($accion === "validar") {
            // Actualiza el estado de la tarea a 2 (Validado)
            $query = "UPDATE tarea SET validado = 1 WHERE id_Tarea = '$id_tarea'";
            if ($link->query($query)) {
                header("Location: " . $redirect . "&mensaje=TAREA VALIDADA");
                exit();
            } else {
                header("Location: " . $redirect . "&mensaje=ERROR AL VALIDAR LA TAREA");
                exit();
            }
        } elseif ($accion === "denegar") {
            $selectFile = "SELECT archivo FROM entregas WHERE id_Tarea = '$id_tarea' LIMIT 1";
            $resultFile = $link->query($selectFile);
            if ($resultFile && $resultFile->num_rows > 0) {
                $rowFile = $resultFile->fetch_assoc();
                $filePath = $rowFile['archivo'];
                // Verifica si existe el archivo en el servidor y bórralo
                if (!empty($filePath) && file_exists($filePath)) {
                    unlink($filePath);
                }
                // Actualiza la tabla "entregas" para limpiar la ruta del archivo
                $updateEntrega = "DELETE FROM entregas WHERE id_Tarea = '$id_tarea'";
                $link->query($updateEntrega);
            }
            $query = "UPDATE tarea SET Estatus = 0 WHERE id_Tarea = '$id_tarea'";
            if ($link->query($query)) {

                header("Location: " . $redirect . "&mensaje=TAREA DENEGADA Y PENDIENTE");
                exit();
            } else {
                header("Location: " . $redirect . "&mensaje=INTENTE DE NUEVO");
                exit();
            }
        } else {
            header("Location: " . $redirect . "&mensaje=ACCION NO RECONOCIDA");
            exit();
        }
    } catch (Exception $e) {
        header("Location: " . $redirect . "&mensaje=Excepción: " . $e->getMessage());
        exit();
    }
}

//---------------------------------- PROCESAR GUARDAR ANOTACIONES --------------------------------
if (isset($_POST['anotaciones']) && isset($_POST['id_tarea'])) {
    $anotaciones = antiscript($_POST['anotaciones']);
    $id_tarea = antiscript($_POST['id_tarea']);

    try {
        $query = "UPDATE tarea SET Anotaciones = '$anotaciones' WHERE id_Tarea = '$id_tarea'";
        if ($link->query($query)) {
            header("Location: " . $redirect . "&mensaje=ANOTACIONES GUARDADAS CORRECTAMENTE");
            exit();
        } else {
            header("Location: " . $redirect . "&mensaje=ERROR AL GUARDAR LAS ANOTACIONES");
            exit();
        }
    } catch (Exception $e) {
        header("Location: " . $redirect . "&mensaje=Excepción: " . $e->getMessage());
        exit();
    }
}

// Si no se envió ningún dato esperado, redirigir con un mensaje
header("Location: " . $redirect . "&mensaje=No se procesó la solicitud");
exit();
