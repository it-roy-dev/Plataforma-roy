<?php
require_once "../Funsiones/global.php";
?>
<nav class="navbar navbar-light bg-light justify-content-center">

    <div class="form-group mx-2">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fas fa-building"></i>
          </span>
        </div>
        <select name="sbs" onchange="">
        <option value="">tiendas...</option>
        <?php echo listadoTienda() ?>
        </select>
      </div>


</html>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Horas</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        input[type='text'], input[type='number'] {
            width: 60px;
            border: 1px solid #ccc;
            padding: 4px;
            box-sizing: border-box;
            text-align: center;
        }
    </style>
    <script>
        function formatTimeInput(input) {
            var value = input.value;
            value = value.replace(/[^\d]/g, '');

            if (value.length > 4) {
                value = value.substr(0, 4);
            }

            input.value = value;
        }

        function finalizeTimeInput(input) {
            var value = input.value;

            if (value.length === 1 || value.length === 2) {
                value += ':00';
            } else if (value.length === 3) {
                value = value.substr(0, 1) + ':' + value.substr(1, 2);
            } else if (value.length === 4) {
                value = value.substr(0, 2) + ':' + value.substr(2, 2);
            }
                // Validar que las horas no sean mayores a 24
            var parts = value.split(':');
            if (parseInt(parts[0]) > 24) {
                alert('La hora no puede ser mayor a 24.');
                input.value = '';
                return;
            } else if (parseInt(parts[0]) === 24 && parseInt(parts[1]) > 0) {
                alert('No puede especificar minutos después de las 24:00 horas.');
                input.value = '24:00';
                return;
            }

            input.value = value;
        }

        function calcularHoras(codigo, dia) {
            var entrada = document.getElementById(`entrada_${codigo}_${dia}`).value;
            var salida = document.getElementById(`salida_${codigo}_${dia}`).value;

            if (entrada.length == 5 && salida.length == 5) {
                var horaEntrada = entrada.split(':');
                var horaSalida = salida.split(':');

                var inicio = parseInt(horaEntrada[0]) * 60 + parseInt(horaEntrada[1]);
                var fin = parseInt(horaSalida[0]) * 60 + parseInt(horaSalida[1]);

                if (fin < inicio) {
                    fin += 1440;
                }

                var horasTrabajadas = (fin - inicio) / 60;
                document.getElementById(`horas_${codigo}_${dia}`).innerText = horasTrabajadas.toFixed(1);
                actualizarTotalHoras(codigo);
            }
        }

        function actualizarTotalHoras(codigo) {
            var total = 0;
            var dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
            dias.forEach(function(dia) {
                var horas = document.getElementById(`horas_${codigo}_${dia}`).innerText;
                if (horas) {
                    total += parseFloat(horas);
                }
            });
            document.getElementById(`totalHoras_${codigo}`).innerText = total.toFixed(2);
        }
    </script>
</head>
<body>

    <h2>Registro de Horas Semanal</h2>
    <form method="POST" action="">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombres</th>
                    <?php
                    $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                    foreach ($dias as $dia) {
                        echo "<th>$dia<br>In</th>";
                        echo "<th>Out</th>";
                        echo "<th>Hrs</th>";
                    }
                    ?>
                    <th>Total Horas</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //  método para obtener empleados
                //$empleados = Tiendas(); //  obtener los empleados

                /* foreach ($empleados as $empleado) {
                    echo "<tr>";
                    echo "<td>{$empleado['codigo']}</td>";
                    echo "<td>{$empleado['nombre']}</td>";

                    foreach ($dias as $dia) {
                        $inputIdBase = "{$empleado['codigo']}_$dia";
                        echo "<td>
                            <input type='text' id='entrada_$inputIdBase' oninput='formatTimeInput(this)' onblur='finalizeTimeInput(this); calcularHoras({$empleado['codigo']}, \"$dia\")' placeholder='HHMM'>
                            </td>";
                        echo "<td>
                            <input type='text' id='salida_$inputIdBase' oninput='formatTimeInput(this)' onblur='finalizeTimeInput(this); calcularHoras({$empleado['codigo']}, \"$dia\")' placeholder='HHMM'>
                            </td>";
                        echo "<td><span id='horas_$inputIdBase'>0</span></td>";
                    }

                    echo "<td><span id='totalHoras_{$empleado['codigo']}'>0</span></td>";
                    echo "</tr>";
                } */
                ?>
            </tbody>
        </table>
    </form>
</body>
</html>

