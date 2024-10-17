<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación de horarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center"><i class="fas fa-user-friends"></i> Asignación de horarios</h3>
    <form id="form-horarios" method="POST">
        <!-- Selección de supervisor -->
        <div class="form-group">
            <label for="employee_code"><i class="fas fa-user"></i> Seleccione Supervisor:</label>
            <select id="employee_code" name="employee_code" class="form-control" required>
                <option value="" disabled selected>Seleccione un supervisor</option>
            </select>
        </div>

        <!-- Selección de tienda -->
        <div class="form-group">
            <label for="store_no"><i class="fas fa-store"></i> Seleccione Tienda:</label>
            <select id="store_no" name="store_no" class="form-control" required>
                <option value="" disabled selected>Seleccione una tienda</option>
            </select>
        </div>

        <!-- Selección de fecha y tabla de horarios -->
        <div class="mt-4">
            <label for="start-date"><i class="fas fa-calendar-alt"></i> Selecciona la fecha de inicio (domingo):</label>
            <input type="date" id="start-date" name="fecha" class="form-control" onchange="actualizarFechasYSemana()" required>
        </div>

        <div class="mt-4">
            <h4 id="semana_display">Semana del año: <span id="numero_semana">0</span></h4>
            <input type="hidden" id="semana" name="semana">
        </div>
        <!-- Tabla de empleados -->
        <div class="table-responsive">
            <i class="fas fa-users"></i> Empleados Asignados:
            <table id="empleadosTable" class="table table-hover table-sm tbrdst">
                <thead class="thead-dark">
                    <tr>
                        <th>CÓDIGO</th>
                        <th>ASESORA</th>
                        <th>DOMINGO <br> <span id="fecha-domingo"></span></th>
                        <th>LUNES <br> <span id="fecha-lunes"></span></th>
                        <th>MARTES <br> <span id="fecha-martes"></span></th>
                        <th>MIÉRCOLES <br> <span id="fecha-miercoles"></span></th>
                        <th>JUEVES <br> <span id="fecha-jueves"></span></th>
                        <th>VIERNES <br> <span id="fecha-viernes"></span></th>
                        <th>SÁBADO <br> <span id="fecha-sabado"></span></th>
                        <th>HORAS DE LEY</th>
                        <th>HORAS DE ALMUERZO</th>
                        <th>HORAS EXTRAS</th>
                        <th>TOTAL HORAS TRABAJADAS</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Contenido de empleados se carga dinámicamente -->
                </tbody>
                <tfoot>
                    <!-- Fila de totales por día -->
                    <tr class="bg-light">
                        <td colspan="2"><strong>Totales por día:</strong></td>
                        <td><strong id="total-domingo">0</strong></td>
                        <td><strong id="total-lunes">0</strong></td>
                        <td><strong id="total-martes">0</strong></td>
                        <td><strong id="total-miercoles">0</strong></td>
                        <td><strong id="total-jueves">0</strong></td>
                        <td><strong id="total-viernes">0</strong></td>
                        <td><strong id="total-sabado">0</strong></td>
                        <td colspan="4"></td> <!-- Celdas vacías para horas de ley, almuerzo, extras y total -->
                    </tr>
                    <tr>
                        <td colspan="9"><strong>Total General:</strong></td>
                        <td><strong id="total-ley"></strong></td>
                        <td><strong id="total-horas-almuerzo">0</strong></td>
                        <td><strong id="total-horas-extras">0</strong></td>
                        <td><strong id="total-horas-trabajadas">0</strong></td>

                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary mt-4"><i class="fas fa-save"></i> Guardar cambios</button>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Cargar supervisores al cargar la página
    $.ajax({
        url: 'insert_hours.php?action=get_supervisors',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var supervisorSelect = $('#employee_code');
            supervisorSelect.empty();
            supervisorSelect.append('<option value="" disabled selected>Seleccione un supervisor</option>');
            data.forEach(function(supervisor) {
                supervisorSelect.append('<option value="' + supervisor.SUPERVISOR_ID + '">' + supervisor.SUPERVISOR_ID + ' - ' + supervisor.SUPERVISOR_NAME + '</option>');
            });
        },
        error: function() {
            console.error('Error al cargar supervisores');
        }
    });

    // Cargar tiendas al seleccionar un supervisor
    $('#employee_code').change(function() {
        var supervisorId = $(this).val();
        $.ajax({
            url: 'insert_hours.php?action=get_stores',
            type: 'GET',
            data: { supervisor_id: supervisorId },
            dataType: 'json',
            success: function(data) {
                var storeSelect = $('#store_no');
                storeSelect.empty();
                storeSelect.append('<option value="" disabled selected>Seleccione una tienda</option>');
                data.forEach(function(store) {
                    storeSelect.append('<option value="' + store.STORE_NO + '"> ' + store.STORE_NO + ' </option>');
                });
            },
            error: function() {
                alert('Error al cargar tiendas');
            }
        });
    });

    // Cargar empleados al seleccionar una tienda
    $('#store_no').change(function() {
        var storeNo = $(this).val();
        $.ajax({
            url: 'insert_hours.php?action=get_employees',
            type: 'GET',
            data: { store_no: storeNo },
            dataType: 'json',
            success: function(data) {
                var employeeTable = $('#empleadosTable tbody');
                employeeTable.empty();

                if (data.length === 0) {
                    employeeTable.append('<tr><td colspan="13">No se encontraron empleados para esta tienda.</td></tr>');
                    return;
                }

                data.forEach(function(employee, index) {
                    var employeeRow = `
                        <tr>
                            <td>${employee.EMPL_NAME}<input type="hidden" name="employees[${index}][codigo_emp]" value="${employee.EMPL_NAME}"></td>
                            <td>${employee.FULL_NAME}<input type="hidden" name="employees[${index}][nombre_emp]" value="${employee.FULL_NAME}"></td>

                            <!-- Generar celdas para cada día -->
                            <td>
                                <input type="text" class="form-control hora-input" name="employees[${index}][domingo][hora_in]" placeholder="HH:MM">
                                <input type="text" class="form-control hora-input" name="employees[${index}][domingo][hora_out]" placeholder="HH:MM">
                                <div class="horas-trabajadas" data-dia="domingo"></div>
                            </td>
                            <td>
                                <input type="text" class="form-control hora-input" name="employees[${index}][lunes][hora_in]" placeholder="HH:MM">
                                <input type="text" class="form-control hora-input" name="employees[${index}][lunes][hora_out]" placeholder="HH:MM">
                                <div class="horas-trabajadas" data-dia="lunes"></div>
                            </td>
                            <td>
                                <input type="text" class="form-control hora-input" name="employees[${index}][martes][hora_in]" placeholder="HH:MM">
                                <input type="text" class="form-control hora-input" name="employees[${index}][martes][hora_out]" placeholder="HH:MM">
                                <div class="horas-trabajadas" data-dia="martes"></div>
                            </td>
                            <td>
                                <input type="text" class="form-control hora-input" name="employees[${index}][miercoles][hora_in]" placeholder="HH:MM">
                                <input type="text" class="form-control hora-input" name="employees[${index}][miercoles][hora_out]" placeholder="HH:MM">
                                <div class="horas-trabajadas" data-dia="miercoles"></div>
                            </td>
                            <td>
                                <input type="text" class="form-control hora-input" name="employees[${index}][jueves][hora_in]" placeholder="HH:MM">
                                <input type="text" class="form-control hora-input" name="employees[${index}][jueves][hora_out]" placeholder="HH:MM">
                                <div class="horas-trabajadas" data-dia="jueves"></div>
                            </td>
                            <td>
                                <input type="text" class="form-control hora-input" name="employees[${index}][viernes][hora_in]" placeholder="HH:MM">
                                <input type="text" class="form-control hora-input" name="employees[${index}][viernes][hora_out]" placeholder="HH:MM">
                                <div class="horas-trabajadas" data-dia="viernes"></div>
                            </td>
                            <td>
                                <input type="text" class="form-control hora-input" name="employees[${index}][sabado][hora_in]" placeholder="HH:MM">
                                <input type="text" class="form-control hora-input" name="employees[${index}][sabado][hora_out]" placeholder="HH:MM">
                                <div class="horas-trabajadas" data-dia="sabado"></div>
                            </td>

                            <!-- Horas de ley -->
                            <td><input type="text" class="form-control" name="employees[${index}][hora_ley_s]" value="44" placeholder="Horas de ley"></td>
                            <!-- Horas de almuerzo -->
                            <td><input type="text" class="form-control" name="employees[${index}][hora_alm_s]" value="5" placeholder="Horas de almuerzo">
                            </td>
                            <!-- Horas extras y totales -->
                            <td><input type="text" class="form-control hora-extra" name="employees[${index}][hora_extra_s]" placeholder="Horas extras" readonly></td>
                            <td><input type="text" class="form-control total-horas" name="employees[${index}][hora_tot_s]" placeholder="Total horas" readonly></td>
                        </tr>
                    `;
                    employeeTable.append(employeeRow);
                });

                // Recalcular horas trabajadas al cargar la tabla de empleados
                calcularHorasTrabajadas();
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar empleados:', error);
                console.log(xhr.responseText);  // Mostrar el error en la consola
            }
        });
    });
  // Función para enviar el formulario sin redirigir
