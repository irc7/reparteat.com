<?php

	$dias = array('Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado', 'Domingo');
	
	function paramDirect($c) {
		$c = trim($c);
		$len = strlen($c);

		$part1 = substr($c, 0, 2);
		$part2 = substr($c, 2, $len);	
		
		if($part1 == "ss" && $len > 2) {
			$s = explode("-", $part2); 
			$text = $s[0];
			$page = $s[1];
		} else {
			$text = "";
			$page = 0;
		}
		
		$urls = array(
					'aa' => array ('view' => 'article',
									'section' => 0,
									'id' => $part2
					),
					'as' => array ('view' => 'article',
									'section' => $part2,
									'id' => 0
					),
					'ev' => array ('view' => 'event',
									'section' => 0,
									'id' => $part2
					),					
					'vs' => array ('view' => 'video',
									'section' => $part2,
									'id' => 0
					),
					'vv' => array ('view' => 'video',
									'section' => 0,
									'id' => $part2
					),
					'ss' => array('view' => 'search',
									'section' => 0,
									'id' => 0,
									'text' => $text,
									'page' => $page
					),
					'ga' => array ('view' => 'gallery',
									'section' => 0,
									'id' => $part2
					),
					'gs' => array ('view' => 'gallery',
									'section' => $part2,
									'id' => 0
					),					
					'ds' => array('view' => 'download',
									'section' => $part2,
									'id' => $part2
					));
		
		
		$p = $urls[$part1];
		
		return $p;
	}
	function pre($a) {
		echo "<div style='width:100%;border: 1px solid #000; background-color: #fff; padding: 10px; font: normal normal 12px Arial, Verdana;'>";
		echo "<pre>";
		print_r($a);
		echo "</pre>";
		echo "</div>";
	}
	
	function present_date() {
		$now = new DateTime;
		$now = date("Y-m-d H:i:s");
		
		return ($now);	
	} 
	function ObtenerSistemaOperativo() {
	$u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $platform = 'Unknown';

	 if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
	return $platform;
}

