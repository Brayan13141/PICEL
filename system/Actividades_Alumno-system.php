<?php
session_start();
include('conexion.php'); // Incluye la conexión a la base de datos

// Verifica si el usuario está autenticado
if (!isset($_SESSION['id_User'])) {
    header("Location: ../main/login.php");
    exit();
}

// Obtener el ID del estudiante desde la base de datos
$id_Estudiante = null;
$queryEstudiante = "SELECT id_Estudiante FROM estudiantes WHERE id_User = ?";
$stmt = $link->prepare($queryEstudiante);
$stmt->bind_param("i", $_SESSION['id_User']);
$stmt->execute();
$resultEstudiante = $stmt->get_result();

if ($rowEstudiante = $resultEstudiante->fetch_assoc()) {
    $id_Estudiante = $rowEstudiante['id_Estudiante'];
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica si se subió un archivo
    if (!isset($_FILES["archivo"]) || $_FILES["archivo"]["error"] !== UPLOAD_ERR_OK) {
        echo "<script>location.href='../main/Actividades-Alumnos.php?mensaje=Error al subir el archivo. Asegúrate de seleccionar un archivo válido';</script>";
        exit();
    }

    // Obtiene la información del archivo
    $id_tarea = $_POST["id_tarea"];
    $num_control = $_SESSION['num_control'];
    $nombreArchivo = $_FILES["archivo"]["name"];
    $extension = strtolower(pathinfo($nombreArchivo, PATHINFO_EXTENSION));

    // **Extensiones permitidas**
    $permitidos = ['pdf', 'doc', 'docx', 'jpg', 'png', 'zip', 'jpeg'];
    if (!in_array($extension, $permitidos)) {
        echo "<script>location.href='../main/Actividades-Alumnos.php?mensaje=Formato no permitido';</script>";
        exit();
    }

    // **Verificación de tamaño del archivo (Máximo 5MB)**
    if ($_FILES["archivo"]["size"] > 5 * 1024 * 1024) {
        echo "<script>location.href='../main/Actividades-Alumnos.php?mensaje=El archivo excede el tamaño permitido (5MB)';</script>";
        exit();
    }

    // **Directorio de almacenamiento**
    $directorio = "../Archivos/$num_control/";
    if (!is_dir($directorio) && !mkdir($directorio, 0777, true)) {
        echo "<script>location.href='../main/Actividades-Alumnos.php?mensaje=Error al crear el directorio';</script>";
        exit();
    }

    // **Renombrar el archivo para evitar sobrescrituras**
    $nombreUnico = uniqid("archivo_", true) . "." . $extension;
    $rutaArchivo = $directorio . $nombreUnico;

    // **Mover el archivo al directorio de almacenamiento**
    if (!move_uploaded_file($_FILES["archivo"]["tmp_name"], $rutaArchivo)) {
        echo "<script>location.href='../main/Actividades-Alumnos.php?mensaje=Error al mover el archivo al servidor';</script>";
        exit();
    }

    // **Guardar la información en la base de datos**
    $query = "INSERT INTO entregas (id_Tarea, archivo) VALUES (?, ?)";
    $stmt = $link->prepare($query);
    $stmt->bind_param("is", $id_tarea, $rutaArchivo);

    if ($stmt->execute()) {
        // Actualizar el estado de la tarea a 1 en la tabla "tarea"
        $query_update = "UPDATE tarea SET estatus = 1 WHERE id_Tarea = ?";
        $stmt_update = $link->prepare($query_update);
        $stmt_update->bind_param("i", $id_tarea);
        $stmt_update->execute();
        $stmt_update->close();

        echo "<script>location.href='../main/Actividades-Alumnos.php?mensaje=SE REGISTRO LA TAREA AL ALUMNO';</script>";
    } else {
        echo "<script>location.href='../main/Actividades-Alumnos.php?mensaje=ERROR AL REGISTRAR LA TAREA';</script>";
    }
    $stmt->close();
} else {
    header("Location: ../main/Actividades-Alumnos.php");
    exit();
}
