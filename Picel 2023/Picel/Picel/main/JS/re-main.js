document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("frm-re").addEventListener("submit", function () {
    e.preventDefault(); // Prevent form submission
    if (validarFormulario()) {
      this.submit();
    }
  });

  document.getElementById("confirmDelete").addEventListener("click", function () {
      eliminarAlumno(idEstudiante);
    });
});
//--------------------------VISTA-----------------------------------------
let idEstudiante;

function modal(id, Nombre) {
  document.getElementById("Nombre-Modal").innerText = Nombre;
  idEstudiante = id;
}
//---------------------------FUNCIONALIDAD-----------------------------------------
function eliminarAlumno(idRegistro) {
  // Eliminar el registro con ID "idRegistro" de la base de datos
  try {
    $.ajax({
      url: "../system/sm_re.php",
      type: "POST",
      data: {
        accion: "eliminar_registro",
        id_registro: parseInt(idRegistro),
      },
      success: function (respuesta) {
        window.location.href = "./re-main.php?mensaje=SE ELIMINO CORRECTAMENTE";
      },
    });
  } catch (e) {
    window.location.href =
      "./re-main.php?mensaje=ERROR AL ELIMINAR EL REGISTRO" + e;
  }
}
function editarAlumno(idRegistro) {
  try {
    $.ajax({
      url: "../system/sm_re.php",
      type: "POST",
      data: {
        accion: "editar_registro",
        id_registro: parseInt(idRegistro),
      },
      success: function (respuesta) {
        var datos = JSON.parse(respuesta);
        alert(datos);
        var estudiante = datos.ESTUDIANTE;
        load(1);
        $("#nc-c").val(estudiante.num_control);
        $("#nombre-c").val(estudiante.nombre);
        $("#apellidoP-c").val(estudiante.apellidoP);
        $("#apellidoM-c").val(estudiante.apellidoM);
        $("#carrera-c").val(estudiante.carrera);
        $("#correo-c").val(estudiante.correo);
        $("#semestre-c").val(estudiante.semestre);
        $("#telefono-c").val(estudiante.num_celular);
        $("#usuario-c").val(estudiante.usuario);
        $("#password-c").val(estudiante.contrasena);
        $("#btn-regis").text("Aceptar");
      },
    });
  } catch (e) {
    window.location.href =
      "./re-main.php?mensaje=ERROR AL ELIMINAR EL REGISTRO" + e;
  }
}
function load(id) {
  if (id == 0) {
    document.getElementById("registros").style =
      "display:block;visibility:visible;";
    document.getElementById("registrar").style =
      "display:none;visibility:hidden;";
  } else {
    $("#nc-c").val("");
    $("#nombre-c").val("");
    $("#apellidoP-c").val("");
    $("#apellidoM-c").val("");
    $("#carrera-c").val("");
    $("#correo-c").val("");
    $("#semestre-c").val("");
    $("#telefono-c").val("");
    $("#usuario-c").val("");
    $("#password-c").val("");
    document.getElementById("registrar").style =
      "display:block;visibility:visible;";
    document.getElementById("registros").style =
      "display:none;visibility:hidden;";
  }
}
function cancel() {
  try {
    $.ajax({
      url: "../system/sm_re.php",
      type: "POST",
      data: {
        cancel: true,
      },
      success: function (respuesta) {
        location.href = "re-main.php";
      },
    });
  } catch (e) {
    alert("Error" + e);
  }
}
//------------------------------------VALIDACIONES-------------------------------------
function validarFormulario() {
  var numControl = document.getElementById("nc-c").value;
  var nombre = document.getElementById("nombre-c").value;
  var apellidoP = document.getElementById("apellidoP-c").value;
  var apellidoM = document.getElementById("apellidoM-c").value;
  var carrera = document.getElementById("carrera-c").value;
  var correo = document.getElementById("correo-c").value;
  var telefono = document.getElementById("telefono-c").value;
  var usuario = document.getElementById("usuario-c").value;
  var password = document.getElementById("password-c").value;

  if (numControl.trim() === "") {
    window.location.replace(
      "../main/re-main.php?mensaje=INGRESA EL NÚMERO DE CONTROL"
    );
    return false;
  }

  if (nombre.trim() === "") {
    window.location.replace("../main/re-main.php?mensaje=INGRESA EL NOMBRE");
    return false;
  }

  if (apellidoP.trim() === "") {
    window.location.replace(
      "../main/re-main.php?mensaje=INGRESA EL APELLIDO PATERNO"
    );
    return false;
  }

  if (apellidoM.trim() === "") {
    window.location.replace(
      "../main/re-main.php?mensaje=INGRESA EL APELLIDO MATERNO"
    );
    return false;
  }

  if (carrera.trim() === "") {
    window.location.replace("../main/re-main.php?mensaje=INGRESA LA CARRERA");
    return false;
  }

  if (correo.trim() === "") {
    window.location.replace("../main/re-main.php?mensaje=INGRESA EL CORREO");
    return false;
  }

  if (telefono.trim() === "") {
    window.location.replace("../main/re-main.php?mensaje=INGRESA EL TELÉFONO");
    return false;
  }

  if (usuario.trim() === "") {
    window.location.replace("../main/re-main.php?mensaje=INGRESA EL USUARIO");
    return false;
  }

  if (password.trim() === "") {
    window.location.replace(
      "../main/re-main.php?mensaje=INGRESA LA CONTRASEÑA"
    );
    return false;
  }

  return true;
}
