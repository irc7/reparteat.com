<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	if (!allowed("content")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if ($_POST) {
		
		$Gallery = trim($_POST["Gallery"]);
		$Title = addslashes(trim($_POST["Title"]));
		$Title_seo = addslashes(trim($_POST["Title_seo"]));
		if($Title_seo == ""){
			$Title_seo = $Title;
		}
		$Description = addslashes(trim($_POST["Description"]));
		
		$q1 = "SELECT * FROM ".preBD."users WHERE Login='".$_SESSION[PDCLOG]['Login']."'";
		$result1 = checkingQuery($connectBD, $q1);
		
		$row1 = mysqli_fetch_array($result1);
		
		$pwdhash1 = $row1['Pwd'];
		
		$q = "select TITLE from ".preBD."videos_gallery where ID = " . $Gallery;
		$result = checkingQuery($connectBD, $q);
		
		$row = mysqli_fetch_assoc($result);
		$old_sitemap = formatNameUrl(stripslashes($row["TITLE"])) . ".xml";		
		
		
		$q="UPDATE ".preBD."videos_gallery SET TITLE = '" . $Title . "', TITLE_SEO = '". $Title_seo ."', DESCRIPTION = '". $Description . "' WHERE ID = '" . $Gallery . "'";
		checkingQuery($connectBD, $q);
		
		$msg = $Title." modificado correctamente";
		
		//Gestion Sitemap
		$new_sitemap = formatNameUrl(stripslashes($Title)) . ".xml";
		$url = "../../../sitemaps/" . $new_sitemap;
		if(file_exists($url)) {
			renameSitemap($old_sitemap, $new_sitemap);
		}
		$action = "edit_section";
		$msg_alt = construcIndexSitemap($action);
		//Fin Gestion Sitemap			
	
		
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=content&com=videos&tpl=option&opt=gallery&msg=".$msg;
	header($location);
?>
