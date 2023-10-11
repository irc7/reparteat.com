<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	date_default_timezone_set("Europe/Paris");
	$mnu = trim($_GET["mnu"]);
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	checkingQuery($connectBD, "SET lc_time_names = 'es_ES'");
	checkingQuery($connectBD, "SET NAMES 'UTF8'");


	require_once("../../includes/classes/UserWeb/class.UserWeb.php");
	
	if($_GET) {

		$com = trim($_GET["com"]);
		$tpl = trim($_GET["tpl"]);
		$opt = trim($_GET["opt"]);
		$regs = array();
		$error = NULL;

		$heads[0]="ID usuario";
		$heads[1]="Apellidos";
		$heads[2]="Nombre";
		$heads[3]="DNI";
		$heads[4]=utf8_decode("Correo electrónico");
		$heads[5]=utf8_decode("Teléfono");
		$regs[] = $heads;
		$userObj = new UserWeb();
		$list = $userObj->listUserWebByType(4);

		foreach($list as $row) {
			$linea[0]=$row->ID;
			$linea[1]=utf8_decode($row->SURNAME);
			$linea[2]=utf8_decode($row->NAME);
			$linea[3]=$row->DNI;
			$linea[4]=$row->LOGIN;
			$linea[5]=$row->PHONE;
			$regs[] = $linea;
		}


		$total = count($regs);
		if ($total > 1) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/csv');
			header("Content-Disposition: attachment; filename=" . date("Ymd") . "-clientes_reparteat.csv");
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			
			$handle = fopen('php://output', 'w');
    		ob_clean();
			$rows = "";
			for ($i=0;$i<$total;$i++) {
				fputcsv($handle, $regs[$i],';');
				//$rows .= $regs[$i][0] . ";" . $regs[$i][1] . ";" .$regs[$i][2] . ";" .$regs[$i][3] . ";" .$regs[$i][4] . ";" .$regs[$i][5] . ";" . "\r\n";
			}
			ob_flush(); // dump buffer
			fclose($handle);
			die();	
			$msg .= "Exportación de usuarios realizada correctamente.";
		}else {
			$msg .= "No existen registros para los parametros requeridos.";
		}

	}else {
		$msg .= "No se han recibido datos para la exportación, vuelva a intentarlo, si el error persiste consulte con el administrador.";
		
	}
	disconnectdb($connectBD);
?>