<?php
    session_start();
    require_once "conexion.php";


    function consulta($op, $query){
        switch ($op) {
            case 1:
                $consulta = mysqli_query(MariaDB(),$query);
                $resultado  = mysqli_fetch_all($consulta,MYSQLI_BOTH);
                break;

            case 2:
                $consulta = mysqli_query(MariaDB(),$query);
                $resultado = $consulta;
                break;
            case 3:
                $consulta = mysqli_query(MariaDB(),$query);
                $resultado  = mysqli_fetch_row($consulta);
                break;
            case 4:
              $consulta = mysqli_query(Mysql(), $query);
              $resultado  = mysqli_fetch_row($consulta);
              break;
            default:
                break;
        }
        return $resultado;
        mysqli_free_result($consulta);
    }

    function consultaOracle($opcion,$query){

      switch ($opcion) {
        case 1:
          $consulta = oci_parse(Oracle(),$query);
          oci_execute($consulta);
          $resultado = oci_fetch_row($consulta);
          break;
        case 2:
          $consulta = oci_parse(Oracle(), $query);
          $resultado = oci_execute($consulta);
          break;
        case 3:
          $consulta = oci_parse(Oracle(), $query);
          oci_execute($consulta);
          $resul = oci_fetch_all($consulta,$res,null,null, OCI_FETCHSTATEMENT_BY_ROW + OCI_NUM);
          $resultado = $res;
          break;
        default:
          break;
      }
      return $resultado;
      oci_free_statement($consulta);
    }


    function registroLog($consulta,$explicacion="Sin comentarios"){
      $id_session = isset($_SESSION['user']) ? $_SESSION['user'][0] : '';
      $tipo = substr($consulta,0,6);
      $tabla = "";

      switch ($tipo) {
          case 'INSERT':
              $tabla = trim(substr($consulta,strpos($consulta,'INTO')+5,(strpos($consulta,'(') - (strpos($consulta,'INTO')+5) )));
              break;
          case 'UPDATE':
              $tabla = trim(substr($consulta,strpos($consulta,'UPDATE')+6,(strpos($consulta,'SET') - (strpos($consulta,'UPDATE')+6))));
              break;
          case 'DELETE':
              $tabla = trim(substr($consulta,strpos($consulta,'FROM')+4,(strpos($consulta,'WHERE') - (strpos($consulta,'FROM')+4))));
              break;
          default:
              break;
      }
      $consulta = str_replace("'","\'",$consulta);
      $query = "INSERT INTO log(tipo, tabla, explicacion, query, user_id)
                  VALUES('$tipo','$tabla','$explicacion','$consulta',$id_session);
              ";
      $resultado = mysqli_query(MariaDB(),$query);
      return $resultado;
      mysqli_free_result($resultado);
    }
