<?php
	session_start();
	header ('Content-type: text/html; charset=utf-8');
	$V_PHP = explode(".", phpversion());
	
	
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}
	require_once ("../../../pdc-ihp/includes/database.php");
	$connectBD = connectdb();
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	require_once ("../../../pdc-ihp/includes/config.inc.php");
	require_once ("../../../includes/functions.inc.php");
	require_once("../head/permission.php");
	require_once("../head/strings.php");
	require_once("../../includes/functions.php");
	require_once("../../../lib/FileAccess/class.FileAccess.php");
	if (!isset($_SESSION[nameSessionZP]) || $_SESSION[nameSessionZP] == NULL) {
		header("Location: " . DOMAINZP);
	}
	$groupUser = groupByIdUser($_SESSION[nameSessionZP]->ID, $_SESSION[nameSessionZP]->SUPERADMIN);
	$view = trim($_GET["view"]);
	$module = trim($_GET["module"]);
	$id = intval($_GET["id"]);
	$Weight = checkPermissionUser($module, $id, $groupUser, $_SESSION[nameSessionZP]);
	if($Weight != 0 && $Weight <= WeightActionUser) {
			
		if($_GET) {
			$error = NULL;
			$msg = "";
			$userBD = userByID($id);
			
			$action = trim($_GET["action"]);
			
			if($id >= 0) {
				switch($action) {
					case "publish":
						$qs = "UPDATE `".preBDzp."user` 
								SET 
							`STATUS`='1' WHERE ID = " . $id;
						checkingQuery($connectBD, $qs);
						$msgAlert = "Usuario <em>".$userBD->NAME." ".$userBD->SURNAME."</em> publicado correctamente.";
					break;
					case "unpublish":
						$qs = "UPDATE `".preBDzp."user` 
								SET 
							`STATUS`='0' WHERE ID = " . $id;
						checkingQuery($connectBD, $qs);
						$msgAlert = "Usuario <em>".$userBD->NAME." ".$userBD->SURNAME."</em> desactivado correctamente.";
					break;
					case "delete":
						
						//deleteUserRequest($id);
						//deleteUserNotas($id);
						
						//borrar direcion
						//$qD2 = "DELETE FROM `".preBDzp."user_address` WHERE IDUSER = " . $id;
						//checkingQuery($connectBD, $qD2);
						
						//borro registro
						$qD2 = "UPDATE `".preBDzp."user` SET `STATUS`= 2 WHERE ID = " . $id;
						checkingQuery($connectBD, $qD2);
						
						$msgAlert = "Usuario <em>".$userBD->NAME." ".$userBD->SURNAME."</em> desactivado correctamente.";
					break;
				}
			}
		}else{
			$msg .= NOPOST;
			$error = "error";
		}
	}else{
		$msg .= NOACCESS;
		$error = "error";
	}	
	$_SESSION["resultzp"]["msg"] = $msg;
	$_SESSION["resultzp"]["class"] = $error;
	disconnectdb($connectBD);
	$location = "Location: " . DOMAINZP . "usuarios/listado";
	
	header($location);

?>