function ObtenerNavegador() {
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
	$ub = 'Unknown';
    $version= "";

    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { 
        $bname = 'Internet Explorer'; 
        $navegadores = array(
			'Explorer7' => '(MSIE 7\.[0-9]+)',
			'Explorer8' => '(MSIE 8\.[0-9]+)',
			'Explorer9' => '(MSIE 9\.[0-9]+)'
		);
		foreach($navegadores as $navegador=>$pattern){
			if (preg_match($pattern, $u_agent)){
			   $ub = $navegador;
			} else {
				$ub = 'Explorer';
			}
		}
    } elseif(preg_match('/Firefox/i',$u_agent)) { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } elseif(preg_match('/Chrome/i',$u_agent)){ 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } elseif(preg_match('/Safari/i',$u_agent)) { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } elseif(preg_match('/Opera/i',$u_agent)) { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } elseif(preg_match('/Netscape/i',$u_agent)) { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
	return $ub;
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
	
	
	function displayItemMenu($title_item, $display, $thumbnail){
		$item2 = "";
		switch($display) {
			case 1:
				$item2 .= $title_item;
			break;
			
			case 2:
				$item2 .= "<img src='".DOMAIN."files/menus/image/".$thumbnail."' alt='".$title_item."' title='".$title_item."' />";			
			break;	
			
			case 3:
				$item2 .= "<div class='item-img-mnu'>".$title_item."</div>";			
				$item2 .= "<img src='".DOMAIN."files/menus/image/".$thumbnail."' alt='".$title_item."' title='".$title_item."'/>";			
			break;
		}
		return $item2;		
	}
	
	
	function constructItemMenu($title_item, $type, $view, $target, $display, $thumbnail, $class , $v) {
		$item = "";
		global $connectBD;
		switch($type) {
			case -1:
			//$item .= "<div ".$class.">";
				if(strpos($view, "#") === 0){ //detectamos si es un ancla
					if($v == "home") {
						$item .= "<a class='".$class."' href='".stripslashes($view)."' alt='".strip_tags($title_item)."' title='".strip_tags($title_item)."' target='".$target."' data-type='ancla-home'>";				
					}else{
						$item .= "<a ".$class."href='".DOMAIN."home".stripslashes($view)."' alt='".strip_tags($title_item)."' title='".strip_tags($title_item)."' target='".$target."'>";				
					}
				}else {
					if($view == "en-construccion"){
						$view = DOMAIN.$view;
					}
					if(strpos($view, "http://")===0 || strpos($view, "https://")===0) {
						$item .= "<a class='".$class."' href='".stripslashes($view)."' alt='".strip_tags($title_item)."' title='".strip_tags($title_item)."' target='".$target."'>";				
					}else{
						$item .= "<a class='".$class."' href='".DOMAIN.stripslashes($view)."' alt='".strip_tags($title_item)."' title='".strip_tags($title_item)."' target='".$target."'>";				
					}
				}
						$texto = displayItemMenu($title_item, $display, $thumbnail).$p;
					$item .= $texto."</a>";
			//	$item .= "</div>";		
			break;
			case 0:
			//	$item .= "<div ".$class.">";
				
					//$item = displayItemMenu($title_item, $display, $thumbnail);
					$item = '<a href="#" class="dropdown-toggle '.$class.'" data-toggle="dropdown" data-hover="dropdown" data-delay="300" data-close-others="false">'.$title_item.'</a>';
			//	$item .= $texto."</div>";			
			break;
			case 1:
				$q = "select ".preBD."url_web.TITLE as seo, 
							".preBD."url_web.SLUG as slug,
							".preBD."articles_sections.TITLE as title
							from ".preBD."url_web 
							left join ".preBD."articles_sections on ".preBD."articles_sections.ID = ".preBD."url_web.SEC_VIEW
							where ".preBD."url_web.SEC_VIEW = " . $view . " and ".preBD."url_web.TYPE = 'section'";
				
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_object($result);
			//	$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAIN.$row->slug."' target='".$target."' alt='".$row->seo."'>";				
						$texto = displayItemMenu($row->title, $display, $thumbnail);								
					$item .= $texto."</a>";
			//	$item .= "</div>";
			break;
			case 2:
				$q = "select ".preBD."url_web.TITLE as seo, 
							".preBD."url_web.SLUG as slug,
							".preBD."articles.TITLE as title
							from ".preBD."url_web 
							left join ".preBD."articles on ".preBD."articles.ID = ".preBD."url_web.ID_VIEW
							where ".preBD."url_web.ID_VIEW = " . $view . " and ".preBD."url_web.TYPE = 'article'";
				
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_object($result);
				
			//	$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAIN.$row->slug."' target='".$target."' alt='".$row->title."'>";				
						$texto = displayItemMenu($row->title, $display, $thumbnail);				
					$item .= $texto."</a>";
			//	$item .= "</div>";
			break;
			case 3:
				$q = "select TITLE from ".preBD."download_sections where ID = " . $view;
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_assoc($result);
				if($row["TITLE_SEO"] == "" || $row["TITLE"] == NULL) {
					$title = $row["TITLE"];	
				} else {
					$title = $row["TITLE_SEO"];
				}
//				$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAIN.titleFromUrl($title)."_ds".$view.".html' target='".$target."' alt='".$title."'>";
					$item .= $title_item . "</a>";
//				$item .= "</div>";
			break;
			case 4:
	//			$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAIN."template/modules/download/downloader.php?file=".$view."' target='".$target."' alt='".$title."'>";
					$item .= $title_item . "</a>";
	//			$item .= "</div>";
			break;
			case 5:
				$q = "select TITLE from ".preBD."videos_gallery where ID = " . $view;
				
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_assoc($result);
				if($row["TITLE_SEO"] == "" || $row["TITLE"] == NULL) {
					$title = $row["TITLE"];	
				} else {
					$title = $row["TITLE_SEO"];
				}
		//		$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAIN.titleFromUrl($title)."_vs".$view.".html' target='".$target."' alt='".$title."'>";
					$item .= $title_item . "</a>";
		//		$item .= "</div>";
			break;
			case 6:
				$q = "select TITLE from ".preBD."videos where ID = " . $view;
				
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_assoc($result);
				if($row["TITLE_SEO"] == "" || $row["TITLE"] == NULL) {
					$title = $row["TITLE"];	
				} else {
					$title = $row["TITLE_SEO"];
				}
		//		$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAIN.titleFromUrl($title)."_vv".$view.".html' target='".$target."' alt='".$title."'>";
					$item .= $title_item . "</a>";
		//		$item .= "</div>";
			break;	
			case 7:
				$q = "select TITLE from ".preBD."images_gallery_sections where ID = " . $view;
				
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_assoc($result);
				if($row["TITLE_SEO"] == "" || $row["TITLE"] == NULL) {
					$title = $row["TITLE"];	
				} else {
					$title = $row["TITLE_SEO"];
				}
		//		$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAIN.titleFromUrl($title)."_gs".$view.".html' target='".$target."' alt='".$title."'>";
					$item .= $title_item . "</a>";
		//		$item .= "</div>";
			break;
			case 8:
				$q = "select TITLE from ".preBD."images_gallery where ID = " . $view;
				
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_assoc($result);
				if($row["TITLE_SEO"] == "" || $row["TITLE"] == NULL) {
					$title = $row["TITLE"];	
				} else {
					$title = $row["TITLE_SEO"];
				}
		//		$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAIN.titleFromUrl($title)."_ga".$view.".html' target='".$target."' alt='".$title."'>";
					$item .= $title_item . "</a>";
		//		$item .= "</div>";
			break;				
		}	
		echo $item;
	}				
	function constructItemMenuFundacion($title_item, $type, $view, $target, $display, $thumbnail, $class , $v) {
		$item = "";
		global $connectBD;
		switch($type) {
			case -1:
			//$item .= "<div ".$class.">";
				if(strpos($view, "#") === 0){ //detectamos si es un ancla
					if($v == "home") {
						$item .= "<a class='".$class."' href='".stripslashes($view)."' alt='".strip_tags($title_item)."' title='".strip_tags($title_item)."' target='".$target."' data-type='ancla-home'>";				
					}else{
						$item .= "<a ".$class."href='".DOMAINFUNDACION."home".stripslashes($view)."' alt='".strip_tags($title_item)."' title='".strip_tags($title_item)."' target='".$target."'>";				
					}
				}else {
					if($view == "en-construccion"){
						$view = DOMAINFUNDACION.$view;
					}
					if(strpos($view, "http://")===0 || strpos($view, "https://")===0) {
						$item .= "<a class='".$class."' href='".stripslashes($view)."' alt='".strip_tags($title_item)."' title='".strip_tags($title_item)."' target='".$target."'>";				
					}else{
						$item .= "<a class='".$class."' href='".DOMAINFUNDACION.stripslashes($view)."' alt='".strip_tags($title_item)."' title='".strip_tags($title_item)."' target='".$target."'>";				
					}
				}
						$texto = displayItemMenu($title_item, $display, $thumbnail).$p;
					$item .= $texto."</a>";
			//	$item .= "</div>";		
			break;
			case 0:
			//	$item .= "<div ".$class.">";
				
					//$item = displayItemMenu($title_item, $display, $thumbnail);
					$item = '<a href="#" class="dropdown-toggle '.$class.'" data-toggle="dropdown" data-hover="dropdown" data-delay="300" data-close-others="false">'.$title_item.'</a>';
			//	$item .= $texto."</div>";			
			break;
			case 1:
				$q = "select ".preBD."url_web.TITLE as seo, 
							".preBD."url_web.SLUG as slug,
							".preBD."articles_sections.TITLE as title
							from ".preBD."url_web 
							left join ".preBD."articles_sections on ".preBD."articles_sections.ID = ".preBD."url_web.SEC_VIEW
							where ".preBD."url_web.SEC_VIEW = " . $view . " and ".preBD."url_web.TYPE = 'section'";
				
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_object($result);
			//	$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAINFUNDACION.$row->slug."' target='".$target."' alt='".$row->seo."'>";				
						$texto = displayItemMenu($row->title, $display, $thumbnail);								
					$item .= $texto."</a>";
			//	$item .= "</div>";
			break;
			case 2:
				$q = "select ".preBD."url_web.TITLE as seo, 
							".preBD."url_web.SLUG as slug,
							".preBD."articles.TITLE as title
							from ".preBD."url_web 
							left join ".preBD."articles on ".preBD."articles.ID = ".preBD."url_web.ID_VIEW
							where ".preBD."url_web.ID_VIEW = " . $view . " and ".preBD."url_web.TYPE = 'article'";
				
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_object($result);
				if($view == 111) {//para que el trabaja con nosotros se mueva al portal normal
					$urlart = DOMAIN.$row->slug;
				}else {
					$urlart = DOMAINFUNDACION.$row->slug;
				}
				
			//	$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".$urlart."' target='".$target."' alt='".$row->title."'>";				
						$texto = displayItemMenu($row->title, $display, $thumbnail);				
					$item .= $texto."</a>";
			//	$item .= "</div>";
			break;
			case 3:
				$q = "select TITLE from ".preBD."download_sections where ID = " . $view;
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_assoc($result);
				if($row["TITLE_SEO"] == "" || $row["TITLE"] == NULL) {
					$title = $row["TITLE"];	
				} else {
					$title = $row["TITLE_SEO"];
				}
//				$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAINFUNDACION.titleFromUrl($title)."_ds".$view.".html' target='".$target."' alt='".$title."'>";
					$item .= $title_item . "</a>";
//				$item .= "</div>";
			break;
			case 4:
	//			$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAINFUNDACION."template/modules/download/downloader.php?file=".$view."' target='".$target."' alt='".$title."'>";
					$item .= $title_item . "</a>";
	//			$item .= "</div>";
			break;
			case 5:
				$q = "select TITLE from ".preBD."videos_gallery where ID = " . $view;
				
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_assoc($result);
				if($row["TITLE_SEO"] == "" || $row["TITLE"] == NULL) {
					$title = $row["TITLE"];	
				} else {
					$title = $row["TITLE_SEO"];
				}
		//		$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAINFUNDACION.titleFromUrl($title)."_vs".$view.".html' target='".$target."' alt='".$title."'>";
					$item .= $title_item . "</a>";
		//		$item .= "</div>";
			break;
			case 6:
				$q = "select TITLE from ".preBD."videos where ID = " . $view;
				
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_assoc($result);
				if($row["TITLE_SEO"] == "" || $row["TITLE"] == NULL) {
					$title = $row["TITLE"];	
				} else {
					$title = $row["TITLE_SEO"];
				}
		//		$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAINFUNDACION.titleFromUrl($title)."_vv".$view.".html' target='".$target."' alt='".$title."'>";
					$item .= $title_item . "</a>";
		//		$item .= "</div>";
			break;	
			case 7:
				$q = "select TITLE from ".preBD."images_gallery_sections where ID = " . $view;
				
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_assoc($result);
				if($row["TITLE_SEO"] == "" || $row["TITLE"] == NULL) {
					$title = $row["TITLE"];	
				} else {
					$title = $row["TITLE_SEO"];
				}
		//		$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAINFUNDACION.titleFromUrl($title)."_gs".$view.".html' target='".$target."' alt='".$title."'>";
					$item .= $title_item . "</a>";
		//		$item .= "</div>";
			break;
			case 8:
				$q = "select TITLE from ".preBD."images_gallery where ID = " . $view;
				
				$result = checkingQuery($connectBD,$q);
				$row = mysqli_fetch_assoc($result);
				if($row["TITLE_SEO"] == "" || $row["TITLE"] == NULL) {
					$title = $row["TITLE"];	
				} else {
					$title = $row["TITLE_SEO"];
				}
		//		$item .= "<div ".$class.">";
					$item .= "<a class='".$class."' href='".DOMAINFUNDACION.titleFromUrl($title)."_ga".$view.".html' target='".$target."' alt='".$title."'>";
					$item .= $title_item . "</a>";
		//		$item .= "</div>";
			break;				
		}	
		echo $item;
	}				

	
	function calculateResume($idArt, $caesura) {
		global $connectBD;
		$p = 1;
		$q_par = "select TEXT as text from ".preBD."paragraphs where IDARTICLE = " . $idArt . " and POSITION = " . $p;	
		
		$result_par = checkingQuery($connectBD,$q_par);
		$row_par = mysqli_fetch_array($result_par);
		
		$text = strip_tags($row_par['text'],"<br>");
		while(strlen($text) <= $caesura) {
			$p++;
			$q_aux = "select TEXT as text from ".preBD."paragraphs where IDARTICLE = " . $idArt . " and POSITION = " . $p;	
			if (!checkingQuery($connectBD,$q_aux)) {
				die('Error2: '.mysqli_error());
			}
			$result_aux = checkingQuery($connectBD,$q_aux);
			$row_aux = mysqli_fetch_assoc($result_aux);
			if($text != ""){
				$text .= "<br/>" . strip_tags($row_aux['text'],"<br>");
			} else {
				$text .= "<br/>" . strip_tags($row_aux['text'],"<br>");
			}
			if(!$row_aux){
				break;	
			}
		}
		$text_f = cutting($text, $caesura);
		return $text_f;
	}
	
	/*funcion para cortar cadenas con la longitud pasada*/
	function cutting($cadena, $longitud){
		if(strlen(strip_tags($cadena)) > $longitud){
			$prueba = ($cadena);
			$prueba = utf8_decode($cadena);
			$prueba2 = substr(strip_tags($prueba),0,($longitud-3))."...";
			$fin = utf8_encode($prueba2);		
		}else{
			$fin = strip_tags($cadena);
		}
		return $fin;
	}
	/*funcion para cortar cadenas con la longitud pasada*/
	function cuttingBlog($cadena, $longitud){
		if(strlen(strip_tags($cadena)) > $longitud){
			$prueba = ($cadena);
			$prueba2 = substr(strip_tags($prueba),0,($longitud-3))."...";
			$fin = $prueba2;		
		}else{
			$fin = strip_tags($cadena);
		}
		return $fin;
	}
	/*funcion para cortar cadenas con la longitud pasada*/
	function cuttingTitle($cadena, $longitud){
		if(strlen(strip_tags($cadena)) > $longitud){
			$prueba = utf8_decode($cadena);
			$prueba2 = substr(strip_tags($prueba),0,($longitud-3))."...";
			$fin = utf8_encode($prueba2);		
		}else{
			$fin = strip_tags($cadena);
		}
		return $fin;
	}
	
	function orderArrayByCamp($A, $camp){
	    $n= count($A);
		for($i=1;$i<$n;$i++){
    	    for($j=0;$j<$n-$i;$j++) {
				if($A[$j][$camp]<$A[$j+1][$camp]){
					$k=$A[$j+1];
					$A[$j+1]=$A[$j];
					$A[$j]=$k;
				}
	       }
        }
    	return $A;
	}
	function orderArrayByCampAsc($A, $camp){
	    $n= count($A);
		for($i=1;$i<$n;$i++){
    	    for($j=0;$j<$n-$i;$j++) {
				if($A[$j][$camp]>$A[$j+1][$camp]){
					$k=$A[$j+1];
					$A[$j+1]=$A[$j];
					$A[$j]=$k;
				}
	       }
        }
    	return $A;
	}
	
	function formatSpecialChar($name){
		
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
		$name = str_replace("%", "", $name);
		$name = str_replace("'", "", $name);
		$name = str_replace("/", "-", $name);
		$name = str_replace("&", "-", $name);
		$name = str_replace("#", "-", $name);
		$name = str_replace("@", "-", $name);
		$name = str_replace("$", "-", $name);
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
		$name = str_replace("¬", "-", $name);
		$name = str_replace("\"", "-", $name);
		$name = str_replace("ª", "", $name);
		$name = str_replace("º", "", $name);
		$name = str_replace("€", "-euro", $name);
		$name = str_replace("$", "-dolar", $name);
		$name = str_replace("¥", "-yen", $name);
		$name = str_replace("£", "-libra", $name);
		$name = str_replace("----", "-", $name);
		$name = str_replace("---", "-", $name);
		$name = str_replace("--", "-", $name);
		$name = str_replace("â", "a", $name);
		$name = str_replace("ê", "e", $name);
		$name = str_replace("î", "i", $name);
		$name = str_replace("ô", "o", $name);
		$name = str_replace("û", "u", $name);
		$name = str_replace("Â", "A", $name);
		$name = str_replace("Ê", "E", $name);
		$name = str_replace("Î", "I", $name);
		$name = str_replace("Ô", "O", $name);
		$name = str_replace("Û", "U", $name);			
		$name = strtolower($name);
		return $name;
	}
	
	function formatNameUrl($name) {
		$name = trim($name);
		$name = strip_tags($name);
		$name = str_replace(".", "", $name);
		$name = formatSpecialChar($name);
		return $name;
	}
	function formatNameFile($name) {
		$name = trim($name);
		$name = strip_tags($name);
		$name = mb_strtolower($name,"UTF-8");
		$name = formatSpecialChar($name);
		$name = time() . "-" . $name;
		return $name;
	}

	function is_email($email) {
		$regex = "/^([a-z0-9+_]|\-|\.)+@(([a-z0-9_]|\-)+\.)+[a-z]{2,6}$/i";
		
		if (strpos($email, '@') !== false && strpos($email, '.') !== false) {
		
			if (preg_match($regex, $email)) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


function urls_amigables($url) {
// Tranformamos todo a minusculas
	$url = strtolower($url);
//Rememplazamos caracteres especiales latinos
	$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
	$repl = array('a', 'e', 'i', 'o', 'u', 'n');
	$url = str_replace ($find, $repl, $url);
// Añaadimos los guiones
	$find = array(' ', '&', '\r\n', '\n', '+'); 
	$url = str_replace ($find, '-', $url);
// Eliminamos y Reemplazamos demás caracteres especiales
	$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
	$repl = array('', '-', '');
	$url = preg_replace ($find, $repl, $url);
	return $url;
}

function transformSubject($s){
	$s = utf8_decode($s);
	
	$s = trim(str_replace("á", "_=E1",$s));
	$s = trim(str_replace("é", "_=E9",$s));
	$s = trim(str_replace("ó", "_=F3",$s));
	$s = trim(str_replace("í", "_=ED",$s));
	$s = trim(str_replace("ú", "_=FA",$s));
	$s = trim($s);
	return $s;
}
	function sendMailAlert($userSend, $subject, $text, $link, $textButton, $template, $dir) {
		global $connectBD;
		$msg = "";
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug = 0;
		$mail->Host = MAILHOST;
		$mail->From = MAILSEND;
		$mail->FromName = "=?ISO-8859-1?B?".base64_encode(NAMESEND)."=?=";
		$mail->Subject = "=?ISO-8859-1?B?".base64_encode(utf8_decode($subject)." - RepartEat")."=?=";
		$mail->AltBody = utf8_encode("Se le está intentando enviar un e-mail con contenido que su gestor de correos no puede leer");
		
		$fileAccess = new FileAccess($dir."files/mail-templates/".$template);
		$body = $fileAccess->read();
		
		$body = str_replace("#DOMAIN#", DOMAIN, $body);
		$body = str_replace("#SUBJECT#", $subject, $body);
		$body = str_replace("#TEXT#", $text, $body);
		$body = str_replace("#LINK#", $link, $body);
		$body = str_replace("#TEXTBUTTON#", $textButton, $body);
		
		$mail->MsgHTML(utf8_decode($body)); // utf8 decode just BEFORE send.
		$mail->SMTPAuth = true;
		$mail->Username = USERHOST;
		$mail->Password = PASSHOST;
		$mail->Port = PORTHOST;
		if(SECURITYHOST == 1) {
			$mail->SMTPSecure = "ssl";
		}elseif(SECURITYHOST == 2) {
			$mail->SMTPSecure = "tls";
		}
		if(count($userSend) > 0) {
			for($i=0;$i<count($userSend);$i++) {
				$mail->AddAddress($userSend[$i]["mail"], utf8_decode($userSend[$i]["name"]));
			}
			
			if(!$mail->Send()) {
				$msg .= "<span class='red'><em>'Error - " . $mail->ErrorInfo."</em>.</span><br/>";
			} else {
				$msg .= ""; 
				$mail->ClearAddresses();
				$mail->ClearAttachments();
			}
		}else{
			$msg .= NOUSERMAIL;
		}
		return $msg;
	}

function nameCountry($code, $pre) {
	global $connectBD;
	$q = "select Country from ".$pre."countries where Code = '" . $code . "'";
	
	$result = checkingQuery($connectBD,$q);
	$row = mysqli_fetch_object($result);
	$aux = $row->Country;
	return $aux;
}

function GetLastDayofMonth($year, $month) {//Calcula ultimo dia del mes
    for ($day=31; $day>=28; $day--) {
        if (checkdate($month, $day, $year)) {
            return $day;
        }
    }    
}

function selectThumbnail($img) {
	$url = "files/articles/thumb/";
	$urlAbs = DOMAIN."files/articles/thumb/";
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
	$image["type"] = $type;
	
	return $image;
}
function selectThumbnail100($img) {
	$url = "files/articles/thumb/";
	$urlAbs = DOMAIN."files/articles/thumb/";
	$urlBig = DOMAIN."files/articles/image/";
	$type = "image";
	if($img == "") {
		$urlAbs.= "default.jpg";
		$urlBig.= "";
	} else {
		$urlAbs.= $img;
		$urlBig.= $img;
		$url .= $img;		
		if(!file_exists($url)) {
			$p = explode("=", $img);
			$urlAbs = "http://img.youtube.com/vi/".$p[1]."/0.jpg";
			$urlBig = $urlAbs;
			$type = "youtube";
		}
	}
	$image = array();
	$image["url"] = $urlAbs;
	$image["urlbig"] = $urlBig;
	$image["type"] = $type;
	
	return $image;

}

function name_month($x) {	
	$meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	return ($meses[$x-1]);
}

function last_day($anho,$mes){ 
   if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) { 
       $dias_febrero = 29; 
   } else { 
       $dias_febrero = 28; 
   } 
   switch($mes) { 
       case 1: return 31; break; 
       case 2: return $dias_febrero; break; 
       case 3: return 31; break; 
       case 4: return 30; break; 
       case 5: return 31; break; 
       case 6: return 30; break; 
       case 7: return 31; break; 
       case 8: return 31; break; 
       case 9: return 30; break; 
       case 10: return 31; break; 
       case 11: return 30; break; 
       case 12: return 31; break; 
   } 
}
function calcula_numero_dia_semana($dia,$mes,$ano){ 
   	$numerodiasemana = date('w', mktime(0,0,0,$mes,$dia,$ano)); 
   	if ($numerodiasemana == 0) { 
      	 $numerodiasemana = 6; 
	} else { 
      	 $numerodiasemana--; 
	}
   	return $numerodiasemana; 
} 

function selectItemSlide($date, $pos) {
	global $connectBD;
	if($date != "") {
	$d1 = explode(" ", $date);
	$d12 = explode("-", $d1[0]);
	
	$q = "select ID, DATE_START, DATE_END, TITLE from ".preBD."agenda where ID_AGENDA_SECTION = 1 and STATUS = 1";
	$q .= " and DATE_FORMAT(DATE_START, '%Y%m%d') >";
	if($pos == 0) {
		$q .= "=";
	}
	$q .= $d12[0].$d12[1].$d12[2];
	$q .= " order by DATE_START asc";
	$q .= " limit 0, 1";
$result = checkingQuery($connectBD,$q);
	$control = mysqli_num_rows($result);
	if($control > 0) {
		$event[0] = mysqli_fetch_assoc($result);
		$event[0]["type"] = "event";
		$d = explode(" ", $event[0]["DATE_START"]);
		$event[0]["date"] = strtotime($d["0"]);
		$d2 = explode("-", $d[0]);
		
		$q = "select ID, DATE_START, DATE_END, TITLE from ".preBD."agenda where STATUS = 1";
		$q .= " and ID != " . $event[0]["ID"];
		$q .= " and DATE_FORMAT(DATE_START, '%Y%m%d') = " . $d2[0].$d2[1].$d2[2]; 
		$q .= " order by DATE_START asc";
	
		if(!checkingQuery($connectBD,$q)) {
			die("Error(select events): " . mysqli_error());
		}
		$result= checkingQuery($connectBD,$q);
		$j=1;
		while($row = mysqli_fetch_assoc($result)) {
			$event[$j] = $row;
			$d = explode(" ", $row["DATE_START"]);
			$event[$j]["type"] = "event";
			$event[$j]["date"] = strtotime($d[0]);
			$j++;
		}
	}
	return $event;
	}
}
function actualiceFestivo() {
	global $connectBD;
	$q2 = "select * from ".preBD."agenda_days where `STATUS` = 1 and `REPEAT` = 1";
	
	$result2 = checkingQuery($connectBD,$q2);
	while($row2=mysqli_fetch_assoc($result2)) {
		if($row2["YEAR"] < intval(date('Y'))) {
			$d = explode("-", $row2["DATE"]);
			$Date = date('Y').$d[1].$d[2];
			$q = "UPDATE ".preBD."agenda_days set";
			$q .= " `YEAR`='".date('Y')."'";
			$q .= ", `DATE`='".$Date."'";
			$q .= " where ID = " . $row2["ID"];	
			if(!checkingQuery($connectBD,$q)) {
				die("Error(actualice festive): " . mysqli_error());
			}
		}
	}
}

function prev_nextMonthYear($m, $y, $act) {
	if($m==1 && $act == "prev") {
		$month = 12;
		$year = $y-1;
	} elseif($m==12 && $act == "next"){
		$month = 1;
		$year = $y+1;
	}else{
		if($act == "prev") {
			$month = $m-1;	
		}else {
			$month = $m+1;
		}
		$year = $y;
	}
	return "/".$year . "/" . $month;
}
function order_date($d) {
		$aux = explode(" ", $d);
		$format = explode("-", $aux[0]);
		
		$date_order = $format[2] . "/" . $format[1] . "/" . $format[0];
		return ($date_order);	
	} 
function order_date_hour($d) {
		$aux = explode(" ", $d);
		$format = explode("-", $aux[0]);
		
		$date_order = $format[2] . "-" . $format[1] . "-" . $format[0] . " " .$aux[1];
		return ($date_order);	
	} 
function order_date_blog($d) {
		$meses = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');

		$aux = explode(" ", $d);
		$format = explode("-", $aux[0]);	

		$date_order = ltrim($format[2],0) ." ". $meses[$format[1]-1] ." ". $format[0];
		return ($date_order);	
	} 
function order_date2($d) {
		$format = explode("-", $d);
		
		
		$date_order = $format[2] . "/" . $format[1] . "/" . $format[0];
		return ($date_order);	
	} 
	
	
function orderByDate($A){
	$n= count($A);
	for($i=1;$i<$n;$i++){
		for($j=0;$j<$n-$i;$j++) {
			if($A[$j]["DATE_START"]<$A[$j+1]["DATE_START"]){
				$k=$A[$j+1];
				$A[$j+1]=$A[$j];
				$A[$j]=$k;
			}
	   }
	}
	return $A;
}

function selectTextIntroduction($text, $id){
	global $connectBD;
	if($text == ""){
		$q_p1 = "select * from ".preBD."paragraphs where IDARTICLE = " . $id . " order by POSITION asc limit 0,1";
		$result_p1 = checkingQuery($connectBD,$q_p1);
		$fila1 = mysqli_fetch_row($result_p1); 
		$texto = $fila1[2];
	}else{
		$texto = $text;
	}
	
	return $texto;
}	

function infoBlogByID($id) {
	global $connectBD;
	$blog = array();
	
	$q = "select * from ".preBD."blog where ID = '" . $id . "'";
	$r = checkingQuery($connectBD,$q);
	$row = mysqli_fetch_object($r);
	$blog["id"] = $row->ID;
	$blog["author"] = $row->AUTHOR;
	$blog["slug"] = $row->SLUG;
	
	$q = "select Login, Name, Type from ".preBD."users where ID = ". $row->AUTHOR;
	$ra = checkingQuery($connectBD,$q);
	$author = mysqli_fetch_object($ra);
	$blog["authorLogin"] = $author->Login;
	$blog["authorName"] = $row->Name;
	$blog["authorType"] = $row->Type;
	
	$blog["section"] = $row->IDSECTION;
	$q = "SELECT TITLE FROM ".preBD."articles_sections WHERE ID = '" . $row->IDSECTION . "'";
	$r = checkingQuery($connectBD,$q);
	$sec = mysqli_fetch_object($r);
	$blog["title"] = stripslashes($sec->TITLE);
	
	return $blog;
}
	
	
	/*obtiene la proporcion de una imagen al redimensionarla*/
    function getNewSize($w, $h, $lw, $lh) {
        if($w > $lw) {
            $percent = ($lw * 100) / $w;
            $w = $lw;
            $h = $h * ($percent / 100);
        }
        if($h > $lh) {
            $percent = ($lh * 100) / $h;
            $h = $lh;
            $w = $w * ($percent / 100);
        }
        return array('w' => $w, 'h' => $h);
    }	
	
	function calculatePositionImage($imagen, $ancho, $alto, $max_altura){
		/*obtenemos el tamaño de la imagen original al redimensionarla al ancho de la caja contenedora*/
		$nueva2 = getNewSize($imagen[0], $imagen[1], $ancho, $max_altura);									
		
		$vector;
		
		if($nueva2['h'] < $alto){ 
			$top = $alto - $nueva2['h']; 
			$margin_top = ($top/2);
			
			$vector[0] = $margin_top;
			
			/*imagen es menos ancha que la anchura de la caja que la contiene*/
			if($nueva2['w'] < $ancho){
				$left = $ancho - $nueva2['w']; 
				$margin_left = ($left/2); 
				
				$vector[1] = $margin_left;
			}else{
				$vector[1] = 0;
			}										
		}else{ 
			/*imagen redimensionada es más alta que la altura de la caja que la contiene, la movemos con margen negativo*/
			$top = $nueva2['h'] - $alto; 
			$margin_top = -($top/2);
			
			$vector[0] = $margin_top;
			$vector[1] = 0;			
		}	

		return $vector;
	}
	
	
function selectThumbnailArticle($img) {
	$url = "files/articles/thumb/";
	$urlAbs = DOMAIN."files/articles/thumb/";
	$type = "image";
	if($img == "") {
		$urlAbs.= "default.jpg";
	} else {
		$urlAbs.= $img;
		$url .= $img;		
		if(!file_exists($url)) {
			$p = explode("=", $img);
			$urlAbs = "http://img.youtube.com/vi/".$p[1]."/0.jpg";
			$type = "youtube";
		}
	}
	$image = array();
	$image["url"] = $urlAbs;
	$image["type"] = $type;
	
	return $image;

}	

function selectThumbnailVideo($video) {
	if($video->TYPEIMAGE == "youtube"){ 
		$url = "http://img.youtube.com/vi/".$video->IMAGE."/maxresdefault.jpg";						
	}elseif($video->TYPEIMAGE == "personal"){
		if($video->IMAGE == ""){ 
			$url = DOMAIN."css/images/body/logo_new.jpg";
		}else{
			$url = DOMAIN."files/videos/thumb/".$video->IMAGE;
		}
	} 
	$image["url"] = $url;
	$image["type"] = $video->TYPEIMAGE;
	
	return $image;
}

	
	function extension_file_download($filename) {
		global $connectBD;
		preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $ext);
		$idx = $ext[1];
		
		$q = "Select * from ".preBD."extensions where EXT = '".$idx."'";
		
		$result = checkingQuery($connectBD,$q);
		$num_resultado = mysqli_num_rows($result);
		 
		if ($num_resultado == 1) {
			$row = mysqli_fetch_object($result);
			return $row->TYPE;
		} else {
			return 'application/octet-stream';
		}
	}	
function calculateWidth($s, $size) {
	$l = strlen($s);
	$w = ($l * $size) + 10;
	$w = $w * 0.0732;
	return $w;
}

	function selectDataVideo($id_video){
		global $connectBD;
		$q = "Select * from ".preBD."videos where ID = ".$id_video." and STATUS = 1";
		
		$result = checkingQuery($connectBD,$q);		
		$vid = mysqli_fetch_object($result);
		
		return $vid;
	}
	
function infoArticleByID($id) {
	global $connectBD;
	$q = "select ".preBD."articles.ID as idA,
				".preBD."articles.TITLE as tsA,
				".preBD."articles.TITLE_SEO as tA,
				".preBD."articles.SUBTITLE as subA,
				".preBD."articles.SUMARY as resA,
				".preBD."articles.INTRO as inA,
				".preBD."articles.DATE_START as dateA,
				".preBD."articles.THUMBNAIL as image,
				".preBD."articles_sections.ID as idS,
				".preBD."articles_sections.TITLE as tS,
				".preBD."articles_sections.TITLE_SEO as tsS,
				".preBD."articles_sections.THUMB_WIDTH as image_W,
				".preBD."articles_sections.THUMB_HEIGHT as image_H,
				".preBD."paragraphs.TYPE as icon,
				".preBD."paragraphs.TEXT as text,
				".preBD."paragraphs.VIDEO as video,
				".preBD."paragraphs.IDALBUM as gallery,
				".preBD."url_web.SLUG as slug
				from ".preBD."articles 
				left join ".preBD."articles_sections 
				on ".preBD."articles.IDSECTION = ".preBD."articles_sections.ID 
				left join ".preBD."paragraphs 
				on ".preBD."articles.ID = ".preBD."paragraphs.IDARTICLE and ".preBD."paragraphs.POSITION = 1 
				left join ".preBD."url_web 
				on ".preBD."articles.ID = ".preBD."url_web.ID_VIEW and ".preBD."url_web.TYPE = 'article' 
				where ".preBD."articles.ID = ".$id." 
				and STATUS = 1 
				and TRASH = 0 
				and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
				
	$r = checkingQuery($connectBD,$q);
	
	$row = mysqli_fetch_object($r);
	return $row;
}

function lastNewsSection($sec, $limit, $type) {
	global $connectBD;
	$q = "select ".preBD."articles.ID as idA,
				".preBD."articles.TITLE as tsA,
				".preBD."articles.TITLE_SEO as tA,
				".preBD."articles.FIRM as fmA,
				".preBD."articles.SUMARY as resA,
				".preBD."articles.DATE_START as dateA,
				".preBD."articles.THUMBNAIL as image,
				".preBD."articles_sections.ID as idS,
				".preBD."articles_sections.TITLE as tS,
				".preBD."articles_sections.TITLE_SEO as tsS,
				".preBD."articles_sections.DESCRIPTION as textS,
				".preBD."articles_sections.THUMB_WIDTH as image_W,
				".preBD."articles_sections.THUMB_HEIGHT as image_H,";
			if($type == "blog") {
				$q .= preBD."blog.SLUG as secSlug,";
			}
			$q.=preBD."paragraphs.TYPE as icon,
				".preBD."paragraphs.TEXT as text,
				".preBD."url_web.SLUG as slug
				from ".preBD."articles 
				left join ".preBD."articles_sections 
				on ".preBD."articles.IDSECTION = ".preBD."articles_sections.ID ";
			if($type == "blog") {
			$q .="inner join ".preBD."blog 
					on ".preBD."articles.IDSECTION = ".preBD."blog.IDSECTION ";
				}
			$q.="left join ".preBD."paragraphs 
				on ".preBD."articles.ID = ".preBD."paragraphs.IDARTICLE and ".preBD."paragraphs.POSITION = 1 
				left join ".preBD."url_web 
				on ".preBD."articles.ID = ".preBD."url_web.ID_VIEW and ".preBD."url_web.TYPE = '".$type."' 
				where true
				and ".preBD."articles.TYPE = '" . $type . "'";
			if($sec > 0) {
			$q.= " and ".preBD."articles.IDSECTION = " . $sec;
			}
			$q.= " and ".preBD."articles.STATUS = 1 and ".preBD."articles.TRASH = 0 
				and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))
				order by ".preBD."articles.DATE_START desc, ".preBD."articles.ID desc limit 0, ". $limit;

	$r = checkingQuery($connectBD,$q);
	$news = array();
	while($row = mysqli_fetch_object($r)) {
		$news[] = $row;
	}
	return $news;
}



