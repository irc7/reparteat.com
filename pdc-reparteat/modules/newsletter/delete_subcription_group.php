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
	if(allowed("mailing")) {
		if(isset($_GET["id"])) {
			$id = intval($_GET["id"]);
			$title = utf8_encode(trim($_GET["title"]));
			
			$q = "select ID, MAIL from ".preBD."subscriptions where IDGROUP = " . $id;
			$r = checkingQuery($connectBD, $q);
			$t = mysqli_num_rows($r);
			
			while($sus = mysqli_fetch_object($r)) {
				
				//BORRADO DE DATOS NEWSLETTER
					$q = "DELETE FROM ".preBD."newsletter_trail WHERE IDSUBSCRIPTION = " . $sus->ID;
					checkingQuery($connectBD, $q);
			
					$q = "DELETE FROM ".preBD."newsletter_mailsend WHERE IDSUBSCRIPTION = " . $sus->ID;
					checkingQuery($connectBD, $q);
				//BORRADO DE ESTADISTICAS	
					$q = "DELETE FROM ".preBD."statistics_newsletter_open WHERE IDSUBSCRIPTION = " . $sus->ID;
					checkingQuery($connectBD, $q);
					$q = "DELETE FROM ".preBD."statistics_subscription WHERE IDSUBSCRIPTION= " . $sus->ID;
					checkingQuery($connectBD, $q);
					
					$q = "DELETE FROM ".preBD."subscriptions WHERE ID='".$sus->ID."'";
					checkingQuery($connectBD, $q);
					
			}
			$msg = "Suscriptores del grupo <em>".$title."</em> eliminados correctamente.";
			
			disconnectdb($connectBD);
			$location = "Location: ../../index.php?mnu=mailing&com=newsletter&tpl=option&opt=suscription&msg=".utf8_decode($msg);
			header($location);
		}
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>