<?php
    session_start();
    if($_SESSION['tipo_us']!="Admin"){
      header("Location: index.php");
    }
    if ($_SESSION['id_User']) {
      include('../system/conexion.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>PICEL ~ RD</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="../ico/logo.ico">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/estilo.css?v=<?php echo time();?>" rel="stylesheet">
        <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
    </head>
    <body>
      <?php
        require('menu.php');
      ?>
   <div class="container margen">
    <div class="row mt-3">
      <div class="col"><button type="button" onclick="load(0);" class="mx-auto d-flex justify-content-center btn btn-success">Ver Registros</button></div>
      <div class="col"><button type="button" onclick="load(1);" class="mx-auto d-flex justify-content-center btn btn-success">Registrar Estudiante</button></div>
    </div>
    <div class="row" style="display:none;visibility:hidden;" id="registros">
      <div class="col-12 pt-2 text-center">
          <h3>Estudiantes</h3>
      </div>
      <div class="grid mx-auto col-md-12">      
    <?php
    $query = "SELECT * FROM estudiantes";
    echo '<table class="table table-hover mt-3" border="0" cellspacing="2" cellpadding="2"> 
        <tr> 
            <td><p>No. Control</p></td> 
            <td><p>Nombre</p></td>
            <td><p>Correo</p></td> 
            <td><p>Carrera</p></td> 
            <td><p>Semestre</p></td> 
            <td><p>Teléfono</p></td> 
        </tr>';

  if ($result = $link->query($query)) {
      while ($row = $result->fetch_assoc()) {          
          $ncon = $row["num_control"];
          $name = $row["nombre"]." ".$row["apellidoP"]." ".$row["apellidoM"];
          $correo = $row["correo"];
          $carr = $row["carrera"];
          $sem = $row["semestre"];
          $cel = $row["num_celular"];

          echo '<tr> 
                    <td>'.$ncon.'</td> 
                    <td>'.$name.'</td> 
                    <td>'.$correo.'</td>
                    <td>'.$carr.'</td> 
                    <td>'.$sem.'</td> 
                    <td>'.$cel.'</td> 
                </tr>';
      }
      echo "</table>";
      $result->free();
  } 
  ?>  
      </div>
    </div>
      <div class="row" style="display:none;visibility:hidden;" id="registrar">
        <div class="col text-center pt-2 pb-4">
            <h3>Registro de Estudiantes</h3>
        </div>
        <div class="grid mx-auto col-md-8" style="--bs-columns: 2;">
          <form method="POST" action="../system/sm_re.php">
            <div class="row">
                <div class="mb-3 col-3">
                    <label for="nc-c" class="form-label">No. de Control</label>
                    <input type="text" class="form-control" id="nc-c" name="numerocontrol" >
                </div>
                <div class="mb-3 col-3">
                    <label for="nombre-c" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="nombre-c" name="nombre" >
                </div>
                <div class="mb-3 col-3">
                    <label for="apellidoP-c" class="form-label">Apellido Paterno</label>
                    <input type="text" class="form-control" id="apellidoP-c" name="apellidoP" >
                </div>
                <div class="mb-3 col-3">
                    <label for="apellidoM-c" class="form-label">Apellido Materno</label>
                    <input type="text" class="form-control" id="apellidoM-c" name="apellidoM" >
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-6">
                    <label for="carrera-c" class="form-label">Carrera</label>
                    <select name="carrera" class="form-control" id="carrera-c">
                        <option value="SISTEMAS COMPUTACIONALES">ING SISTEMAS COMPUTACIONALES</option>
                        <option value="AMBIENTAL">ING AMBIENTAL</option>
                        <option value="INDUSTRIAL">ING INDUSTRIAL</option>
                        <option value="GESTION EMPRESARIAL">ING GESTION EMPRESARIAL</option>
                        <option value="ELECTRONICA">ING ELECTRONICA</option>
                        <option value="SISTEMAS AUTOMOTRICES">ING SISTEMAS AUTOMOTRICES</option>
                        <option value="GASTRONOMIA">GASTRONOMIA</option>
                    </select>
                </div>
                <div class="mb-3 col-6">
                    <label for="correoi-c" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="correoi-c" name="correoinstituto" >
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-6">
                    <label for="semestre-c" class="form-label">Semestre</label>
                    <input type="number" class="form-control" id="semestre-c" min="1" max="16" value="1" name="semestre" >
                </div>
                <div class="mb-3 col-6">
                    <label for="telefono-c" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono-c" name="telefono" >
                </div>
            </div>
            <div class="row" id="usuarioEstudiante">
                <div class="mb-3 col-6">
                    <label for="usuario-c" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="usuario-c" name="usuario" >
                </div>
                <div class="mb-3 col-6">
                    <label for="password-c" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password-c" name="password" >
                </div>
              </div>
            <div class="pt-2 d-flex justify-content-center">
                <button type="submit" class="btn btn-picel">Registrar</button>
            </div>
          </form>    
        </div>
      </div>
    </div>

<?php
    if(isset($_GET['r'])){
        ?>
<div class="modal fade" id="modalStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="background: #ffffff;margin-top: 40%;overflow-y: auto;">
      <div class="modal-header" style="border-bottom: none;text-align: center;">
        <h5 class="modal-title" id="lbproverData" style="color: green;font-size: 20px"><?php echo base64_decode($_GET['r']); ?></h5>    
        <button type="button" class="btn btn-danger" onclick="reload_box();" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
      </div>
      
    </div>
  </div>
</div>
<script>
    $(document).ready(function(){
        $('#modalStatus').modal('toggle');
    });

function reload_box(){
  location.href='re-main.php';
}  
    </script>
        <?php
    }
?>


<?php
include('footer.php');
?>
<script>
  function load(id){
    if(id==0){
      document.getElementById("registros").style="display:block;visibility:visible;";
      document.getElementById("registrar").style="display:none;visibility:hidden;";
    }else{
      document.getElementById("registrar").style="display:block;visibility:visible;";
      document.getElementById("registros").style="display:none;visibility:hidden;";
    }
  }

  </script>
    </body>
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
location.href ="../";
 </script>
</body>
<?php
}
?>
