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
		if (isset($_GET["id"])) {
			$id = $_GET["id"];
			$q = "select MAIL from ".preBD."subscriptions where ID = " . $id;
			$r = checkingQuery($connectBD, $q);
			$sus = mysqli_fetch_object($r);
			
			$q="UPDATE ".preBD."subscriptions SET STATUS = '0' WHERE ID = '" . $id. "'";
			checkingQuery($connectBD, $q);
			
			$msg = "Suscripción del correo <em>".$sus->MAIL."</em> desactivada.";
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=option&opt=suscription&msg=".utf8_decode($msg);
		if(isset($_GET["filtergroup"])) {
			$location .= "&filtergroup=".intval($_GET["filtergroup"]);	
		}
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>