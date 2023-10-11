<?php
	function pre($a) {
		echo "<div style='border: 1px solid #000; background-color: #fff; padding: 10px; font: normal normal 12px Arial, Verdana;'>";
		echo "<pre>";
		print_r($a);
		echo "</pre>";
		echo "</div>";
	} 
	
	function preForm($die) {
		pre($_POST);
		pre($_FILES);
		if($die == 0) {
			die();
		}
	}
	
	
	function viewMsg($msg) {
		echo '<div class="cp_info">
				<ul style="width:100%;display:block;margin:0px;list-style:none;padding:0px;">
					<li style="float:left;width:4%;margin-right:2%;height:auto;">
						<img class="cp_msgicon" src="images/info.png" alt="¡INFORMACIÓN!" />
					</li>
					<li style="float:left;width:85%;height:auto;">
						<p>'.$msg.'</p>
					</li>
				</ul>
				<div class="separator5">&nbsp;</div>
			</div>
			<br/>';
	}
	function checkingExtFile($ext) {
		global $connectBD;
		$ext = strtolower($ext);
		$q = "select * from ".preBD."extensions where EXT = '" . $ext . "'";
		$r = checkingQuery($connectBD, $q);
		if(!$e = mysqli_fetch_assoc($r)) {
			$e["upload"] = 0;
			$e["msg"] = "Tipo de archivo desconocido.";
		} else {
			if($e["STATUS"] == 0) {
				$e["upload"] = 0;
				$e["msg"] = "Tipo de archivo prohibido.";
			} else {
				$e["upload"] = 1;
				$e["msg"] = "";
			}
		}
		return $e;
	}
	
	function listExtensionFile() {
		global $connectBD;
		$q = "select * from ".preBD."extensions where STATUS = 1";
		$r = checkingQuery($connectBD, $q);
		$ext = array();
		while($row = mysqli_fetch_object($r)) {
			$ext[]=$row;
		}
		return $ext;
	}
	function searchExtensionFile($arrayExt, $ext) {
		$enc = false;
		for($i=0;$i<count($arrayExt);$i++) {
			if($ext == $arrayExt[$i]){
				$enc = true;
				break;
			}
		}
		return $enc;
	}
	
	
	function arrayCountries() {
		global $connectBD;
		$q = "select * from ".preBD."countries order by CODE asc";
		$r = checkingQuery($connectBD, $q);
		$c = array();
		while($row = mysqli_fetch_object($r)) {
			$c[]=$row;
		}
		return $c;
	}
	
	function arrayProvinces() {
		global $connectBD;
		$q = "select * from ".preBD."provinces order by PROVINCE asc";
		$r = checkingQuery($connectBD, $q);
		$p = array();
		while($row = mysqli_fetch_object($r)) {
			$p[]=$row;
		}
		return $p;
	}
	
	function transDate ($date) {
		if ($date == "0000-00-00 00:00:00"){ 
			$date_unix = "Publicaci&oacute;n indefinida";
		} else {		
			$dyt = explode(" ", $date);
			$num = strtotime($dyt[0]);
			$date_unix = date("d-m-Y", $num) . " " . substr($dyt[1],0,-3);
		}
		return $date_unix;
	}
	
	function allowed($permission) {
		global $connectBD;
		$q = "SELECT ".$permission." FROM ".preBD."users_permissions WHERE Id_user='".$_SESSION[PDCLOG]['Type']."';";
	
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$allowed = $row[$permission];
		return $allowed;
	}
	
	function getdegree($usertype) {
		global $connectBD;
		$q = "SELECT Degree FROM ".preBD."users_permissions WHERE Id_user='".$usertype."';";

		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$degree = $row['Degree'];
		return $degree;
	}
	
	function checkingSection($tableBD, $campBD, $id) {
		global $connectBD;
		$q = "select count(*) as total from ".preBD.$tableBD." where ".$campBD." = " . $id;
		$r=checkingQuery($connectBD, $q);
		$t = mysqli_fetch_object($r);
		//pre(intval($t->total));
		return intval($t->total);
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
	function checking_email($email){ 
		$mail_correcto = 0; 
		if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){ 
			 if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) { 
				 if (substr_count($email,".")>= 1){ 
					 $term_dom = substr(strrchr ($email, '.'),1); 
					 if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 
						 $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1); 
						 $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 
						 if ($caracter_ult != "@" && $caracter_ult != "."){ 
							 $mail_correcto = 1; 
						 } 
					 } 
				 } 
			 } 
		} 
		if ($mail_correcto) {
			 return 1; 
		} else { 
			 return 0; 
		}
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
		$name = str_replace("/", "", $name);
		$name = str_replace("&", "", $name);
		$name = str_replace("#", "", $name);
		$name = str_replace("@", "", $name);
		$name = str_replace("$", "", $name);
		$name = str_replace(",", "-", $name);
		$name = str_replace("?", "", $name);
		$name = str_replace("¿", "", $name);
		$name = str_replace("=", "", $name);
		$name = str_replace("!", "", $name);
		$name = str_replace("¡", "", $name);
		$name = str_replace("[", "", $name);
		$name = str_replace("]", "", $name);
		$name = str_replace("(", "", $name);
		$name = str_replace(")", "", $name);
		$name = str_replace("{", "", $name);
		$name = str_replace("}", "-", $name);
		$name = str_replace("Ç", "", $name);
		$name = str_replace("*", "", $name);
		$name = str_replace("+", "", $name);
		$name = str_replace(";", "", $name);
		$name = str_replace(":", "", $name);
		$name = str_replace("<", "-", $name);
		$name = str_replace(">", "-", $name);
		$name = str_replace("|", "-", $name);
		$name = str_replace("’", "", $name);
		$name = str_replace("‘", "", $name);
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
		$name = str_replace("_", "-", $name);
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
	function tildesEnMayuscula($name) {
		$name = str_replace("á", "Á", $name);
		$name = str_replace("é", "É", $name);
		$name = str_replace("í", "Í", $name);
		$name = str_replace("ó", "Ó", $name);
		$name = str_replace("ú", "Ú", $name);
		
		return $name;
	}
	
	function resizeImage($temp_url, $url, $imagename, $image_width, $ext, $del) { 
		$ext = strtolower($ext);
		
		$source_url = $temp_url.$imagename;
		$image_url = $url.$imagename;
		$source_info = getimagesize($source_url);
		
		$source_width = $source_info[0];
		$source_height = $source_info[1];
		$source_proportion = $source_width / $source_height;
		if ($image_width > $source_width) {
			$image_width = $source_width;
		}
		$image_height = $image_width / $source_proportion;

		if($ext == "jpg" || $ext == "jpeg" || $ext == "pjpeg") {
			$source = imagecreatefromjpeg($source_url);
			$image = imagecreatetruecolor($image_width, $image_height);
			imagecopyresized($image, $source, 0, 0, 0, 0, $image_width, $image_height, $source_width, $source_height);
			if($width != $thumb_width) {
				imagefilter($image, IMG_FILTER_SMOOTH, 20);
			}
			imagejpeg($image,$image_url,95);
		} elseif($ext == "png") {
			$source = imagecreatefrompng($source_url);
			$image = imagecreatetruecolor($image_width, $image_height);
			imagealphablending($image, false);
			imagesavealpha($image, true); 
			imagecopyresampled($image, $source, 0, 0, 0, 0, $image_width, $image_height, $source_width, $source_height);
			imagepng($image,$image_url);
		} elseif($ext == "gif") {
			$source = imagecreatefromgif($source_url);
			$image = imagecreatetruecolor($image_width, $image_height);
			$transcolor=imagecolortransparent($source);
			if($transcolor!=-1){
				 $trnprt_color = imagecolorsforindex($source, $transcolor);
				 $trnprt_indx = imagecolorallocatealpha($image, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue'], $trnprt_color['alpha']);
				 imagefill($image, 0, 0, $trnprt_indx);
				 imagecolortransparent($image, $trnprt_indx);
			}
			imagecopyresized($image, $source, 0, 0, 0, 0, $image_width, $image_height, $source_width, $source_height);
			imagegif($image,$image_url);
		}
		if($del == 1) {
			deleteFile($source_url);
		}
	}
	
	function customImageNewsletter($temp, $url, $imagename, $ext, $w_BD, $h_BD) {
		$ext = strtolower($ext);
		
		$w_x_2 = $w_BD;
		$h_x_2 = $h_BD;
		$p_x_2 = $w_x_2 / $h_x_2;
		
		$image_url = $temp.$imagename;
		$image_info = getimagesize($image_url);
		$w_img = $image_info[0];
		$h_img = $image_info[1];
		$p_img = $w_img / $h_img;
		
		if($p_x_2 == $p_img) {
			if($w_img > $w_x_2) { //Tanto el ancho como el alto es mas grande del que queremos asi que redimensiono al doble
				$thumb_width = $w_x_2;
				$thumb_height = $h_x_2;
			} else {//es menor o igual que el doble asi que no la toco
				$thumb_width = $w_img;
				$thumb_height = $h_img;
			}
		} else {
			if($w_img >= $w_x_2 && $h_img >= $h_x_2) { //Tanto el ancho como el alto es mas grande del que queremos asi que redimensiono al doble
				$thumb_width = $w_x_2;
				$thumb_height = $h_x_2;
			}elseif ($w_img < $w_x_2 && $h_img >= $h_x_2){ //El ancho de la imagen es menor al ancho del doble pero el alto es mayor o igual 
				$thumb_width = $w_img;
				$thumb_height = $thumb_width / $p_x_2;
			}elseif($w_img >= $w_x_2 && $h_img < $h_x_2) { //El alto de la imagen es menor al alto del doble pero el el ancho es mayor o igual
				$thumb_height = $h_img;
				$thumb_width = $thumb_height * $p_x_2;
			}elseif($w_img < $w_x_2 && $h_img < $h_x_2) {	//tanto el alto como el ancho son menores al tamaño de la caja del doble
				if($p_x_2 >=1) {//imagen horizontal o cuadrada
					$thumb_width = $w_img;
					$thumb_height = $thumb_width / $p_x_2;
				}else{//imagen vertical
					$thumb_height = $h_img;
					$thumb_width = $thumb_height * $p_x_2;
				}
			}
		}
		
		$thumb_proportion = $p_x_2;
		
		if (($w_img / $thumb_width) >= ($h_img / $thumb_height)) {
			$crop_width = $h_img * $thumb_proportion;
			$crop_height = $h_img;
			$crop_x = ceil(($w_img - $crop_width) / 2);
			$crop_y = 0;
		}else {
			$crop_width = $w_img;
			$crop_height = $w_img / $thumb_proportion;
			$crop_x = 0;
			$crop_y = ceil(($h_img - $crop_height) / 2);
		}
		$thumb_url = $url.$imagename;
		if($ext == "jpeg" || $ext == "pjpeg" || $ext == "jpg") {	
			$source = imagecreatefromjpeg($image_url);
			$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
			imagecopyresized($thumb, $source, 0, 0, $crop_x, $crop_y, $thumb_width, $thumb_height, $crop_width, $crop_height);
			if($w_img != $thumb_width) {
				imagefilter($thumb, IMG_FILTER_SMOOTH, 20);
			}
			imagejpeg($thumb,$thumb_url,95);
		} elseif($ext == "png") {
			$source = imagecreatefrompng($image_url);
			$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
			imagealphablending($thumb, false);
			imagesavealpha($thumb, true); 
			imagecopyresampled($thumb, $source, 0, 0, $crop_x, $crop_y, $thumb_width, $thumb_height, $crop_width, $crop_height);
			imagepng($thumb,$thumb_url);
		} elseif($ext == "gif") {
			$source = imagecreatefromgif($image_url);
			$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
			$transcolor=imagecolortransparent($source);
			if($transcolor!=-1){
				 $trnprt_color = imagecolorsforindex($source, $transcolor);
				 $trnprt_indx = imagecolorallocatealpha($thumb, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue'], $trnprt_color['alpha']);
				 imagefill($thumb, 0, 0, $trnprt_indx);
				 imagecolortransparent($thumb, $trnprt_indx);
			}
			imagecopyresized($thumb, $source, 0, 0, $crop_x, $crop_y, $thumb_width, $thumb_height, $crop_width, $crop_height);
			imagegif($thumb,$thumb_url);
		}
	}
	function customImage($temp, $url, $imagename, $ext, $w_BD, $h_BD) {
		$ext = strtolower($ext);
		
		$w_x_2 = $w_BD;
		$h_x_2 = $h_BD;
		$p_x_2 = $w_x_2 / $h_x_2;
		
		$image_url = $temp.$imagename;
		$image_info = getimagesize($image_url);
		$w_img = $image_info[0];
		$h_img = $image_info[1];
		$p_img = $w_img / $h_img;
		
		if($p_x_2 == $p_img) {
			if($w_img > $w_x_2) { //Tanto el ancho como el alto es mas grande del que queremos asi que redimensiono al doble
				$thumb_width = $w_x_2;
				$thumb_height = $h_x_2;
			} else {//es menor o igual que el doble asi que no la toco
				$thumb_width = $w_img;
				$thumb_height = $h_img;
			}
		} else {
			if($w_img >= $w_x_2 && $h_img >= $h_x_2) { //Tanto el ancho como el alto es mas grande del que queremos asi que redimensiono al doble
				$thumb_width = $w_x_2;
				$thumb_height = $h_x_2;
			}elseif ($w_img < $w_x_2 && $h_img >= $h_x_2){ //El ancho de la imagen es menor al ancho del doble pero el alto es mayor o igual 
				$thumb_width = $w_img;
				$thumb_height = $thumb_width / $p_x_2;
			}elseif($w_img >= $w_x_2 && $h_img < $h_x_2) { //El alto de la imagen es menor al alto del doble pero el el ancho es mayor o igual
				$thumb_height = $h_img;
				$thumb_width = $thumb_height * $p_x_2;
			}elseif($w_img < $w_x_2 && $h_img < $h_x_2) {	//tanto el alto como el ancho son menores al tamaño de la caja del doble
				if($p_x_2 >=1) {//imagen horizontal o cuadrada
					$thumb_width = $w_img;
					$thumb_height = $thumb_width / $p_x_2;
				}else{//imagen vertical
					$thumb_height = $h_img;
					$thumb_width = $thumb_height * $p_x_2;
				}
			}
		}
		
		$thumb_proportion = $p_x_2;
		
		if (($w_img / $thumb_width) >= ($h_img / $thumb_height)) {
			$crop_width = $h_img * $thumb_proportion;
			$crop_height = $h_img;
			$crop_x = ceil(($w_img - $crop_width) / 2);
			$crop_y = 0;
		}else {
			$crop_width = $w_img;
			$crop_height = $w_img / $thumb_proportion;
			$crop_x = 0;
			$crop_y = ceil(($h_img - $crop_height) / 2);
		}
		$thumb_url = $url.$imagename;
		if($ext == "jpeg" || $ext == "pjpeg" || $ext == "jpg") {	
			$source = imagecreatefromjpeg($image_url);
			$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
			imagecopyresized($thumb, $source, 0, 0, $crop_x, $crop_y, $thumb_width, $thumb_height, $crop_width, $crop_height);
			if($w_img != $thumb_width) {
				imagefilter($thumb, IMG_FILTER_SMOOTH, 20);
			}
			imagejpeg($thumb,$thumb_url,95);
		} elseif($ext == "png") {
			$source = imagecreatefrompng($image_url);
			$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
			imagealphablending($thumb, false);
			imagesavealpha($thumb, true); 
			imagecopyresampled($thumb, $source, 0, 0, $crop_x, $crop_y, $thumb_width, $thumb_height, $crop_width, $crop_height);
			imagepng($thumb,$thumb_url);
		} elseif($ext == "gif") {
			$source = imagecreatefromgif($image_url);
			$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
			$transcolor=imagecolortransparent($source);
			if($transcolor!=-1){
				 $trnprt_color = imagecolorsforindex($source, $transcolor);
				 $trnprt_indx = imagecolorallocatealpha($thumb, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue'], $trnprt_color['alpha']);
				 imagefill($thumb, 0, 0, $trnprt_indx);
				 imagecolortransparent($thumb, $trnprt_indx);
			}
			imagecopyresized($thumb, $source, 0, 0, $crop_x, $crop_y, $thumb_width, $thumb_height, $crop_width, $crop_height);
			imagegif($thumb,$thumb_url);
		}
		deleteFile($image_url);
	}
	function customImageClass($temp, $url, $ind, $imagename, $ext, $w_BD, $h_BD) {
		$ext = strtolower($ext);
		
		$w_x_2 = $w_BD;
		$h_x_2 = $h_BD;
		$p_x_2 = $w_x_2 / $h_x_2;
		
		$image_url = $temp.$imagename;
		$image_info = getimagesize($image_url);
		$w_img = $image_info[0];
		$h_img = $image_info[1];
		$p_img = $w_img / $h_img;
		
		if($p_x_2 == $p_img) {
			if($w_img > $w_x_2) { //Tanto el ancho como el alto es mas grande del que queremos asi que redimensiono al doble
				$thumb_width = $w_x_2;
				$thumb_height = $h_x_2;
			} else {//es menor o igual que el doble asi que no la toco
				$thumb_width = $w_img;
				$thumb_height = $h_img;
			}
		} else {
			if($w_img >= $w_x_2 && $h_img >= $h_x_2) { //Tanto el ancho como el alto es mas grande del que queremos asi que redimensiono al doble
				$thumb_width = $w_x_2;
				$thumb_height = $h_x_2;
			}elseif ($w_img < $w_x_2 && $h_img >= $h_x_2){ //El ancho de la imagen es menor al ancho del doble pero el alto es mayor o igual 
				$thumb_width = $w_img;
				$thumb_height = $thumb_width / $p_x_2;
			}elseif($w_img >= $w_x_2 && $h_img < $h_x_2) { //El alto de la imagen es menor al alto del doble pero el el ancho es mayor o igual
				$thumb_height = $h_img;
				$thumb_width = $thumb_height * $p_x_2;
			}elseif($w_img < $w_x_2 && $h_img < $h_x_2) {	//tanto el alto como el ancho son menores al tamaño de la caja del doble
				if($p_x_2 >=1) {//imagen horizontal o cuadrada
					$thumb_width = $w_img;
					$thumb_height = $thumb_width / $p_x_2;
				}else{//imagen vertical
					$thumb_height = $h_img;
					$thumb_width = $thumb_height * $p_x_2;
				}
			}
		}
		
		$thumb_proportion = $p_x_2;
		
		if (($w_img / $thumb_width) >= ($h_img / $thumb_height)) {
			$crop_width = $h_img * $thumb_proportion;
			$crop_height = $h_img;
			$crop_x = ceil(($w_img - $crop_width) / 2);
			$crop_y = 0;
		}else {
			$crop_width = $w_img;
			$crop_height = $w_img / $thumb_proportion;
			$crop_x = 0;
			$crop_y = ceil(($h_img - $crop_height) / 2);
		}
		if($ind == "") {
			$thumb_url = $url.$imagename;
		}else{
			$thumb_url = $url.$ind."-".$imagename;
		}
		if($ext == "jpeg" || $ext == "pjpeg" || $ext == "jpg") {	
			$source = imagecreatefromjpeg($image_url);
			$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
			imagecopyresized($thumb, $source, 0, 0, $crop_x, $crop_y, $thumb_width, $thumb_height, $crop_width, $crop_height);
			if($w_img != $thumb_width) {
				imagefilter($thumb, IMG_FILTER_SMOOTH, 20);
			}
			imagejpeg($thumb,$thumb_url,95);
		} elseif($ext == "png") {
			$source = imagecreatefrompng($image_url);
			$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
			imagealphablending($thumb, false);
			imagesavealpha($thumb, true); 
			imagecopyresampled($thumb, $source, 0, 0, $crop_x, $crop_y, $thumb_width, $thumb_height, $crop_width, $crop_height);
			imagepng($thumb,$thumb_url);
		} elseif($ext == "gif") {
			$source = imagecreatefromgif($image_url);
			$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
			$transcolor=imagecolortransparent($source);
			if($transcolor!=-1){
				 $trnprt_color = imagecolorsforindex($source, $transcolor);
				 $trnprt_indx = imagecolorallocatealpha($thumb, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue'], $trnprt_color['alpha']);
				 imagefill($thumb, 0, 0, $trnprt_indx);
				 imagecolortransparent($thumb, $trnprt_indx);
			}
			imagecopyresized($thumb, $source, 0, 0, $crop_x, $crop_y, $thumb_width, $thumb_height, $crop_width, $crop_height);
			imagegif($thumb,$thumb_url);
		}
		
	}
	
	function deleteFile($image_url) {
		if(file_exists($image_url)) {
			unlink($image_url);
		}
	}
	
//FUNCIONES SITEMAPS

	function construcIndexSitemap($action) {
		global $connectBD;
		if($action == "regenerate") {
		//Borrado de archivos
			$dir = "../../../sitemaps/";
			$sitemaps = opendir($dir);
			while ($file_art = readdir($sitemaps))  {   
				if (is_file($dir1.$file_art)) { 
					unlink($dir1.$file_art); 
				}
			}
		}
		$msg = "";
		$code = '<?xml version="1.0" encoding="UTF-8"?>
					<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		$now = new DateTime();
	//Seciones de Articulos
		$includeBlog = icon_header(2); 
		
		$q = "select ID, TITLE from ".preBD."articles_sections";
		if($includeBlog == 0) {
			$q .= " where TYPE = 'article'"; 
		}
		$result = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_assoc($result)) {
			
			$file_xml = formatNameUrl(stripslashes($row["TITLE"])) . ".xml";
			$code .= '
					<sitemap>
						<loc>'.DOMAIN.'sitemaps/'.$file_xml.'</loc>
						<lastmod>'.$now->format('c').'</lastmod>
					</sitemap>';
			if($action == "regenerate") {
				$msg .= construcSitemapArticles($row["ID"], $file_xml);
			}
		}
	// Restaurantes
		$file_xml = "restaurantes.xml";
		$code .= '
				<sitemap>
					<loc>'.DOMAIN.'sitemaps/'.$file_xml.'</loc>
					<lastmod>'.$now->format('c').'</lastmod>
				</sitemap>';
		$msg .= construcSitemapSupplier($file_xml);		
	// Productos
	$q = "select ".preBD."suppliers.ID,
				".preBD."suppliers.TITLE,
				".preBD."url_web.SLUG
				from ".preBD."suppliers
				inner join ".preBD."url_web on ".preBD."url_web.ID_VIEW = ".preBD."suppliers.ID and ".preBD."url_web.TYPE = 'supplier'
				where true
				and ".preBD."suppliers.STATUS = 1";
		
		$result = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_object($result)) {
			$file_xml = $row->SLUG.".xml";
			$code .= '
					<sitemap>
						<loc>'.DOMAIN.'sitemaps/'.$file_xml.'</loc>
						<lastmod>'.$now->format('c').'</lastmod>
					</sitemap>';
			$msg .= construcSitemapSupplierProduct($row->ID, $row->SLUG, $file_xml);
		}
	
	/*/Categorias de productos
		
		$q = "select ID, TITLE from ".preBD."products_cat";
		$result = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_object($result)) {
			$file_xml = formatNameUrl(stripslashes($row->TITLE)) . ".xml";
			$code .= '
					<sitemap>
						<loc>'.DOMAIN.'sitemaps/'.$file_xml.'</loc>
						<lastmod>'.$now->format('c').'</lastmod>
					</sitemap>';
			if($action == "regenerate") {
				$msg .= construcSitemapProduct($row->ID, $file_xml);
			}
		}
	*/	
		
		$code .= '
				</sitemapindex>';
		$code = utf8_decode($code);
		
		$path = "../../../sitemap_index.xml";
		$mode = "w+";
		
		if($fp = fopen($path,$mode)) {
		   fwrite($fp,$code);
		   fclose($fp);
		   $msg .= "Archivo sitemap_index.xml creado correctamente.";
		} else { 
		   $msg = "Ha habido un problema y el archivo sitemap_index.xml no ha sido creado correctamente.";
		}
		return $msg;
	}
	
	function construcSitemapArticles($id, $file) {
		global $connectBD;
		
		$code = '<?xml version="1.0" encoding="UTF-8"?>
					<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		
		//Seciones de Articulos
		$q = "select ID, TITLE, TITLE_SEO, TYPE, DATE_START from ".preBD."articles where true"; 
		$q .= "	and IDSECTION = " . $id;
		$q .= " and STATUS = 1";
		$q .= " and TRASH = 0";
		$q .= " and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
		$result = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_object($result)) {
			$q = "select SLUG from ".preBD."url_web where ID_VIEW = " . $row->ID. " and TYPE = 'article'";
			$r = checkingQuery($connectBD, $q);
			$url = mysqli_fetch_object($r);
			$date = new DateTime($row->DATE_START);
			if($row->TYPE == "blog"){
				$urlArt = DOMAIN. slugBlog."/". $url->SLUG;
			}else if($row->TYPE == "fundacion"){
				$urlArt = DOMAINFUNDACION . $url->SLUG;
			}else {
				$urlArt = DOMAIN . $url->SLUG;
			}
			
			
			$code .= '
					<url>
						<loc>'. $urlArt.'</loc>
						<lastmod>'.$date->format("Y-m-d").'</lastmod>
						<changefreq>monthly</changefreq>
						<priority>0.5</priority>
					</url>';
		}

		$code .= '
				</urlset>';
		$code = utf8_decode($code);
		$path = "../../../sitemaps/" . $file;
		$mode = "w+";
		
		if($fp = fopen($path,$mode)) {
		   fwrite($fp,$code);
		   fclose($fp);
		   $msg .= "Archivo ".$file." creado correctamente.<br/>";
		} else { 
		   $msg .= "Ha habido un problema con el archivo ".$file." no ha sido creado correctamente.</br>";
		}
		
		return($msg);
	}
	
	function construcSitemapSupplier($file) {
		global $connectBD;
		
		$date = new DateTime();
		$code = '<?xml version="1.0" encoding="UTF-8"?>
					<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			$code .= '
					<url>
						<loc>'. DOMAIN.'restaurantes</loc>
						<lastmod>'.$date->format("Y-m-d").'</lastmod>
						<changefreq>monthly</changefreq>
						<priority>0.5</priority>
					</url>';
			
			//Seciones de Articulos
		$q = "select ".preBD."suppliers.TITLE,
				".preBD."url_web.SLUG
				from ".preBD."suppliers 
				inner join ".preBD."url_web on ".preBD."url_web.ID_VIEW = ".preBD."suppliers.ID and ".preBD."url_web.TYPE = 'supplier'
				where true
				and ".preBD."suppliers.STATUS = 1";
		
		$result = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_object($result)) {
			
			$code .= '
					<url>
						<loc>'. DOMAIN.'restaurantes/'.$row->SLUG.'</loc>
						<lastmod>'.$date->format("Y-m-d").'</lastmod>
						<changefreq>monthly</changefreq>
						<priority>0.5</priority>
					</url>';
			
		}
		$code .= '
				</urlset>';
		$code = utf8_decode($code);
		$path = "../../../sitemaps/" . $file;
		$mode = "w+";
		
		if($fp = fopen($path,$mode)) {
		   fwrite($fp,$code);
		   fclose($fp);
		   $msg .= "Archivo ".$file." creado correctamente.<br/>";
		} else { 
		   $msg .= "Ha habido un problema con el archivo ".$file." no ha sido creado correctamente.</br>";
		}
		
		return($msg);
	}
	function construcSitemapSupplierProduct($idSup, $slug, $file) {
		global $connectBD;
		
		$date = new DateTime();
		$code = '<?xml version="1.0" encoding="UTF-8"?>
					<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
			$code .= '
					<url>
						<loc>'. DOMAIN.'restaurantes</loc>
						<lastmod>'.$date->format("Y-m-d").'</lastmod>
						<changefreq>monthly</changefreq>
						<priority>0.5</priority>
					</url>';
			
			//Seciones de Articulos
		$q = "select ".preBD."products.TITLE,
				".preBD."url_web.SLUG
				from ".preBD."products 
				inner join ".preBD."url_web on ".preBD."url_web.ID_VIEW = ".preBD."products.ID and ".preBD."url_web.TYPE = 'product'
				where true
				and ".preBD."products.STATUS = 1 
				and ".preBD."products.IDSUPPLIER = " . $idSup;
		
		$result = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_object($result)) {
			
			$code .= '
					<url>
						<loc>'. DOMAIN.'restaurantes/'.$slug.'/'.$row->SLUG.'</loc>
						<lastmod>'.$date->format("Y-m-d").'</lastmod>
						<changefreq>monthly</changefreq>
						<priority>0.5</priority>
					</url>';
			
		}
		$code .= '
				</urlset>';
		$code = utf8_decode($code);
		$path = "../../../sitemaps/" . $file;
		$mode = "w+";
		
		if($fp = fopen($path,$mode)) {
		   fwrite($fp,$code);
		   fclose($fp);
		   $msg .= "Archivo ".$file." creado correctamente.<br/>";
		} else { 
		   $msg .= "Ha habido un problema con el archivo ".$file." no ha sido creado correctamente.</br>";
		}
		
		return($msg);
	}
	function construcSitemapProduct($id, $file) {
		global $connectBD;
		
		$code = '<?xml version="1.0" encoding="UTF-8"?>
					<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		
		//Seciones de Articulos
		$q = "select ID, TITLE, DATE_START from ".preBD."products 
				where IDCAT = " . $id . " 
				and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))";
		$result = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_object($result)) {
			$q = "select SLUG from ".preBD."url_web where ID_VIEW = " . $row->ID. " and TYPE = 'product'";
			$r = checkingQuery($connectBD, $q);
			$url = mysqli_fetch_object($r);
			$date = new DateTime($row->DATE_START);
			$code .= '
					<url>
						<loc>' . DOMAIN . $url->SLUG.'</loc>
						<lastmod>'.$date->format("Y-m-d").'</lastmod>
						<changefreq>monthly</changefreq>
						<priority>0.8</priority>
					</url>';
		}

		$code .= '
				</urlset>';
		$code = utf8_decode($code);
		$path = "../../../sitemaps/" . $file;
		$mode = "w+";
		
		if($fp = fopen($path,$mode)) {
		   fwrite($fp,$code);
		   fclose($fp);
		   $msg .= "Archivo ".$file." creado correctamente.<br/>";
		} else { 
		   $msg .= "Ha habido un problema con el archivo ".$file." no ha sido creado correctamente.</br>";
		}
		
		return($msg);
	}
	
	
	function construcSitemapGalerias($id, $file) {
		global $connectBD;
		$now = date('Y').date('m').date('d').date('H').date('i').date('s');
		$date_joker = "0000-00-00 00:00:00";	

		$code = '<?xml version="1.0" encoding="UTF-8"?>
					<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
		//Seciones de Videos
		$q = "select * from ".preBD."videos where IDGALLERY = " . $id;
		$q .= " and STATUS = 1";
		$q .= " and DATE_START <= " . $now . " and (DATE_END = '" . $date_joker . "' or DATE_END >= '" . $now . "')";
		//pre($q);
		$result = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_assoc($result)) {
			$url = formatNameUrl(stripslashes($row["TITLE"])) . "_vv".$row["ID"] . ".html";
			$date = explode(" ", $row["DATE_START"]);
			$code .= '
					<url>
						<loc>' . DOMAIN . $url.'</loc>
						<lastmod>'.$date[0].'</lastmod>
					</url>';
		}

		$code .= '</urlset>';
		$code = utf8_decode($code);
		$path = "../../../sitemaps/" . $file;
		$mode = "w+";
		
		if($fp = fopen($path,$mode)) {
		   fwrite($fp,$code);
		   fclose($fp);
		   $msg .= "Archivo ".$file." creado correctamente.<br/>";
		} else { 
		   $msg .= "Ha habido un problema con el archivo ".$file." no ha sido creado correctamente.</br>";
		}
		
		return($msg);
	}			

	function renameSitemap($old, $new) {
		$old_url = "../../../sitemaps/" . $old;
		$new_url = "../../../sitemaps/" . $new;
		
		rename($old_url, $new_url);
	}
	
	function deleteSitemap($del) {
		$url = "../../../sitemaps/" . $del;
		if(file_exists($url)) {
			unlink($url);
		}
	}
	
