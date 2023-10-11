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
		if ($_POST) {
			
			
			$section = trim($_POST["section"]);
			$newsection = addslashes(trim($_POST["newsection"]));		
			$title_seo = addslashes(trim($_POST["Title_seo"]));
			if($title_seo == ""){
				$title_seo = $newsection; 
			}	
			$description = addslashes(trim($_POST["description"]));
			$id_blog = $_POST["id_blog"];
			$author = abs(intval($_POST["author"]));
			$val = $_POST["Validation"];
			
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
					$qS = "select count(*) as total from ".preBD."blog where SLUG = '" . $slug . "' and ID != '" . $id_blog ."'";
					
					$rS = checkingQuery($connectBD, $qS);
					$tS = mysqli_fetch_object($rS);
					if($tS->total > 0) {
						$slug = $slug . "-r";
					}
					
					$qI = "select SLUG from ".preBD."blog where ID = '" . $id_blog . "'";
					$r = checkingQuery($connectBD, $qI);
					$s = mysqli_fetch_object($r);
						
					//Gestion RSS
						$oldRSS = $s->SLUG . ".rss";
						$newRSS = $slug . ".rss";
						$url = "../../../RSS/" . $oldRSS;
						if(file_exists($url)) {
							renameBlogRSS($oldRSS, $newRSS);
						}
						constructBlogRSS($id_blog);
					//Fin Gestion RSS
					
					$qB = "update ".preBD."blog set";
					$qB .= " AUTHOR = '" . $author;
					$qB .= "', SLUG = '" . $slug;
					$qB .= "', VALIDATION = '" . $val;
					$qB .= "' where ID = " . $id_blog;
					$r = checkingQuery($connectBD, $qB);
					
					//Gestion RSS
						
						$oldRSS = $s->SLUG . ".rss";
						$newRSS = $slug . ".rss";
						
						$url = "../../../RSS/" . $oldRSS;
						if(file_exists($url)) {
							renameBlogRSS($oldRSS, $newRSS);
						}
						constructBlogRSS($id_blog);
					//Fin Gestion RSS
				
			
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=option&opt=section&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>