function lastDownSection($sec, $limit) {
	global $connectBD;
	$q = "select ".preBD."downloads.ID as idD,
				".preBD."downloads.TITLE as tD,
				".preBD."downloads.DATE_START as dateD,
				".preBD."downloads.IMAGE as image,
				".preBD."download_sections.ID as idS,
				".preBD."download_sections.TITLE as tS,
				".preBD."download_sections.WIDTH as image_W,
				".preBD."download_sections.HEIGHT as image_H,
				".preBD."download_docs.URL as url,
				".preBD."download_docs.EXTENSION as ext,
				".preBD."download_docs.SIZE as size
				from ".preBD."downloads 
				left join ".preBD."download_sections 
				on ".preBD."downloads.IDSECTION = ".preBD."download_sections.ID 
				left join ".preBD."download_docs 
				on ".preBD."downloads.ID = ".preBD."download_docs.IDDOWNLOAD and ".preBD."download_docs.POSITION = 1 
				where IDSECTION = ".$sec." and STATUS = 1
				and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))
				order by DATE_START desc, ".preBD."articles.ID desc limit 0, ". $limit;
				
	$r = checkingQuery($connectBD,$q);
	$down = array();
	while($row = mysqli_fetch_object($r)) {
		$down[] = $row;
	}
	return $down;
}
function selectMoreRead($limit, $type) {
	global $connectBD;
	$q = "select ".preBD."articles.ID as idA,
				".preBD."articles.TITLE as tsA,
				".preBD."articles.TITLE_SEO as tA,
				".preBD."articles.DATE_START as dateA,
				".preBD."articles.THUMBNAIL as image,
				".preBD."articles_sections.TITLE as tS,
				".preBD."articles_sections.TITLE_SEO as tsS,
				".preBD."paragraphs.TYPE as icon,
				".preBD."paragraphs.TEXT as text, 
				SUM(".preBD."statistics_content.VISITS) as visit,
				".preBD."url_web.SLUG as slug	
				from ".preBD."statistics_content
				inner join  ".preBD."articles 
				on ".preBD."articles.ID = ".preBD."statistics_content.IDCONTENT 
				inner join ".preBD."articles_sections 
				on ".preBD."articles.IDSECTION = ".preBD."articles_sections.ID 
				inner join ".preBD."paragraphs 
				on ".preBD."articles.ID = ".preBD."paragraphs.IDARTICLE and ".preBD."paragraphs.POSITION = 1 
				inner join ".preBD."url_web 
				on ".preBD."articles.ID = ".preBD."url_web.ID_VIEW and ".preBD."url_web.TYPE = '".$type."' 
				where true 
				and ".preBD."articles.STATUS = 1 
				and ".preBD."articles.TRASH = 0 
				and ".preBD."articles.DATE_START <= NOW() 
				and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))
				and ".preBD."statistics_content.TYPE = '".$type."' GROUP BY ".preBD."statistics_content.IDCONTENT 
				order by SUM(".preBD."statistics_content.VISITS) desc, ".preBD."articles.DATE_START desc, ".preBD."articles.ID desc
				limit 0, ".$limit;
	//pre($q)			
	$r = checkingQuery($connectBD,$q);
	$news = array();
	while($row = mysqli_fetch_object($r)) {
		$news[] = $row;
	}
	return $news;
}
function selectMoreNow($limit, $type) {
	global $connectBD;
	$q = "select ".preBD."articles.ID as idA,
				".preBD."articles.TITLE as tsA,
				".preBD."articles.TITLE_SEO as tA,
				".preBD."articles.SUBTITLE as subA,
				".preBD."articles.SUMARY as resA,
				".preBD."articles.INTRO as inA,
				".preBD."articles.DATE_START as dateA,
				".preBD."articles.THUMBNAIL as image,
				".preBD."articles_sections.ID as idS,
				".preBD."articles_sections.TITLE as tS,
				".preBD."articles_sections.TITLE_SEO as tsS,
				".preBD."articles_sections.THUMB_WIDTH as image_W,
				".preBD."articles_sections.THUMB_HEIGHT as image_H,
				".preBD."paragraphs.TYPE as icon,
				".preBD."paragraphs.VIDEO as video,
				".preBD."paragraphs.IDALBUM as gallery,
				".preBD."paragraphs.TEXT as text,
				".preBD."url_web.SLUG as slug
				from ".preBD."articles 
				left join ".preBD."articles_sections 
				on ".preBD."articles.IDSECTION = ".preBD."articles_sections.ID 
				left join ".preBD."paragraphs 
				on ".preBD."articles.ID = ".preBD."paragraphs.IDARTICLE and ".preBD."paragraphs.POSITION = 1 
				left join ".preBD."url_web 
				on ".preBD."articles.ID = ".preBD."url_web.ID_VIEW and ".preBD."url_web.TYPE = '".$type."' 
				where true
				and ".preBD."articles.TYPE = '".$type."' 
				and ".preBD."articles.STATUS = 1 
				and ".preBD."articles.TRASH = 0 
				and (".preBD."articles.DATE_START <= NOW() or (".preBD."articles.DATE_START <= NOW() and ".preBD."articles.DATE_END <> ".preBD."articles.DATE_START and ".preBD."articles.DATE_END >= NOW()))
				order by ".preBD."articles.DATE_START desc, ".preBD."articles.ID desc
				limit 0, ".$limit;
				
	$r = checkingQuery($connectBD,$q);
	$news = array();
	while($row = mysqli_fetch_object($r)) {
		$news[] = $row;
	}
	return $news;
}

