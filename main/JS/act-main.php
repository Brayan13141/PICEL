<?php
if (session_status() == PHP_SESSION_ACTIVE) {
} else {
    session_start();
}
if (isset($_SESSION['id_User']) && $_SESSION['tipo_us'] == "Admin" || $_SESSION['tipo_us'] == "Docente") {
?>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById('frm_act');
    const selectNoAlumnos = document.getElementById("noAlumnos");
    actualizarSelects();
    // 1) Validación del formulario al enviarse
    formulario.addEventListener('submit', e => {
        e.preventDefault();
        if (validarFormulario()) {
            formulario.submit();
        }
    });

    // 2) Al cambiar el número de alumnos, reconstruimos los campos
    selectNoAlumnos.addEventListener('change', () => {
        const limite = parseInt(selectNoAlumnos.value, 10);
        actualizarCampos(limite);
    });

    // 3) Si al cargar ya había un valor, generamos campos
    if (selectNoAlumnos.value) {
        actualizarCampos(parseInt(selectNoAlumnos.value, 10));
    }

    // 4) Configuración del botón de eliminación en el modal
    document.getElementById("confirmDelete").addEventListener("click", () => {
        eliminarAct(idAct);
    });
});

// Variable global para la actividad a eliminar
let idAct = null;

// Función para lanzar la petición AJAX de eliminación
function eliminarAct(idRegistro) {
    $.post("../system/act-main.php", {
            accion: "eliminar_registro",
            id_registro: parseInt(idRegistro, 10)
        })
        .done(res => {
            window.location.href = "../main/act-main.php?mensaje=SE ELIMINÓ CORRECTAMENTE";
        })
        .fail(err => {
            window.location.href = "../main/act-main.php?mensaje=ERROR AL ELIMINAR EL REGISTRO";
        });
}

// Función para enviar datos a la vista de detalles
function enviarDatos(idEvento, id_Docente, id_actividad) {
    window.location.href =
        `../main/mact-main.php?id_E=${idEvento}&id_D=${id_Docente}&id_A=${id_actividad}`;
}

// Mostrar modal de confirmación
function modal(id, Nombre) {
    document.getElementById("Nombre-Modal").innerText = Nombre;
    idAct = id;
    new bootstrap.Modal(document.getElementById("deleteModal")).show();
}

// Alternar vistas Registros / Definir
function load(id) {
    document.getElementById("registros").style = id === 0 ?
        "display:block;visibility:visible;" :
        "display:none;visibility:hidden;";
    document.getElementById("definir").style = id === 1 ?
        "display:block;visibility:visible;" :
        "display:none;visibility:hidden;";
}

// Array maestro de opciones (se llena cada vez)
let opcionesArr = [];

/**
 * Genera dinámicamente los campos "Estudiante i" y "Descripción de la tarea i"
 * según el límite seleccionado en #noAlumnos.
 * Además, recarga las opciones de alumno para que no se repitan.
 * 
 */
function actualizarCampos(limite) {
    // 1) Limpia todo el contenedor antes de volver a poblarlo
    const contenedor = document.getElementById('contenedorAlumnos');
    contenedor.innerHTML = '';

    // 2) Parseamos las <option> inyectadas desde PHP en SESSION
    const temp = document.createElement('div');
    temp.innerHTML = `<?php echo $_SESSION['opciones'] ?>`;

    // Creamos un array de objetos con { value, text } para cada alumno
    const opcionesArr = Array.from(
        temp.querySelectorAll('option')
    ).map(opt => ({
        value: opt.value, // num_control
        text: opt.textContent // "Nombre – num_control"
    }));

    // 3) Por cada índice 1..limite, creamos la fila de inputs
    for (let i = 1; i <= limite; i++) {
        // Fila contenedora
        const row = document.createElement('div');
        row.className = 'row mb-3';
        row.id = `campoAlumno${i}`;

        // === Columna 1: Select de alumno ===
        const col1 = document.createElement('div');
        col1.className = 'mb-3 col-6';

        const lbl1 = document.createElement('label');
        lbl1.className = 'form-label';
        lbl1.setAttribute('for', `cmbAlumno${i}`);
        lbl1.innerText = `Estudiante ${i}`;

        const sel = document.createElement('select');
        sel.name = `alumno${i}`;
        sel.id = `cmbAlumno${i}`;
        sel.className = 'form-control';

        // Cada vez que cambie, volvemos a repartir opciones
        sel.addEventListener('change', actualizarSelects);

        // Guardamos las opciones completas como dato en el propio select
        sel._todasOpciones = opcionesArr;

        col1.append(lbl1, sel);

        // === Columna 2: Input de descripción de tarea ===
        const col2 = document.createElement('div');
        col2.className = 'mb-3 col-6';

        const lbl2 = document.createElement('label');
        lbl2.className = 'form-label';
        lbl2.setAttribute('for', `txtTarea${i}`);
        lbl2.innerText = 'Descripción de la tarea';

        const inp = document.createElement('input');
        inp.type = 'text';
        inp.id = `txtTarea${i}`;
        inp.name = `tarea${i}`;
        inp.className = 'form-control';

        col2.append(lbl2, inp);

        // Montamos fila completa y la añadimos
        row.append(col1, col2);
        contenedor.appendChild(row);
    }

    // 4) Después de crear todos, repartimos las opciones
    actualizarSelects();
}

/**
 * Reparte entre todos los <select id="cmbAlumno*"> las opciones
 * de forma que el mismo alumno (value) no pueda seleccionarse dos veces.
 * 
 * Se basa en el array guardado en sel._todasOpciones.
 */
function actualizarSelects() {
    // 1) Recogemos todos los selects creados
    const selects = document.querySelectorAll('select[id^="cmbAlumno"]');

    // 2) Leemos los valores ya elegidos
    const elegidos = Array.from(selects).map(s => s.value);

    // 3) Para cada select:
    selects.forEach(select => {
        // Recuperamos la lista completa de opciones que guardamos
        const todas = select._todasOpciones;
        const actual = select.value; // lo que ya esté seleccionado

        // Disponibles = el actual (si hubiera) + el resto no elegidos
        const disponibles = todas.filter(opt =>
            opt.value === actual || !elegidos.includes(opt.value)
        );
        select.innerHTML = disponibles
            .map(opt => `<option value="${opt.value}">${opt.text}</option>`)
            .join('');
        // 5) Restauramos el valor que ya tenía el select
        select.value = actual;
    });
}

// Valida que todos los campos estén completos
function validarFormulario() {
    const nombreA = document.getElementById('NombreA').value.trim();
    if (!nombreA) {
        window.location.replace('../main/act-main.php?mensaje=POR FAVOR LLENE EL NOMBRE DE LA ACTIVIDAD');
        return false;
    }
    // Textos de tarea
    const tareas = document.querySelectorAll('input[id^="txtTarea"]');
    for (let t of tareas) {
        if (!t.value.trim()) {
            window.location.replace('../main/act-main.php?mensaje=POR FAVOR LLENE TODOS LOS CAMPOS DE TAREA');
            return false;
        }
    }
    // Selects de alumnos
    const selects = document.querySelectorAll('select[id^="cmbAlumno"]');
    for (let s of selects) {
        if (!s.value.trim()) {
            window.location.replace('../main/act-main.php?mensaje=POR FAVOR LLENE TODOS LOS ALUMNOS');
            return false;
        }
    }
    return true;
}
</script>
<?php
}
?>