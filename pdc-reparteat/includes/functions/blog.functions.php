<?php
	function isBlogger($idUser) {
		global $connectBD;
		$q = "select count(*) as total from ".preBD."blog where AUTHOR = " . $idUser;
		$r = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($r);
		if($row->total > 0) {
			return TRUE;
		}else {
			return FALSE;
		}
	}
	
	function whatBlogUser($idUser) {
		global $connectBD;
		$q = "select * from ".preBD."blog where AUTHOR = '" . $idUser . "'";
		$r = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($r);
		return $row;	
	}
	function whatBlogByID($id) {
		global $connectBD;
		$q = "select * from ".preBD."blog where ID = '" . $id . "'";
		$r = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($r);
		return $row;	
	}
	function whatBlogByPost($id) {
		global $connectBD;
		
		$q = "select IDSECTION from ".preBD."articles where ID = '" . $id . "'";
		$r = checkingQuery($connectBD, $q);
		$s = mysqli_fetch_object($r);
		
		$q = "select * from ".preBD."blog where IDSECTION = '" . $s->IDSECTION . "'";
		$r = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($r);
		return $row;
	}
	
	
	
	function transformInSecond($totalSeg) {
		if($totalSeg > 60){
			$seg = $totalSeg % 60;
			if(strlen($seg) == 1) {
				$seg = "0".$seg;
			}
			$totalMin = floor($totalSeg / 60);
			if($totalMin > 60) {
				$min = $totalMin % 60;
				$hour = floor($totalMin / 60);
				return $hour . ":" . $min . ":"   . $seg . " seg";
			}else{
				return $totalMin . ":" . $seg . " seg";
			}
		}else{
			return "menos de 1 minuto";
		}
	}
	function getsection($id) {
		global $connectBD;
		$q = "SELECT TITLE FROM ".preBD."articles_sections WHERE ID = '" . $id . "'";
		
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$section = $row['TITLE'];
		if ($section != NULL) {
			return $section;
		}
		else {
			return "Ninguna";
		}
	}
	
