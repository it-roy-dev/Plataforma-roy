
$(document).ready(function () {
  var fechas;

  fechas = $('#rangoFecha').val();

  tblDepositos = $('#tblDepositoVenta').DataTable({
    "responsive": true,
    "autoWidth": false,
    "language": {
      "sProcessing": "Procesando...",
      "sLengthMenu": "Mostrar _MENU_ registros",
      "sZeroRecords": "No se encontraron resultados",
      "sEmptyTable": "Ningún dato disponible en esta tabla =(",
      "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
      "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
      "sInfoPostFix": "",
      "sSearch": "Buscar:",
      "sUrl": "",
      "sInfoThousands": ",",
      "sLoadingRecords": "Cargando...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Último",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      }
    },
    "ajax": {
      "url": "../Funsiones/digitacion/depositoventa.php",
      "method": 'POST',
      "data": {fechas: fechas },
      "dataSrc": ""
    },
    "columns": [
      { "data": 0 },
      { "data": 1 },
      { "data": 2 },
      { "data": 3,  "render": $.fn.dataTable.render.number(',', '.', 2, 'Q ') },
      { "data": 4,  "render": $.fn.dataTable.render.number(',', '.', 2, 'Q ')},
      { "data": 5, "render": $.fn.dataTable.render.number(',', '.', 2, 'Q ') },
      { "data": 6,
          "className": "text-center",
          "render":
            function (data) {
              var status = '';
              if (data == 0) {
                status = '<span><i class="fas fa-check text-success fa-lg"></i></span>';
              }
              else {
                status = '<span><i class="fas fa-times text-danger fa-lg"></i></span>';
              }
              return status;
            }
      }
    ]
  });


});