$('#form-horarios').submit(function(event) {
    event.preventDefault();  // Prevenir la redirección

    // Serializar los datos del formulario
    var formData = $(this).serialize();

    // Enviar la solicitud AJAX
    $.ajax({
        url: 'insert_hours.php',
        type: 'POST',
        data: formData,
        success: function(response) {
            // Mostrar la alerta de éxito
            alert("Horas de empleados insertadas correctamente.");

            // Opción 1: Recargar la página para limpiar los campos y resetear el formulario
            location.reload(); // Esto recargará la página por completo

             //Opción 2: Limpiar los campos del formulario manualmente
             //$('#form-horarios')[0].reset(); // Descomentar esta línea para limpiar el formulario sin recargar

            // Opción 3: Limpiar solo los campos de texto y time
            // $('#form-horarios input[type="text"], #form-horarios input[type="time"]').val('');

            // Opción 4: Limpiar y recalcular las horas
            // $('#empleadosTable tbody').empty(); // Limpiar la tabla de empleados
            // calcularHorasTrabajadas(); // Recalcular las horas (si tienes una función específica)
        },
        error: function(xhr, status, error) {
            console.error('Error al insertar horas:', error);
            console.log(xhr.responseText);
        }
    });
});

    // Función para calcular el número de la semana con la fecha seleccionada y mostrar las fechas correspondientes
    function actualizarFechasYSemana() {
        var fechaSeleccionada = new Date($('#start-date').val());
        var numeroSemana = getWeekNumber(fechaSeleccionada);
        $('#semana').val(numeroSemana);
        $('#numero_semana').text(numeroSemana);

        // Calcular las fechas correspondientes a cada día
        var diasSemana = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
        for (var i = 0; i < diasSemana.length; i++) {
            var fechaDia = new Date(fechaSeleccionada);
            fechaDia.setDate(fechaSeleccionada.getDate() + i);

            var dia = ('0' + fechaDia.getDate()).slice(-2);
            var mes = ('0' + (fechaDia.getMonth() + 1)).slice(-2);
            var anio = fechaDia.getFullYear();
            var fechaFormateada = `${dia}-${mes}-${anio}`;

            // Mostrar la fecha debajo de cada día
            $('#fecha-' + diasSemana[i]).text(fechaFormateada);
        }
    }

    // Función para obtener el número de la semana
    function getWeekNumber(date) {
        var firstDayOfYear = new Date(date.getFullYear(), 0, 1);
        var pastDaysOfYear = (date - firstDayOfYear) / 86400000;
        return Math.ceil((pastDaysOfYear + firstDayOfYear.getDay() + 1) / 7);
    }

    // Detectar cambios en la fecha de inicio y actualizar semana y fechas de los días
    $('#start-date').on('change', actualizarFechasYSemana);
    // Función para convertir hora en formato HH:MM a minutos
    function convertirHoraAMinutos(hora) {
        if (!hora || hora === "") return 0; // Si hora está vacío o es null, retornar 0
        var partes = hora.split(':');
        return parseInt(partes[0]) * 60 + parseInt(partes[1]);
    }

    // Función para convertir minutos a formato HH:MM
    function convertirMinutosAHoras(minutos) {
        if (isNaN(minutos) || minutos < 0) return "00:00"; // Si los minutos no son válidos, retornar "00:00"
        var horas = Math.floor(minutos / 60);
        var mins = minutos % 60;
        return horas.toString().padStart(2, '0') + ':' + mins.toString().padStart(2, '0');
    }

        // Vincula el evento de cambio a los campos de horas de almuerzo
        $(document).on('change', 'input[name*="[hora_alm_s]"]', function() {
        calcularHorasTrabajadas();  // Recalcula cuando las horas de almuerzo cambian
    });
    // Función para calcular las horas trabajadas por día y empleado
    function calcularHorasTrabajadas() {
        var totalPorDia = {
            domingo: 0,
            lunes: 0,
            martes: 0,
            miercoles: 0,
            jueves: 0,
            viernes: 0,
            sabado: 0
        };

        var totalHorasTrabajadas = 0;
        var totalHorasExtras = 0;
        var totalHorasAlmuerzo = 0;

         // Iterar sobre cada fila de empleado
         $('#empleadosTable tbody tr').each(function() {
            var totalEmpleado = 0;
            var dias = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];

            dias.forEach(function(dia) {
                var horaIn = $(this).find(`input[name*="[${dia}][hora_in]"]`).val();
                var horaOut = $(this).find(`input[name*="[${dia}][hora_out]"]`).val();
                
                if (horaIn && horaOut) {
                    var minutosTrabajados = convertirHoraAMinutos(horaOut) - convertirHoraAMinutos(horaIn);
                    if (minutosTrabajados < 0) minutosTrabajados += 24 * 60;  // Ajuste para cuando hay horas cruzando medianoche
                    
                    var horasTrabajadas = minutosTrabajados / 60;
                    totalEmpleado += horasTrabajadas;
                    totalPorDia[dia] += horasTrabajadas;

                    // Mostrar las horas trabajadas como texto en la celda correspondiente
                    $(this).find(`div[data-dia="${dia}"]`).text(horasTrabajadas.toFixed(2));
                } else {
                    // Si no hay valores válidos, mostrar 0 en la celda correspondiente
                    $(this).find(`div[data-dia="${dia}"]`).text("0");
                }
            }, this);

            // Obtener las horas de almuerzo y ley
            var horasAlmuerzo = parseFloat($(this).find('input[name*="[hora_alm_s]"]').val()) || 0;
            var horasLey = parseFloat($(this).find('input[name*="[hora_ley_s]"]').val()) || 44;

            // Calcular horas netas trabajadas
            var horasNetasTrabajadas = totalEmpleado - horasAlmuerzo;
            var horasExtras = Math.max(horasNetasTrabajadas - horasLey, 0);

            // Restar horas extras del total de horas netas trabajadas
            horasNetasTrabajadas -= horasExtras;

            // Actualizar los valores en la tabla
            $(this).find('.total-horas').val(horasNetasTrabajadas.toFixed(2));
            $(this).find('.hora-extra').val(horasExtras.toFixed(2));
            totalHorasTrabajadas += horasNetasTrabajadas;
            totalHorasExtras += horasExtras;
            totalHorasAlmuerzo += horasAlmuerzo;
        });


        // Actualizar las sumas totales por día en el pie de la tabla
        for (var dia in totalPorDia) {
            $(`#total-${dia}`).text(totalPorDia[dia].toFixed(2));
        }

        // Actualizar los totales generales en el pie de la tabla
        $('#total-horas-trabajadas').text(totalHorasTrabajadas.toFixed(2));
        $('#total-horas-almuerzo').text(totalHorasAlmuerzo.toFixed(2));
        $('#total-horas-extras').text(totalHorasExtras.toFixed(2));
    }

    // Detectar cambios en las horas de entrada o salida y recalcular
    $(document).on('change', '.hora-input', calcularHorasTrabajadas);

    // Convertir entrada de texto a formato HH:00 automáticamente
    $(document).on('change', '.hora-input', function() {
        let value = $(this).val().trim();

        // Si el valor es un número entre 0 y 23, agregar ":00"
        if (/^\d{1,2}$/.test(value) && parseInt(value) >= 0 && parseInt(value) <= 23) {
            $(this).val(value.padStart(2, '0') + ":00"); // Convertir a formato "HH:00"
        }

        // Recalcular después de formatear la entrada
        calcularHorasTrabajadas();
    });
});
</script>
</body>
</html>