function infoProductByID($id) {
	global $connectBD;
	$q = "select ".preBD."products.ID as idP,
				".preBD."products.TITLE as tP,
				".preBD."products.SUMARY as res,
				".preBD."products.DATE_START as dateP,
				".preBD."products_cat.TITLE as tC,
				".preBD."url_web.SLUG as slug
				from ".preBD."products 
				left join ".preBD."products_cat 
				on ".preBD."products.IDCAT = ".preBD."products_cat.ID 
				left join ".preBD."url_web 
				on ".preBD."products.ID = ".preBD."url_web.ID_VIEW and ".preBD."url_web.TYPE = 'product' 
				where ".preBD."products.ID = ".$id." 
				and ".preBD."products.STATUS = 1 
				and (".preBD."products.DATE_START <= NOW() or (".preBD."products.DATE_START <= NOW() and ".preBD."products.DATE_END <> ".preBD."products.DATE_START and ".preBD."products.DATE_END >= NOW()))";
				
	$r = checkingQuery($connectBD,$q);
	
	$row = mysqli_fetch_object($r);
	return $row;
}

function infoProductHome($t) {
	global $connectBD;
	$q = "select ".preBD."products.ID as idP,
				".preBD."products.TITLE as tP,
				".preBD."products.SUMARY as res,
				".preBD."products.DATE_START as dateP,
				".preBD."products_cat.TITLE as tC,
				".preBD."products_images.URL as image,
				".preBD."url_web.SLUG as slug
				from ".preBD."products 
				left join ".preBD."products_cat 
				on ".preBD."products.IDCAT = ".preBD."products_cat.ID 
				left join ".preBD."products_images 
				on ".preBD."products.ID = ".preBD."products_images.IDPRODUCT and ".preBD."products_images.POSITION = 1 
				left join ".preBD."url_web 
				on ".preBD."products.ID = ".preBD."url_web.ID_VIEW and ".preBD."url_web.TYPE = 'product' 
				where ".preBD."products.STATUS = 1 
				and ".preBD."products.HOME = 1 
				and (".preBD."products.DATE_START <= NOW() or (".preBD."products.DATE_START <= NOW() and ".preBD."products.DATE_END <> ".preBD."products.DATE_START and ".preBD."products.DATE_END >= NOW()))
				order by ".preBD."products.POSITION asc
				limit 0,".$t;
				
	$r = checkingQuery($connectBD,$q);
	$p = array();
	while($row = mysqli_fetch_object($r)) {
		$p[]=$row;
	}
	return $p;
}


