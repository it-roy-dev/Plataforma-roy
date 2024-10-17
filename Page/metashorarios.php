<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center"><i class="fas fa-chart-area"></i> Asignacion de Metas Semanal</h3>
    <form id="form-horarios" method="POST">
        <div class="form-group">
            <label for="employee_code"><i class="fas fa-user"></i> Seleccione Supervisor:</label>
            <select id="employee_code" name="employee_code" class="form-control" required>
                <option value="" disabled selected>Seleccione un supervisor</option>
            </select>
        </div>
        <div class="form-group">
            <label for="store_no"><i class="fas fa-store"></i> Seleccione Tienda:</label>
            <select id="store_no" name="store_no" class="form-control" required>
                <option value="" disabled selected>Seleccione una tienda</option>
            </select>
        </div>
        <div class="form-group">
            <label for="year"><i class="fas fa-calendar-alt"></i> Ingrese el año:</label>
            <input type="number" id="year" name="year" class="form-control" min="2000" max="3000" value="2024" required>
        </div>
        <div class="form-group">
            <label for="week_number"><i class="fas fa-calendar-week"></i> Ingrese el número de semana:</label>
            <input type="number" id="week_number" name="week_number" class="form-control" min="1" max="52" placeholder="Número de semana" required>
        </div>
    </form>
</div>
<div class="container mt-5">
    <h3 id="title-meta" class="text-center font-weight-bold text-primary">Metas</h3>
</div>
<div class="mt-4">
<table id="empleadosTable" class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>CÓDIGO</th>
            <th>ASESORA</th>
            <th>PUESTO</th>
            <th>HORAS SEMANA</th>
            <th>PORCENTAJE</th>
            <th>MONTO SEMANAL</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
    <tr>
    <th colspan="4">Total Meta Semana:</th>
    <th id="percentageTotal"></th> 
    <th id="totalMetas"></th>
    <td><button id="saveAllMetas" class="btn btn-success">Guardar Meta</button></td>
    </tr>
    </tfoot>