//ARTÍCULOS TEMPORALES

	function createArticleTemp($x) {
		global $connectBD;
	/*COPIA DEL ARTICULO*/
		
		$q = "select * from ".preBD."articles where ID = " . $x;
		
		$result = checkingQuery($connectBD, $q);
		$article = mysqli_fetch_assoc($result);
		
		if($article['THUMBNAIL'] != ""){
			$nameImg = explode("=", $article['THUMBNAIL'], 2);
			if($nameImg[0] == "v" && strlen($nameImg[1]) == 11) {
				$thumbYoutube = true;
			}else {
				$thumbYoutube = false;
			}	

			if(!$thumbYoutube) {
				$origen_img = "../files/articles/image/".$article['THUMBNAIL'];
				$origen_thumb = "../files/articles/thumb/".$article['THUMBNAIL'];
				$destino_img = "../files/articles_temp/image/".$article['THUMBNAIL'];
				$destino_thumb = "../files/articles_temp/thumb/".$article['THUMBNAIL'];
				if(!file_exists($destino_img)) {
					copy($origen_img, $destino_img);
				}
				if(!file_exists($destino_thumb)) {
					copy($origen_thumb, $destino_thumb);
				}
			}
		}
		
		$q = "select TYPE from ".preBD."articles_sections where ID = '" . $article['IDSECTION']."'";
		$r = checkingQuery($connectBD, $q);
		$typeSection = mysqli_fetch_object($r);
		
		
		$q = "INSERT INTO ".preBD."articles_temp (IDARTICLE, IDSECTION, TYPE, FIRM, APPOINTMENT, STATUS, DATE_START, DATE_END, AUTHOR, TITLE, TITLE_SEO, SUBTITLE, SUMARY, INTRO, THUMBNAIL) VALUES";
		$q .= " ('" .$x . "','" .$article['IDSECTION'] . "', '" . $typeSection->TYPE . "', '" . $article['FIRM'] . "', '" .$article['APPOINTMENT'] . "', '" . $article['STATUS'] . "', '" . $article['DATE_START']. "', '" . $article['DATE_END'] . "', '" . $article['AUTHOR'] . "', '" . addslashes($article['TITLE']) . "', '" . addslashes($article['TITLE_SEO']) . "', '" . addslashes($article['SUBTITLE']) . "', '" . addslashes($article['SUMARY']) . "', '" . addslashes($article['INTRO']) . "', '" . $article['THUMBNAIL'] . "')";

		checkingQuery($connectBD, $q);
		
		$id_temp = mysqli_insert_id($connectBD);
		
	/*COPIA DE PARRAFOS*/
		$q = "SELECT * FROM ".preBD."paragraphs WHERE IDARTICLE = " . $x;
		$q .= " order by POSITION asc";
		
		$result_blocks = checkingQuery($connectBD, $q);
		while($block = mysqli_fetch_assoc($result_blocks)){
			
			if($block["IMAGE"] != "" && $block["IMAGE"] != $article['THUMBNAIL']) {		
				$origen_img = "../files/articles/image/".$block["IMAGE"];
				$origen_thumb = "../files/articles/thumb/".$block["IMAGE"];
				$destino_img = "../files/articles_temp/image/".$block["IMAGE"];
				$destino_thumb = "../files/articles_temp/thumb/".$block["IMAGE"];
				if(file_exists($origen_img) && !file_exists($destino_img)) {
					copy($origen_img, $destino_img);
				}
				if(file_exists($origen_thumb) && !file_exists($destino_thumb)) {
					copy($origen_thumb, $destino_thumb);
				}
			}
			if($block["TYPE"] == "video") {
				$origen_V = "../files/articles/video/".$block["VIDEO"];
				$destino_V = "../files/articles_temp/video/".$block["VIDEO"];
				if(file_exists($origen_V) && !file_exists($destino_V)) {
					copy($origen_V, $destino_V);
				}
			}
			$block['TEXT'] = stripslashes($block['TEXT']);
			$q2 = "INSERT INTO ".preBD."paragraphs_temp (`TITLE`, `TEXT`, `IMAGE`, `VIDEO`, `FOOT`, `ALIGN`, `IDARTICLE`, `POSITION`, `TYPE`, `LINK`, `TARGET`, `IDALBUM`) VALUES";
			$q2 .= "('" . addslashes($block['TITLE']) . "','" . addslashes($block['TEXT']) . "','" . $block['IMAGE'] . "','" . $block['VIDEO'] . "','" .addslashes($block['FOOT']) . "','" . $block['ALIGN'] . "','" . $id_temp . "', '".$block['POSITION']."', '".$block['TYPE']."', '" . $block['LINK'] . "' ,'" . $block['TARGET'] . "','" . $block['IDALBUM'] . "')";
			checkingQuery($connectBD, $q2);
			
			$paragraphs = mysqli_insert_id($connectBD);
			
			$q3 = "select * from ".preBD."paragraphs_file where IDPARAGRAPH = " . $block["ID"];
			
			$result_file = checkingQuery($connectBD, $q3);
			while($files = mysqli_fetch_assoc($result_file)){
				$q4 = "INSERT INTO ".preBD."paragraphs_file_temp (IDCOPY,`IDPARAGRAPH`, `TITLE`, `URL`, `SIZE`, `POSITION`) VALUES";
				$q4 .= "('".$files["ID"]."', '".$paragraphs."', '".$files["TITLE"]."', '".$files["URL"]."', '".$files["SIZE"]."', '".$files["POSITION"]."')"; 
				checkingQuery($connectBD, $q4);
				
			}
		}
		return $id_temp;
	}
	
	
	function deleteArticleTemp($article){
		global $connectBD;
		$q = "SELECT * FROM ".preBD."articles_temp WHERE ID='" . $article . "'";
		
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$Thumbnail_name = $row['THUMBNAIL'];
		
		$q1 = "SELECT * FROM ".preBD."paragraphs_temp WHERE IDARTICLE = '" . $article . "'";
		
		$result1 = checkingQuery($connectBD, $q1);
		$i = 1;
		$v = 0;
		while($row_blocks = mysqli_fetch_assoc($result1)) {
			$q3 = "DELETE FROM ".preBD."paragraphs_file_temp WHERE ID='".$row_blocks["ID"]."'";
			checkingQuery($connectBD, $q3);
			
			$images[$i] = $row_blocks["IMAGE"];
			if($row_blocks["TYPE"] == "video") {
				$videos[$v] = $row_blocks["VIDEO"];
				$v++;
			}
			$i++;
		}
		//BORRADO DE ARTICULOS Y PARRAFOS		
		$q = "DELETE FROM ".preBD."articles_temp WHERE ID='".$article."'";
		checkingQuery($connectBD, $q);
		
		$q = "DELETE FROM ".preBD."paragraphs_temp WHERE IDARTICLE = '" . $article . "'";
		checkingQuery($connectBD, $q);
		
		
		//BORRADO DE IMÁGENES
		$delete_thumb_article = FALSE;
		for($j=1;$j < $i;$j++) {
			if ($images[$j] != ""){
				if ($Thumbnail_name != $images[$j]){
					if($images[$j] != "") {
						$urlDelete = "../../../files/articles_temp/image/" . $images[$j];
						deleteFile($urlDelete);
						$urlDelete = "../../../files/articles_temp/thumb/" . $images[$j];
						deleteFile($urlDelete);
					}
					
				} else {
					$delete_thumb_article = TRUE;	
				}
			}
		}
		if ($delete_thumb_article) {
			if($Thumbnail_name != "") {
				$urlDelete = "../../../files/articles_temp/image/" . $Thumbnail_name;
				deleteFile($urlDelete);
				$urlDelete = "../../../files/articles_temp/thumb/" . $Thumbnail_name;
				deleteFile($urlDelete);
			}
		}
		for($j=1;$j < count($videos);$j++) {
			$url_video = "../files/articles_temp/video/".$videos[$i];
			if ($videos[$j] != "" && file_exists($url_video)){
				unlink($videos[$j]);
			}
		}
		
			
	}	
	
	function sizeImgForSection($section_id, $alignment) {
		global $connectBD;
		$size = array();
	//ancho imagen en grande	
		if ($alignment == 'center') {
			$q = "SELECT IMAGE_C FROM ".preBD."articles_sections WHERE ID = '" . $section_id . "'";
			$result = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_array($result);
			$size["big"] = $row['IMAGE_C'];
		} else {
			$q = "SELECT IMAGE_LR FROM ".preBD."articles_sections WHERE ID = '" . $section_id . "'";
			$result = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_array($result);
			$size["big"] = $row['IMAGE_LR'];
  		}
	//size thumb
		$q = "SELECT * FROM ".preBD."articles_sections WHERE ID = '" . $section_id . "'";
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$size["w-thumb"] = $row['THUMB_WIDTH'];
		$size["h-thumb"] = $row['THUMB_HEIGHT'];
		
		return $size;
	}

