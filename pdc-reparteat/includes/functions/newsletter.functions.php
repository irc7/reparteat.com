<?php
	/*Declaracion de variable global ERRORMAIL, nº de errores permitido por suscripción en el envío del newsletter*/
	$q = "select  VALUE from ".preBD."configuration where ID = 17";
	$r = checkingQuery($connectBD, $q);
	$config = mysqli_fetch_object($r);
	define("ERRORMAIL" , $config->VALUE);
	
	function getgroup($id) {
		global $connectBD;
		$q = "SELECT TITLE FROM ".preBD."groups_subscriptions WHERE ID = '" . $id . "'";
		
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$team = $row['TITLE'];
		if ($team!= NULL) {
			return $team;
		}
		else {
			return "Ninguna";
		}
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
	
	function checkingSuscription($idN, $idS, $idT) {
		global $connectBD;
		$q = "select * from ".preBD."subscriptions where ID = " . $idS;
		$r = checkingQuery($connectBD, $q);
		$sus = mysqli_fetch_object($r);
		
		switch($sus->STATUS) {
			case 0:
			//Registro de error pq el suscriptor esta desactivado
				$typeError = "errorSuscriptor";
				$textError = $sus->ID . ".- " . $sus->MAIL . " - <span style=\"color:#c00\">Suscriptor desactivado temporalmente.</span>";
				registerError($idN, $idS, $sus->MAIL, $sus->ERROR, $typeError, $textError);
				//eliminamos de la cola
				$q_error = "DELETE FROM `".preBD."newsletter_trail` WHERE ID = " . $idT;
				checkingQuery($connectBD, $q_error);
				return false;
			break;
			case 1:
				return true;
			break;
			case 2:
			//Registro de error pq el suscriptor esta desactivado
				$typeError = "errorSuscriptor";
				$textError = $sus->ID . ".- " . $sus->MAIL . " - <span style=\"color:#c00\">Suscriptor dado de baja.</span>";
				registerError($idN, $idS, $sus->MAIL, $sus->ERROR, $typeError, $textError);
				//eliminamos de la cola
				$q_error = "DELETE FROM `".preBD."newsletter_trail` WHERE ID = " . $idT;
				checkingQuery($connectBD, $q_error);
			
				return false;
			break;
		}
	
	}
	
	
	function cleanTrail($id) {
		global $connectBD;
		$q = "DELETE FROM ".preBD."newsletter_trail WHERE IDNEWSLETTER = '".$id."'";
		checkingQuery($connectBD, $q);
	}
	
	
	function cleanError($id) {
		global $connectBD;
		$qE = "select * from ".preBD."newsletter_mailsend where IDNEWSLETTER = " . $id . " and RESULT = 2";
		$rE = checkingQuery($connectBD, $qE);
		$totalError = mysqli_num_rows($rE);

		$q = "update ".preBD."newsletter set SEND_OFF = SEND_OFF + " . $totalError . " where ID = " . $id;
		checkingQuery($connectBD, $q);
		
		$q = "DELETE FROM ".preBD."newsletter_mailsend WHERE IDNEWSLETTER = '".$id."' and RESULT = 2";
		checkingQuery($connectBD, $q);
		
	}
	
	
	function cleanSend($id) {
		global $connectBD;
		$qS = "select ID, IDSUBSCRIPTION, MAIL from ".preBD."newsletter_mailsend where IDNEWSLETTER = " . $id . " and RESULT = 1";
		$rS = checkingQuery($connectBD, $qS);
		$totalSend = mysqli_num_rows($rS);
		
		$q = "update ".preBD."newsletter set SEND_OK = " . $totalSend . " where ID = " . $id;
		checkingQuery($connectBD, $q);
		
		$q = "DELETE FROM ".preBD."newsletter_mailsend WHERE IDNEWSLETTER = '".$id."' and RESULT = 1";
		checkingQuery($connectBD, $q);
	}
	
	function registerError($idNewsletter,$idSus,$mailSus, $numError, $typeError, $textError) {
		global $connectBD;
		$q = "INSERT INTO `".preBD."newsletter_mailsend`(`IDNEWSLETTER`, `IDSUBSCRIPTION`, `MAIL`, `RESULT`, `ERROR`) 
		VALUES 
		('".$idNewsletter."','".$idSus."','".$mailSus."',2,'".$textError."')";
		checkingQuery($connectBD, $q);	
		if($typeError != "errorSuscriptor") {//error por darse de baja o desactivarse en el proceso
			$q = "update ".preBD."subscriptions set ERROR = '".($numError+1)."' where  ID = " . $idSus;	
			checkingQuery($connectBD, $q);
		}
		if(ERRORMAIL <= ($numError+1)) {
			$q = "update ".preBD."subscriptions set STATUS = 3 where  ID = " . $idSus;	
			checkingQuery($connectBD, $q);
		}
	}

	
	
	
	
	function newsletterTitleSection($idSec, $typeArt) {
		global $connectBD;
		
		$q = "select ".preBD."articles_sections.TITLE as title,";
				if($typeArt == "blog") {
					$q .= preBD."blog.SLUG as slug";			
				}else {
					$q .= preBD."url_web.SLUG as slug";
				}
		$q .= "	from ".preBD."articles_sections"; 
				if($typeArt == "blog") {
					$q .= " left join ".preBD."blog 
							on ".preBD."articles_sections.ID = ".preBD."blog.IDSECTION"; 
				}else {
					$q .= " left join ".preBD."url_web
							on ".preBD."articles_sections.ID = ".preBD."url_web.SEC_VIEW and ".preBD."url_web.TYPE = 'section'"; 
				}
		$q .= " where ".preBD."articles_sections.ID = " . $idSec;
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_assoc($result);
		return $row;
	}

	function newsletterInfoArticle($idArt, $typeArt) {
		global $connectBD;
		$q = "select ".preBD."articles.ID as id,
			".preBD."articles.TITLE_SEO as title,
			".preBD."articles.SUMARY as sumary,
			".preBD."articles.THUMBNAIL as image,
			".preBD."articles.DATE_START as dateA,
			".preBD."articles_sections.TYPE as type,
			".preBD."articles.IDSECTION as idS,
			".preBD."url_web.SLUG as slug
			from ".preBD."articles 
			left join ".preBD."articles_sections 
			on ".preBD."articles.IDSECTION = ".preBD."articles_sections.ID 
			left join ".preBD."url_web 
			on ".preBD."articles.ID = ".preBD."url_web.ID_VIEW and ".preBD."url_web.TYPE = '".$typeArt."' 
			where ".preBD."articles.ID = " . $idArt;
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($result);
		
		$info["section"] = newsletterTitleSection($row->idS, $typeArt);
		$info["section"]["title"] = tildesEnMayuscula(utf8_encode(strtoupper(utf8_decode($info["section"]["title"]))));
		
		$info["id"] = $idArt;
		
		$info["title"] = stripslashes($row->title);
		$info["title"] = str_replace('‘', '"', $info["title"]);
		$info["title"] = str_replace('’', '"', $info["title"]);
		
		
		$caesura = 360;
		if($row->sumary == "") {
			$info["sumary"] = calculateResume($idArt, $caesura);
		} else {
			if(strlen(stripslashes($row->sumary)) > $caesura) {
				$info["sumary"] = cutting($row->sumary,$caesura);
			} else {
				$info["sumary"] = stripslashes($row->sumary);
			}
		}
		if($row->type == "blog") {
			$info["slug"] = DOMAINBLOG.$row->slug;
		}else if($row->type == "fundacion") {
			$info["slug"] = DOMAINFUNDACION.$row->slug;
		}else{
			$info["slug"] = $row->slug;
		}
		$info["image"] = $row->image;
		$info["dateStart"] = $row->dateA;
		
		return $info;
	}
	
	
	function selectThumbnail($img, $t) {
		global $connectBD;
		$url = "../../../files/articles/".$t."/";
		$urlAbs = DOMAIN."files/articles/".$t."/";
		$type = "image";
		if($img == "") {
			$urlAbs.= "default.jpg";
		} else {
			$urlAbs.= $img;
			$url.= $img;
			if(!file_exists($url)) {
				$p = explode("=", $img);
				$urlAbs = "http://img.youtube.com/vi/".$p[1]."/0.jpg";
				$type = "youtube";
			}
		}
		
		$image = array();
		$image["url"] = $urlAbs;
		$image["urlRelative"] = $url;
		$image["type"] = $type;
		
		return $image;
	}
	function manualUpper($a) {
		
		$a = str_replace("á","Á",$a);
		$a = str_replace("é","É",$a);
		$a = str_replace("í","Í",$a);
		$a = str_replace("ó","Ó",$a);
		$a = str_replace("ú","Ú",$a);
		return $a;
	}
	function displayItemMenu($title_item, $display, $thumbnail){
		$item2 = "";
		switch($display) {
			case 1:
				$item2 .= $title_item;
			break;
			
			case 2:
				$item2 .= '<img src="'.DOMAIN.'files/menus/image/'.$thumbnail.'" alt="'.$title_item.'" title="'.$title_item.'" border="0" width="30" height="30" style="width:30px;height:30px;margin-right:15px;" />';			
			break;	
			
			case 3:
				$item2 .= '<img src="'.DOMAIN.'"files/menus/image/'.$thumbnail.'" alt="'.$title_item.'" title="'.$title_item.'" border="0" />';			
				$item2 .= '<span>'.$title_item.'</span>';			
			break;
		}
		return $item2;		
	}
	
	
	function constructItemMenu($title_item, $type, $view, $target, $display, $thumbnail, $zone) {
		global $connectBD;
		$item = '';
		if($zone == "header") {
			$style = "line-height:40px;padding-left:5px;padding-right:5px;text-decoration:none !important;color:#ffffff;font-family: 'Oswald', Arial, Verdana, sans-serif;font-weight: 400;font-size: 15px;";
		}elseif($zone == "footer") {
			$style = "text-decoration:none !important;color:#ffffff;font-family:'Arimo', Tahoma, 'Trebuchet MS', sans-serif;font-weight: 400;font-size: 12px;";
		}else {
			$style = "text-decoration:none !important;color:#ffffff;font-family: 'Oswald', Arial, Verdana, sans-serif;";
		}
		switch($type) {
			case -1:
				$item .= '<a class="link-mnu" style="'.$style.'" href="'.stripslashes($view).'#STATISTICS_PARAM#" alt="'.$title_item.'" target="'.$target.'">';				
					$texto = displayItemMenu($title_item, $display, $thumbnail);				
				$item .= $texto.'</a>';
			break;
			case 0:
				$item .= '<span class="link-mnu" style="'.$style.'">';
					$texto = displayItemMenu($title_item, $display, $thumbnail);
				$item .= $texto.'</span>';			
			break;
			case 1:
				$q = "select ".preBD."url_web.TITLE as seo, 
							".preBD."url_web.SLUG as slug,
							".preBD."articles_sections.TITLE as title
							from ".preBD."url_web 
							left join ".preBD."articles_sections on ".preBD."articles_sections.ID = ".preBD."url_web.SEC_VIEW
							where ".preBD."url_web.SEC_VIEW = " . $view . " and ".preBD."url_web.TYPE = 'section'";
				
				$result = checkingQuery($connectBD, $q);
				$row = mysqli_fetch_object($result);
				
				$item .= '<a class="link-mnu" style="'.$style.'" href="'.DOMAIN.$row->slug.'#STATISTICS_PARAM#" target="'.$target.'" alt="'.$row->seo.'">';				
					$texto = displayItemMenu($title_item, $display, $thumbnail);								
				$item .= $texto.'</a>';
			
			break;
			case 2:
				$q = "select ".preBD."url_web.TITLE as seo, 
							".preBD."url_web.SLUG as slug,
							".preBD."articles.TITLE as title
							from ".preBD."url_web 
							left join ".preBD."articles on ".preBD."articles.ID = ".preBD."url_web.ID_VIEW
							where ".preBD."url_web.ID_VIEW = " . $view . " and ".preBD."url_web.TYPE = 'article'";
				
				$result = checkingQuery($connectBD, $q);
				$row = mysqli_fetch_object($result);
				$item .= '<a class="link-mnu" style="'.$style.'" href="'.DOMAIN.$row->slug.'#STATISTICS_PARAM#" target="'.$target.'" alt="'.$row->seo.'">';
					$texto = displayItemMenu($title_item, $display, $thumbnail);				
				$item .= $texto.'</a>';
				
			break;
		}
		return $item;
	}
	
	
	
	function titleFromUrl($name){
		$name = strip_tags($name);
		//$name = utf8_decode($name);
		$name = str_replace(" ", "-", $name);
		$name = str_replace("ñ", "n", $name);
		$name = str_replace("ç", "c", $name);
		$name = str_replace("á", "a", $name);
		$name = str_replace("é", "e", $name);
		$name = str_replace("í", "i", $name);
		$name = str_replace("ó", "o", $name);
		$name = str_replace("ú", "u", $name);
		$name = str_replace("à", "a", $name);
		$name = str_replace("è", "e", $name);
		$name = str_replace("ì", "i", $name);
		$name = str_replace("ò", "o", $name);
		$name = str_replace("ù", "u", $name);
		$name = str_replace("ä", "a", $name);
		$name = str_replace("ë", "e", $name);
		$name = str_replace("ï", "i", $name);
		$name = str_replace("ö", "o", $name);
		$name = str_replace("ü", "u", $name);
		$name = str_replace("Ñ", "N", $name);
		$name = str_replace("Ç", "C", $name);
		$name = str_replace("Á", "A", $name);
		$name = str_replace("É", "E", $name);
		$name = str_replace("Í", "I", $name);
		$name = str_replace("Ó", "O", $name);
		$name = str_replace("Ú", "U", $name);
		$name = str_replace("À", "A", $name);
		$name = str_replace("È", "E", $name);
		$name = str_replace("Ì", "I", $name);
		$name = str_replace("Ò", "O", $name);
		$name = str_replace("Ù", "U", $name);
		$name = str_replace("Ä", "A", $name);
		$name = str_replace("Ë", "E", $name);
		$name = str_replace("Ï", "I", $name);
		$name = str_replace("Ö", "O", $name);
		$name = str_replace("Ü", "U", $name);
		$name = str_replace(".", "", $name);
		$name = str_replace("'", "", $name);
		$name = str_replace('"', '', $name);
		$name = str_replace('“', '', $name);
		$name = str_replace('”', '', $name);
		$name = str_replace("/", "-", $name);
		$name = str_replace("&", "-", $name);
		$name = str_replace("#", "-", $name);
		$name = str_replace("@", "-", $name);
		$name = str_replace(",", "-", $name);
		$name = str_replace("?", "-", $name);
		$name = str_replace("¿", "-", $name);
		$name = str_replace("=", "-", $name);
		$name = str_replace("!", "-", $name);
		$name = str_replace("¡", "-", $name);
		$name = str_replace("[", "-", $name);
		$name = str_replace("]", "-", $name);
		$name = str_replace("(", "-", $name);
		$name = str_replace(")", "-", $name);
		$name = str_replace("{", "-", $name);
		$name = str_replace("}", "-", $name);
		$name = str_replace("Ç", "-", $name);
		$name = str_replace("*", "-", $name);
		$name = str_replace("+", "-", $name);
		$name = str_replace(";", "-", $name);
		$name = str_replace(":", "-", $name);
		$name = str_replace("<", "-", $name);
		$name = str_replace(">", "-", $name);
		$name = str_replace("|", "-", $name);
		$name = str_replace("ª", "", $name);
		$name = str_replace("º", "", $name);
		$name = str_replace("€", "-euro", $name);
		$name = str_replace("$", "-dolar", $name);
		$name = str_replace("¥", "-yen", $name);
		$name = str_replace("£", "-libra", $name);
		$name = str_replace("¬", "-", $name);
		$name = str_replace("\"", "-", $name);
		$name = str_replace(".", "", $name);
		$name = str_replace("----", "-", $name);
		$name = str_replace("---", "-", $name);
		$name = str_replace("--", "-", $name);
		
		
		$name = strtolower($name);
		$name = urlencode($name);
		return $name;
	}	
	function securitySMTP($v) {
		switch($v) {
			case 0:
				return "";
			break;
			case 1:
				return "SSL";
			break;
			case 2:
				return "TLS";
			break;
		}
	}
	function orderEvents($ag) {
		$e = 0;
		$e2 = 0;
		$agenda = array();
		for($i=0;$i<count($ag);$i++) {
			if(count($agenda) != 0) {
				$dateCheck = $agenda[$e][$e2]["bd"]["dateS"];
			}
			$dateStart = $ag[$i]["dateS"];
			if(count($agenda) == 0) {
				$agenda[$e][$e2]["bd"] = $ag[$i];
				$agenda[$e][$e2]["date"] = $dateStart->getTimestamp();
				$agenda[$e][$e2]["type"] = "event";
			}elseif($dateCheck->format("Ymd") == $dateStart->format("Ymd")){
				$e2++;
				$agenda[$e][$e2]["bd"] = $ag[$i];
				$agenda[$e][$e2]["date"] = $dateStart->getTimestamp();
				$agenda[$e][$e2]["type"] = "event";
			} else {
				$e2 = 0;
				$e++;
				$agenda[$e][$e2]["bd"] = $ag[$i];
				$agenda[$e][$e2]["date"] = $dateStart->getTimestamp();
				$agenda[$e][$e2]["type"] = "event";
			}
		}
		return $agenda;
	}
	
	
	function descriptionEvent($dateTimeStart, $dateTimeEnd) {
		$days = array('Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'S&aacute;bado', 'Domingo');
		$months = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
		
		$code = "";
		$timeStampStart = $dateTimeStart->getTimestamp();
		$timeStampEnd = $dateTimeEnd->getTimestamp();
		if($timeStampStart == $timeStampEnd) {
			$code .= "El ".$days[$dateTimeStart->format("w")].", ".$dateTimeStart->format("d")." de ". $months[(intval($dateTimeStart->format("m"))-1)].", 
					 a las " .$dateTimeStart->format("H:i")."h";
		}else if(($dateTimeStart->format("Ymd") == $dateTimeEnd->format("Ymd")) && $timeStampStart < $timeStampEnd) {
			$code .= "El ".$days[$dateTimeStart->format("w")].", ".$dateTimeStart->format("d")." de ". $months[(intval($dateTimeStart->format("m"))-1)].", 
					 de " .$dateTimeStart->format("H:i")."h a ". $dateTimeEnd->format("H:i") . "h";
			
		}else if(($dateTimeStart->format("Ymd") != $dateTimeEnd->format("Ymd"))) {
			$code .= "Desde ".$days[$dateTimeStart->format("w")].", ".$dateTimeStart->format("d")." de ". $months[(intval($dateTimeStart->format("m"))-1)].", 
					hasta ".$days[$dateTimeEnd->format("w")].", ".$dateTimeEnd->format("d")." de ". $months[(intval($dateTimeEnd->format("m"))-1)].",
					 de " .$dateTimeStart->format("H:i")."h a ". $dateTimeEnd->format("H:i") . "h";
					
		}
		return $code;
	}
?>