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
	
	if (allowed("content")) {
		if ($_POST) {
			
			
			$section = trim($_POST["section"]);
			$newsection = addslashes(trim($_POST["newsection"]));		
			$title_seo = addslashes(trim($_POST["Title_seo"]));
			if($title_seo == ""){
				$title_seo = $newsection; 
			}	
			$description = addslashes(trim($_POST["description"]));
			
			$q1 = "SELECT * FROM ".preBD."users WHERE Login='".$_SESSION[PDCLOG]['Login']."'";
			$result1 = checkingQuery($connectBD, $q1);
			$row1 = mysqli_fetch_array($result1);
			
			
			$q = "select TITLE from ".preBD."articles_sections where ID = " . $section;
			
			$result = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_assoc($result);
			$old_sitemap = formatNameUrl(stripslashes($row["TITLE"])) . ".xml";
			
			$q="UPDATE ".preBD."articles_sections SET TITLE = '" . $newsection . "', TITLE_SEO = '" . $title_seo . "', DESCRIPTION = '". $description . "' WHERE ID = '" . $section . "'";
			checkingQuery($connectBD, $q);
			
			$msg = "Sección ".$newsection." modificada";
			//Gestion Sitemap
			$new_sitemap = formatNameUrl(stripslashes($newsection)) . ".xml";
			$url = "../../../sitemaps/" . $new_sitemap;
			if(file_exists($url)) {
				renameSitemap($old_sitemap, $new_sitemap);
			}
			$action = "edit_section";
			$msg_alt = construcIndexSitemap($action);
			//Fin Gestion Sitemap
			$slug = formatNameUrl($title_seo);
			$che = true;
			while($che) {
				$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and SEC_VIEW != " . $section;
				$r = checkingQuery($connectBD, $q);
				$t = mysqli_fetch_object($r);
				if($t->t == 0){
					$che = false;
				}else {
					$slug = $slug."-r";
				}
			}
			$q = "UPDATE `".preBD."url_web` SET 
					`SLUG`='".$slug."',
					`TITLE`='".$title_seo."' 
					WHERE SEC_VIEW = '" . $section . "' and TYPE = 'section'";
			checkingQuery($connectBD, $q);
			
			
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=content&com=articles&tpl=option&opt=section&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>