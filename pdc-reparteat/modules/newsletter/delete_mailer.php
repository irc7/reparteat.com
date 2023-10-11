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
		if (isset($_GET["record"])) {
			$id = $_GET["record"];
				//BORRADO
					$q = "DELETE FROM ".preBD."newsletter_mailer WHERE ID='".$id."'";
					checkingQuery($connectBD, $q);
					$msg = "E-mail para envios número '".$id."' eliminado.";
		}
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=list&opt=mailer&msg=".utf8_decode($msg);
		
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>