function urlByID($id, $type) {
	global $connectBD;
	if($type=="section"){
		$camp = "SEC_VIEW";
	}elseif($type=="article") {
		$camp = "ID_VIEW";
	}
	$q = "select SLUG from ".preBD."url_web where ".$camp." = ".$id." and TYPE = '".$type."'";
	$r = checkingQuery($connectBD,$q);
	$row = mysqli_fetch_object($r);
	
	return $row->SLUG;
}
function extFile($file) {
	global $connectBD;
	preg_match("|\.([a-z0-9]{2,4})$|i", $file, $ext);
	
	$q = "select * from ".preBD."extensions where EXT = '" . $ext[1] . "'";
	$r = checkingQuery($connectBD, $q);
	$file = mysqli_fetch_object($r);
	return $file;
}



function specilityByDoctor($id) {
	global $connectBD;
	$q = "select ".preBD."cm_specialities.ID as id,
				".preBD."cm_specialities.NAME as name
				from ".preBD."cm_ds 
				left join ".preBD."cm_specialities 
				on ".preBD."cm_ds.IDSPECIALITY = ".preBD."cm_specialities.ID
				where ".preBD."cm_ds.IDDOCTOR = ". $id;
				
	$r = checkingQuery($connectBD,$q);
	$p = array();
	while($row = mysqli_fetch_object($r)) {
		$p[]=$row;
	}
	return $p;
}

