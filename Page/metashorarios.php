<!DOCTYPE html>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignación de Metas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center"><i class="fas fa-user-friends"></i> Metas</h3>
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

<div class="mt-4">
<table id="empleadosTable" class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>CÓDIGO</th>
            <th>ASESORA</th>
            <th>PUESTO</th>
            <th>PORCENTAJE</th>
            <th>MONTO SEMANAL</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">Total Monto Quetzales:</th>
            <th id="totalMetas"></th>
        </tr>
    </tfoot>
</table>

</div>

<script>
$(document).ready(function() {
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

    // Funcionalidad para editar metas
    $('#empleadosTable').on('click', '.edit-meta', function() {
        var $row = $(this).closest('tr');
        var $meta = $row.find('.meta');
        $meta.attr('contenteditable', 'true').focus(); // Hacer el campo editable
        $(this).siblings('.save-meta').show(); // Mostrar botón guardar
        $(this).hide(); // Ocultar botón editar
    });
// Función para recalcular y actualizar el total de metas
function updateTotalMetas() {
    var totalMetas = 0;
    $('#empleadosTable tbody tr').each(function() {
        var metaValue = parseFloat($(this).find('.meta').text());
        if (!isNaN(metaValue)) {
            totalMetas += metaValue;
        }
    });
    $('#totalMetas').text(`Q ${totalMetas.toFixed(2)}`);
}

// Funcionalidad para guardar metas
$('#empleadosTable').on('click', '.save-meta', function() {
    var $row = $(this).closest('tr');
    var storeNo = $('#store_no').val();
    var employeeCode = $row.find('td:first').text();
    var meta = $row.find('.meta').text();
    var weekNumber = $('#week_number').val();
    var tipo = $row.find('td:eq(2)').text(); // Asumiendo que 'PUESTO' es la tercera columna
    var year = $('#year').val();

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
            anio: year
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

});

</script>
</body>
</html>
