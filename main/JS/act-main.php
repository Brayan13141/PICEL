 <?php
    if (session_status() == PHP_SESSION_ACTIVE) {
    } else {
        session_start();
    }
    if (isset($_SESSION['id_User']) && $_SESSION['tipo_us'] == "Admin" || $_SESSION['tipo_us'] == "Docente") {
    ?>
     <script>
         let OpcionesGlobales;
         let OpcionesTemporales;
         document.addEventListener("DOMContentLoaded", () => {
             document.getElementById("noAlumnos").value = "";
             const formulario = document.getElementById('frm_act');
             //----------------------- VALIDAR FORMULARIO
             document.getElementById('frm_act').addEventListener('submit', (e) => {
                 e.preventDefault(); // Prevent form submission

                 if (validarFormulario()) {
                     formulario.submit();
                     alert('PASO');
                 }
             });



             //------------------------INICIALIZAR EL CONTADOR Y AÑADIR LOS EVENTOS ----------------
             var contenedor = document.getElementById('contenedorAlumnos');

             document.getElementById("noAlumnos").addEventListener('change',
                 () => {
                     limite = document.getElementById("noAlumnos").value;
                     contenedor.innerHTML = '';
                     OpcionesGlobales = {};
                     OpcionesTemporales = {};
                     actualizarCampos(limite);
                 });



             //Obtener el botón que se pulsó de las tareas registradas
             let tabla = document.getElementById("tabReg");
             let botones = tabla.getElementsByTagName("button");
             for (var i = 0; i < botones.length; i++) {
                 botones[i].addEventListener("click", function() {
                     var valueBoton = this.value;
                     //alert("Se hizo clic en el botón con value: " + valueBoton);
                     window.location.href =
                         "mact-main.php?act=" + encodeURIComponent(valueBoton);
                 });
             }
         });

         function load(id) {
             if (id == 0) {
                 document.getElementById("registros").style =
                     "display:block;visibility:visible;";
                 document.getElementById("definir").style =
                     "display:none;visibility:hidden;";
             } else {
                 document.getElementById("definir").style =
                     "display:block;visibility:visible;";
                 document.getElementById("registros").style =
                     "display:none;visibility:hidden;";
             }
         }

         function actualizarCampos(limite) {

             for (let i = 1; i <= limite; i++) {
                 let campo = document.createElement('div');
                 campo.className = 'row mb-3';
                 campo.id = 'campoAlumno' + i;

                 let col1 = document.createElement('div');
                 col1.className = 'mb-3 col-6';

                 let etiqueta1 = document.createElement('label');
                 etiqueta1.className = 'form-label';
                 etiqueta1.setAttribute('for', 'estudiante-c');
                 etiqueta1.innerText = 'Estudiante ' + i;

                 let select = document.createElement('select');
                 select.name = 'alumno' + i;
                 select.id = 'cmbAlumno' + i;
                 select.className = 'form-control';
                 select.innerHTML = `<?php echo $_SESSION['opciones'] ?>`;
                 select.addEventListener('change', actualizarSelects);
                 select.value = '';

                 col1.appendChild(etiqueta1);
                 col1.appendChild(select);

                 let col2 = document.createElement('div');
                 col2.className = 'mb-3 col-6';

                 let etiqueta2 = document.createElement('label');
                 etiqueta2.className = 'form-label';
                 etiqueta2.setAttribute('for', 'nombre-c');
                 etiqueta2.innerText = 'Tarea';

                 let input = document.createElement('input');
                 input.type = 'text';
                 input.id = 'txtTarea' + i;
                 input.className = 'form-control';
                 input.name = 'tarea' + i;

                 col2.appendChild(etiqueta2);
                 col2.appendChild(input);

                 campo.appendChild(col1);
                 campo.appendChild(col2);
                 let contenedor = document.getElementById('contenedorAlumnos');
                 if (contenedor) {
                     contenedor.appendChild(campo);
                 }
             }

             actualizarSelects();

         }


         function actualizarSelects() {

             //------------------------------------------SE OBTIENEN LOS SELECT CREADOS Y SUS VALORES SELECCIONADOS--------------------------
             let selects = document.querySelectorAll('select[id^="cmbAlumno"]');
             let selectedValues = Array.from(selects).map(select => select.value);

             //------------------------------------------SE RECUPERAN LAS OPCIONES QUE OBTUBIMOS DE LA BD------------------------------
             let opciones = `<?php echo $_SESSION['opciones'] ?>`;
             //--------------SE CREA UN DIV TEMPORAR PARA COLOCAR LOS SELECT(OPCIONES) Y OBTENER LOS VALORES CON UN ARRAY.FROM---------------
             let tempDiv = document.createElement('div');
             tempDiv.innerHTML = opciones;
             let opcionesArr = Array.from(tempDiv.querySelectorAll('option')).map(option => option.value);
             //--------------------------------------------------------------------------------------------- 


             selects.forEach(select => {
                 let currentValue = select.value;
                 //CREAR UN NUEVO ARREGLO CON LAS OPCIONES RESTANTES QUE NO INCLUYAN EL VALOR ACTUAL
                 if (opcionesArr.includes(currentValue)) {
                     OpcionesTemporales = opcionesArr.filter(value => value != currentValue);

                 }
                 //SE FILTRAN LAS OPCIONESTEMPORALES PARA TENER UN ARRAY NUEVO CON LOS VALORES RESTANTES QUE NO ESTAN EN LA SELECCION
                 if (OpcionesGlobales) {
                     OpcionesGlobales = OpcionesTemporales.filter(value => !selectedValues.includes(value));
                     select.innerHTML = '<option>' + currentValue + '</option>';

                 }

                 //SE CREAN LAS NUEVAS OPCIONES PARA LOS SELECT RESTANTES
                 OpcionesGlobales.forEach(optionValue => {
                     let option = document.createElement('option');
                     option.value = optionValue;
                     option.textContent = optionValue;
                     select.appendChild(option);
                 });

             });
         }


         function validarFormulario() {
             let camposTarea = document.querySelectorAll('input[id^="txtTarea"]');
             let selects = document.querySelectorAll('select[id^="cmbAlumno"]');
             let NombreA = document.getElementById('NombreA');

             let bandera = true;

             if (NombreA.value.trim() == '') {
                 window.location.replace('../main/act-main.php?mensaje=POR FAVOR LLENE EL NOMBRE DE LA ACTIVIDAD');
                 bandera = false;
             }
             camposTarea.forEach(campo => {
                 if (campo.value.trim() === '') {
                     window.location.replace('../main/act-main.php?mensaje=POR FAVOR LLENE TODOS LOS CAMPOS DE TAREA');
                     bandera = false;
                 }
             });
             selects.forEach(campo => {
                 if (campo.value.trim() === '') {
                     bandera = false;
                     window.location.replace('../main/act-main.php?mensaje=POR FAVOR LLENE TODOS LOS ALUMNOS');
                 }
             });
             if (bandera) {
                 return true;
             } else {
                 return false;
             }
         }
     </script>
 <?php
    }
    ?>