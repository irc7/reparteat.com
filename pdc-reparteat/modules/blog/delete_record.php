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
	
			$article = $_POST["record"];
			$option = $_POST["option"];
			$trash = $_POST["trash"];
			
			$q = "SELECT * FROM ".preBD."articles WHERE ID='" . $article . "'";
			
			$result = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_array($result);
			$section = $row["IDSECTION"];
		
			switch ($option) {
				case 0:
					$q = "UPDATE ".preBD."articles SET TRASH='1' WHERE ID='" . $article . "'";
					checkingQuery($connectBD, $q);
					
					$msg = "Post en papelera";
					break;
				case 1:
					$Thumbnail = $row['THUMBNAIL'];
					$q1 = "SELECT * FROM ".preBD."paragraphs WHERE IDARTICLE = '" . $article . "'";
					
					$result1 = checkingQuery($connectBD, $q1);
					$i = 1;
					$blocks = array();
					while($row_blocks = mysqli_fetch_assoc($result1)) {
						$blocks[$i]["type"] = $row_blocks["TYPE"];
						$blocks[$i]["image"] = $row_blocks["IMAGE"];
						$blocks[$i]["video"] = $row_blocks["VIDEO"];
						
						$q2 = "select ID, URL from ".preBD."paragraphs_file where IDPARAGRAPH = " . $row_blocks["ID"];
						$result2 = checkingQuery($connectBD, $q2);
						
						while($row_files = mysqli_fetch_assoc($result2)) {
							$url_file = "../../../files/articles/doc/" . $row_files["URL"];
							if(file_exists($url_file)) {
								unlink($url_file);	
							}
							$q3 = "DELETE FROM ".preBD."paragraphs_file WHERE ID='".$row_files["ID"]."'";
							checkingQuery($connectBD, $q3);
							
						}
						
						$i++;
					}
					
					//BORRADO DE ARTICULOS Y PARRAFOS		
					$q = "DELETE FROM ".preBD."articles WHERE ID='".$article."'";
					checkingQuery($connectBD, $q);
					
					$q = "DELETE FROM ".preBD."paragraphs WHERE IDARTICLE = '" . $article . "'";
					checkingQuery($connectBD, $q);
					
					$q = "DELETE FROM ".preBD."url_web WHERE ID_VIEW = '" . $article . "' and TYPE = 'blog'";
					checkingQuery($connectBD, $q);
					
					$q = "DELETE FROM ".preBD."blog_related WHERE ID1 = '" . $article . "' or ID2 = '" . $article . "'";
					checkingQuery($connectBD, $q);
					
				//BORRADO DE COMENTARIOS	
					$q = "DELETE FROM ".preBD."blog_comment WHERE IDARTICLE = '" . $article . "'";
					checkingQuery($connectBD, $q);
					
					//BORRADO DE IMÁGENES
					$delete_thumb_article = FALSE;
					for($j=1;$j<=count($blocks);$j++) {
						if ($blocks[$j]["image"] != "" && $blocks[$j]["video"] != $blocks[$j]["image"]){
							if ($Thumbnail != $blocks[$j]["image"]){
								deleteImageParagraph($blocks[$j]["image"]);
							} else {
								$delete_thumb_article = TRUE;	
							}
						}
						if ($blocks[$j]["type"] == video){
							deleteVideoParagraph($blocks[$j]["video"]);
						}
					}
					if ($delete_thumb_article) {
						deleteImageParagraph($Thumbnail);
					}
					$msg = "Post ".$article." eliminado definitivamente";
			}
			
			//GESTION DE SITEMAP
			if($section != 0) {
				$q_sec = "select TITLE from ".preBD."articles_sections where ID = '" . $section . "'";
				
				$result_sec = checkingQuery($connectBD, $q_sec);
				$row_sec = mysqli_fetch_assoc($result_sec);
				$sitemap = formatNameUrl(stripslashes($row_sec["TITLE"])) . ".xml";
				$msg_alt = construcSitemapArticles($section, $sitemap);
			}
			//FIN
			//GESTION DE RSS
			if($section != 0) {
				$qI = "select ID from ".preBD."blog where IDSECTION = '" . $section . "'";
				$r = checkingQuery($connectBD, $qI);
				$blog = mysqli_fetch_object($r);
				constructBlogRSS($blog->ID);
			}
			//FIN
		
		disconnectdb($connectBD);
		if ($trash == 1) {
			$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=trash&msg=".utf8_decode($msg);
		}else {
			$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=option&msg=".utf8_decode($msg);
		}
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>