//Limpiar nombre RSS

	function clrAll($str) {
		$str=str_replace("&","",$str);
		$str=str_replace('"','',$str);
		$str=str_replace("'","",$str);
		$str=str_replace(">","",$str);
		$str=str_replace("<","",$str);
		
		return $str;
	}

//Resumen de artículo

	function calculateResume($idArt, $caesura) {
		global $connectBD;
		$p = 1;
		$q_par = "select TEXT as text from ".preBD."paragraphs where IDARTICLE = " . $idArt . " and POSITION = " . $p;	
		
		$result_par = checkingQuery($connectBD, $q_par);
		$row_par = mysqli_fetch_array($result_par);
		
		$text = strip_tags($row_par['text'],"<br>");
		while(strlen($text) <= $caesura) {
			$p++;
			$q_aux = "select TEXT as text from ".preBD."paragraphs where IDARTICLE = " . $idArt . " and POSITION = " . $p;	
			
			$result_aux = checkingQuery($connectBD, $q_aux);
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
			$prueba = utf8_decode($cadena);
			$prueba2 = substr(strip_tags($prueba),0,($longitud-3))." ...";
			$fin = utf8_encode($prueba2);		
		}else{
			$fin = $cadena;
		}
		return $fin;
	}
	
//Envio de alertas desde modules	
	function sendMailAlert($subject, $body, $email) {
		include("../../../includes/class/class.phpmailer.php");
		include("../../../includes/class/class.smtp.php");
			
		$sendmail = new PHPMailer();
		
		$body = utf8_decode($body);
		$sendmail->IsSMTP();
		// la dirección del servidor, p. ej.: smtp.servidor.com
		$sendmail->Host = MAILHOST;
		// dirección remitente, p. ej.: no-responder@miempresa.com
		$sendmail->From = MAILSEND;
		// nombre remitente, p. ej.: "Servicio de envío automático"
		$sendmail->FromName = utf8_decode('WebMaster');
		// asunto y cuerpo alternativo del mensaje
		$sendmail->Subject = utf8_decode($subject);
		$sendmail->AltBody = 'Su lector de correos no soporta HTML, para leer este mensaje utilice cualquier otro.';
		// si el cuerpo del mensaje es HTML
		$sendmail->MsgHTML($body);
		// podemos hacer varios AddAdress
		$sendmail->AddAddress($email, 'Usuario');
		
		// si el SMTP necesita autenticación
		$sendmail->SMTPAuth = true;
		// credenciales usuario
		$sendmail->Username = USERHOST;
		$sendmail->Password = PASSHOST;
		if(!$sendmail->Send()) {
			$msg = 'Error enviando: ' . $sendmail->ErrorInfo;
		} else {
			$msg = "Se le ha enviado un correo informativo al e-mail ".$email.".";
			$sendmail->ClearAddresses();
			$sendmail->ClearAttachments();
		}
		return $msg;
	}


	
