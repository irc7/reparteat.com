<?php	
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../../pdc-reparteat/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../../../../pdc-reparteat/includes/config.inc.php");
	require_once("../../../../includes/functions.inc.php");
	require_once("../../../includes/functions.php");
	
	if(!isset($_SESSION[nameSessionZP]) || $_SESSION[nameSessionZP]->ID == 0) {
		header("Location: iniciar-sesion");
	}
	require_once("../../../../includes/class/class.System.php");
	require_once("../../../../includes/class/Zone/class.Zone.php");
	
	$msg = "";
	$error = 0;
	
	$idZone = intval($_POST["idZone"]);
	
	$zObj = new Zone();
	if($zObj->isUserWebZone($idZone, $_SESSION[nameSessionZP])) {
		if($_POST) {
			
			$zObj->orderLimit = intval($_POST["OrderLimit"]);
			$zObj->repLimit = intval($_POST["RepLimit"]);
			if($zObj->repLimit == 0 || $zObj->repLimit == 0){
				$error = 1;
			}
			if($error == 0) {
				$zObj->updateResp($idZone);
				$msg .= "Control de límite de pedidos actualizado correctamente";
				
			} else {
				$error = 1;
				$msg .= "Los valores tienen que ser un número mayor a 0.";
			}
		}else{
			$error = 1;
			$msg.= NOPOST;
		}
	}else{
		$error = 1;
		$msg.= NOACCESS;
	}
	$_SESSION[msgError]["result"] = $error;
	$_SESSION[msgError]["msg"] = $msg;
	
	disconnectdb($connectBD);
	$location = "Location: " . DOMAINZP . "?view=zone&mod=zone&tpl=config&z=".$idZone;
	header($location);

?>