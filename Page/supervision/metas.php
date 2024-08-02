<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Horas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Registro de Horas Semanal</h2>
    <form method="POST" action="">
        <label for="fecha_inicio">Fecha de Inicio de Semana:</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" required>
        <br><br>
        <table>
            <tr>
                <th>Día</th>
                <th>Fecha</th>
                <th>Hora de Entrada</th>
                <th>Hora de Salida</th>
                <th>Horas Trabajadas</th>
            </tr>
            <?php
                $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                $totalHoras = 0;

                if (isset($_POST['fecha_inicio'])) {
                    $fechaInicio = $_POST['fecha_inicio'];
                } else {
                    $fechaInicio = date('Y-m-d');
                }

                foreach ($dias as $index => $dia) {
                    $fechaDia = date('Y-m-d', strtotime("$fechaInicio +$index days"));
                    echo "<tr>";
                    echo "<td>$dia</td>";
                    echo "<td>$fechaDia</td>";
                    echo "<td><input type='time' name='entrada[]' required></td>";
                    echo "<td><input type='time' name='salida[]' required></td>";
                    echo "<td id='horas_$index'>0</td>";
                    echo "</tr>";
                }
            ?>
            <tr>
                <td colspan="4"><strong>Total de Horas</strong></td>
                <td id="totalHoras">0</td>
            </tr>
        </table>
        <br>
        <button type="submit" name="calcular">Calcular Total</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $entradas = $_POST['entrada'];
        $salidas = $_POST['salida'];

        echo '<script>';
        foreach ($entradas as $index => $entrada) {
            $salida = $salidas[$index];
            $horasTrabajadas = (strtotime($salida) - strtotime($entrada)) / 3600; // convertir a horas

            // Verificar si el cálculo de horas es negativo (entrada después de salida)
            if ($horasTrabajadas < 0) {
                $horasTrabajadas += 24; // Ajuste para casos donde la salida es después de medianoche
            }

            $totalHoras += $horasTrabajadas;
            echo "document.getElementById('horas_$index').textContent = $horasTrabajadas.toFixed(2);";
        }
        echo "document.getElementById('totalHoras').textContent = $totalHoras.toFixed(2);";
        echo '</script>';
    }
    ?>
</body>
</html>
