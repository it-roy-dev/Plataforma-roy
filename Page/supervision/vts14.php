<?php
require_once "../../Funsiones/consulta.php";
require_once "../../Funsiones/kpi.php";
require_once "../../Funsiones/supervision/queryRpro.php";


$tienda = (isset($_POST['tienda'])) ? $_POST['tienda'] : '';
$fi = date('Y-m-d', strtotime(substr($_POST['fecha'], 0, -13)));
$ff = date('Y-m-d', strtotime(substr($_POST['fecha'], -10)));
$fechaini = date('Y-m-d');
$fechafin = date('Y-m-d');
$sbs = isset($_POST['sbs']) ? $_POST['sbs'] : '';
$pais = $_SESSION['user'][7];
$sim = impuestoSimbolo($sbs);

$iva = (isset($_POST['iva'])) ? $_POST['iva'] : '';
$vacacionista = (isset($_POST['vacacionista'])) ? $_POST['vacacionista'] : '';
$filtro = '';



if ($vacacionista == '1') {
  $filtro = '';
} else {
  $filtro = " AND EMP.EMPL_NAME < '5000'";
}
$semanas = rangoWY($fi, $ff);
$tiendas = explode(',', $tienda);
sort($tiendas);
?>
<div class="container-fluid shadow rounded py-3 px-4">
  <?php
  foreach ($tiendas as $tienda) {

    foreach ($semanas as $semana) {
      $total = array(
        $factura = 0,
        $pare_roy = 0,
        $pares_otro = 0,
        $tota_pares = 0,
        $accesorios = 0,
        $venta = 0,
        $meta = 0,
        $hora = 0
      );

      $query = "
      SELECT A.STORE_NO,	A.NOMBRETIENDA,
					    MT.META_S_IVA,ROUND(SUM(A.venta_SIN_IVA),2) VENTA_SIN_IVA,
						ROUND(SUM(A.venta_SIN_IVA),2)- (MT.META_S_IVA)DIF
										   FROM (
										   select  t1.store_NO, trunc(t1.created_datetime) FECHA, t1.employee1_login_name COD_VENDEDOR, 
										   
										   t1.employee1_full_name VENDEDOR,
                       s.store_name NOMBRETIENDA,
										   --MAX((SELECT STORE_NAME FROM RPS.STORE  WHERE STORE_NO = s.store_no AND ADDRESS1 IS NOT NULL )) NOMBRETIENDA,
										   case when t1.receipt_type=0 then 1 when t1.receipt_type=1 then -1 end TRANSACCIONES, 
										   
										   sum(case when t1.receipt_type=0 and t2.vend_code='001' then (t2.qty)
													when t1.receipt_type=1 and t2.vend_code='001' then (t2.qty)*-1 end) as par_roy, 
										   
										   sum(case when t1.receipt_type=0 and t2.vend_code <> 001 and SUBSTR(T2.DCS_CODE,1,3)not in ('ACC','SER','PRE','PRO')  then (t2.qty)
													when t1.receipt_type=1 and t2.vend_code <> 001 and SUBSTR(T2.DCS_CODE,1,3)not in ('ACC','SER','PRE','PRO')  then (t2.qty)*-1 end) as par_otros, 
										   
										   sum(case when t1.receipt_type=0 and   SUBSTR(T2.DCS_CODE,1,3)= 'ACC' then (t2.qty)
													when t1.receipt_type=1 and   SUBSTR(T2.DCS_CODE,1,3)= 'ACC' then (t2.qty)*-1 end) par_acce,
													
										   sum(case when t1.receipt_type=0  and SUBSTR(T2.DCS_CODE,1,3)not in ('SER','PRE','PRO')  then (T2.qty) 
													when t1.receipt_type=1  and SUBSTR(T2.DCS_CODE,1,3)not in ('SER','PRE','PRO')  then (T2.qty)*-1 end ) as cantidad,           
										   
										   sum(case when t1.receipt_type=0 and SUBSTR(T2.DCS_CODE,1,3)= 'ACC' then (t2.qty*T2.PRICE)
													when t1.receipt_type=1 and SUBSTR(T2.DCS_CODE,1,3)= 'ACC' then (t2.qty*T2.PRICE)*-1 end)venta_CON_IVA_ACC,
										   
											sum(case when t1.receipt_type=0 and   SUBSTR(T2.DCS_CODE,1,3)= 'ACC' then ((T2.price)/1.12*(T2.qty)) 
													 when t1.receipt_type=1 and   SUBSTR(T2.DCS_CODE,1,3)= 'ACC' then ((T2.price)/1.12*(T2.qty))*-1 end ) as venta_sin_iva_ACC,    
										   
										   sum(case when t1.receipt_type=0 then (t2.qty*t2.cost) when t1.receipt_type=1 then (t2.qty*t2.cost)*-1 else 0 end) as costo, 
										   
										   sum(case WHEN t1.receipt_type=0 AND SUBSTR(T2.DCS_CODE,1,3)= 'ACC' then ((T2.COST)*(T2.qty))
													when t1.receipt_type=1 AND SUBSTR(T2.DCS_CODE,1,3)= 'ACC' then ((T2.COST)*(T2.qty))*-1 end ) as COSTO_sin_iva_ACC ,
												 
										   NVL(sum(case when t1.receipt_type=0 then ((t2.price-( t2.price*NVL(t1.disc_perc,0)/100))*(t2.qty))
														when t1.receipt_type=1 then ((t2.price-( t2.price*NVL(t1.disc_perc,0)/100))*(t2.qty))*-1 end ),0) as venta_con_iva, 
												 
										   NVL(sum(case when t1.receipt_type=0 then ((t2.price-( t2.price*NVL(t1.disc_perc,0)/100))*(t2.qty))/1.12 
														when t1.receipt_type=1 then ((t2.price-( t2.price*NVL(t1.disc_perc,0)/100))*(t2.qty))/1.12*-1 end ),0) as venta_sin_iva     
										   
												 
										   from rps.document t1 inner join rps.document_item t2 on (t1.sid = t2.doc_sid)					   
										   INNER join rps.STORE S on (S.sid=t1.STORE_SID)
										   where 1=1
										   and t1.status=4 
											and t1.receipt_type<>2
						and t1.sbs_no = $sbs
                        and S.UDF1_STRING in($tienda)
					
						and t1.CREATED_DATETIME between to_date('$fi 00:00:00', 'DD/MM/YYYY HH24:MI:SS') ANd to_date('$ff 23:59:59', 'DD/MM/YYYY HH24:MI:SS')
						
						group by s.store_name, t1.store_NO,  t1.employee1_login_name, t1.employee1_full_name, trunc(t1.created_datetime), T1.DOC_NO, t1.receipt_type, t1.disc_amt
							)A 
							 
							INNER JOIN 
                              
							(SELECT * FROM ROY_META_DIARIA
			   WHERE FECHA  between to_date('$fi 00:00:00', 'DD/MM/YYYY HH24:MI:SS') ANd to_date('$ff 23:59:59', 'DD/MM/YYYY HH24:MI:SS'))MT
			   ON A.STORE_NO = MT.TIENDA AND A.FECHA = MT.FECHA 
			   GROUP BY A.STORE_NO, MT.META_S_IVA,A.NOMBRETIENDA
			   ORDER BY A.STORE_NO";
      $resultado = consultaOracle(3, $query);
      
      $cnt=1;
   
  ?>
      <h3 class="text-center font-weight-bold text-primary">supervisor: <?php echo $tienda ?>
   
      <br><small class="h6 text-primary font-weight-bold text-center"><?php echo "| Dia: " . date('d-m') . " | Semana: " . substr($semana, -2) . " | Meta Semana: Q " . number_format(MTSS($tienda, substr($semana, -2), substr($semana, 0, 4), $sbs)[0], 2) . " |" ?></small></br></h3>
    
      <table  style="font-size:14px;" class="table table-hover table-sm tbrdst">
        <thead class="bg-primary">
          <td>No</td>
          <td>Tienda</td>
          <td>Nombre de tienda</td>
          <td>Meta del dia</td>
          <td>Venta del dia</td>
          <td>Diferencia</td>
          <td>%</td>
          <td>Estado</td>
        </thead>
        
        <tbody class="align-middle font-size" style="width:100%">
          <?php
          foreach ($resultado as $rdst) {
          ?>
            <tr>
              <td><?php echo $cnt++ ?></td>
            
              <td><b><?php echo $rdst[0] ?><b></td>
              <td colspan = 1><?php echo $rdst[1] ?></td>
              <td style="<?php echo v_vrs_m($rdst[2]) ?>"><?php echo iva($iva, $rdst[2], $sbs) ?></td>
              <td style="<?php echo v_vrs_m($rdst[3]) ?>"><?php echo iva($iva, $rdst[3], $sbs) ?></td>
              <td style="<?php echo v_vrs_m($rdst[4]) ?>"><?php echo iva($iva, $rdst[4], $sbs) ?></td>
              <td><?php echo Porcentaje($rdst[3], $rdst[2]) . " %" ?></td>
              <td>
                <span class="<?php echo status(Porcentaje($rdst[3], $rdst[2])) ?>" style="<?php echo color(Porcentaje($rdst[3], $rdst[2])) ?>">
                </span>
              </td>
              
            </tr>
          <?php

            if ($rdst[2] === 'VACACIONISTA') {
              $rdst[3] = 0;
            }

            $total = array(
             
              $venta += $rdst[3],
              $meta += $rdst[2]
             
             
            );
                 }
               
            
          ?>
          <tr class="table-active align-middle font-weight-bold">
            <td></td>         
            
            <td align="center">TOTAL</td>
            <td></td>
                            
            <td><?php echo iva($iva, $total[1], $sbs) ?></td>
            <td><?php echo iva($iva, $total[0], $sbs) ?></td>
            <td style="<?php echo v_vrs_m(DifVentaMeta($total[0], $total[1])) ?>"><?php echo iva($iva, DifVentaMeta($total[0], $total[1]), $sbs) ?></td>
            <td><?php echo Porcentaje($total[0], $total[1]) . " %" ?></th>
            <td>
              <span class="<?php echo status(Porcentaje($total[0], $total[1])) ?>" style="<?php echo color(Porcentaje($total[0], $total[1])) ?>"></span>
            </td>
            
          </tr>
        </tbody>
       
        <tfoot>

        </tfoot>

      </table>
         
      <hr>
  <?php
   }
  }
  ?>
</div>

<script>
  $('.tbrdst').DataTable({
    "searching": false,
    "paging": false,
    "ordering": false,
    "info": false,
    "responsive": true,
    "autoWidth": false
  });

  $('.tooltip').tooltip();

  var url = "../Js/supervision/supervisor.js";
  $.getScript(url);

</script>