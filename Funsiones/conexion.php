<?php

    function MariaDB(){
      $link = mysqli_connect("localhost", "root", "", "roy");

      if (!$link) {
          echo "Error: Unable to connect to MySQL." . PHP_EOL;
          echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
          echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
          exit;
      }
      else
      {
        return $link;
      }
      mysqli_close($link);
    }



	Function Oracle()
	{

		$tns = "
			(DESCRIPTION =
			    (ADDRESS_LIST =
			      (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.0.170)(PORT = 1521))
			    )
			    (CONNECT_DATA =
			      (SERVICE_NAME = RPROODS)
			    )
			  )
			       ";
		$db_username = "REPORTUSER";
		$db_password = "REPORT"; 

		$con = oci_connect($db_username, $db_password, $tns);
			if (!$con)
			{
			    $e = oci_error();
			    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		return $con;
	}

	Function OracleRpro()
	{

		$tns = "
			(DESCRIPTION =
			    (ADDRESS_LIST =
			      (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.0.251)(PORT = 1521))
			    )
			    (CONNECT_DATA =
			      (SERVICE_NAME = RPROODS)
			    )
			  )
			       ";
		$db_username = "REPORTUSER";
		$db_password = "REPORT";

		$con = oci_connect($db_username, $db_password, $tns);
			if (!$con)
			{
			    $e = oci_error();
			    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		return $con;
	}



	function Mysql()
	{
		$link = mysqli_connect("192.168.0.173", "cybernelsk8", "Cyb3rn3lsk8", "friedman_db");

		if (!$link) {
		    echo "Error: Unable to connect to MySQL." . PHP_EOL;
		    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		    exit;
		}
		else
		{
			return $link;
		}
		mysqli_close($link);
  }
