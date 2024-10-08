<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Horarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center">Reporte de Horarios por Tienda</h3>
    <form id="reportForm">
        <div class="form-group">
            <label for="store_no">Seleccione Tienda:</label>
            <select id="store_no" name="store_no" class="form-control" required>
                <!-- Las tiendas se cargarán dinámicamente -->
            </select>
        </div>
        <div class="form-group">
            <label for="week_no">Número de Semana:</label>
            <input type="number" id="week_no" name="week_no" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Generar Reporte</button>
    </form>
    <hr>
    <table id="reportTable" class="table table-bordered">
        <thead>
            <tr>
                <th>Código Empleado</th>
                <th>Nombre Empleado</th>
                <th>Día</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Horas Totales</th>
            </tr>
        </thead>
        <tbody>
            <!-- Los datos del reporte se cargarán aquí -->
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#reportTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Reporte de Horarios',
                className: 'btn btn-success',
            }
        ]
    });

    $('#reportForm').submit(function(e) {
        e.preventDefault();
        var storeNo = $('#store_no').val();
        var weekNo = $('#week_no').val();
        $.ajax({
            url: 'fetch_report.php',
            type: 'POST',
            data: {store_no: storeNo, week_no: weekNo},
            success: function(data) {
                table.clear();
                if (data.length > 0) {
                    table.rows.add(data);
                }
                table.draw();
            },
            error: function() {
                alert('Error al cargar el reporte');
            }
        });
    });

    // Cargar tiendas aquí
});
</script>
</body>
</html>
