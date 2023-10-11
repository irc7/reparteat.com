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
	
	if (allowed("content") == 1) {	
		if (isset($_GET["section"])) {
			
			$section = intval($_GET["section"]);
			
			$q1 = "SELECT * FROM ".preBD."users WHERE Login='".$_SESSION[PDCLOG]['Login']."'";
			$result1 = checkingQuery($connectBD, $q1);
			
			$row1 = mysqli_fetch_array($result1);
			
			$pwdhash1 = $row1['Pwd'];
			
			$q = "select TITLE from ".preBD."articles_sections where ID = '" . $section . "'";
			$result = checkingQuery($connectBD, $q);
			
			$row = mysqli_fetch_assoc($result);
			$sitemap = formatNameUrl(stripslashes($row["TITLE"])) . ".xml";
			
			$q = "DELETE FROM ".preBD."articles_sections WHERE ID = '" . $section . "'";
			checkingQuery($connectBD, $q);

			$msg = "Sección eliminada correctamente";
			
			$q2 = "UPDATE ".preBD."articles SET IDSECTION = '0' WHERE IDSECTION = '" . $section . "'";
			checkingQuery($connectBD, $q2);
			
			//Gestion Sitemap
			deleteSitemap($sitemap);
			$action = "delete_section";
			$msg_alt = construcIndexSitemap($action);
			//Fin Gestion Sitemap
			$q = "DELETE FROM `".preBD."url_web` WHERE SEC_VIEW = '" . $section . "' and TYPE = 'section'";
			checkingQuery($connectBD, $q);
			
		}
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=content&com=articles&tpl=option&opt=section&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>