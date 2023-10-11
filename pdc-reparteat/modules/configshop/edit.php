<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once("../../includes/classes/ConfigShop/class.ConfigShop.php");
	$mnu = trim($_POST["mnu"]);
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	$com = trim($_POST["com"]);
	$tpl = trim($_POST["tpl"]);
	$opt = trim($_POST["opt"]);
	
	if ($_POST) {
		
		$msg = "";
		$error = NULL;
		$config = new ConfigShop();
		
		$params = $config->listParams();
		
		
		if(count($params) > 0) {
			foreach($params as $item) {
				$itemUp = new ConfigShop();
				$itemUp->id = $item->ID;
				$postName = "Config-".$item->ID;
				$value = intval($_POST[$postName]);
				if($value > 0) {
					$itemUp->value = $value;
					if($itemUp->update()) {
						$msg .= $item->TITLE." modificado correctamente. // ";
					}else {
						$msg .= "ERROR -> ".$item->TITLE." no se puede modificar. // ";
					}
				}else {
					$msg .= "ERROR -> ".$item->TITLE." debe ser un número entero mayor que 0. // ";
				}
			}
			disconnectdb($connectBD);
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&msg=".utf8_decode($msg);
			header($location);
			
		} else {
			disconnectdb($connectBD);
			$msg .= "No existen parametros para modificar.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&msg=".utf8_decode($msg);
			header($location);
		}
		
	}else {
		disconnectdb($connectBD);
		$msg .= "Error al modificar los parametros de configuración.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&msg=".utf8_decode($msg);
		header($location);
	}
?>