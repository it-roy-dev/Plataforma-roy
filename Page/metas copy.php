<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresar Horas de Empleados</title>
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Ingresar Horas de Entrada y Salida</h2>
        <form action="insert_hours.php" method="POST">
            <!-- Información de la tienda -->
            <div class="form-group">
                <label for="store_no">Número de Tienda</label>
                <input type="number" name="store_no" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" name="fecha" class="form-control" required>
            </div>

            <!-- Tabla de empleados y horas -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Hora de Entrada</th>
                        <th>Hora de Salida</th>
                    </tr>
                </thead>
                <tbody id="employee-table">
                    <!-- Fila de empleado 1 (se pueden agregar más dinámicamente con JavaScript) -->
                    <tr>
                        <td>
                            <input type="text" name="empleados[0][codigo]" placeholder="Código de Empleado" class="form-control" required>
                        </td>
                        <td>
                            <input type="time" name="empleados[0][hora_in]" class="form-control" required>
                        </td>
                        <td>
                            <input type="time" name="empleados[0][hora_out]" class="form-control" required>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Botón para agregar otro empleado -->
            <button type="button" id="add-employee" class="btn btn-secondary mb-3">Agregar Empleado</button>

            <!-- Botón para enviar el formulario -->
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>

    <script src="public/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para agregar más filas de empleados
        let employeeCount = 1;
        document.getElementById('add-employee').addEventListener('click', function() {
            const table = document.getElementById('employee-table');
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>
                    <input type="text" name="empleados[${employeeCount}][codigo]" placeholder="Código de Empleado" class="form-control" required>
                </td>
                <td>
                    <input type="time" name="empleados[${employeeCount}][hora_in]" class="form-control" required>
                </td>
                <td>
                    <input type="time" name="empleados[${employeeCount}][hora_out]" class="form-control" required>
                </td>
            `;

            table.appendChild(row);
            employeeCount++;
        });
    </script>
</body>
</html>
