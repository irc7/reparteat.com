
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
	$mnu = trim($_GET["mnu"]);
	if(allowed($mnu)) {
		if (isset($_GET["record"])) {
			
			$typeArticle = trim($_GET["type"]);
			$article = intval($_GET["record"]);
			
			$q = "UPDATE ".preBD."articles SET TRASH='0' WHERE ID = '" . $article . "'";
			checkingQuery($connectBD, $q);
			
			$msg = "Artículo ".$article." recuperado";
		
		//GESTION DE SITEMAP
			$q1 = "SELECT IDSECTION FROM ".preBD."articles WHERE ID = '" . $article . "'";
			
			$result1 = checkingQuery($connectBD, $q1);
			$row1 = mysqli_fetch_array($result1);
			$section = $row1['IDSECTION'];
			
			$q_sec = "select TITLE from ".preBD."articles_sections where ID = '" . $section . "'";
			
			$result_sec = checkingQuery($connectBD, $q_sec);
			$row_sec = mysqli_fetch_assoc($result_sec);
			$sitemap = formatNameUrl(stripslashes($row_sec["TITLE"])) . ".xml";
			
			$msg_alt = construcSitemapArticles($section, $sitemap);
			//FIN		

			disconnectdb($connectBD);
			
			$location = "Location: ../../index.php?mnu=".$mnu."&com=articles&tpl=trash&type=".$typeArticle."&msg=".utf8_decode($msg);
			header($location);			
		}
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}		
?>