</table>
</div>
<script>
    var storeMeta = 0; // Almacena la meta de la tienda

    function getCurrentWeekNumber() {
    const now = new Date();
    const startOfYear = new Date(now.getFullYear(), 0, 1);
    const pastDaysOfYear = (now - startOfYear) / 86400000;
    return Math.ceil((pastDaysOfYear + startOfYear.getDay() + 1) / 7);
}
$(document).ready(function() {
    const currentWeekNumber = getCurrentWeekNumber();
    $('#week_number').val(currentWeekNumber);
    $.ajax({
        url: 'backendmetas.php?action=get_supervisors',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var supervisorSelect = $('#employee_code');
            supervisorSelect.empty();
            supervisorSelect.append('<option value="" disabled selected>Seleccione un supervisor</option>');
            data.forEach(function(supervisor) {
                supervisorSelect.append(new Option(supervisor.SUPERVISOR_NAME, supervisor.SUPERVISOR_ID));
            });
        },
        error: function() {
            console.error('Error al cargar supervisores');
        }
    });

    $('#employee_code').change(function() {
        var supervisorId = $(this).val();
        $.ajax({
            url: 'backendmetas.php?action=get_stores',
            type: 'GET',
            data: { supervisor_id: supervisorId },
            dataType: 'json',
            success: function(data) {
                var storeSelect = $('#store_no');
                storeSelect.empty();
                storeSelect.append('<option value="" disabled selected>Seleccione una tienda</option>');
                data.forEach(function(store) {
                    storeSelect.append(new Option(store.STORE_NAME, store.STORE_NO));
                });
            },
            error: function(xhr) {
                alert('Error al cargar tiendas: ' + xhr.responseText);
            }
        });
    });

    $('#store_no').change(function() {
        var storeNo = $(this).val();
        var weekNumber = $('#week_number').val();
        var year = $('#year').val();
        if (!storeNo || !year) {
            alert('Por favor, complete todos los campos necesarios.');
            return;
        }

        $.ajax({
            url: 'backendmetas.php?action=get_employees',
            type: 'GET',
            data: { store_no: storeNo, semana: weekNumber, anio: year },
            dataType: 'json',
            success: function(data) {
                var employeeTable = $('#empleadosTable tbody');
                employeeTable.empty();
                var totalMetas = 0;
                data.forEach(function(employee) {
                    totalMetas += parseFloat(employee.META || 0);
                    var row = `<tr>
                        <td>${employee.EMPL_NAME}</td>
                        <td>${employee.FULL_NAME}</td>
                        <td>${employee.TIPO_PUESTO}</td>
                        <td contenteditable="true" class="hours">${employee.HORA || 0}</td>
                        <td>${(100 / data.length).toFixed(2)}%</td>
                        <td contenteditable="false" class="meta">${parseFloat(employee.META || 0).toFixed(2)}</td>
                        <td>
                            <button class="btn btn-primary edit-meta">Editar</button>
                            <button class="btn btn-success save-meta" style="display:none;">Guardar</button>
                        </td>
                    </tr>`;
                    employeeTable.append(row);
                });
                $('#totalMetas').text(`Q ${totalMetas.toFixed(2)}`);
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar empleados y metas:', error);
            }
        });
    });
    function updateTitle(storeNo, weekNumber, year) {
        $.ajax({
            url: 'backendmetas.php?action=tile-metas',
            type: 'GET',
            data: {
                t: storeNo,
                s: weekNumber,
                a: year
            },
            dataType: 'json',
            success: function(response) {
                if (response.meta) {
                    storeMeta = parseFloat(response.meta); // Actualiza la meta global de la tienda
                    $('#title-meta').html(`Tienda no: ${storeNo}<br><small class="h4 text-primary font-weight-bold text-center">| Año: ${year} | Semana: ${weekNumber} | Meta tienda: Q ${storeMeta.toFixed(2)} |</small>`);
                   // updateTotalMetas();  // Actualiza el total de metas inmediatamente después de actualizar storeMeta
                } else {
                    console.error('Error al cargar metas de la tienda:', response.error);
                    $('#title-meta').html("Error al cargar datos de la tienda");
                }
            },
            error: function(xhr) {
                console.error('Error al conectar con el backend para metas de tienda:', xhr.responseText);
                $('#title-meta').html("Error de conexión");
            }
        });
    }
  
        $('#store_no, #week_number, #year').change(function() {
            if ($('#store_no').val() && $('#week_number').val() && $('#year').val()) {
                updateTitle($('#store_no').val(), $('#week_number').val(), $('#year').val());
            }
        });



    // Funcion editar metas
    $('#empleadosTable').on('click', '.edit-meta', function() {
        var $row = $(this).closest('tr');
        var $meta = $row.find('.meta');
        $meta.attr('contenteditable', 'true').focus(); // Hacer el campo editable
        $(this).siblings('.save-meta').show(); // Mostrar botón guardar
        $(this).hide(); // Ocultar botón editar
    });
        // Detectar cambios en tiempo real en los campos de meta y recalcular el total
        $('#empleadosTable').on('input', '.meta', function() {
            updateTotalMetas(); // Actualizar el total de metas mientras el usuario edita
        });

        // Funcion editar metas
    $('#empleadosTable').on('click', '.edit-meta', function() {
        var $row = $(this).closest('tr');
        var $meta = $row.find('.meta');
        $meta.attr('contenteditable', 'true').focus(); // Hacer el campo editable
        $(this).siblings('.save-meta').show(); // Mostrar botón guardar
        $(this).hide(); // Ocultar botón editar
    });

    // Funcion guardar metas
    $('#empleadosTable').on('click', '.save-meta', function() {
        var $row = $(this).closest('tr');
        var storeNo = $('#store_no').val();
        var employeeCode = $row.find('td:first').text();
        var meta = $row.find('.meta').text();
        var weekNumber = $('#week_number').val();
        var tipo = $row.find('td:eq(2)').text(); 
        var year = $('#year').val();
        var hours = $row.find('.hours').text(); 


        // Llamar al backend para actualizar la base de datos
        $.ajax({
            url: 'backendmetas.php?action=update_meta',
            type: 'POST',
            data: {
                store_no: storeNo,
                employee_name: employeeCode,
                meta: meta,
                semana: weekNumber,
                tipo: tipo,
                anio: year,
                hora: hours  
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('Meta actualizada correctamente');
                    $row.find('.edit-meta').show(); // Mostrar botón editar
                    $row.find('.save-meta').hide(); // Ocultar botón guardar
                    $row.find('.meta').attr('contenteditable', 'false'); // Deshabilitar edición
                    updateTotalMetas(); // Recalcular y actualizar el total de metas
                } else {
                    alert('Error al actualizar la meta: ' + response.error);
                }
            },
            error: function(xhr) {
                alert('Error al conectar con el backend: ' + xhr.responseText);
            }
        });
    });

    $('#saveAllMetas').click(function() {
    var storeNo = $('#store_no').val();
    var weekNumber = $('#week_number').val();
    var year = $('#year').val();
    var metas = [];

    // Recorrer cada fila para recopilar las metas
    $('#empleadosTable tbody tr').each(function() {
        var employeeCode = $(this).find('td:first').text();
        var meta = $(this).find('.meta').text();
        var hours = $(this).find('.hours').text(); // Asegura recolectar las horas
        var tipo = $(this).find('td:eq(2)').text(); 
        metas.push({
            employee_name: employeeCode,
            meta: meta,
            tipo: tipo,
            hours: hours  // Incluye las horas en el objeto
        });
    });

    // Enviar los datos al backend
        $.ajax({
        url: 'backendmetas.php?action=save_all_metas',
        type: 'POST',
        contentType: 'application/json', // Asegurando que los datos se envían en formato JSON
        data: JSON.stringify({
            store_no: storeNo,
            semana: weekNumber,
            anio: year,
            metas: metas  // Envía el array completo de metas
        }),
        success: function(response) {
        if (response.success) {
            alert('Todas las metas han sido guardadas correctamente');
        } else if (response.error) {
            alert('Error al guardar las metas: ' + response.error);
        } else {
            alert('Las metas de la semana de la tienda han sido guardada correctamente');
        }
        },
        error: function(xhr, status, error) {
        alert('Error al conectar con el backend: ' + error);
         }
    });
    });
    function updateTotalMetas() {
    var totalMetas = 0;
    $('#empleadosTable tbody tr').each(function() {
        var metaValue = parseFloat($(this).find('.meta').text());
        if (!isNaN(metaValue)) {
            totalMetas += metaValue;
        }
    });

    var formattedTotalMetas = parseFloat(totalMetas.toFixed(2));
    var formattedStoreMeta = parseFloat(storeMeta.toFixed(2));
    $('#totalMetas').text(`Q ${formattedTotalMetas}`);

    // Compara y cambia el color del texto según si coincide con la meta de la tienda
    if (formattedTotalMetas === formattedStoreMeta) {
        $('#totalMetas').css('color', 'green');
    } else {
        $('#totalMetas').css('color', 'red');
    }

    // Calcula el porcentaje del total de metas respecto a la meta global
    var percentageOfTotal = (formattedTotalMetas / formattedStoreMeta) * 100;
    $('#percentageTotal').text(`${percentageOfTotal.toFixed(2)}%`);
}


});
</script>
</body>
</html>
