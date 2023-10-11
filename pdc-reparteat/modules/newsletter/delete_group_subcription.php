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
	
	if (allowed("mailing")) {
		if (isset($_GET["group"])){
			$group = $_GET["group"];
			
				$q = "select TITLE from ".preBD."groups_subscriptions WHERE ID = '" . $group . "'";
				$result = checkingQuery($connectBD, $q);
				
				$row_group = mysqli_fetch_assoc($result);
				
				$q = "select ID from ".preBD."subscriptions WHERE IDGROUP = '" . $group . "'";
				
				$result = checkingQuery($connectBD, $q);
				
				if($row = mysqli_fetch_assoc($result)){
					$msg = "El grupo debe de estar vacio para poder eliminarlo";	
				} else {
					$q = "DELETE FROM ".preBD."groups_subscriptions WHERE ID = '" . $group . "'";
					checkingQuery($connectBD, $q);
					
					$msg = "Grupo <em>" . $row_group["TITLE"] . "</em> borrado correctamente.";
				}
			
			disconnectdb($connectBD);
		} else {
			$msg = "Error al intentar eliminar el grupo ".$row_group["TITLE"].".";	
		}
		$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=option&opt=groupsuscription&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>