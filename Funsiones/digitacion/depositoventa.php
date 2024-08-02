<?php

  require_once "../consulta.php";
  require_once "../global.php";

  $fi = isset($_POST['fechas']) ? date('Y-m-d', strtotime(substr($_POST['fechas'], 0, -13))) : '';
  $ff = isset($_POST['fechas']) ? date('Y-m-d', strtotime(substr($_POST['fechas'], -10))) : '';

$query = "SELECT LPAD(V.SBS,3,'0') SUBSIDIARIA,V.FECHA, CONCAT('T-', LPAD(V.TIENDA,3,'0')) TIENDA, V.VENTA, NVL(D.DEPOSITO,0) DEPOSITO, (NVL(D.DEPOSITO,0) - NVL(V.VENTA,0)) DIFERENCIA, (D.DEPOSITO - V.VENTA)
          FROM (SELECT
            TO_CHAR(INV.CREATED_DATE,'RRRR-MM-DD') FECHA,
            INV.STORE_NO TIENDA,
            INV.SBS_NO SBS,
            ROUND(SUM(CASE WHEN INV.INVC_TYPE = 0 THEN ((I_I.PRICE) * I_I.QTY)
                          WHEN INV.INVC_TYPE = 2 THEN ((I_I.PRICE) * I_I.QTY)* -1 END),2) AS VENTA
          FROM INVOICE INV
            INNER JOIN INVC_ITEM I_I
              ON INV.INVC_SID = I_I.INVC_SID
          WHERE INV.SBS_NO = 1
          AND INV.CREATED_DATE BETWEEN TO_DATE('$fi 00:00:00','RRRR-MM-DD HH24:MI:SS') AND TO_DATE('$ff 23:59:59','RRRR-MM-DD HH24:MI:SS')
          GROUP BY
            TO_CHAR(INV.CREATED_DATE,'RRRR-MM-DD'),
            INV.STORE_NO,
            INV.SBS_NO ) V

          LEFT JOIN

          (SELECT
            TO_CHAR(FECHACORTE,'RRRR-MM-DD') FECHA,
            DEPARTAMENTO_ID TIENDA,
            SBS,
            SUM(MONTO) DEPOSITO
          FROM ROY_CORTE
          WHERE FECHACORTE BETWEEN TO_DATE('$fi 00:00:00','RRRR-MM-DD HH24:MI:SS') AND TO_DATE('$ff 23:59:59','RRRR-MM-DD HH24:MI:SS')
          AND SBS = 1
          GROUP BY
            TO_CHAR(FECHACORTE,'RRRR-MM-DD'),
            DEPARTAMENTO_ID,
            SBS) D

          ON V.FECHA = D.FECHA AND V.TIENDA = D.TIENDA AND V.SBS = D.SBS
          WHERE V.TIENDA NOT IN(0,100)
          ORDER BY
          FECHA,
          TIENDA";

$resultado = consultaOracle(3, $query);


print json_encode($resultado, JSON_UNESCAPED_UNICODE);