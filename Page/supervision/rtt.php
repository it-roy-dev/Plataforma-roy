<?php
require_once "../../Funsiones/consulta.php";
require_once "../../Funsiones/kpi.php";
require_once "../../Funsiones/supervision/queryRpro.php";


$tienda = (isset($_POST['tienda'])) ? $_POST['tienda'] : '';
$fi = date('Y-m-d', strtotime(substr($_POST['fecha'], 0, -13)));
$ff = date('Y-m-d', strtotime(substr($_POST['fecha'], -10)));
$sbs = isset($_POST['sbs']) ? $_POST['sbs'] : '';
$pais = $_SESSION['user'][7];


$iva = (isset($_POST['iva'])) ? $_POST['iva'] : '';
$vacacionista = (isset($_POST['vacacionista'])) ? $_POST['vacacionista'] : '';
$filtro = '';

if ($vacacionista == '1') {
  $filtro = '';
} else {
  $filtro = " AND EMP.EMPL_NAME < '5000'";
}

$tiendas = explode(',', $tienda);
sort($tiendas);
?>
<div class="container-fluid shadow rounded py-3 px-4">
  <?php
  foreach ($tiendas as $tie) {

    $query = "
     SELECT ST.STORE_NO, t1.employee1_login_name, E.FULL_NAME,J.JOB_NAME FROM RPS.DOCUMENT T1
                               inner join rps.store st on t1.store_no = st.store_no 
                               left join rps.employee e on (t1.employee1_login_name = e.empl_name  and  st.sid= e.base_store_sid )
                               LEFT JOIN RPS.JOB J ON E.JOB_SID = J.SID
                               WHERE 1 = 1
                                and t1.status=4 
						and t1.receipt_type<>2
                          AND T1.sbs_no = $sbs
                          AND t1.STORE_NO in ($tienda)
                     --     and EXTRACT(YEAR FROM t1.CREATED_dATETIME)|| TO_CHAR(trunc(T1.CREATED_DATETIME,'d'),'IW')+1 = 202422
                        and t1.CREATED_DATETIME between to_date('$fi 00:00:00', 'YYYY-MM-DD HH24:MI:SS') ANd to_date('$ff 23:59:59', 'YYYY-MM-DD HH24:MI:SS')
						
						GROUP BY ST.STORE_NO,t1.employee1_login_name , E.FULL_NAME,J.JOB_NAME ,J.SID
                            ORDER BY J.SID";
    $consulta = consultaOracle(3,$query);

  ?>
    <h3 class="text-center font-weight-bold text-primary">Tienda no: <?php echo $tie; ?><br><small class="h4 text-primary text-center"><?php echo "( " . date('d/m/Y', strtotime($fi)) . " --al-- " . date('d/m/Y', strtotime($ff)) . " )" ?></small></br></h3>

    <table style="font-size:14px;" class="table table-hover table-sm tbrtt">
      <thead class="bg-primary">
        <td>Código</td>
        <td>Asesora</td>
        <td>Puesto</td>
        <?php foreach (rangoWY($fi, $ff) as $sem) { ?>
          <td><?php echo substr($sem, -2) ?></td>
        <?php
        }
        ?>
      </thead>

      <tbody class="align-middle font-size" style="width:100%">
        <?php
        foreach ($consulta as $rtt) {
        ?>
          <tr>
            <td><b><?php echo $rtt[1] ?><b></td>
            <td><?php echo ucwords(strtolower($rtt[2])) ?></td>
            <td><?php echo substr($rtt[3], 0, 3) ?></td>
            <?php foreach (rangoWY($fi, $ff) as $yw) { ?>
              <td>
                <?php echo str_pad(number_format(Porcentaje(VMSE($sbs, $rtt[1], $rtt[0], $yw)[1], VMSE($sbs, $rtt[1], $rtt[0], $yw)[0]), 0),2,'0',STR_PAD_LEFT) . " %" ?>
                <span class="<?php echo status(number_format(Porcentaje(VMSE($sbs, $rtt[1], $rtt[0], $yw)[1], VMSE($sbs, $rtt[1], $rtt[0], $yw)[0]), 0)) ?>" style="<?php echo color(number_format(Porcentaje(VMSE($sbs, $rtt[1], $rtt[0], $yw)[1], VMSE($sbs, $rtt[1], $rtt[0], $yw)[0]), 0)) ?>">
                </span>
              </td>
            <?php
            }
            ?>
          </tr>
        <?php
        }
        ?>
        <tr class="table-active align-middle font-weight-bold">
          <td></td>
          <td align="center">TOTAL</td>
          <td></td>
          <?php foreach (rangoWY($fi, $ff) as $yw) { ?>
            <td>
              <?php echo str_pad(number_format(Porcentaje(VMST($sbs, $tie, $yw)[1], VMST($sbs, $tie, $yw)[0]), 0),2,'0',STR_PAD_LEFT) . " %" ?>
              <span class="<?php echo status(number_format(Porcentaje(VMST($sbs, $tie, $yw)[1], VMST($sbs, $tie, $yw)[0]), 0)) ?>" style="<?php echo color(number_format(Porcentaje(VMST($sbs, $tie, $yw)[1], VMST($sbs, $tie, $yw)[0]), 0)) ?>">
              </span>
            </td>
          <?php
          }
          ?>
        </tr>
      </tbody>

      <tfoot>

      </tfoot>
    </table>
    <hr>
  <?php
  }
  ?>
</div>
<script>
  $('.tbrtt').DataTable({
    "searching": false,
    "paging": false,
    "ordering": false,
    "info": false,
    "responsive": true,
    "autoWidth": false
  });
</script>