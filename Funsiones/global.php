<?php
    require_once "consulta.php";

    function listadoTienda()
    {
        $query ="SELECT id,numero FROM departamento WHERE departamento = 'Tienda'";
        $tienda = consulta(1, $query);
        foreach ($tienda as $key) {
          echo  "<option value=" . $key[0] . ">Tienda -" . $key[1] . "</option>";
        }
    }

    function listadoBanco()
    {
        $query ="SELECT id,banco FROM banco";
        $banco = consulta(1, $query);
        foreach ($banco as $key) {
          echo  "<option value=" . $key[0] . ">" . $key[1] . "</option>";
        }
    }

    function listadoTipoDeposito()
    {
        $query ="SELECT id,descripcion FROM tipopago";
        $tipopago = consulta(1, $query);
        foreach ($tipopago as $key) {
          echo  "<option value=" . $key[0] . ">" . $key[1] . "</option>";
        }
    }

    function listadoPais()
    {
      $query = "SELECT id,pais FROM pais";
      $pais = consulta(1,$query);
      foreach ($pais as $key) {
        echo  "<option value=" . $key[0] . ">" . $key[1] ."</option>";
      }
    }

    function listadoDepartamento()
    {
      $query = "SELECT id,departamento, numero FROM departamento";
      $departamento = consulta(1,$query);
      foreach ($departamento as $key) {
        echo  "<option value=" . $key[0] . ">" . $key[1] . " " . $key[2] . "</option>";
      }
    }

    function listadoPerfil()
    {
      $query = "SELECT id,perfil FROM perfil";
      $perfil = consulta(1, $query);
      foreach ($perfil as $key) {
        echo  "<option value=" . $key[0] . ">" . $key[1] . "</option>";
      }
    }

    function Subsidiaria()
    {
      $query = "SELECT subsidiaria,nombre_sbs FROM pais
      order by id";
      $sbs = consulta(1, $query);
      foreach ($sbs as $key) {
        echo  "<option value=" . $key[0] . ">" . $key[0] .' - '. $key[1] . "</option>";
      }
    }

    function departamento($op,$num = 0){
        switch ($op) {
            case 'Tienda':
                return "Tienda - ".$num;
                break;

            default:
                return $op;
                break;
        }
    }
    function Tiendas(){
      $query = "SELECT id, departamento FROM departamento ";  
      $sbs = consulta(1, $query);
      foreach ($sbs as $key) {
        echo  "<option value=" . $key[0] . ">" . $key[0] .' - '. $key[1] . "</option>";
      }
    }

    function buscarTienda($departamento_id){
      $query = "SELECT numero FROM departamento WHERE id = $departamento_id";
      return consulta(3,$query);
    }

    function imagenPerfil($pais,$imagen){
        if(empty($pais) || empty($imagen)){
          echo "../Image/user/default.svg";
        }
        else{
          echo "../Image/user/$pais/$imagen"."?ver=".time();
        }
    }
    function nombrePerfil($nombrePerfil)
    {
      echo ucwords(strtolower($nombrePerfil));
    }

    function modulos($perfil){
      $query = "SELECT m.id,m.icono,m.modulo
                FROM 	acceso a

                  INNER JOIN pagina p
                    ON a.pagina_id = p.id
                  INNER JOIN modulo m
                    ON p.modulo_id = m.id
                WHERE a.perfil_id = $perfil
                GROUP BY m.id";
      return consulta(1, $query);
    }

  function paginas($modulo)
  {
    $query = "SELECT CONCAT(m.id,'-',p.id), p.pagina, p.icono
              FROM 	acceso a
                INNER JOIN pagina p
                  ON a.pagina_id = p.id
                INNER JOIN modulo m
                  ON p.modulo_id = m.id
              WHERE m.id = $modulo
              GROUP BY CONCAT(m.id,'-',p.id), p.pagina, p.icono"; //se agrega para agrupar listado de opciones en el menu HWG19062024
    return consulta(1, $query);
  }
