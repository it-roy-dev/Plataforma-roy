<?php
    require_once "../consulta.php";
    require_once "../global.php";

    $opcion = isset($_POST['opcion']) ? $_POST['opcion'] : '';
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    $fechaDeposito = isset($_POST['fechaDeposito']) ? $_POST['fechaDeposito'] : '';
    $tiendaDeposito = isset($_POST['tiendaDeposito']) ? $_POST['tiendaDeposito'] : '';
    $tipoDeposito = isset($_POST['tipoDeposito']) ? $_POST['tipoDeposito'] : '';
    $noDeposito = isset($_POST['noDeposito']) ? $_POST['noDeposito'] : '';
    $montoDeposito = isset($_POST['montoDeposito']) ? str_replace([',','Q'],'',$_POST['montoDeposito']) : '';
    $bancoDeposito = isset($_POST['bancoDeposito']) ? $_POST['bancoDeposito'] : '';
    $comentario = isset($_POST['comentario']) ? $_POST['comentario'] : '';
    $explicacion = isset($_POST['explicacionDeposito']) ? $_POST['explicacionDeposito'] : '';

    $fi = isset($_POST['fechas']) ? date('Y-m-d',strtotime(substr($_POST['fechas'],0,-13))) : '';
    $ff = isset($_POST['fechas']) ? date('Y-m-d',strtotime(substr($_POST['fechas'],-10))) : '';



    switch ($opcion) {
        case 1:
            $query = "SELECT C.ID, TO_CHAR(C.FECHACORTE,'RRRR-MM-DD'), C.DEPARTAMENTO_ID TIENDA, T.DESCRIPCION TIPO, C.TRANSACCION NO_BOLETA, C.MONTO, B.BANCO
                      FROM ROY_CORTE C
                        INNER JOIN ROY_BANCO B
                          ON C.BANCO_ID = B.ID
                        INNER JOIN ROY_TIPOPAGO T
                          ON C.TIPOPAGO_ID = T.ID
                      WHERE C.FECHACORTE BETWEEN TO_DATE('$fi 00:00:00','RRRR-MM-DD HH24:MI:SS') AND TO_DATE('$ff 11:59:59','RRRR-MM-DD HH24:MI:SS')
                        ";

            $resultado = consultaOracle(3,$query);
            break;
        case 2:
            $query = "INSERT INTO ROY_CORTE (TRANSACCION, MONTO, FECHACORTE, OBSERVACION, BANCO_ID, TIPOPAGO_ID, DEPARTAMENTO_ID)
                        VALUES('$noDeposito',$montoDeposito,'$fechaDeposito','$comentario',$bancoDeposito,$tipoDeposito,$tiendaDeposito)
                     ";
            $resultado = consultaOracle(2,$query);
            break;
        case 3:

            $query = "UPDATE ROY_CORTE
                        SET TRANSACCION = '$noDeposito',
                            MONTO = $montoDeposito,
                            FECHACORTE = TO_DATE('$fechaDeposito','RRRR-MM-DD'),
                            BANCO_ID = $bancoDeposito ,
                            TIPOPAGO_ID = $tipoDeposito,
                            DEPARTAMENTO_ID = $tiendaDeposito
                        WHERE ID = $id
                     ";
            if($consulta = consultaOracle(2,$query)){
                $resultado = registroLog($query,$explicacion);
            }
            else{
                $resultado = $consulta;
            }
            break;
        case 4:

            $query = "DELETE
                        FROM ROY_CORTE
                        WHERE ID = $id
                     ";
            if($consulta = consultaOracle(2,$query)){
                $resultado = registroLog($query,$explicacion);
            }
            else{
                $resultado = $consulta;
            }
            break;
        case 5:
          $query = "SELECT TRANSACCION, MONTO, TO_CHAR(FECHACORTE,'RRRR-MM-DD'), OBSERVACION,BANCO_ID, TIPOPAGO_ID,DEPARTAMENTO_ID
                    FROM ROY_CORTE
                    WHERE ID = $id
                    ";
          $resultado = consultaOracle(1, $query);
        default:

            break;
    }

    print json_encode($resultado,JSON_UNESCAPED_UNICODE);