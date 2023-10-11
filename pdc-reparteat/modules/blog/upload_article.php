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
	$submnu = intval($_GET["submnu"]);
	if (allowed($mnu)) {
		if (isset($_GET['msg'])) {
			$msg = ($_GET['msg']);
		}	
		if (isset($_GET['recordTemp'])) {
			$idTemp = $_GET['recordTemp'];
		}
		if (isset($_GET['record'])) {
			$id = $_GET['record'];
		}
		if(isset($_GET["filtersection"]) && intval($_GET["filtersection"]) > 0) {
			$filtersection = intval($_GET["filtersection"]);
		}else {
			$filtersection = 0;
		}
		
	/*COMPARARACION DE TABLA DE ARTICULO*/	
		$q = "select * from ".preBD."articles where ID = " . $id;
		$result = checkingQuery($connectBD, $q);
		
		$article = mysqli_fetch_assoc($result);
		
		$q = "select * from ".preBD."articles_temp where ID = " . $idTemp;
		$result = checkingQuery($connectBD, $q);
		
		$temp = mysqli_fetch_assoc($result);
		
		//pre($temp);
		if($temp["STATUS"] != 1 && $temp["STATUS"] != 0 && $temp["STATUS"] != 2) {
			$status = 0;
		} else {
			$status = $temp["STATUS"];
		}
		if($article["STATUS"] != $temp["STATUS"]){
			$change_status = TRUE;
		} else {
			$change_status = FALSE;
		}
		if($article["IDSECTION"] != $temp["IDSECTION"]){
			$old_idsection = $article["IDSECTION"];
			$idsection = $temp["IDSECTION"];
			$change_section = TRUE;
			
		} else {
			$change_section = FALSE;
			$idsection = $article["IDSECTION"];
		}
		
		$Firm = mysqli_real_escape_string($connectBD,$temp["FIRM"]);
		$Appointment = stripslashes($temp["APPOINTMENT"]);
		
		if($article["TITLE"] != $temp["TITLE"]){
			$title = stripslashes($temp["TITLE"]);
			$change_title = TRUE;
		} else {
			$change_title = FALSE;
		}
		
		if($article["TITLE_SEO"] != $temp["TITLE_SEO"]){
			$title_seo = stripslashes($temp["TITLE_SEO"]);
			$change_title_seo = TRUE;
		} else {
			$title_seo = mysqli_real_escape_string($connectBD,$article["TITLE_SEO"]);
			$change_title_seo = FALSE;
		}
		if($article["SUBTITLE"] != $temp["SUBTITLE"]){
			$subtitle = stripslashes($temp["SUBTITLE"]);
			$change_subtitle = TRUE;
		} else {
			$change_subtitle = FALSE;
		}

		if($article["SUMARY"] != $temp["SUMARY"]){
			$sumary = stripslashes($temp["SUMARY"]);
			$change_sumary = TRUE;
		} else {
			$change_sumary = FALSE;
		}
		
		if($article["INTRO"] != $temp["INTRO"]){
			$intro = stripslashes($temp["INTRO"]);
			$change_intro = TRUE;
		} else {
			$change_intro = FALSE;
		}
		
		$Date_start_art = new DateTime($article["DATE_START"]);
		$Date_start_temp = new DateTime($temp["DATE_START"]);
		if($Date_start_art->getTimestamp() != $Date_start_temp->getTimestamp()){
			$date_start = $temp["DATE_START"];
			$change_date_start = TRUE;
		} else {
			$change_date_start = FALSE;
		}
		
		$Date_end_art = new DateTime($article["DATE_END"]);
		$Date_end_temp = new DateTime($temp["DATE_END"]);
		if($Date_end_art->getTimestamp() != $Date_end_temp->getTimestamp()){
			$date_end = $temp["DATE_END"];
			$change_date_end = TRUE;
		} else {
			$change_date_end = FALSE;
		}
		
		if($article["AUTHOR"] != $temp["AUTHOR"]){
			$author = $temp["AUTHOR"];
			$change_author = TRUE;
		} else {
			$change_author = FALSE;
		}
		
		if($article["THUMBNAIL"] != $temp["THUMBNAIL"]){
			$img_art = $temp["THUMBNAIL"];
			$old_img_art = $article["THUMBNAIL"];
			$change_img_art = TRUE;
		} else {
			$img_art = $article["THUMBNAIL"];
			$change_img_art = FALSE;
		}

	/*TOTAL DE PARRAFOS*/
		$q = "select count(*) as total from ".preBD."paragraphs where IDARTICLE = " . $id;
		
		$result = checkingQuery($connectBD, $q);
		$row_art = mysqli_fetch_assoc($result);
		$p_art = $row_art["total"];
		
		$q = "select count(*) as total from ".preBD."paragraphs_temp where IDARTICLE = " . $idTemp;
		
		$result = checkingQuery($connectBD, $q);
		$row_temp = mysqli_fetch_assoc($result);
		$p_temp = $row_temp["total"];
		
	/*EQUILIBRAMOS EL TOTAL DE PARRAFOS*/
		if($p_temp > $p_art){
			for($i = ($p_art+1);$i<=$p_temp;$i++){
				$q2 = "INSERT INTO ".preBD."paragraphs (`TITLE`, `IDARTICLE`, `POSITION`, `TEXT`, `ALIGN`, `FOOT`, `TYPE`, `IMAGE`, `VIDEO`, `LINK`, `TARGET`) ";
				$q2 .= "VALUES ('', '" . $id . "', '" . $i . "', '', 'right', '', '', '', '', '', '_blank')";
				checkingQuery($connectBD, $q2);
				
			}	
		} else if($p_temp < $p_art) {
			for($i = ($p_temp+1);$i<=$p_art;$i++){
				$q = "SELECT ID, IMAGE, VIDEO from ".preBD."paragraphs WHERE IDARTICLE = " . $id . " and POSITION = " . $i;
				$result = checkingQuery($connectBD, $q);
				
				$row = mysqli_fetch_assoc($result);
				deleteImageParagraph($row["IMAGE"]);
				deleteImageParagraph($row["VIDEO"]);
				
				$q = "DELETE FROM ".preBD."paragraphs WHERE ID = '" . $row["ID"] . "'";
				checkingQuery($connectBD, $q);
				
			}
		}

	/*COMPARACION DE PARRAFOS*/	
		$q = "select * from ".preBD."paragraphs where IDARTICLE = " . $id;
		$q .= " order by POSITION asc";
		
		$result_article = checkingQuery($connectBD, $q);
			
		$q = "select * from ".preBD."paragraphs_temp where IDARTICLE = " . $idTemp;
		$q .= " order by POSITION asc";
		
		$result_temp = checkingQuery($connectBD, $q);
		$cont=0;
		while($row_temp = mysqli_fetch_assoc($result_temp)) {
			
			$row_article = mysqli_fetch_assoc($result_article);
			
			$block_type = $row_temp["TYPE"];
			$block_title = stripslashes($row_temp["TITLE"]);
			$block_text = stripslashes($row_temp["TEXT"]);
			$block_foot = stripslashes($row_temp["FOOT"]);
			$block_align = $row_temp["ALIGN"];
			$block_image = $row_temp["IMAGE"];
			$block_link = $row_temp["LINK"];
			$block_target = $row_temp["TARGET"];
			$block_video = $row_temp["VIDEO"];
			$position = $row_temp["POSITION"];
			$album = $row_temp["IDALBUM"];
			
			if ($row_article["IMAGE"] != "" && $row_article["IMAGE"] != NULL) {
				$urlImg = "../../../files/articles/image/".$row_article["IMAGE"];
				$urlThumb = "../../../files/articles/thumb/".$row_article["IMAGE"];
				deleteFile($urlImg);
				deleteFile($urlThumb);
			}
			$article_opt[$cont]["image"] = $row_article["IMAGE"];
			$temp_opt[$cont]["image"] = $row_temp["IMAGE"];

			if ($row_article["VIDEO"] != "") {
				$urlVideo = "../../../files/articles/video/".$row_article["VIDEO"];
				deleteFile($urlVideo);
			}
			$article_opt[$cont]["video"] = $row_article["VIDEO"];
			$temp_opt[$cont]["video"] = $row_temp["VIDEO"];
			
			$article_opt[$cont]["type"] = $row_article["TYPE"];
			$temp_opt[$cont]["type"] = $row_temp["TYPE"];
			$q_up = "UPDATE ".preBD."paragraphs SET"; 
			$q_up .= " TITLE = '" . addslashes($block_title);
			$q_up .= "', ALIGN = '". $block_align;
			$q_up .= "', FOOT = '" . addslashes($block_foot);
			$q_up .= "', TEXT = '" . addslashes($block_text);
			$q_up .= "', IMAGE = '" . $block_image;
			$q_up .= "', LINK = '" . $block_link;
			$q_up .= "', TARGET = '" . $block_target;
			$q_up .= "', VIDEO = '" . $block_video;
			$q_up .= "', TYPE = '" . $block_type;
			$q_up .= "', IDALBUM = '" . $album;
			$q_up .= "' WHERE IDARTICLE = " . $id . " AND POSITION = " . $position;
			//echo $q_up;
			//die();
			checkingQuery($connectBD, $q_up);
			
			
			//consulto la id del parrafo que actualizo para actualizar las descargas
			$q_in = "select ID from ".preBD."paragraphs ";
			$q_in .= " WHERE IDARTICLE = " . $id . " AND POSITION = " . $position;
			$r=checkingQuery($connectBD, $q_in);
			$pUp = mysqli_fetch_object($r);
			
			$q3 = "select * from ".preBD."paragraphs_file_temp where IDPARAGRAPH = " . $row_temp["ID"];
			$result_file = checkingQuery($connectBD, $q3);
			
			while($files = mysqli_fetch_object($result_file)){
				if ($files->IDCOPY == 0 && $files->URL != "") {
					
					$q4 = "INSERT INTO ".preBD."paragraphs_file (`IDPARAGRAPH`, `TITLE`, `URL`, `SIZE`, `POSITION`) VALUES";
					$q4 .= "('".$pUp->ID."', '".$files->TITLE."', '".$files->URL."', '".$files->SIZE."', '".$files->POSITION."')"; 
					checkingQuery($connectBD, $q4);
					
					$q = "DELETE FROM ".preBD."paragraphs_file_temp WHERE ID = '" . $files->ID . "'";
					checkingQuery($connectBD, $q);
					
				}else if($files->IDCOPY != 0 && $files->URL != "") {
					$q5 = "UPDATE ".preBD."paragraphs_file SET IDPARAGRAPH = " . $pUp->ID . ", POSITION = " . $files->POSITION . " where ID = " . $files->IDCOPY; 
					checkingQuery($connectBD, $q5);
					
				} else {
					$q = "DELETE FROM ".preBD."paragraphs_file_temp WHERE ID = '" . $files->ID . "'";
					checkingQuery($connectBD, $q);
				}
			}
			$cont++;
		}
		
	//BORRADO DE IMÁGENES
		for($j=0;$j<$cont;$j++) {
			if(($temp_opt[$j]["type"] == "image") || (($temp_opt[$j]["type"] == "youtube") && ($temp_opt[$j]["image"] != $temp_opt[$j]["video"]))) {
				$destino_img = "../../../files/articles/image/".$temp_opt[$j]["image"];
				$destino_thumb = "../../../files/articles/thumb/".$temp_opt[$j]["image"];
				$origen_img = "../../../files/articles_temp/image/".$temp_opt[$j]["image"];
				$origen_thumb = "../../../files/articles_temp/thumb/".$temp_opt[$j]["image"];
				
				if($temp_opt[$j]["image"] != "") {
					if(!file_exists($destino_img) && file_exists($origen_img)) {
						copy($origen_img, $destino_img);
					}
					if(!file_exists($destino_thumb) && file_exists($origen_thumb)) {
						copy($origen_thumb, $destino_thumb);
					}
					$urlImg = "../../../files/articles_temp/image/".$temp_opt[$j]["image"];
					$urlThumb = "../../../files/articles_temp/thumb/".$temp_opt[$j]["image"];
					deleteFile($urlImg);
					deleteFile($urlThumb);
				}
			} elseif($temp_opt[$j]["type"] == "video") {
				$destino_img = "../../../files/articles/image/".$temp_opt[$j]["image"];
				$destino_thumb = "../../../files/articles/thumb/".$temp_opt[$j]["image"];
				$origen_img = "../../../files/articles_temp/image/".$temp_opt[$j]["image"];
				$origen_thumb = "../../../files/articles_temp/thumb/".$temp_opt[$j]["image"];
				
				if($temp_opt[$j]["image"] != "") {
					if(!file_exists($destino_img) && file_exists($origen_img)) {
						copy($origen_img, $destino_img);
					}
					if(!file_exists($destino_thumb) && file_exists($origen_thumb)) {
						copy($origen_thumb, $destino_thumb);
					}
					$urlImg = "../../../files/articles_temp/image/".$temp_opt[$j]["image"];
					$urlThumb = "../../../files/articles_temp/thumb/".$temp_opt[$j]["image"];
					deleteFile($urlImg);
					deleteFile($urlThumb);
				}
			
				$destino_video = "../../../files/articles/video/".$temp_opt[$j]["video"];
				$origen_video = "../../../files/articles_temp/video/".$temp_opt[$j]["video"];
				
				if($temp_opt[$j]["video"] != "") {
					if(!file_exists($destino_video) && file_exists($origen_video)) {
						copy($origen_video, $destino_video);
					}
					$urlVideo = "../../../files/articles_temp/image/".$temp_opt[$j]["video"];
					
					deleteFile($urlVideo);
				}
			}
		}
			
		$thumbYoutube = false;
		
		$nameImg = explode("=", $img_art, 2);
		if($nameImg[0] == "v" && strlen($nameImg[1]) == 11) {
			$thumbYoutube = true;
		}else {
			$thumbYoutube = false;
		}
		
		$url = "../../../files/articles/image/".$img_art;
		$url_thumb = "../../../files/articles/thumb/".$img_art;
		


		if (($img_art != "") && ($thumbYoutube == false)) {
			$destino_img = "../../../files/articles/image/".$img_art;
			$destino_thumb = "../../../files/articles/thumb/".$img_art;
			$origen_img = "../../../files/articles_temp/image/".$img_art;
			$origen_thumb = "../../../files/articles_temp/thumb/".$img_art;
			
			if(!file_exists($destino_img) && file_exists($origen_img)) {
				copy($origen_img, $destino_img);
				unlink($origen_img);
			}
			if(!file_exists($destino_thumb) && file_exists($origen_thumb)) {
				copy($origen_thumb, $destino_thumb);
				unlink($origen_thumb);
			}
			
			if($change_img_art) {
				$delete_img_art = TRUE;
				for($j=0;$j<count($temp_opt);$j++) {
					if($temp_opt[$j]["image"] == $old_img_art) {
						$delete_img_art = FALSE;
						break;
					}
				}
				if($delete_img_art) {
					$old_img = "../../../files/articles/image/".$old_img_art;
					$old_thumb = "../../../files/articles/thumb/".$old_img_art;
					if(file_exists($old_img) && $old_img_art != "") {
						unlink($old_img);
						unlink($old_thumb);
					}		
				}
			}
		}
		
		deleteArticleTemp($idTemp);
	/*CONSULTAS*/

		$q = "UPDATE ".preBD."articles SET"; 
			$q .= " STATUS = '" . $status;
		if($change_section) {
			$q .= "', IDSECTION = '" . $idsection;
		}
		if($change_date_start) {
			$q .= "', DATE_START = '" . $date_start;
		}
		if($change_date_end) {
			$q .= "', DATE_END = '". $date_end;
		}
		if($change_author == 1) {
			$q .= "', AUTHOR = '" . $author;
		}
		$q .= "', FIRM = '" . $Firm;
		$q .= "', APPOINTMENT = '" . addslashes($Appointment);
		if($change_title) {
			$q .= "', TITLE = '" . addslashes($title);
		}
		if($change_title_seo) {
			$q .= "', TITLE_SEO = '" . addslashes($title_seo);
		}
		if($change_subtitle) {
			$q .= "', SUBTITLE = '" . addslashes($subtitle);
		}
		if($change_sumary) {
			$q .= "', SUMARY = '" . addslashes($sumary);
		}
		if($change_intro) {
			$q .= "', INTRO = '" . addslashes($intro);
		}
		if($change_img_art) {
			$q .= "', THUMBNAIL = '" . $img_art;
		}
		$q .= "' WHERE ID = " . $id;
		//pre($q);
		checkingQuery($connectBD, $q);
		
		
	//GESTION DE URL
		if(isset($_GET["urlart"]) && trim($_GET["urlart"]) != "") {
			$slug = trim($_GET["urlart"]);
		}else {
			$slug = formatNameUrl($Title_seo);
		}
		$che = true;
		while($che) {
			$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and ID_VIEW != " . $id;
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
			`TITLE`='".mysqli_real_escape_string($connectBD,$title_seo)."' 
			WHERE ID_VIEW = '" . $id . "' and TYPE = 'blog'";
		checkingQuery($connectBD, $q);
	
		//Noticias relacionadas
		$q = "DELETE FROM `".preBD."blog_related` WHERE (ID1 = " . $id ." or ID2 = " . $id .")";
		checkingQuery($connectBD, $q);
		if(isset($_SESSION["relatedNews"]) && count($_SESSION["relatedNews"]) > 0) {
			$q = "INSERT INTO `".preBD."blog_related`(`ID1`, `ID2`) VALUES ";
			$i = 1;
			foreach($_SESSION["relatedNews"] as $new) {
				$q .= "(".$id.",".$new.")";
				if($i < count($_SESSION["relatedNews"])){
					$q .= ", ";
				}
				$i++;
			}
			checkingQuery($connectBD, $q);
		}
		unset($_SESSION["relatedNews"]);
	
	//GESTION DE SITEMAP
		if($change_section) {
			$q_sec_new = "select TITLE from ".preBD."articles_sections where ID = '" . $idsection . "'";
			
			$result_sec_new = checkingQuery($connectBD, $q_sec_new);
			$row_sec_new = mysqli_fetch_assoc($result_sec_new);
			$sitemap_new = formatNameUrl(stripslashes($row_sec_new["TITLE"])) . ".xml";
			
			$msg_alt = construcSitemapArticles($idsection, $sitemap_new);
			
			$q_sec_old = "select TITLE from ".preBD."articles_sections where ID = '" . $old_idsection . "'";
			
			$result_sec_old = checkingQuery($connectBD, $q_sec_old);
			$row_sec_old = mysqli_fetch_assoc($result_sec_old);
			$sitemap_old = formatNameUrl(stripslashes($row_sec_old["TITLE"])) . ".xml";
			
			$msg_alt = construcSitemapArticles($old_idsection, $sitemap_old);
			
		} elseif(!$change_section && ($change_title || $change_status)) {
			$q_sec = "select TITLE from ".preBD."articles_sections where ID = '" . $idsection . "'";
			
			$result_sec = checkingQuery($connectBD, $q_sec);
			$row_sec = mysqli_fetch_assoc($result_sec);
			$sitemap = formatNameUrl(stripslashes($row_sec["TITLE"])) . ".xml";
			
			$msg_alt = construcSitemapArticles($idsection, $sitemap);
		}
		/*/GESTION DE RSS
			if($isection == 2) {
				constructRSS();	
			}
		FIN/*/
	//FIN   
		disconnectdb($connectBD);
		$msg .= "Artículo modificado correctamente";
		
		$location = "Location: ../../index.php?mnu=".$mnu."&submnu=".$submnu."&com=blog&tpl=edit&record=".$id."&msg=".utf8_decode($msg);
		if($filtersection > 0) {
			$location .= "&filtersection=".$filtersection;
		}
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>