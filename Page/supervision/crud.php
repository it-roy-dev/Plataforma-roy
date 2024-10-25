<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .pagination { justify-content: center; }
    .active { background-color: #007bff; color: white; }
  </style>
</head>
<body>
  <div class="container mt-5">

    <div class="form-group">
      <input type="text" id="searchInput" class="form-control" placeholder="Buscar en todos los campos...">
    </div>

    <table id="tblVendedores" class="table table-bordered table-hover">
      <thead class="thead-dark">
        <tr>
          <th>Tienda No.</th>
          <th>Código Vendedor</th>
          <th>Nombre</th>
          <th>Puesto</th>
          <th>Activo</th>
          <th>Fecha Ingreso</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

    <nav>
      <ul class="pagination"></ul>
    </nav>
  </div>

  <script>
    (function () {
      let vendedores = [];
      let rowsPerPage = 10;
      let currentPage = 1;

      $(document).ready(function () {
        cargarVendedores();

        $('#searchInput').on('input', function () {
          const searchValue = $(this).val().toLowerCase();
          const filteredData = vendedores.filter(vendedor =>
            Object.values(vendedor).some(value =>
              value.toString().toLowerCase().includes(searchValue)
            )
          );
          renderTable(filteredData);
          setupPagination(filteredData);
        });

        function cargarVendedores() {
          $.ajax({
            url: './supervision/crudVendedores.php?action=get_employees',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
              vendedores = data;
              renderTable(vendedores);
              setupPagination(vendedores);
            },
            error: function (xhr, status, error) {
              console.error('Error al cargar los datos:', xhr.responseText, error);
              Swal.fire('Error', 'No se pudieron cargar los datos.', 'error');
            }
          });
        }

        function renderTable(data) {
          const tbody = $('#tblVendedores tbody');
          tbody.empty();
          const start = (currentPage - 1) * rowsPerPage;
          const end = start + rowsPerPage;
          const pageData = data.slice(start, end);

          pageData.forEach(vendedor => {
            const row = `
              <tr>
                <td>${vendedor.TIENDA_NO}</td>
                <td>${vendedor.CODIGO_VENDEDOR}</td>
                <td>${vendedor.NOMBRE}</td>
                <td>${vendedor.PUESTO}</td>
                <td>${vendedor.ACTIVO}</td>
                <td>${vendedor.FECHA_INGRESO}</td>
                <td>
                  <button class="btn btn-primary btn-sm btnEditar" data-id="${vendedor.CODIGO_VENDEDOR}">Editar</button>
                  <button class="btn btn-danger btn-sm btnDesactivar" data-id="${vendedor.CODIGO_VENDEDOR}">Desactivar</button>
                </td>
              </tr>`;
            tbody.append(row);
          });
        }

        function setupPagination(data) {
          const totalPages = Math.ceil(data.length / rowsPerPage);
          const pagination = $('.pagination');
          pagination.empty();

          for (let i = 1; i <= totalPages; i++) {
            const pageItem = `<li class="page-item ${i === currentPage ? 'active' : ''}">
                                <a class="page-link" href="#">${i}</a>
                              </li>`;
            pagination.append(pageItem);
          }

          $('.pagination li').click(function () {
            currentPage = parseInt($(this).text());
            $('.pagination li').removeClass('active');
            $(this).addClass('active');
            renderTable(data);
          });
        }

        $(document).on('click', '.btnEditar', function () {
          const id = $(this).data('id');
          const vendedor = vendedores.find(v => v.CODIGO_VENDEDOR == id);
          Swal.fire({
            title: 'Editar Vendedor',
            html: `
              Numero tienda:<input type="text" id="tienda" class="swal2-input" placeholder="Número de tienda" value="${vendedor.TIENDA_NO}">
             Código de Empleado: <input type="number" id="codigo_vendedor" class="swal2-input" placeholder="Número de tienda" value="${vendedor.CODIGO_VENDEDOR}">
             Nombre de Empleado:<input type="text" id="nombre" class="swal2-input" placeholder="Número de tienda" value="${vendedor.NOMBRE}">
              Puesto de Empleado:<input type="text" id="puesto" class="swal2-input" placeholder="Puesto" value="${vendedor.PUESTO}">
              Fecha de Ingreso:<input type="date" id="fecha_ingreso" class="swal2-input" placeholder="Número de tienda" value="${vendedor.FECHA_INGRESO}">

            `,
            focusConfirm: false,
            preConfirm: () => {
              const tienda = Swal.getPopup().querySelector('#tienda').value;
              const puesto = Swal.getPopup().querySelector('#puesto').value;
              if (!tienda || !puesto) {
                Swal.showValidationMessage('Por favor ingrese ambos valores');
              }
              return { tienda, puesto };
            }
          }).then((result) => {
            if (result.isConfirmed) {
              actualizarVendedor(id, result.value);
            }
          });
        });

        function actualizarVendedor(id, { tienda, puesto }) {
          $.ajax({
            url: './supervision/crudVendedores.php?action=update_employee',
            type: 'POST',
            data: {
              codigo_vendedor: id,
              tienda_no: tienda,
              puesto: puesto,
              activo: 1
            },
            success: function (response) {
              if (response === 'true') {
                Swal.fire('Actualizado', 'Vendedor actualizado con éxito.', 'success');
                cargarVendedores();
              } else {
                Swal.fire('Error', 'No se pudo actualizar el vendedor.', 'error');
              }
            }
          });
        }

        $(document).on('click', '.btnDesactivar', function () {
          const id = $(this).data('id');
          Swal.fire({
            title: '¿Estás seguro?',
            text: 'El vendedor será desactivado.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, desactivar'
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                url: './supervision/crudVendedores.php?action=update_employee',
                type: 'POST',
                data: { codigo_vendedor: id, activo: 0 },
                success: function (response) {
                  if (response === 'true') {
                    Swal.fire('Desactivado', 'El vendedor ha sido desactivado.', 'success');
                    cargarVendedores();
                  } else {
                    Swal.fire('Error', 'No se pudo desactivar al vendedor.', 'error');
                  }
                }
              });
            }
          });
        });
      });
    })();
  </script>
</body>
</html>
