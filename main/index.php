<?php

/**
 * Archivo: /c:/wamp64/www/PICEL-master/PICEL-master/main/index.php
 *
 * Este archivo es la página principal del sistema PICEL.
 * Verifica si el usuario ha iniciado sesión y muestra contenido basado en el tipo de usuario.
 *
 * Variables principales:
 * - $_SESSION['id_User']: Identificador del usuario.
 * - $_SESSION['nombre']: Nombre del usuario.
 * - $_SESSION['apellidoP']: Apellido paterno del usuario.
 * - $_SESSION['apellidoM']: Apellido materno del usuario.
 * - $_SESSION['tipo_us']: Tipo de usuario (Admin, Docente, Estudiante).
 *
 * Archivos requeridos:
 * - menu.php: Archivo que contiene el menú de navegación.
 * - footer.php: Archivo que contiene el pie de página.
 * - ../ico/logo.ico: Icono de la página.
 * - ../css/bootstrap.min.css: Hoja de estilos de Bootstrap.
 * - ../css/estilo.css: Hoja de estilos personalizada.
 * - ../js/2a6b22e937.js: Archivo JavaScript para funcionalidades adicionales.
 *
 * Dependiendo del tipo de usuario, se muestran diferentes opciones en la página principal:
 * - Admin: Acceso a Docentes y Eventos.
 * - Docente: Acceso a Actividades y Estudiantes.
 * - Estudiante: Acceso a Mis actividades.
 *
 * Si el usuario no ha iniciado sesión, se redirige a la página principal.
 */
session_start();
if ($_SESSION['id_User']) {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>PANEL PRINCIPAL</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="../ico/logo.ico">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/estilo.css?v=<?php echo time(); ?>" rel="stylesheet">
        <script src="../js/2a6b22e937.js" crossorigin="anonymous"></script>

    </head>
    <style>
        .col {
            margin: 5px !important;
        }
    </style>

    <body>
        <?php
        require('menu.php');
        ?>
        <section class="container margen">
            <div class="row mt-4">
                <div class="col text-center">
                    <h3>¡Bienvenido
                        <?php echo $_SESSION['nombre'] . ' ' . $_SESSION['apellidoP'] . ' ' . $_SESSION['apellidoM'] . '!'; ?>
                    </h3>
                </div>
            </div>
            <div class="row mt-4">
                <?php
                if ($_SESSION['tipo_us'] == "Admin") {
                ?>
                    <div class="col">
                        <div class="card card-main mx-auto" onclick="location.href='rd-main.php';" style="width: 18rem;">
                            <i class="fa fa-users" aria-hidden="true" style="font-size: 218px;text-align: center;"></i>
                            <div class="card-body">
                                <h4 class="text-center"><strong>Docentes</strong></h4>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="card card-main mx-auto" onclick="location.href='evento-main.php';" style="width: 18rem;">
                            <i class="fa fa-calendar" aria-hidden="true" style="font-size: 218px;text-align: center;"></i>
                            <div class="card-body">
                                <h4 class="text-center"><strong>Eventos</strong></h4>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <?php
                if ($_SESSION['tipo_us'] == "Docente" || $_SESSION['tipo_us'] == "Admin") {
                ?>
                    <div class="col">
                        <div class="card card-main mx-auto" onclick="location.href='act-main.php';" style="width: 18rem;">
                            <i class="fa fa-book " aria-hidden="true" style="font-size: 218px;text-align: center;"></i>
                            <div class="card-body">
                                <h4 class="text-center"><strong>Actividades</strong></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-main mx-auto" onclick="location.href='re-main.php';" style="width: 18rem;">
                            <i class="fa fa-users" aria-hidden="true" style="font-size: 218px;text-align: center;"></i>
                            <div class="card-body">
                                <h4 class="text-center"><strong>Estudiantes</strong></h4>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <?php
                if ($_SESSION['tipo_us'] == "Estudiante") {
                ?>
                    <div class="col">
                        <div class="card card-main mx-auto" onclick="location.href='../main/Actividades-Alumnos.php';"
                            style="width: 18rem;">
                            <i class="fa fa-paper-plane" aria-hidden="true" style="font-size: 218px;text-align: center;"></i>
                            <div class="card-body">
                                <h4 class="text-center"><strong>Mis actividades</strong></h4>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </section>
    </body>
    <?php
    require('footer.php');
    ?>

    </html>
<?php
} else {
?>
    <HTML>

    <HEAD>
        <TITLE>Picel</TITLE>

    </HEAD>

    <body BGCOLOR="black">
        <script>
            location.href = "../";
        </script>
    </body>
<?php
}
?>