<?php
require_once "../Funsiones/consulta.php";

require_once "../Funsiones/global.php";

$query = "SELECT nombre_afiliado, apellido, dpi_afiliado, nit_de_afiliado, telefono_casa_afiliado, celular_afiliado, direccion_mercaderia
                FROM ROY_CLIENTES 
                WHERE 1=1";
$resultado = consultaOracleRpro(3, $query);


?>


    <div class="form-group mb-2">
                    <label for="nombre" class="sr-only">Nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ingrese nombre">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="apellido" class="sr-only">Apellido</label>
                    <input type="text" name="apellido" id="apellido" class="form-control" placeholder="Ingrese apellido">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Buscar</button>


    <div class="container-fluid" id="Tablas">

</div>
<?php if (!empty($resultados)): ?>
            <h3>Resultados:</h3>
            <table class="table table-striped ">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DPI</th>
                        <th>NIT</th>
                        <th>Telefono Casa</th>
                        <th>Telefono Celular </th>
                        <th>Dirección</th>
<!--                         <th>CREDITO</th>
                        <th>SALDO A FAVOR</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $cliente): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cliente['NOMBRE_AFILIADO']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['APELLIDO']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['DPI_AFILIADO']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['NIT_DE_AFILIADO']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['TELEFONO_CASA_AFILIADO']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['CELULAR_AFILIADO']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['DIRECCION_MERCADERIA']); ?></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <p>No se encontraron resultados.</p>
        <?php endif; ?>
    </div>
</body>
<footer class="bg-body-tertiary text-center">
  <p><strong>DIRECCIÓN:</strong><br>
  12 calle 0-47 zona 9, Edificio La Premiere Local 102, Guatemala.</p>

  <p><strong>TELÉFONO:</strong><br>
  +(502) 2324 3500</p>

  <p><strong>EMAIL:</strong><br>
  <a href="mailto:ventas.online@calzadoroy.com">ventas.online@calzadoroy.com</a></p>
</footer>