function centerByDoctor($id) {
	global $connectBD;
	$q = "select ".preBD."cm_centers.*
				from ".preBD."cm_dc 
				left join ".preBD."cm_centers 
				on ".preBD."cm_dc.IDCENTER = ".preBD."cm_centers.ID
				where ".preBD."cm_dc.IDDOCTOR = ". $id . " 
				and ".preBD."cm_centers.STATUS = 1";
				
	$r = checkingQuery($connectBD,$q);
	$p = array();
	while($row = mysqli_fetch_object($r)) {
		$p[]=$row;
	}
	return $p;
}

function doctorByCenter($id) {
	global $connectBD;
	$q = "select ".preBD."cm_dc.IDDOCTOR
				from ".preBD."cm_dc 
				where ".preBD."cm_dc.IDCENTER = ". $id;
				
	$r = checkingQuery($connectBD,$q);
	$p = array();
	while($row = mysqli_fetch_object($r)) {
		$p[]=$row;
	}
	return $p;
}


function doctorBySpeciality($id) {
	global $connectBD;
	$q = "select ".preBD."cm_ds.IDDOCTOR
				from ".preBD."cm_ds 
				where ".preBD."cm_ds.IDSPECIALITY = ". $id;
				
	$r = checkingQuery($connectBD,$q);
	$p = array();
	while($row = mysqli_fetch_object($r)) {
		$p[]=$row;
	}
	return $p;
}
function centerBySpeciality($id) {
	global $connectBD;
	$q = "select ".preBD."cm_cs.IDCENTER
				from ".preBD."cm_cs 
				where ".preBD."cm_cs.IDSPECIALITY = ". $id;
				
	$r = checkingQuery($connectBD,$q);
	$p = array();
	while($row = mysqli_fetch_object($r)) {
		$p[]=$row;
	}
	return $p;
}