//REGLA DE MAQUETACION	
	function createruler($section_id, $image_url, $image_thumb_url, $source_url) {
		global $connectBD;
		$q = "SELECT * FROM ".preBD."articles_sections WHERE ID = '" . $section_id . "'";
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$image_width = $row['IMAGE_LR'];
		$image_thumb_width = $row['THUMB_WIDTH'];
		$image_thumb_height = $row['THUMB_HEIGHT'];
		
		$source = imagecreatefromgif($source_url);
		$source_info = getimagesize($source_url);
		
		$source_thumb = imagecreatefromgif($source_url);
		$source_thumb_info = getimagesize($source_url);
		
		$source_width = $source_info[0];
		$source_height = $source_info[1];
		$source_proportion = $source_width / $source_height;
		if ($image_width > $source_width) {
			$image_width = $source_width;
		}
		
		$source_thumb_width = $source_thumb_info[0];
		$source_thumb_height = $source_thumb_info[1];
		$source_thumb_proportion = $image_thumb_width /$image_thumb_height;
		if ($image_thumb_width > $source_thumb_width) {
			$image_thumb_width = $source_thumb_width;
		}
		
		$image_height = $source_height;
		$image_url .= "ruler.gif";
		$image_thumb_url .= "ruler.gif";
		
		$image = imagecreate($image_width, $image_height);
		$xstart = 800 - $image_width;
		imagecopy($image, $source, 0, 0, $xstart, 0, $image_width, $image_height);
		imagegif($image,$image_url);

		$image_thumb = imagecreate($image_thumb_width, $image_thumb_height);
		$xstart_thumb = 800 - $image_thumb_width;
		
		imagecopy($image_thumb, $source_thumb, 0, 0, $xstart_thumb, 0, $image_thumb_width, $image_thumb_height);
		imagegif($image_thumb,$image_thumb_url);

	}
	
	
	
	function deleteImageParagraph($image){
		if($image != "") {
			$url = "../../../files/articles/image/" . $image;
			$url_thumb = "../../../files/articles/thumb/" . $image;
			if (file_exists($url)){
				unlink($url);
			}
			if (file_exists($url_thumb)){
				unlink($url_thumb);
			}
		}
	}
	
	
	
	function deleteVideoParagraph($video){
		if($video != "") {
			$url = "../../../files/articles/video/" . $video;
			if (file_exists($url)){
				unlink($url);
			}
		}
	}
	
	function deleteParagraphFile($file){
		global $connectBD;
		$q = "select * from ".preBD."paragraphs_file_temp where ID = " . $file; 
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_assoc($result);
		
		$position = $row['POSITION'];
		$parent = $row['IDPARAGRAPH'];
		
		$url = "../../../files/articles/doc/" . $row["URL"];
		if (file_exists($url)){
			unlink($url);
		}
		$q = "DELETE FROM ".preBD."paragraphs_file_temp WHERE ID = '" . $file . "'";
		checkingQuery($connectBD, $q);
		
		$q1 = "DELETE FROM ".preBD."paragraphs_file WHERE ID = '" . $row["IDCOPY"] . "'";
		checkingQuery($connectBD, $q1);
		
		
		/*actualizamos posiciones*/
		$q_s_update = "SELECT * FROM ".preBD."paragraphs_file_temp WHERE POSITION > ".$position ." and IDPARAGRAPH = ".$parent;
		checkingQuery($connectBD, $q_s_update);
		
		$result_s_update = checkingQuery($connectBD, $q_s_update);
		while ($row_update = mysqli_fetch_assoc($result_s_update)) {
			$q_up = "UPDATE ".preBD."paragraphs_file_temp SET POSITION = '".($row_update["POSITION"] - 1)."' WHERE ID = ".$row_update["ID"]." AND IDPARAGRAPH = ".$parent;
			checkingQuery($connectBD, $q_up);
			
		}
	}

	function constructBlogRSS($id) {
		global $connectBD;
		$q = "select * from ".preBD."blog where ID = ".$id;
		$result = checkingQuery($connectBD, $q);
		$blog = mysqli_fetch_object($result);
		
		$date_joker = "0000-00-00 00:00:00";
		$now = date('Y').date('m').date('d').date('H').date('i').date('s');
		
		$q = "select * from ".preBD."articles_sections where ID = ".$blog->IDSECTION;
		$result = checkingQuery($connectBD, $q);
		$section = mysqli_fetch_object($result);
		
		$title_blog = $blog->SLUG;	
		$aux = $section->TITLE;
		$urlBLOG = DOMAIN."blog/".$blog->SLUG;	
		
		$code = "<?xml version='1.0'"." encoding='ISO-8859-1'?>";
		$code .= '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">';
		$code .= '<channel>';
		$code .= '<title><![CDATA[El blog de '.utf8_decode($section->TITLE_SEO).']]></title>';
		$code .= '<link>'.$urlBLOG.'</link>';
		$title_sec = utf8_decode(stripslashes($section->DESCRIPTION));
		$code .= '<description><![CDATA['.formatNameUrl($title_sec).']]></description>';
		$code .= '<language>es-es</language>';
		$code .= '<copyright><![CDATA['.utf8_decode($aux).']]></copyright>';
		
		//Articulos
		$q = "select ID, TITLE, SUMARY, DATE_START from ".preBD."articles where IDSECTION = " . $section->ID;
		$q .= " and STATUS = 1";
		$q .= " and TRASH = 0";
		$q .= " and DATE_START <= ".$now." and (DATE_END = '" . $date_joker . "' or DATE_END >= ".$now.")";
		$q .= " order by DATE_START desc";		
		$result = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_object($result)) {
			$url = formatNameUrl($row->TITLE) . "_aa".$row->ID . ".html";
			$code .= '<item>';
			$title_art = clrAll(stripslashes($row->TITLE));
			$code .= '<title>'.utf8_decode($title_art).'</title>';
			$code .= '<link>'.$urlBLOG."/".$url.'</link>';
			
			if($row->SUMARY != ""){			
				$aux = clrAll(stripslashes($row->SUMARY));
				if(strlen(strip_tags($row->SUMARY)) > 300){ 
					$prueba = utf8_decode($aux);
					$prueba2 = substr($prueba,0,297)."...";
					$sumary = utf8_encode($prueba2);	
				}else{
					$sumary = $aux;
				}								
			}else{
				$sumary = calculateResume($row->ID, 300);
			}
			$code .= '<description><![CDATA['.utf8_decode($sumary).']]></description>';
			$date = explode(" ", $row->DATE_START);
			$code .= '<pubDate>'.$date[0]."</pubDate>";
			$code .= '</item>';
		}
		
		$code .= '</channel>';
		$code .= '</rss>';
		
		$file = $title_blog.".rss";
		$path = "../../../RSS/" . $file;
		$mode = "w+";
		
		if($fp = fopen($path,$mode)) {
		   fwrite($fp,$code);
		   fclose($fp);
		}
	}

	function renameBlogRSS($old, $new) {
		$old_url = "../../../RSS/" . $old;
		$new_url = "../../../RSS/" . $new;
		
		rename($old_url, $new_url);
	}


function articlesRelated($id) {
		global $connectBD;
		$related = array();
		$q = "select 
				".preBD."articles.ID,
				".preBD."articles.TITLE,
				".preBD."articles.IDSECTION
			from ".preBD."blog_related
			inner join ".preBD."articles
			on (".preBD."articles.ID = ".preBD."blog_related.ID1 or ".preBD."articles.ID = ".preBD."blog_related.ID2) 
			where (".preBD."blog_related.ID1 = ".$id." or ".preBD."blog_related.ID2 = ".$id.") 
			and ".preBD."articles.ID != ".$id." 
			and ".preBD."articles.STATUS != 0 
			and ".preBD."articles.TRASH = 0 
			and ".preBD."articles.TYPE = 'blog' 
			group by ".preBD."blog_related.ID 
			order by ".preBD."articles.DATE_START desc";
		$r = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_object($r)) {
			$related[] = $row;
		}
		return $related;
	}



?>