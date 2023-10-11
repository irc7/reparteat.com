<?php 
	header ('Content-type: text/xml; charset=utf-8');
	require_once ("../pdc-ihp/includes/database.php");
	$connectBD = connectdb();
	require_once ("../pdc-ihp/includes/config.inc.php");
	require_once("../pdc-ihp/includes/functions/global.functions.php");
	require_once("../pdc-ihp/includes/functions/configuration.functions.php");
		
		
		
	if(isset($_GET["s"]) && trim($_GET["s"]) != "") {
		$s = trim($_GET["s"]);
		if(is_numeric($_GET["s"])) {
			$section = abs(intval($s));
		}else{
			$s = trim($_GET["s"]);
			$q = "select IDSECTION from ".preBD."blog where SLUG = '" .$s. "'";
			if(!$r = mysql_query($q)) {
				die("Error(blog section): " . mysql_error());
			}
			if($row = mysql_fetch_object($r)) {
				$section = $row->IDSECTION;
			}else {
				$section = 0;
			}
		}
	}else{
		$section = 0;
	}
	
	if($section != 0) {
	/*seleccionamos la descripción de la Web*/
		$q = "select * from ".preBD."configuration where ID = 5 or ID = 9 or ID = 12 order by ID asc";
		$result = checkingQuery($connectBD,$q);
		
		$description = mysqli_fetch_object($result);
		$config = mysqli_fetch_object($result);
		$image = mysqli_fetch_object($result);
		
		$auxW = explode("#-width-#", $imageRSS->AUXILIARY);
		$maxwidth = $auxW[1]; 
		
		$auxH = explode("#-height-#", $imageRSS->AUXILIARY);
		$maxheight = $auxH[1];
		
		
		$q = "select * from ".preBD."articles_sections where ID = " . $section;
		$result = checkingQuery($connectBD,$q);
		$secBD = mysqli_fetch_object($result);
		
		$code = '<?xml version="1.0" encoding="utf-8"?>';
		
		$code .= '
		<rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" version="2.0">
			<channel>
				<title><![CDATA['.stripslashes($secBD->TITLE). " | " . TITLEWEB.']]></title>
				<link>'.DOMAIN.formatNameUrl($secBD->TITLE).'_as'.$secBD->ID.'.html</link>
				<description>';
				if(trim($secBD->DESCRIPTION) == "") {
					$code .= '<![CDATA['.stripslashes($description->TEXT).']]>';
				} else {
					$code .= '<![CDATA['.stripslashes($secBD->DESCRIPTION).']]>';
				}
		$code .= '
				</description>
				<language>es</language>';
		$code .='<copyright>
					<![CDATA[ (c) 2014, '.SHORTTITLE.' ]]>
				</copyright>';
		$code .= '
					<pubDate>'.date("D").', '.date("d").' '.date("M").' '.date("Y").' '.date("H").':'.date("i").':'.date("s").' '.date("O").'</pubDate>
					<lastBuildDate>'.date("D").', '.date("d").' '.date("M").' '.date("Y").' '.date("H").':'.date("i").':'.date("s").' '.date("O").'</lastBuildDate>
					<ttl>60</ttl>
					<atom:link href="'.DOMAIN.'rss/'.$s.'" rel="self" type="application/rss+xml"/>';
					$viewSize = true;
					if($secBD->THUMBNAIL != "") {
						$urlImg = '../files/section/image/'.$secBD->THUMBNAIL;
						$Size = getimagesize($urlImg);
						if($Size[0] > $maxwidth || $Size[1] > $maxheight) {
							$viewSize = false;
						}
						$urlLAbs = DOMAIN.'files/section/image/'.$secBD->THUMBNAIL;
						$fileImg = $secBD->THUMBNAIL;
					}else{
						$urlImg = '../files/rss/image/'.$image->TEXT;
						$Size = getimagesize($urlImg);
						$urlLAbs = DOMAIN."files/rss/image/".$image->TEXT;
						$fileImg = $image->TEXT;
					}
			if(file_exists($urlImg)) {
				$code .= '
					<image>
						<title><![CDATA['.stripslashes($secBD->TITLE). " | " . TITLEWEB.']]></title>
						<url>'.$urlLAbs.'</url>
						<link>'.DOMAIN.formatNameUrl($secBD->TITLE).'_as'.$secBD->ID.'.html</link>';
					if($viewSize) {	
					$code .= '
						<width>'.$Size[0].'</width>
						<height>'.$Size[1].'</height>';
					}
					$code .= '
						<description>
							<![CDATA['.stripslashes($description->TEXT).']]>
						</description>
					</image>';
			}
		
			
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
				where true and IDSECTION = ".$section."
				and STATUS = 1 and TRASH = 0 and DATE_START <= NOW() and (DATE_END = '00-00-00 00:00:00' or DATE_END >= NOW()) 
				order by DATE_START desc limit 0, ". abs(intval($config->VALUE));
		
			$r = checkingQuery($connectBD,$q);
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
						$r=checkingQuery($connectBD,$q);
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
	}
			$code .= '	
					</channel>
				</rss>';
				
		echo $code;		
?>