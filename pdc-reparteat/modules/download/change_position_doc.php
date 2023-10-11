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
	
	if (allowed ("content")) { 	
		if (isset($_GET["id"])) {
			$connectBD = connectdb();
			$id = intval($_GET["id"]);
			$down = intval($_GET["down"]);
			$p = intval($_GET["p"]); //position
			$action = trim($_GET["action"]);
			switch($action) {
				case "up":
					$q = "UPDATE ".preBD."download_docs SET `POSITION` = `POSITION` + 1 WHERE POSITION = " . ($p-1) . " and IDDOWNLOAD = " . $down;
					checkingQuery($connectBD, $q);
					
					$q = "UPDATE ".preBD."download_docs SET `POSITION` = `POSITION` - 1 WHERE ID = " . $id;
					checkingQuery($connectBD, $q);
					
					
				break; 
				case "down":
					$q = "UPDATE ".preBD."download_docs SET `POSITION` = `POSITION` - 1 WHERE POSITION = " . ($p+1) . " and IDDOWNLOAD = " . $down;
					checkingQuery($connectBD, $q);
					
					
					$q = "UPDATE ".preBD."download_docs SET `POSITION` = `POSITION` + 1 WHERE ID = " . $id;
					checkingQuery($connectBD, $q);
					
				break;
			}
			$msg = "Posiciones modificadas correctamente";
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=content&com=download&tpl=edit&id=".$down."&msg=".utf8_decode($msg);	
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
?>