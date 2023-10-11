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
	
	if (allowed("blog")) {	
		if (isset($_GET["id"])) {
			$id = abs(intval($_GET["id"]));
			
				$q = "DELETE FROM ".preBD."blog_subscriptions WHERE ID='".$id."'";
				checkingQuery($connectBD, $q);
				
				$msg = "Suscripción ".$id." eliminada";
			
			disconnectdb($connectBD);
			$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=option&opt=suscription&msg=".utf8_decode($msg);		
			if(isset($_GET["filterblog"])) {
				$location .= "&filterblog=".$_GET["filterblog"];
			}
		}else{
			disconnectdb($connectBD);
			$msg = "Ha ocurrido un error al gestionar la suscripción, por favor vuelva a intentarlo.";
			$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=option&opt=suscription&msg=".utf8_decode($msg);		
			if(isset($_GET["filterblog"])) {
				$location .= "&filterblog=".$_GET["filterblog"];
			}
		}
	}else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
	}
	header($location);
?>