function specilityByCenter($id) {
	global $connectBD;
	$q = "select ".preBD."cm_specialities.ID as id,
				".preBD."cm_specialities.NAME as name
				from ".preBD."cm_cs 
				left join ".preBD."cm_specialities 
				on ".preBD."cm_cs.IDSPECIALITY = ".preBD."cm_specialities.ID
				where ".preBD."cm_cs.IDCENTER = ". $id;
				
	$r = checkingQuery($connectBD,$q);
	$p = array();
	while($row = mysqli_fetch_object($r)) {
		$p[]=$row;
	}
	return $p;
}

function doctorListSpeciality($id, $center) {
	global $connectBD;
	$q = "select ".preBD."cm_ds.IDDOCTOR
			from ".preBD."cm_ds";
			if($center > 0) {
				$q .= " inner join ".preBD."cm_dc on ".preBD."cm_ds.IDDOCTOR = ".preBD."cm_dc.IDDOCTOR and ".preBD."cm_dc.IDCENTER = ".$center;
			}
	$q .= " inner join ".preBD."cm_doctors on ".preBD."cm_ds.IDDOCTOR = ".preBD."cm_doctors.ID and ".preBD."cm_doctors.STATUS = 1 
			where ".preBD."cm_ds.IDSPECIALITY = ". $id;
				
	$r = checkingQuery($connectBD,$q);
	$p = array();
	while($row = mysqli_fetch_object($r)) {
		$p[]=$row;
	}
	return $p;
}


function totalArticlesSection($id) {
	global $connectBD;
	$q = "select count(*) as total from ".preBD."articles 
	where IDSECTION = ".$id." 
	and STATUS = 1 
	and TRASH = 0 
	and TYPE = 'blog'
	and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
	$r = checkingQuery($connectBD, $q);
	$t = mysqli_fetch_object($r);
	
	return $t->total;
	
}

?>
