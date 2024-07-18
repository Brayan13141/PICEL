document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('frm-rd').addEventListener('submit', function () {
        event.preventDefault(); // Prevent form submission
        if (validarFormulario()) {
            this.submit();
        }
    });


    document.getElementById('confirmDelete').addEventListener('click', function () {
        eliminarDocente(idDocente);
    });
});
//--------------------------VISTA-----------------------------------------
let idDocente;

function modal(id, Nombre) {
    document.getElementById('Nombre-Modal').innerText = Nombre;
    idDocente = id;
}
//---------------------------FUNCIONALIDAD-----------------------------------------
function load(id) {
    if (id == 0) {
        document.getElementById("registros").style = "display:block;visibility:visible;";
        document.getElementById("registrar").style = "display:none;visibility:hidden;";
    } else {
        $('#nombre-c').val("");
        $('#apellidoP-c').val("");
        $('#apellidoM-c').val("");
        $('#carrera-c').val("");
        $('#correo-c').val("");
        $('#telefono-c').val("");
        $('#usuario-c').val("");
        $('#password-c').val("");
        document.getElementById("registrar").style = "display:block;visibility:visible;";
        document.getElementById("registros").style = "display:none;visibility:hidden;";
    }
}
function eliminarDocente(idRegistro) {
    // Eliminar el registro con ID "idRegistro" de la base de datos
    try {
        $.ajax({
            url: "../system/sm_rd.php",
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
        window.location.href = "./rd-main.php?mensaje=Error al eliminar el registro" + e;
        
    }
}

function editarDocente(idRegistro) {
    try {
        $.ajax({
            url: "../system/sm_rd.php",
            type: "POST",
            data: {
                accion: "editar_registro",
                id_registro: parseInt(idRegistro)
            },
            success: function (respuesta) {
                var datos = JSON.parse(respuesta);
                var docente = datos.DOCENTE;
                load(1);
                $('#nombre-c').val(docente.nombre);
                $('#apellidoP-c').val(docente.apellidoP);
                $('#apellidoM-c').val(docente.apellidoM);
                $('#carrera-c').val(docente.carrera);
                $('#correo-c').val(docente.correo);
                $('#telefono-c').val(docente.num_celular);
                $('#usuario-c').val(docente.usuario);
                $('#password-c').val(docente.contrasena); 
                $('#btn-regis').text('Aceptar');
            }
        });
    } catch (e) {
        window.location.replace('../main/rd-main.php?mensaje=ERROR AL EDITAR EL DOCENTE:' + e);

    }
}

function cancel() {
    try {
        $.ajax({
            url: "../system/sm_rd.php",
            type: "POST",
            data: {
                cancel: true
            },
            success: function (respuesta) {
                location.href = 'rd-main.php';
            }
        });
    } catch (e) {
        alert("Error" + e);
    }
    
}
//---------------------------------VALIDACIONES----------------------------
// Validaciones del formulario
function validarFormulario() {
    var nombre = document.getElementById('nombre-c').value;
    var apellidoP = document.getElementById('apellidoP-c').value;
    var apellidoM = document.getElementById('apellidoM-c').value;
    var carrera = document.getElementById('carrera-c').value;
    var correo = document.getElementById('correo-c').value;
    var telefono = document.getElementById('telefono-c').value;
    var usuario = document.getElementById('usuario-c').value;
    var password = document.getElementById('password-c').value;

    if (nombre.trim() === '') {
        window.location.replace('../main/rd-main.php?mensaje=POR FAVOR INGRESE UN NOMBRE VALIDO');
        return false;
    }

    if (apellidoP.trim() === '') {
        window.location.replace('../main/rd-main.php?mensaje=POR FAVOR INGRESE UN APELLIDO PATERNO VALIDO');
        return false;
    }

    if (apellidoM.trim() === '') {
        window.location.replace('../main/rd-main.php?mensaje=POR FAVOR INGRESE UN APELLIDO MATERNO VALIDO');
        return false;
    }

    if (carrera.trim() === '') {
        window.location.replace('../main/rd-main.php?mensaje=POR FAVOR INGRESE UNA CARRERA VALIDA');
        return false;
    }

    if (correo.trim() === '') {
        window.location.replace('../main/rd-main.php?mensaje=POR FAVOR INGRESE UN CORREO VALIDO');
        return false;
    }

    if (telefono.trim() === '') {
        window.location.replace('../main/rd-main.php?mensaje=POR FAVOR INGRESE UN NUMERO DE TELEFONO VALIDO');
        return false;
    }

    if (usuario.trim() === '') {
        window.location.replace('../main/rd-main.php?mensaje=POR FAVOR INGRESE UN NOMBRE DE USUARIO VALIDO');
        return false;
    }

    if (password.trim() === '') {
        window.location.replace('../main/rd-main.php?mensaje=POR FAVOR INGRESE UNA CONTRASEÑA VALIDA');
        return false;
    }

    return true;
}