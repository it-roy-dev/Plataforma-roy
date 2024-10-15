<?php
include_once '../Funsiones/conexion.php';
include_once '../Funsiones/tienda/queryRpro.php';
$conn = Oracle();

if (!$conn) {
    error_log("Error de conexión a la base de datos.");
    die("Error de conexión a la base de datos.");
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_supervisors':
            $query = "SELECT DISTINCT udf1_string AS SUPERVISOR_ID, udf2_string AS SUPERVISOR_NAME FROM RPS.STORE WHERE udf1_string IS NOT NULL ORDER BY udf1_string";
            $stmt = oci_parse($conn, $query);
            oci_execute($stmt);
            $supervisors = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $supervisors[] = $row;
            }
            oci_free_statement($stmt);
            echo json_encode($supervisors);
            break;

        case 'get_stores':
            $supervisor_id = $_GET['supervisor_id'];
            $query = "SELECT STORE_NO, STORE_NAME FROM RPS.STORE WHERE udf1_string = :supervisor_id ORDER BY STORE_NO";
            $stmt = oci_parse($conn, $query);
            oci_bind_by_name($stmt, ':supervisor_id', $supervisor_id);
            oci_execute($stmt);
            $stores = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $stores[] = $row;
            }
            oci_free_statement($stmt);
            echo json_encode($stores);
            break;

            case 'get_employees':
                $store_no = $_GET['store_no'];
                $semana = $_GET['semana'];
                $anio = $_GET['anio'];

                $query = "SELECT VF.CODIGO_VENDEDOR AS EMPL_NAME, 
                VF.NOMBRE AS FULL_NAME, 
                VF.PUESTO AS TIPO_PUESTO, 
                MT.META AS META_SEMANAL,
                MT.META /
                ( SELECT COUNT(*) FROM(
                SELECT vf.codigo_vendedor FROM ROY_VENDEDORES_FRIED VF
                INNER JOIN RPS.STORE S ON VF.TIENDA = S.STORE_NO
                INNER JOIN ROY_META_SEM_TDS MT ON S.STORE_NO = MT.TIENDA
                INNER JOIN RPS.SUBSIDIARY SB ON S.SBS_SID = SB.SID AND VF.SBS = SB.SBS_NO
                WHERE S.STORE_NO = :store_no
                AND MT.SEMANA = :semana
                AND MT.ANIO = :anio   
                group by  VF.CODIGO_VENDEDOR)) AS META
                FROM ROY_VENDEDORES_FRIED VF
                INNER JOIN RPS.STORE S ON VF.TIENDA = S.STORE_NO
                INNER JOIN ROY_META_SEM_TDS MT ON S.STORE_NO = MT.TIENDA
                INNER JOIN RPS.SUBSIDIARY SB ON S.SBS_SID = SB.SID AND VF.SBS = SB.SBS_NO
                WHERE S.STORE_NO = :store_no
                AND MT.SEMANA = :semana
                AND MT.ANIO = :anio
                group by  VF.CODIGO_VENDEDOR , VF.NOMBRE , VF.PUESTO , MT.META
                ORDER BY DECODE(VF.PUESTO, 'JEFE DE TIENDA', 1, 'SUB JEFE DE TIENDA', 2, 'ASESOR DE VENTAS', 3, 4)";


                $stmt = oci_parse($conn, $query);
                oci_bind_by_name($stmt, ':store_no', $store_no);
                oci_bind_by_name($stmt, ':semana', $semana);
                oci_bind_by_name($stmt, ':anio', $anio);
                oci_execute($stmt);

                $employees = [];
                while ($row = oci_fetch_assoc($stmt)) {
                    $employees[] = [
                        'EMPL_NAME' => $row['EMPL_NAME'],
                        'FULL_NAME' => $row['FULL_NAME'],
                        'TIPO_PUESTO' => $row['TIPO_PUESTO'],
                        'META' => $row['META'] ?? '0'
                    ];
                }

                oci_free_statement($stmt);

                header('Content-Type: application/json');
                echo json_encode($employees);
                break;
                case 'update_meta':
                    $employee_name = $_POST['employee_name'];
                    $meta = $_POST['meta'];
                    $store_no = $_POST['store_no'];
                    $semana = $_POST['semana'];
                    $anio = $_POST['anio'];
                    $tipo = $_POST['tipo'];  // Asegúrate de que este valor se envía desde el frontend
                
                    $query = "DECLARE
                                  v_count NUMBER;
                              BEGIN
                                  SELECT COUNT(*) INTO v_count FROM ROY_META_SEM_X_VENDEDOR
                                  WHERE CODIGO_EMPLEADO = :employee_name AND TIENDA = :store_no AND SEMANA = :semana AND ANIO = :anio;
                
                                  IF v_count > 0 THEN
                                      DELETE FROM ROY_META_SEM_X_VENDEDOR
                                      WHERE CODIGO_EMPLEADO = :employee_name AND TIENDA = :store_no AND SEMANA = :semana AND ANIO = :anio;
                                  END IF;
                
                                  INSERT INTO ROY_META_SEM_X_VENDEDOR (TIENDA, CODIGO_EMPLEADO, META, SEMANA, TIPO, ANIO, HORA, SBS)
                                  VALUES (:store_no, :employee_name, :meta, :semana, :tipo, :anio, '44', '1');
                              END;";
                
                    $stmt = oci_parse($conn, $query);
                    oci_bind_by_name($stmt, ':meta', $meta);
                    oci_bind_by_name($stmt, ':employee_name', $employee_name);
                    oci_bind_by_name($stmt, ':store_no', $store_no);
                    oci_bind_by_name($stmt, ':semana', $semana);
                    oci_bind_by_name($stmt, ':anio', $anio);
                    oci_bind_by_name($stmt, ':tipo', $tipo);
                    oci_execute($stmt);
                // Dentro de tu caso 'update_meta' en PHP
                    if ($error = oci_error($stmt)) {
                        echo json_encode(['error' => 'Failed to update meta: ' . $error['message']]);
                        oci_rollback($conn); // Asegurarte de revertir si hay un error
                    } else {
                        oci_commit($conn); // Asegurarte de hacer commit si todo está correcto
                        echo json_encode(['success' => 'Meta updated successfully']);
                    }

                    break;
                }
       
}


oci_close($conn);
?>