/*funcion para info de un video de youtube*/
	function youtube_data($url,$return='',$width='',$height='',$rel=0){
		$urls = parse_url($url);
		
		//url is http://youtu.be/xxxx
		if($urls['host'] == 'youtu.be'){
			$id = ltrim($urls['path'],'/');
			}
			//url is http://www.youtube.com/embed/xxxx
		else if(strpos($urls['path'],'embed') == 1){
			$id = end(explode('/',$urls['path']));
			}
			//url is xxxx only
		else if(strpos($url,'/')===false){
			$id = $url;
		}
			//http://www.youtube.com/watch?feature=player_embedded&v=m-t4pcO99gI
			//url is http://www.youtube.com/watch?v=xxxx
		else{
			parse_str($urls['query']);
			$id = $v;
		}
		//return embed iframe
		if($return == 'embed'){
			return '<iframe width="'.($width?$width:560).'" height="'.($height?$height:349).'" src="https://www.youtube.com/embed/'.$id.'?rel='.$rel.'" frameborder="0" allowfullscreen></iframe>';
		}
		//return normal thumb
		else if($return == 'thumb'){
			return 'https://img.youtube.com/vi/'.$id.'/default.jpg';
		}
		//return hqthumb
		else if($return == 'hqthumb'){
			return 'https://img.youtube.com/vi'.$id.'/hqdefault.jpg';
		}//return title
		else if ($return == 'title') {
			$url = "gdata.youtube.com/feeds/api/videos?q=". $id;
			$doc = new DOMDocument;
			$doc->load($url);
			return $doc->getElementsByTagName("title")->item(0)->nodeValue;
		}
		// else return id
		else{
			return $id;
		}
	}	
	
	function format_date($d) {
		$aux = explode(" ", $d);
		$aux2 = explode("-", $aux[0]);
		
		$date_order = $aux2[2]."-".$aux2[1]."-".$aux2[0]." ".$aux[1];
		return $date_order;
	}	
	
	function infoBlogByID($id) {
		global $connectBD;
		$blog = array();
		
		$q = "select * from ".preBD."blog where ID = '" . $id . "'";
		$r = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($r);
		$blog["id"] = $row->ID;
		$blog["author"] = $row->AUTHOR;
		
		$q = "select Login, Name, Type from ".preBD."users where ID = ". $row->AUTHOR;
		$ra = checkingQuery($connectBD, $q);
		$author = mysqli_fetch_object($ra);
		$blog["authorLogin"] = $author->Login;
		$blog["authorName"] = $row->Name;
		$blog["authorType"] = $row->Type;
		
		$blog["section"] = $row->IDSECTION;
		$q = "SELECT TITLE FROM ".preBD."articles_sections WHERE ID = '" . $row->IDSECTION . "'";
		$r = checkingQuery($connectBD, $q);
		$sec = mysqli_fetch_object($r);
		$blog["title"] = stripslashes($sec->TITLE);
		
		return $blog;
	}	
	
	
	function duplicateArchiveArticle($archivo, $directory){
		$pos = strpos($archivo, "-");	
		$part2 = substr($archivo,$pos+1,strlen($archivo)-1);	
		$new_archive = time() . "-" . $part2;				
		
		$origin = "../../../files/articles/".$directory."/".$archivo;		
		$destiny = "../../../temp/".$archivo;			
		$new_destiny = "../../../temp/".$new_archive;
					
		/*copia a directorio temporal*/
		copy($origin,$destiny);
		
		/*cambio de nombre en directorio temporal*/
		rename($destiny, $new_destiny);
		
		/*copia a directorio final*/
		$destino_final = "../../../files/articles/".$directory."/".$new_archive;
		
		copy($new_destiny,$destino_final);
		
		/*eliminamos archivos temporal*/
		unlink($new_destiny);
		
		return $new_archive;
	}	

	function permission_modules($id) {
		global $connectBD;
		$q = "SELECT PERMISSION FROM ".preBD."configuration_modules WHERE ID='".$id."';";
		
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$permission = $row[$permission];
		return $permission;
	}
	
	function icon_header($id) {
		global $connectBD;
		$q = "SELECT COUNT(*) as total FROM ".preBD."configuration_modules WHERE IDMENU = ".$id." and PERMISSION >= " . $_SESSION[PDCLOG]["Type"];
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$count = $row['total'];
		
		if($count > 0){
			$icono = 1;
		}else{
			$icono = 0;
		}
		return $icono;
	}	
 
	function build_menu($id_module, $title_module, $permission, $menu, $image){
		global $connectBD;
		$code = '<div class="cp_mnu title_mnu">'.$title_module;
		
		if($image != ""){
			$code .= "<img class='title_module_image' src='".DOMAIN."pdc-reparteat/images/modules/".$image."' width='24' height='24' />";
		}
		
		$code .= '</div>';
		
		/*url del módulo en cuestión*/
		$q_url = "Select * from ".preBD."configuration_modules_url where IDMODULE = ".$id_module ." and LEVEL_PERMISSION <= ".$permission;
		$result_url = checkingQuery($connectBD, $q_url);
		while($row_url = mysqli_fetch_object($result_url)){ 
			$url = "";
			
			/*elemento de menú normal*/
			if($row_url->TYPE == 0 || $row_url->TYPE == 1){
				/*si viene definido los campos de component y tpl*/			
				if(($row_url->COMPONENT != "") && ($row_url->TPL != "")){
					$url .= "index.php?mnu=".$menu."&com=".$row_url->COMPONENT."&tpl=".$row_url->TPL;
					/*si viene definido el campo option*/			
					if($row_url->OPTION != ""){
						$url .= "&opt=".$row_url->OPTION;
					}
				}
				
				/*si viene definido el campo extra*/
				if($row_url->EXTRA != ""){
					$url .= $row_url->EXTRA;
				}	
				
				if($row_url->TYPE == 1){
					$code .= '<div class="cp_mnu" style="height:10px; border-left: 1px solid '.CORPORATIVE_COLOR.'; border-right: 1px solid '.CORPORATIVE_COLOR.'; width:229px;"></div>';
				}							
				$code .= '<div class="cp_mnu"><a href="'.$url.'" target="'.$row_url->TARGET.'" class="cp_col1_opt">'.$row_url->NAME.'</a></div>';	
				
			/*elemento de menú que es de tipo menú*/				
			}else if($row_url->TYPE == 2){
				$q = "select ID, TITLE from ".preBD."menu";
				$result = checkingQuery($connectBD, $q);
				while($menu_list = mysqli_fetch_assoc($result)){
					
					/*url del módulo en cuestión*/
					$q_url2 = "Select * from ".preBD."configuration_modules_url where IDMODULE = ".$id_module ." and LEVEL_PERMISSION <= ".$permission." and TYPE <> 4";
					//pre($q_url);
					$result_url2 = checkingQuery($connectBD, $q_url2);
					while($row_url2 = mysqli_fetch_object($result_url2)){ 
										
						if(($row_url2->COMPONENT != "") && ($row_url2->TPL != "")){
							$url = "index.php?mnu=".$menu."&com=".$row_url2->COMPONENT."&tpl=".$row_url2->TPL;
						}
						
						$aux = str_replace('IDMENU', stripslashes($menu_list["ID"]) ,$url);
						if($row_url2->EXTRA != ""){
							$var_aux = str_replace("IDMENU",$menu_list["ID"],$row_url2->EXTRA);
							$aux .= $var_aux;
						}
					
						$code .= '<div class="cp_mnu"><a href="'.$aux.'" target="'.$row_url2->TARGET.'" class="cp_col1_opt">'.stripslashes($menu_list["TITLE"]).'</a></div>';		
					}
					$code .= '<div class="cp_mnu"></div>';					
				}			
			/*caso de la opción importar tenga errores de importación */				
			}else if($row_url->TYPE == 3){		
				$q = "select count(*) as total from ".preBD."subscriptions_error";
				$Eresult = checkingQuery($connectBD, $q);
				$old_errors = mysqli_fetch_assoc($Eresult);
				if($old_errors["total"] > 0) {
					if(($row_url->COMPONENT != "") && ($row_url->TPL != "")){
						$url .= "index.php?mnu=".$menu."&com=".$row_url->COMPONENT."&tpl=".$row_url->TPL;
					}

					/*si viene definido el campo option*/			
					if($row_url->OPTION != ""){
						$url .= "&opt=".$row_url->OPTION;
					}
					
					/*si viene definido el campo extra*/
					if($row_url->EXTRA != ""){
						$url .= $row_url->EXTRA;
					}	
					$code .= '<div class="cp_mnu"><a href="'.$url.'" target="'.$row_url->TARGET.'" class="cp_col1_opt" style="padding-left:30px;color:#c50016;width:199px;">'.$row_url->NAME.'</a></div>';
				}
			/*caso de la opción nuevo menú */
			}else if($row_url->TYPE == 4){
				//pre($row_url);
				$url = "index.php?mnu=".$menu."&com=".$row_url->COMPONENT."&tpl=".$row_url->TPL;
		
				if($row_url->OPTION != ""){
					$url .= "&opt=".$row_url->OPTION;
				}	
				if($row_url->EXTRA != ""){
					$url .= $row_url->EXTRA;
				}		
				$code .= '<div class="cp_mnu" style="height:10px; border-left: 1px solid '.CORPORATIVE_COLOR.'; border-right: 1px solid '.CORPORATIVE_COLOR.'; width:229px;"></div>';
				$code .= '<div class="cp_mnu"><a style="padding-top:5px;" target="'.$row_url->TARGET.'" href="'.$url.'" class="cp_col1_opt">'.$row_url->NAME.'</a></div>';
			}
		}
		$code .= "<div class='menu_fin'></div>";
		return $code;
	}
	
		
	function check_temp(){
		global $connectBD;
		$cont = 0;
		
		$temp = glob("{../temp/*.*}",GLOB_BRACE);		
		$archives = array();
		for($i=0;$i<count($temp);$i++) {
			if(($temp[$i] != "../temp/Thumbs.db") && ($temp[$i] != NULL) && ($temp[$i] != "")) {
				$archives[$cont] = $temp[$i];
				$cont++;
			}
		}
		
		$art = glob("{../files/articles_temp/image/*.*}",GLOB_BRACE);
		
		for($i=0;$i<count($art);$i++) {			
			if(($art[$i] != "../files/articles_temp/image/Thumbs.db") && ($art[$i] != NULL) && ($art[$i] != "")) {
				$archives[$cont] = $art[$i];
				$cont++;
			}
		}
		
		$art_thumb = glob("{../files/articles_temp/thumb/*.*}",GLOB_BRACE);
		
		for($i=0;$i<count($art_thumb);$i++) {
			if(($art_thumb[$i] != "../files/articles_temp/thumb/Thumbs.db") && ($art[$i] != NULL) && ($art[$i] != "")) {
				$archives[$cont] = $art_thumb[$i];
				$cont++;
			}
		}
		
		if(count($archives) > 0){
			foreach($archives as $archivo){
				$is_file = TRUE;
			}
		}else{
			 $is_file = FALSE;
		}
		
		$q = "select ID from ".preBD."articles_temp";
		$result = checkingQuery($connectBD, $q);
		$art_temp = mysqli_num_rows($result);
		if($art_temp > 0) {
			$is_file = TRUE;
		}
		
		$q = "select ID from ".preBD."paragraphs_temp";
		$result = checkingQuery($connectBD, $q);
		$par_temp = mysqli_num_rows($result);
		if($par_temp > 0) {
			$is_file = TRUE;
		}
		
		if($is_file) {
			$exists = 1;
		}else{
			$exists = 0;
		}
		return $exists;
	}
	
	function showInfo() { ?>
		<div class='cp_mnu_title'>Informaci&oacute;n del servidor</div>
		<div style='font-size:13px;float:left;width:370px;'>
		Versi&oacute;n de PHP: <span class="bold"><?php echo phpversion(); ?></span><br/>
		
		
		Tama&ntilde;o m&aacute;ximo de subida = <span class="bold"><?php echo ini_get('post_max_size'); ?></span><br/>
		M&aacute;xima subida simultanea = <span class="bold"><?php echo ini_get('upload_max_filesize'); ?></span><br/>
		N&uacute;mero m&aacute;ximo de archivos = <span class="bold"><?php echo ini_get('max_file_uploads'); ?></span><br/>
		Caducidad de sesi&oacute;n = <span class="bold"><?php echo ini_get('session.gc_maxlifetime'); ?></span> seg<br/>
		Probabilidad de caducidad = <span class="bold"><?php echo ((ini_get('session.gc_probability') / ini_get('session.gc_divisor'))*100); ?>%</span><br/>
		Tiempo m&aacute;ximo de ejecuci&oacute;n = <span class="bold"><?php echo (ini_get('max_execution_time')/60); ?></span> min<br/>
		</div>
		
		<div style='font-size:13px;float:left;width:330px;'>
		<br/>
		<br/>
		
		<span class="bold">post_max_size</span><br />
		<span class="bold">upload_max_filesize</span><br />
		<span class="bold">max_file_uploads</span><br />
		<span class="bold">session.gc_maxlifetime</span><br />
		<span class="bold">(session.gc_probability/session.gc_divisor)*100</span><br />
		<span class="bold">max_execution_time)/60</span><br />
		</div>
		
		<br /><a style='clear:both;float:left;margin-top:20px;' href='components/configuration/configuration.infophp.php' target='_blank'>+ info (PHPinfo)</a>
	<?php
	}


	function return_bytes($val) {
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			// The 'G' modifier is available since PHP 5.1.0
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}
	
	function listCountry() {
		global $connectBD;
		$q = "select * from ".preBD."countries order by COUNTRY asc";
		
		$result = checkingQuery($connectBD,$q);
		$c = array();
		while($row = mysqli_fetch_object($result)) {
			$c[] = $row;
		}
		return $c;
	}


	function nameCountry($code) {
		global $connectBD;
		$q = "select Country from ".preBD."countries where Code = '" . $code . "'";
		
		$result = checkingQuery($connectBD,$q);
		$row = mysqli_fetch_object($result);
		$aux = $row->Country;
		return $aux;
	}
	function validateDNI($dni){
		if(strlen($dni)<9) {
			return false;
		}
		$dni = strtoupper($dni);
	 
		$letra = substr($dni, -1, 1);
		$numero = substr($dni, 0, 8);
	 
		// Si es un NIE hay que cambiar la primera letra por 0, 1 ó 2 dependiendo de si es X, Y o Z.
		$numero = str_replace(array('X', 'Y', 'Z'), array(0, 1, 2), $numero);	
	 
		$modulo = $numero % 23;
		$letras_validas = "TRWAGMYFPDXBNJZSQVHLCKE";
		$letra_correcta = substr($letras_validas, $modulo, 1);
	 
		if($letra_correcta!=$letra) {
			return false;
		}else {
			return true;
		}
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
	function sendMailAlertClient($userSend, $subject, $text, $link, $textButton, $template, $dir) {
		global $connectBD;
		$msg = "";
		$mail = new PHPMailer();
		$mail->IsSMTP();
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
	//Envio de alertas desde modules	
	function sendMailVerificationPwd($userSend, $subject, $text, $code, $link) {
		global $connectBD;

		$msg = "";
		
		$mail = new PHPMailer;
		$mail->SMTPDebug = false;
		$mail->IsSMTP();
		$mail->Host = MAILHOST;
		$mail->From = MAILSEND;
		$mail->FromName = "=?ISO-8859-1?B?".base64_encode('Reparteat - Panel de control')."=?=";
		$mail->Subject = "=?ISO-8859-1?B?".base64_encode(utf8_decode($subject))."=?=";
		$mail->AltBody = utf8_encode("Se le está intentando enviar un e-mail con contenido que su gestor de correos no puede leer");
		
		$fileAccess = new FileAccess("../../files/mail-templates/recoverpwd.html");
		$body = $fileAccess->read();
		
		$body = str_replace("#DOMAIN#", DOMAIN, $body);
		$body = str_replace("#SUBJECT#", $subject, $body);
		$body = str_replace("#TEXT#", $text, $body);
		$body = str_replace("#CODE#", $code, $body);
		$body = str_replace("#LINK#", $link, $body);
		if($link == DOMAINZP){
			$body = str_replace("#BOTTON#", "Ir a Panel de control", $body);
		}else {
			$body = str_replace("#BOTTON#", "VERIFICAR", $body);
		}
 		
		$mail->MsgHTML(utf8_decode($body)); // utf8 decode just BEFORE send.
		$mail->SMTPAuth = true;
		$mail->Username = USERHOST;
		$mail->Password = PASSHOST;
		$mail->Port = PORTHOST;
		if(SECURITYHOSTZP == 1) {
			$mail->SMTPSecure = "ssl";
		}elseif(SECURITYHOST == 2) {
			$mail->SMTPSecure = "tls";
		}
		
		for($i=0;$i<count($userSend);$i++) {
			$mail->AddAddress($userSend[$i]["mail"], utf8_decode($userSend[$i]["name"]));
			$msgSend .= "- <em>".$userSend[$i]["name"]."(".$userSend[$i]["mail"].")</em><br/>";
		}
		if(!$mail->Send()) {
			$msg .= "<span class='red'><em>'Error - " . $mail->ErrorInfo."</em>.</span><br/>";
		} else {
			$msg .= "<br/>Alerta enviada a:<br/>".$msgSend; 
			$mail->ClearAddresses();
			$mail->ClearAttachments();
		}
		return $msg;
	}
?>
