document.addEventListener('DOMContentLoaded', function () {
    document
        .getElementById('frm-revento')
        .addEventListener('submit', function () {
            e.preventDefault(); // Prevent form submission
            if (validarFormulario()) {
                this.submit();
            }
        });

    document
        .getElementById('confirmDelete')
        .addEventListener('click', function () {
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
        document.getElementById('registros').style =
            'display:block;visibility:visible;';
        document.getElementById('registrar').style =
            'display:none;visibility:hidden;';
    } else {
        $('#nombre').val('');
        $('#apellidoP').val('');
        $('#apellidoM').val('');
        $('#carrera').val('');
        $('#correo').val('');
        $('#telefono').val('');
        $('#usuario').val('');
        $('#password').val('');
        document.getElementById('registrar').style =
            'display:block;visibility:visible;';
        document.getElementById('registros').style =
            'display:none;visibility:hidden;';
    }
}
function eliminarDocente(idRegistro) {
    // Eliminar el registro con ID "idRegistro" de la base de dato
    try {
        $.ajax({
            url: '../system/evento-system.php',
            type: 'POST',
            data: {
                accion: 'eliminar_registro',
                id_registro: parseInt(idRegistro),
            },
            success: function (respuesta) {
                window.location.href =
                    './evento-main.php?mensaje=SE ELIMINO CORRECTAMENTE';
            },
        });
    } catch (e) {
        window.location.href =
            './evento-main.php?mensaje=Error al eliminar el registro' + e;
    }
}

function editarEvento(idRegistro) {
    try {
        $.ajax({
            url: '../system/evento-system.php',
            type: 'POST',
            data: {
                accion: 'editar_registro',
                id_registro: parseInt(idRegistro),
            },
            success: function (respuesta) {
                var datos = JSON.parse(respuesta);
                var evento = datos.EVENTO;
                load(1);
                $('#periodo').val(evento.periodo);
                $('#nombre').val(evento.nombre);
                $('#descripcion').val(evento.descrip);
            },
        });
    } catch (e) {
        window.location.replace(
            '../main/evento-main.php?mensaje=ERROR AL EDITAR EL DOCENTE:' + e
        );
    }
}

function cancel() {
    try {
        $.ajax({
            url: '../system/evento-system.php',
            type: 'POST',
            data: {
                cancel: true,
            },
            success: function (respuesta) {
                location.href = 'evento-main.php';
            },
        });
    } catch (e) {
        alert('Error' + e);
    }
}
//---------------------------------VALIDACIONES----------------------------
// Validaciones del formulario
function validarFormulari() {
    var periodo = document.getElementById('periodo').value;
    var nombre = document.getElementById('nombre').value;
    var descripcion = document.getElementById('descripcion').value;

    if (periodo.trim() === '') {
        window.location.replace(
            '../main/evento-main.php?mensaje=POR FAVOR INGRESE UN PERIODO VALIDO'
        );
        return false;
    }

    if (nombre.trim() === '') {
        window.location.replace(
            '../main/evento-main.php?mensaje=POR FAVOR INGRESE UN NOMBRE VALIDO'
        );
        return false;
    }

    if (descripcion.trim() === '') {
        window.location.replace(
            '../main/evento-main.php?mensaje=POR FAVOR INGRESE UNA DESCRIPCION VALIDA'
        );
        return false;
    }

    return true;
}
