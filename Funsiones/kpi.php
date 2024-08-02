<?php
  require_once "consulta.php";

  function ppp($venta,$pares,$accesorios = 0){
    if($venta == 0 && $pares == 0){
        return 0;
    }
    else{
        return number_format($venta/($pares + $accesorios),2);
    }
  }

  function upt($factura,$pares,$accesorios = 0){
    if($factura == 0 && $pares == 0)
    {
        return 0;
    }
    else{
        return number_format(($pares+$accesorios)/$factura,2);
    }

  }

  function qpt($venta,$factura){
    if($venta == 0 && $factura == 0)
    {
        return 0;
    }
    else{
        return number_format($venta/$factura,2);
    }
  }
  function vh($venta, $hora)
  {
    if ($venta == 0 || $hora == 0) {
      return 0;
    } else {
      return number_format($venta / $hora, 2);
    }
  }

  function impuestoSimbolo($pais){
    $query = "SELECT simbolo,impuesto FROM pais WHERE id = $pais";
    return consulta(3,$query);
  }

  function iva($op,$valor,$sbs){
    $impuestoSimbolo = impuestoSimbolo($sbs);
    switch ($op) {
      case 1:
        return $impuestoSimbolo[0]." ".number_format($valor * $impuestoSimbolo[1],2);
        break;
      case 0:
        return $impuestoSimbolo[0]." ".number_format($valor,2);
        break;
      default:
        break;
    }
  }

  function DifVentaMeta($venta,$meta = 0){
    return ($venta - $meta);
  }

  function Porcentaje($venta, $meta = 0){
    if($meta != 0){
      return number_format(($venta/$meta)*100,2);
    }
    else{
      return 0;
    }

  }


Function status($valor)
{
  if($valor < 80)
    return "fas fa-circle";
  else if($valor >= 80 && $valor < 100)
    return "fas fa-circle";
  else if($valor >= 100 && $valor < 125)
    return "fas fa-star";
  else if($valor >= 125)
    return "fas fa-trophy";
}

Function color($valor)
{
  if($valor < 80)
    return "color:#E12626; font-size: 2em;";
  else if($valor >= 80 && $valor < 100)
    return "color:#000000; font-size: 2em;";
  else if($valor >= 100 && $valor < 125)
    return "color:#E1C708; font-size: 2em;";
  else if($valor >= 125)
    return "color:#C6A811; font-size: 2em;";
}

function color2($valor, $sem)
{
  if ($valor < 80 && $sem >= 4)
    return "color:#E12626; font-size: 2em;";
  else if ($valor < 80 && $sem <= 3)
    return "color:green; font-size: 2em;";
  else if ($valor >= 80 && $valor < 100)
    return "color:#000000; font-size: 2em;";
  else if ($valor >= 100 && $valor < 125)
    return "color:#E1C708; font-size: 2em;";
  else if ($valor >= 125)
    return "color:#C6A811; font-size: 2em;";
}

  function IdEmpl($CodEmp){
    $query = "SELECT id_usuario FROM usuario WHERE user = $CodEmp";
    $consulta = consulta(4,$query);
    $IdEmp = $consulta;

    if($IdEmp!=""){
      return $IdEmp[0];
    }
    else {
      $IdEmp[0]="";
      return $IdEmp[0];
    }
  }

  Function v_vrs_m($v)
	{
		if(substr($v,0,1)=="-")
		{
			return "color:red";
		}
		else
		{
			return "color:black";
		}

  }

  function rangoWY($fi,$ff){
    $wy = [];
    $wyi = date('YW', strtotime($fi."+ 1 week"));
    $wyf = date('YW', strtotime($ff));
    for ($i = $wyi; $i <= $wyf; $i++) {
      $wy[] = $i;
    }
    return $wy;
  }


  function Antiguedad($f)
  {
    if ($f != 0) {
      $hoy = array(
          date('d'),
          date('m'),
          date('Y')
        );

      $fecha = array(
        date('d', strtotime($f)),
        date('m', strtotime($f)),
        date('Y', strtotime($f))
      );

      $ahora = mktime(0, 0, 0, $hoy[1], $hoy[0], $hoy[2]);
      $antes = mktime(0, 0, 0, $fecha[1], $fecha[0], $fecha[2]);

      $dif_segundos = $ahora - $antes;
      $Dias = $dif_segundos / (60 * 60 * 24);
      $Dias = abs($Dias);
      $Dias = floor($Dias);
      $Semanas = floor($Dias / 7);
      $resultado = array($Dias, $Semanas);
      return $resultado;
    } else {
      return 0;
    }
  }