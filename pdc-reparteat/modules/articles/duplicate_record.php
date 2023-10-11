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

			/*selección datos articulo origen*/
			$q = "SELECT * FROM ".preBD."articles WHERE ID = '" . $article . "'";
			$result = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_object($result);
			
			/*cambiamos el titulo*/
			$new_title = "Copia de ".$row->TITLE;
			
			/*miniatura*/
			if($row->THUMBNAIL != ""){
				$img_youtube = substr($row->THUMBNAIL, 0, 2);
				/*miniatura youtube o no*/
				if($img_youtube != "v="){
					/*titulo miniatura*/
					$new_thumb = duplicateArchiveArticle($row->THUMBNAIL, "thumb");
					$new_thumb = duplicateArchiveArticle($row->THUMBNAIL, "image");
				}else{
					$new_thumb = $row->THUMBNAIL;
				}
			}		
			
			/*fecha en la que se hizo la copia*/
			$Date_start = date('Y') . date('m') . date('d') . date('h') . date('i')."00";
			$Date_end = date('Y') . date('m') . date('d') . date('h') . date('i')."00";
			
			$Author = $_SESSION[PDCLOG]['Login'];
			$Title_seo = $new_title;			
			
			/*insertar en la tabla articles, el nuevo articulo*/
			$q = "INSERT INTO ".preBD."articles (IDSECTION, TYPE, STATUS, DATE_START, DATE_END, AUTHOR, TITLE, TITLE_SEO, SUBTITLE, SUMARY, INTRO, THUMBNAIL) VALUES";
			$q .= " ('" .$row->IDSECTION . "', '".$row->TYPE."', '0', '" . $Date_start. "', '" . $Date_end . "', '" . $Author . "', '" . $new_title . "', '" . $Title_seo . "', '" . $row->SUBTITLE . "', '" . $row->SUMARY . "', '" . $row->INTRO . "', '" . $new_thumb . "')";
			checkingQuery($connectBD, $q);
			
			$record_number = mysqli_insert_id($connectBD);
			
			$slug = formatNameUrl($Title_seo);
			$che = true;
			while($che) {
				$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and ID_VIEW != " . $record_number;
				$r = checkingQuery($connectBD, $q);
				$t = mysqli_fetch_object($r);
				if($t->t == 0){
					$che = false;
				}else {
					$slug = $slug."-r";
				}
			}
			$q = "INSERT INTO `".preBD."url_web` (`SLUG`, `VIEW`, `SEC_VIEW`, `ID_VIEW`, `TYPE`, `TITLE`) 
					VALUES ('".$slug."','article',0,'".$record_number."','article','".$Title_seo."')";
			checkingQuery($connectBD, $q);
				
			/*parrafos*/
			$q_paragraphs = "SELECT * FROM ".preBD."paragraphs WHERE IDARTICLE = '" . $article . "' ORDER BY POSITION";
				
			$result_paragraphs = checkingQuery($connectBD, $q_paragraphs);			
			while ($row_paragraphs = mysqli_fetch_object($result_paragraphs)) {			
				$new_image = $row_paragraphs->IMAGE;
				$new_video = $row_paragraphs->VIDEO;
				
				if(($row_paragraphs->TYPE == "image") && ($row_paragraphs->IMAGE != "")){
					$new_image = duplicateArchiveArticle($row_paragraphs->IMAGE, "image");					
					$new_image = duplicateArchiveArticle($row_paragraphs->IMAGE, "thumb");
				}
				if($row_paragraphs->TYPE == "video"){
					$new_video = duplicateArchiveArticle($row_paragraphs->VIDEO, "video");
					/*caso de que el video lleve imagen*/
					if($row_paragraphs->IMAGE != ""){
						$new_image = duplicateArchiveArticle($row_paragraphs->IMAGE, "thumb");
						$new_image = duplicateArchiveArticle($row_paragraphs->IMAGE, "image");
					}
				}
				if(($row_paragraphs->TYPE == "gallery") && ($row_paragraphs->IMAGE != "")){
					$new_image = duplicateArchiveArticle($row->THUMBNAIL, "image");			
				}					
				if(($row_paragraphs->TYPE == "youtube") && ($row_paragraphs->IMAGE != "")){
					$new_video = $row_paragraphs->VIDEO;	
					
					/*caso de que el video lleve imagen*/
					if($row_paragraphs->IMAGE != $row_paragraphs->VIDEO){
						$new_image = duplicateArchiveArticle($row_paragraphs->IMAGE, "thumb");	
						$new_image = duplicateArchiveArticle($row_paragraphs->IMAGE, "image");
					}					
					
				}				
				
				$q = "INSERT INTO ".preBD."paragraphs (`TITLE`, `TEXT`, `IMAGE`, `LINK`, `TARGET`, `VIDEO`, `TYPE`, `FOOT`, `ALIGN`, `IDARTICLE`, `POSITION`, `IDALBUM`) VALUES";
				$q .= "('" . addslashes($row_paragraphs->TITLE) . "','" . addslashes($row_paragraphs->TEXT) . "','" . $new_image . "','" . $row_paragraphs->LINK . "','" . $row_paragraphs->TARGET . "','" . $new_video . "','" . $row_paragraphs->TYPE . "','" . addslashes($row_paragraphs->FOOT) . "','" .$row_paragraphs->ALIGN."','".$record_number."','".$row_paragraphs->POSITION."','".$row_paragraphs->IDALBUM."')";
				checkingQuery($connectBD, $q);
				
				$record_paragraph = mysqli_insert_id($connectBD);

				/*ADJUNTOS*/
				$q2 = "select * from ".preBD."paragraphs_file where IDPARAGRAPH = " . $row_paragraphs->ID;
				
				$result_file = checkingQuery($connectBD, $q2);
				
				while($files = mysqli_fetch_object($result_file)){
					$new_doc = duplicateArchiveArticle($files->URL, "doc");
					
					$title = "";
					
					if($files->TITLE != ""){
						$title = $files->TITLE;
					}
					
					$q4 = "INSERT INTO ".preBD."paragraphs_file (`IDPARAGRAPH`, `TITLE`, `URL`, `SIZE`) VALUES";
					$q4 .= "('".$record_paragraph."', '".$title."', '".$new_doc."', '".$files->SIZE."')"; 
					checkingQuery($connectBD, $q4);
					
				}				
			}
			
			/*SITEMAP*/
			if($row->IDSECTION != 0) {
				$q_sec = "select TITLE from ".preBD."articles_sections where ID = '" . $row->IDSECTION . "'";
				$result_sec = checkingQuery($connectBD, $q_sec);
				$row_sec = mysqli_fetch_assoc($result_sec);
				$sitemap = formatNameUrl(stripslashes($row_sec["TITLE"])) . ".xml";
				$msg_alt = construcSitemapArticles($row->IDSECTION, $sitemap);
			}			
			

			//FIN
			disconnectdb($connectBD);
			$msg .= "Artículo duplicado correctamente";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=articles&tpl=edit&record=".$record_number."&type=".$typeArticle."&msg=".utf8_decode($msg);
			header($location);
		}
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>