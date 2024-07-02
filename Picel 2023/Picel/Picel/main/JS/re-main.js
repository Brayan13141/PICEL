document.addEventListener('DOMContentLoaded', function () {
     alert('ENTRO');

     document.getElementById('frm-re').addEventListener('submit', function () {
          alert('ENTRO');
          event.preventDefault(); // Prevent form submission
          if (validarFormulario()) {
              this.submit();
          }
      });
     

     document.getElementById('confirmDelete').addEventListener('click', function () {
          eliminarAlumno(idEstudiante);
     });


});


let idEstudiante; 

function modal(id,Nombre) {
     document.getElementById('Nombre-Modal').innerText = Nombre;
     idEstudiante = id;
}

function eliminarAlumno(idRegistro) {
     // Eliminar el registro con ID "idRegistro" de la base de datos
     try {
          $.ajax({
               url: "../system/sm_re.php",
               type: "POST",
               data: {
                    accion: "eliminar_registro",
                    id_registro: parseInt(idRegistro)
               },
               success: function (respuesta) {
                    window.location.href = "./rd-main.php?mensaje=SE ELIMINO CORRECTAMENTE";
               }
          });
     } catch (e) {
          //$('#RESPUESTA').html(respuesta.RESPUESTA);
          alert("Error al eliminar el registro" + e);
     }

}

function editarAlumno(idRegistro) {
     try {
         $.ajax({
             url: "../system/sm_re.php",
             type: "POST",
             data: {
                 accion: "editar_registro",
                 id_registro: parseInt(idRegistro)
             },
             success: function (respuesta) {
                 var datos = JSON.parse(respuesta);
                 var estudiante = datos.ESTUDIANTE;
                 load(1);
                 $('#nc-c').val(estudiante.num_control);
                 $('#nombre-c').val(estudiante.nombre);
                 $('#apellidoP-c').val(estudiante.apellidoP);
                 $('#apellidoM-c').val(estudiante.apellidoM);
                 $('#carrera-c').val(estudiante.carrera);
                 $('#correo-c').val(estudiante.correo);
                 $('#telefono-c').val(estudiante.num_celular);
                 $('#usuario-c').val(estudiante.usuario);
                 $('#password-c').val(estudiante.contrasena);
             }
         });
     } catch (e) {
         //$('#RESPUESTA').html(respuesta.RESPUESTA);
         alert("Error al editar el registro: " + e);
     }
 }

function load(id) {
     if (id == 0) {
          document.getElementById("registros").style = "display:block;visibility:visible;";
          document.getElementById("registrar").style = "display:none;visibility:hidden;";
     } else {
          document.getElementById("registrar").style = "display:block;visibility:visible;";
          document.getElementById("registros").style = "display:none;visibility:hidden;";
     }
}
//------------------------------------VALIDACIONES-------------------------------------

function validarFormulario() {
     var numControl = document.getElementById('nc-c').value;
     var nombre = document.getElementById('nombre-c').value;
     var apellidoP = document.getElementById('apellidoP-c').value;
     var apellidoM = document.getElementById('apellidoM-c').value;
     var carrera = document.getElementById('carrera-c').value;
     var correo = document.getElementById('correo-c').value;
     var telefono = document.getElementById('telefono-c').value;
     var usuario = document.getElementById('usuario-c').value;
     var password = document.getElementById('password-c').value;

     if (numControl.trim() === '') {
          window.location.replace("../main/re-main.php?mensaje=INGRESA EL NÚMERO DE CONTROL");
          return false;
     }

     if (nombre.trim() === '') {
          window.location.replace("../main/re-main.php?mensaje=INGRESA EL NOMBRE");
          return false;
     }

     if (apellidoP.trim() === '') {
          window.location.replace("../main/re-main.php?mensaje=INGRESA EL APELLIDO PATERNO");
          return false;
     }

     if (apellidoM.trim() === '') {
          window.location.replace("../main/re-main.php?mensaje=INGRESA EL APELLIDO MATERNO");
          return false;
     }

     if (carrera.trim() === '') {
          window.location.replace("../main/re-main.php?mensaje=INGRESA LA CARRERA");
          return false;
     }

     if (correo.trim() === '') {
          window.location.replace("../main/re-main.php?mensaje=INGRESA EL CORREO");
          return false;
     }

     if (telefono.trim() === '') {
          window.location.replace("../main/re-main.php?mensaje=INGRESA EL TELÉFONO");
          return false;
     }

     if (usuario.trim() === '') {
          window.location.replace("../main/re-main.php?mensaje=INGRESA EL USUARIO");
          return false;
     }

     if (password.trim() === '') {
          window.location.replace("../main/re-main.php?mensaje=INGRESA LA CONTRASEÑA");
          return false;
     }

     return true;
}