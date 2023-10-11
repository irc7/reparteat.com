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
	require_once("../head/strings.php");
	
	if(!isset($_SESSION[nameSessionZP]) || $_SESSION[nameSessionZP]->ID == 0) {
		header("Location: iniciar-sesion");
	}
	
	require_once("../../../../includes/class/class.System.php");
	require_once("../../../../includes/class/UserWeb/class.UserWeb.php");
	require_once("../../../../includes/class/Password/class.Password.php");
	require_once("../../../../includes/class/Address/class.Address.php");
	require_once("../../../../includes/class/Zone/class.Zone.php");
	
	require_once ("../../../../includes/lib/Util/class.Util.php");
	require_once ("../../../../includes/lib/FileAccess/class.FileAccess.php");
	
	require_once("../../../../includes/class/class.phpmailer.php");
	require_once("../../../../includes/class/class.smtp.php");
	
	require_once("../../../../includes/class/personal_keys.php");
	$msg = "";
	$error = 0;
	$id = intval($_POST["id"]);
	$redirect = trim($_POST["redirect"]);
	if($_SESSION[nameSessionZP]->ID == $id) {
		if($_POST) {
			
			
			$zoneObj = new Zone();
			$zones = array(); 
			$zones = $zoneObj->listZones();
			$userObj = new UserWeb();
			$userObj->id = $id;
			
			$userBD = $userObj->infoUserWebById($id);
			
			
			foreach($zones as $zone) {
				$addressBD = array();
				$addressBD = $userObj->userWebAddressZone($id, $zone->ID);
				$enc = false;
				if(count($addressBD) > 0) {
					for($i=0;$i<count($addressBD);$i++) {
						$addressObj = new Address();
						$addressObj->street = trim($_POST["Street-".$addressBD[$i]->ID]);
						$addressObj->idzone = intval($_POST["Zone-".$addressBD[$i]->ID]);
						
						if($addressObj->idzone == $addressBD[$i]->IDZONE) {
							if(intval($_POST["fav-".$zone->ID]) == $addressBD[$i]->ID) {
								$addressObj->fav = 1;
							}else{
								$addressObj->fav = 0;
							}
						}else{
							$addressObj->fav = 0;	
						}
						$addressObj->update($addressBD[$i]->ID);
						if(!$enc && $addressObj->fav == 1) {
							$enc = true;
						}
					}
					if(!$enc) {
						$addressObj->updateFavZone($id, $zone->ID);
					}
				}
			}
			if(isset($_POST["Street-0"]) && trim($_POST["Street-0"]) != "" && isset($_POST["Zone-0"]) && intval($_POST["Zone-0"]) > 0) {
				$addressObj = new Address();
				$addressObj->idassoc = $_SESSION[nameSessionZP]->ID;
				$addressObj->street = trim($_POST["Street-0"]);
				$addressObj->idzone = trim($_POST["Zone-0"]);
				$addressObj->type = "user";
				$addressObj->fav = 0;
				
				$addressObj->add();
			}

			
			$msg.= "Direcciones actualizadas correctamente";
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
	
	if($changeLog || $changePass) {
		$location = "Location: " . DOMAINZP . "cerrar-sesion";
	}else {
		if(strpos($redirect, "resumen-pedido") !== false) {
			$location = "Location: " . $redirect;
		}else{
			$location = "Location: " . DOMAINZP . "?view=user&mod=user&tpl=address";
		}
	}
	header($location);

?>