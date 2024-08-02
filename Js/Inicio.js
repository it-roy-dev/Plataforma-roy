$(document).ready(function () {

  $('section.content').load('tablero.php');
  $('#titulo').text('Tablero');

  $('.modulo').click(function () {
    $('.modulo').removeClass('active');
    $(this).addClass('active');
  });

  $('.opcion').click(function () {
    $('.opcion').removeClass('active');
    $(this).addClass('active');
  });

    //----------------------------------------- MODULOS DEL SIDEBAR -----------------------------------------

    // TIENDAS

 
    $('#8-71 a').on('click',function(){
      $('section.content').load('filtro_t.php');
      $('#titulo').text('Resumen de desempeño semanal');
      sbs = $('#subsidiaria').val();
      pagina = 'rds';
    });

    $('#8-72 a').on('click',function(){
      $('section.content').load('filtro_t.php');
      $('#titulo').text('Analisis Ventas X Vendedor');
      sbs = $('#subsidiaria').val();
      pagina = 'avxv';
    });

    $('#8-73 a').on('click', function () {
      $('section.content').load('tienda/depositos.php');
      $('#titulo').text('Ingreso de cortes');
    });

    



    // SUPERVISORES


    $('#9-81 a').on('click',function(){
      $('section.content').load('filtro_s.php');
      $('#titulo').text('Ventas 14 Horas Tiendas');
      sbs = $('#subsidiaria').val();
      pagina = 'vts14';
    });

    $('#9-82 a').on('click',function(){
      $('section.content').load('filtro_s.php');
      $('#titulo').text('Resumen de desempeño semanal 2Vendedor');
      sbs = $('#subsidiaria').val();
      pagina = 'rdst2';
    });
    
    $('#9-83 a').on('click',function(){
      $('section.content').load('filtro_s.php');
      $('#titulo').text('Resumen de desempeño semanal Region');
      sbs = $('#subsidiaria').val();
      pagina = 'rdsr';
    });

    $('#9-84 a').on('click', function () {
      $('section.content').load('filtro_s.php');
      $('#titulo').text('Trimestral tienda');
      sbs = $('#subsidiaria').val();
      pagina = 'rtt';
    });


    $('#9-85 a').on('click',function(){
      $('section.content').load('filtro_s.php');
      $('#titulo').text('Resumen de desempeño semanal Cadena');
      sbs = $('#subsidiaria').val();
      pagina = 'rtmt';
    });

    
    $('#9-86 a').on('click',function(){
      $('section.content').load('supervision/metas.php');
      $('#titulo').text('asignacion metas');
      // sbs = $('#subsidiaria').val();
      pagina = 'metas';
    });

    $('#9-88 a').on('click',function(){
      $('section.content').load('supervision/crudUsuario.php');
      $('#titulo').text('Crud Usuarios');
  });



    // DIGITACION

    $('#18-171 a').on('click', function () {
        Swal.fire({
            icon: 'question',
            title: 'Rango fecha a visualizar',
            html: '<input readonly class="form-control fecha1" >',
            confirmButtonText: 'Cargar datos',
            showCancelButton: false,
            onOpen: function () {
                $('.fecha1').daterangepicker({
                    "showDropdowns": true,
                    "showISOWeekNumbers": true,
                    "opens": "center",
                    "autoApply": true,
                    "locale": {
                        "format": "DD-MM-YYYY",
                        "separator": " a ",
                        "weekLabel": "Sm",
                        "daysOfWeek": [
                            "Do",
                            "Lu",
                            "Ma",
                            "Mi",
                            "Ju",
                            "Vi",
                            "Sa"
                        ],
                        "monthNames": [
                            "Enero",
                            "Febrero",
                            "Marzo",
                            "Abril",
                            "Mayo",
                            "Junio",
                            "Julio",
                            "Agosto",
                            "Septiembre",
                            "Octubre",
                            "Noviembre",
                            "Deciembre"
                        ],
                        "firstDay": 0
                    },
                });
            }
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url:'digitacion/crudDeposito.php',
                    type:'POST',
                    datatype:'json',
                    data:{fechas:$('.fecha1').val()},
                    success:function(x){
                        $('section.content').html(x);
                        $('#titulo').text('Crud Depositos');
                    }
                })
            }
        });
    });


  $('#18-172 a').on('click', function () {
    Swal.fire({
      icon: 'question',
      title: 'Rango fecha a visualizar',
      html: '<input readonly class="form-control fecha1" >',
      confirmButtonText: 'Cargar datos',
      showCancelButton: false,
      onOpen: function () {
        $('.fecha1').daterangepicker({
          "showDropdowns": true,
          "showISOWeekNumbers": true,
          "autoApply": true,
          "opens": "center",
          "locale": {
            "format": "DD-MM-YYYY",
            "separator": " a ",
            "weekLabel": "Sm",

            "daysOfWeek": [
              "Do",
              "Lu",
              "Ma",
              "Mi",
              "Ju",
              "Vi",
              "Sa"
            ],
            "monthNames": [
              "Enero",
              "Febrero",
              "Marzo",
              "Abril",
              "Mayo",
              "Junio",
              "Julio",
              "Agosto",
              "Septiembre",
              "Octubre",
              "Noviembre",
              "Deciembre"
            ],
            "firstDay": 0
          },
        });
      }
    }).then(function (result) {
      if (result.value) {
        $.ajax({
          url: 'digitacion/depositoventa.php',
          type: 'POST',
          datatype: 'json',
          data: { fechas: $('.fecha1').val() },
          success: function (x) {
            $('section.content').html(x);
            $('#titulo').text('Venta vrs depósito');
          }
        })
      }
    });
  });

    // OPERACIONES TIENDAS

    $('#4-1 a').on('click',function(){
        Swal.fire({
            icon:'question',
            title: 'Datos bono estrella',
            input: 'text',
            inputPlaceholder:'Semanas separadas por coma',
            showCancelButton: false,
            preConfirm:(inputValue) =>{
               if(!inputValue){
                   Swal.showValidationMessage(
                       `Necesita escribir las semanas a generar`
                   )
               }
               else{
                   $.ajax({
                       url: 'operacionesTienda/bonoEstrella.php',
                       type: 'POST',
                       datatype: 'json',
                       data: { semanas: inputValue},
                       success: function (x) {
                           $('section.content').html(x);
                           $('#titulo').text('Reporte bono estrella');
                       }
                   })
               }
            }
        })


    });

    // INFORMATICA

    $('#3-21 a').on('click',function(){
        $('section.content').load('informatica/crudUsuario.php');
        $('#titulo').text('Crud Usuarios');
    });


    $('.btnchatroy').on('click',function(){
      $('.chatroy').addClass('collapsed-card');
    });


    // CONTABILIDAD TIENDAS

  $('#1-1 a').on('click', function () {
    Swal.fire({
      icon: 'question',
      title: 'Rango fecha a visualizar',
      html: '<input readonly class="form-control fecha1" >',
      confirmButtonText: 'Cargar datos',
      showCancelButton: false,
      onOpen: function () {
        $('.fecha1').daterangepicker({
          "showDropdowns": true,
          "showWeekNumbers": false,
          "showISOWeekNumbers": true,
          "autoApply": true,
          "locale": {
            "format": "DD-MM-YYYY",
            "separator": " a ",
            "weekLabel": "Sm",
            "daysOfWeek": [
              "Do",
              "Lu",
              "Ma",
              "Mi",
              "Ju",
              "Vi",
              "Sa"
            ],
            "monthNames": [
              "Enero",
              "Febrero",
              "Marzo",
              "Abril",
              "Mayo",
              "Junio",
              "Julio",
              "Agosto",
              "Septiembre",
              "Octubre",
              "Noviembre",
              "Deciembre"
            ],
            "firstDay": 0
          },
        });
      }
    }).then(function (result) {
      if (result.value) {
        $.ajax({
          url: 'contatienda/comicion.php',
          type: 'POST',
          datatype: 'json',
          data: { fechas: $('.fecha1').val() },
          success: function (x) {
            $('section.content').html(x);
            $('#titulo').text('Comiciones, premios y extras');
          }
        })
      }
    });
  });

});