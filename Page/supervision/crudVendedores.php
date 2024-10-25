<?php
include_once '../../Funsiones/conexion.php';

$conn = Oracle();
if (!$conn) {
    error_log("Error de conexión a la base de datos.");
    die("Error de conexión a la base de datos.");
}

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'get_employees':
            $query = "SELECT s.store_no AS tienda_no, 
                             vf.codigo_vendedor, 
                             vf.nombre, 
                             vf.puesto, 
                             vf.activo, 
                             vf.fecha_ingreso 
                      FROM ROY_VENDEDORES_FRIED vf
                      INNER JOIN RPS.STORE s ON vf.tienda = s.store_no
                      INNER JOIN RPS.subsidiary sb ON s.sbs_sid = sb.sid  AND vf.sbs = sb.sbs_no
                      ORDER BY s.store_no,  DECODE(VF.PUESTO, 'JEFE DE TIENDA', 1, 'SUB JEFE DE TIENDA', 2, 'ASESOR DE VENTAS', 3, 4),
                       vf.fecha_ingreso ASC";
            $stmt = oci_parse($conn, $query);
            oci_execute($stmt);

            $employees = [];
            while ($row = oci_fetch_assoc($stmt)) {
                $employees[] = [
                    'TIENDA_NO' => $row['TIENDA_NO'],
                    'CODIGO_VENDEDOR' => $row['CODIGO_VENDEDOR'],
                    'NOMBRE' => $row['NOMBRE'],
                    'PUESTO' => $row['PUESTO'],
                    'ACTIVO' => $row['ACTIVO'] == 1 ? 'Sí' : 'No',
                    'FECHA_INGRESO' => $row['FECHA_INGRESO']
                ];
            }

            oci_free_statement($stmt);
            oci_close($conn);

            header('Content-Type: application/json');
            echo json_encode($employees);
            break;

        case 'update_employee':
            $codigo_vendedor = $_POST['codigo_vendedor'];
            $tienda_no = $_POST['tienda_no'];
            $puesto = $_POST['puesto'];
            $activo = $_POST['activo'];

            $query = "UPDATE ROY_VENDEDORES_FRIED 
                      SET tienda = :tienda_no, 
                          puesto = :puesto, 
                          activo = :activo 
                      WHERE codigo_vendedor = :codigo_vendedor";
            $stmt = oci_parse($conn, $query);

            oci_bind_by_name($stmt, ':tienda_no', $tienda_no);
            oci_bind_by_name($stmt, ':puesto', $puesto);
            oci_bind_by_name($stmt, ':activo', $activo);
            oci_bind_by_name($stmt, ':codigo_vendedor', $codigo_vendedor);

            if (oci_execute($stmt)) {
                echo 'true';
            } else {
                echo 'false';
            }

            oci_free_statement($stmt);
            oci_close($conn);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Acción no válida.']);
            break;
    }
}

?>
