<?php
$URL = "system/login.php";
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>PICEL</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="ico/logo.ico">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css?v=<?php echo time();?>" rel="stylesheet">
    <link href="css/fontawesome.min.css" rel="stylesheet">
</head>

<body>
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="imgs/logo.png" class="img-fluid" alt="PICEL">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <?php
                            if(isset($_GET['error'])){
                                echo '<div class="box-log-error">'.base64_decode($_GET['error']).'</div>';
                            }
                        ?>
                    <form method="POST" action="<?php echo htmlspecialchars($URL);?>">
                        <div class="form-outline mb-4">
                            <label class="form-label" for="usuariolb" autocomplete="off">Usuario</label>
                            <input type="text" id="usuariolb" name="usuario" class="form-control form-control-lg"
                                placeholder="Usuario" />
                        </div>

                        <div class="form-outline mb-3">
                            <label class="form-label" for="passlb">Contraseña</label>
                            <input type="password" id="passlb" name="pass" class="form-control form-control-lg"
                                placeholder="Contraseña" />
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="#!" class="text-body">Recuperar Contraseña</a>
                        </div>

                        <div class="text-center text-lg-start mt-4 pt-2">
                            <button type="submit" class="btn btn-picel btn-lg"
                                style="padding-left: 2.5rem; padding-right: 2.5rem;">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div
            class="d-flex flex-column flex-md-row text-center justify-content-between p-lg-2 bg-picel align-items-center">
            <div class="text-white mb-3 mb-md-0 ps-4">
                Copyright 2023 © Programa Institucional Cultural de Experiencias Literarias
            </div>
            <div>
                <img src="imgs/logotecnm.png" class="img-fluid" alt="PICEL" width="125">
            </div>
        </div>
    </footer>
</body>

</html>