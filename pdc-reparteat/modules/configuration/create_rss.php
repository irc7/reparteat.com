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
	$error = NULL;
	if($_POST) {
		
		$sections = $_POST["destino"];
		$text = "";
		for($i=0;$i<count($sections);$i++) {
			$text .= "".$sections[$i];
			if($i<count($sections)-1){
				$text .= "#-RSS-#";
			}
		}
		
		$q = "UPDATE `".preBD."configuration` SET `TEXT`='".$text."' WHERE ID = 9";
		checkingQuery($connectBD, $q);
		$msg = "Rss global guardado correctamente.";
		
		
		
/*seleccionamos la descripción de la Web
		$q = "select * from ".preBD."configuration where ID = 5 or ID = 9";
		$result = checkingQuery($connectBD, $q);
		$description = mysqli_fetch_object($result);
		$config = mysqli_fetch_object($result);
		
		$secs = explode("#-RSS-#", $config->TEXT);
		
		
		$code = '<?xml version="1.0" encoding="ISO-8859-1"?>';
		
		$code .= '
		<rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">
			<channel>
				<title><![CDATA['.TITLEWEB.']]></title>
				<link>'.DOMAIN.'</link>
				<description>
					<![CDATA['.stripslashes($description->TEXT).']]>
				</description>
				<language>es</language>';
		$code .='<copyright>
					<![CDATA[ (c) 2014, '.SHORTTITLE.' ]]>
				</copyright>';
		$code .= '
					<pubDate>'.date("D").', '.date("d").' '.date("M").' '.date("Y").' '.date("H").':'.date("i").':'.date("s").' '.date("O").'</pubDate>
					<lastBuildDate>'.date("D").', '.date("d").' '.date("M").' '.date("Y").' '.date("H").':'.date("i").':'.date("s").' '.date("O").'</lastBuildDate>
					<ttl>60</ttl>
					<atom:link href="'.DOMAIN.'RSS/'.formatNameUrl(SHORTTITLE).'.rss" rel="self" type="application/rss+xml"/>
					<image>
						<title>
							<![CDATA['.TITLEWEB.']]>
						</title>';
						$urlLogo = '../../images/logorss.jpg';
						if(file_exists($urlLogo)) {
							$Size = getimagesize($urlLogo);
							$urlLAbs = DOMAIN."pdc-reparteat/images/logorss.jpg";
						}else {
							$urlLAbs = "";
						}
			$code .= '
						<url>'.$urlLAbs.'</url>
						<link>'.DOMAIN.'</link>
						<width>'.$Size[0].'</width>
						<height>'.$Size[1].'</height>
						<description>
							<![CDATA['.stripslashes($description->TEXT).']]>
						</description>
					</image>';
				
			$q = "select ".preBD."articles.ID as idA,
				".preBD."articles.TITLE as tA,
				".preBD."articles.TITLE_SEO as tsA,
				".preBD."articles.SUMARY as resA,
				".preBD."articles.DATE_START as dateA,
				".preBD."articles.THUMBNAIL as image,
				".preBD."articles.TYPE as type,
				".preBD."articles_sections.ID as idS,
				".preBD."articles_sections.TITLE as tS,
				".preBD."articles_sections.TITLE_SEO as tsS,
				".preBD."articles_sections.THUMB_WIDTH as image_W,
				".preBD."articles_sections.THUMB_HEIGHT as image_H,
				".preBD."paragraphs.TYPE as icon,
				".preBD."paragraphs.TEXT as text
				from ".preBD."articles 
				left join ".preBD."articles_sections 
				on ".preBD."articles.IDSECTION = ".preBD."articles_sections.ID 
				left join ".preBD."paragraphs 
				on ".preBD."articles.ID = ".preBD."paragraphs.IDARTICLE and POSITION = 1
				where true";
				for($i=0;$i<count($secs); $i++) {
					if(count($secs) >1) {
						if($i == 0) {
							$q.= " and (IDSECTION = ".$secs[$i];
						}elseif($i == count($secs) - 1){
							$q.= " or IDSECTION = ".$secs[$i] . ")";
						}else {
							$q.= " or IDSECTION = ".$secs[$i];
						}
					}else {
						$q.= " and IDSECTION = ".$secs[$i];
					}
				}
				$q .= " and STATUS = 1 and TRASH = 0 and DATE_START <= NOW() and (DATE_END = '00-00-00 00:00:00' or DATE_END >= NOW()) order by DATE_START desc limit 0, ". abs(intval($config->VALUE));
		
			$r = checkingQuery($connectBD, $q);
			
			$new = array();
			while($row=mysqli_fetch_object($r)) {
			$code .= '
					<item>
						<title>
							<![CDATA[
							'.stripslashes($row->tA).'
							]]>
						</title>';
					$thumb = selectThumbnail($row->image);
					if($row->resA != "") {
						$sumary = $row->resA;
					}else{
						$sumary = cutting($row->text, $config->AUXILIARY);
					}
					if($row->type == "blog") {
						$q = "select SLUG from ".preBD."blog where IDSECTION = " . $row->idS;
						$r=checkingQuery($connectBD, $q);
							
						$blog = mysqli_fetch_object($r);
						$link = DOMAIN."blog/".$blog->SLUG."/".formatNameUrl(stripslashes($row->tsA))."_aa".$row->idA.".html"; 
					}else {
						$link = DOMAIN.formatNameUrl(stripslashes($row->tsA))."_aa".$row->idA.".html"; 
					}
					preg_match("|\.([a-z0-9]{2,4})$|i", $thumb["url"], $ext);
					$timestamp = strtotime($row->dateA);
			$code .= '
						<description>
							<![CDATA[
							'.$sumary.'
							]]>
						</description>
						<dc:creator>
							<![CDATA['.SHORTTITLE.']]>
						</dc:creator>
						<link>
							'.$link.'
						</link>
						<media:description type="html">
							<![CDATA[
							'.$sumary.'
							]]>
						</media:description>
						<media:title type="html">
							<![CDATA['.stripslashes($row->tsA).']]>
						</media:title>';
				$code .= '
						<media:content url="'.$thumb["url"].'" medium="image" width="'.$thumb["size"][0].'" height="'.$thumb["size"][1].'" type="image/'.strtolower($ext[1]).'"/>
						<media:thumbnail url="'.$thumb["thumb"].'" width="'.$row->image_W.'" height="'.$row->image_H.'"/>
						<guid>
							'.$link.'
						</guid>
						<pubDate>'.date('D, d M Y h:i:s O', $timestamp).'</pubDate>
					</item>';
			}
			$code .= '	
					</channel>
				</rss>';
					
		$file = formatNameUrl(SHORTTITLE).".rss";
		$path = "../../../RSS/" . $file;
		$mode = "w+";
		
		if($fp = fopen($path,$mode)) {
		   fwrite($fp,utf8_decode($code));
		   fclose($fp);
		   $msg .= "Archivo ".$file." creado correctamente.<br/>";
		} else { 
		   $msg .= "Ha habido un problema con el archivo ".$file." no ha sido creado correctamente.</br>";
		}	
*/		
	} else {
		$msg = "Se ha producido un error al crear el rss, inténtelo de nuevo";
	}
	disconnectdb($connectBD);
	$location = "Location: ../../index.php?mnu=configuration&com=configuration&tpl=rss&msg=".utf8_decode($msg);
	header($location);
?>