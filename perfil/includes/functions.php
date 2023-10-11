<?php
	$DIAS = array('LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO', 'DOMINGO');
	$Dias = array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');
	$dias = array('lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo');
	$MESES = array('ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE');
	$Meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
	$meses = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
	
	
	function converMinusc($texto){
		$find = array('Á','É','Í','Ó','Ú','Ñ');
		$replac = array('á','é','í','ó','ú','ñ');
		$text = str_replace ($find, $replac, $texto); 
		$text = strtolower($text);
		return ($text);
	}
	function converMayusc($texto){
		$find = array('á','é','í','ó','ú','ñ');
		$replac = array('Á','É','Í','Ó','Ú','Ñ');
		$text = str_replace ($find, $replac, $texto); 
		$text = strtoupper($text);
		return ($text);
	}
	
	function deleteFile($image_url) {
		if(file_exists($image_url)) {
			unlink($image_url);
		}
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
	function calculateSizeDoc($s) {
		if ($s < 1024) {
		   $Size = $s . " bytes";
		} else {
			$size_kb = $s / 1024;
			if (intval($size_kb) < 1024){
				$Size = intval($size_kb) . " Kb";
			} else {
				$size_mb = intval($size_kb) / 1024;
				$Size = intval($size_mb) . " Mb";
			}
		}
		return $Size;
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
	
//Envio de alertas desde modules	
	function sendMailAlertZP($user, $subject, $text, $tpl) {
		global $connectBD;
		$msg = "";
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->Host = MAILHOST;
		$mail->From = MAILSEND;
		$mail->FromName = "=?ISO-8859-1?B?".base64_encode(utf8_decode(NAMESEND))."=?=";
		$mail->Subject = "=?ISO-8859-1?B?".base64_encode(utf8_decode($subject))."=?=";	
		$mail->AltBody = utf8_encode("Se le está intentando enviar un e-mail con contenido que su gestor de correos no puede leer");
		$fileAccess = new FileAccess("../../../files/mail-templates/".$tpl.".html");
		$body = $fileAccess->read();
		
		if($tpl == "confirm-user") {
			$body = str_replace("#DOMAIN#", DOMAIN, $body);
			$body = str_replace("#SUBJECT#", $subject, $body);
			$body = str_replace("#TEXT#", $text, $body);
		}
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
		
		$mail->AddAddress($user->login, $user->name . " " . $user->surname);
		if(!$mail->Send()) {
			$msg .= "<span class='red'><em>'Error - " . $sendmail->ErrorInfo."</em>.</span><br/>";
		} else {
			$msg .= "<br/>Alerta enviada a:".$user->login; 
			$mail->ClearAddresses();
			$mail->ClearAttachments();
		}
		return $msg;
	}
//Envio de alertas desde modules	
	function sendMailVerificationZP($userSend, $subject, $text, $code, $link) {
		global $connectBD;

		$msg = "";
		
		$mail = new PHPMailer;
		$mail->IsSMTP();
		$mail->Host = MAILHOST;
		$mail->From = MAILSEND;
		$mail->FromName = "=?ISO-8859-1?B?".base64_encode(utf8_decode(NAMESEND))."=?=";
		$mail->Subject = "=?ISO-8859-1?B?".base64_encode(utf8_decode($subject))."=?=";
		$mail->AltBody = utf8_encode("Se le está intentando enviar un e-mail con contenido que su gestor de correos no puede leer");
		
		$fileAccess = new FileAccess("../files/mail-templates/recoverpwd.html");
		$body = $fileAccess->read();
		
		$body = str_replace("#DOMAIN#", DOMAIN, $body);
		$body = str_replace("#SUBJECT#", $subject, $body);
		$body = str_replace("#TEXT#", $text, $body);
		$body = str_replace("#CODE#", $code, $body);
		$body = str_replace("#LINK#", $link, $body);
		if($link == DOMAINZP){
			$body = str_replace("#BOTTON#", "Ir a AREA DEL CLIENTE", $body);
		}else {
			$body = str_replace("#BOTTON#", "VERIFICAR", $body);
		}
 		
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
		
		for($i=0;$i<count($userSend);$i++) {
			$mail->AddAddress($userSend[$i]["mail"], utf8_decode($userSend[$i]["name"]));
			$msgSend .= "- <em>".$userSend[$i]["name"]."(".$userSend[$i]["mail"].")</em><br/>";
		}
		if(!$mail->Send()) {
			$msg .= "<span class='red'><em>'Error - " . $sendmail->ErrorInfo."</em>.</span><br/>";
		} else {
			$msg .= "<br/>Alerta enviada a:<br/>".$msgSend; 
			$mail->ClearAddresses();
			$mail->ClearAttachments();
		}
		return $msg;
	}
		
	function formatNameFileDownload($name) {
		$name = trim($name);
		$name = strip_tags($name);
		$name = mb_strtolower($name,"UTF-8");
		$name = formatSpecialChar($name);
		return $name;
	}
	
	function cleanerDNI($dni) {
		$dni = str_replace(".", "", $dni);
		$dni = str_replace(",", "", $dni);
		$dni = str_replace("-", "", $dni);
		$dni = converMinusc($dni);
		
		return $dni;
	}
	
	function checkEncoding($texto){ 
		if (!mb_check_encoding($texto, 'UTF-8')){
			$texto = utf8_encode($texto);
		}
		return $texto;
	} 
	function sumaryOrderMail($order) {
		
		$proObj = new Product();
		$ordObj = new Order();
		$products = $ordObj->listProductOrder($order->ID);
		
		$textMail = "";
		$textMail .= "<h4 style='color:#009975;font-size:15px;text-align:left;'>Resumen del pedido - REF: ".$order->REF."</h4>";
		$textMail .= "<table cellspacing='0' cellpadding='1' align='left' border='0' style='min-width:620px;width:620px;margin:0 auto;'><thead><tr>";
			$textMail .= "<th style='width:75px;background-color:#009975;color:#e8b400;font-size:13px;text-align:left;'>Uds.</th>";
			$textMail .= "<th style='width:400px;background-color:#009975;color:#e8b400;font-size:13px;text-align:left;'>Producto</th>";
			$textMail .= "<th style='width:175px;background-color:#009975;color:#e8b400;font-size:13px;text-align:center;'>Precio</th>";
			$textMail .= "</tr></thead><tbody>";
		foreach($products as $item) {
			$product = $proObj->infoProductByIdNoStatus($item->IDPRODUCT);
			$textMail .= "<tr>";
				$textMail .= "<td style='width:75px;color:#333;font-size:12px;text-align:left;'>".$item->UDS."</td>";
				$textMail .= "<td style='width:400px;color:#333;font-size:12px;text-align:left;'>".$product->TITLE."</td>";
				$textMail .= "<td style='width:175px;color:#333;font-size:12px;text-align:center;'>".$item->COST . " &euro;</td>";
				
			$textMail .= "</tr>";
		}
		$textMail .= "</tbody></table>";
		$textMail .= "<div style='width:100%;height:1px;display:block;clear:both;background-color:#666666;'>&nbsp;</div>";
		$textMail .= "<table cellspacing='0' cellpadding='1' align='left' border='0' style='min-width:620px;width:620px;margin:0 auto;'><tbody>";
			$textMail .= "<tr>";
				$textMail .= "<td style='width:475px;color:#333;font-size:12px;text-align:right;'>Subtotal</td>";
				$textMail .= "<td style='width:175px;color:#333;font-size:12px;text-align:center;'>".$order->SUBTOTAL . " &euro;</td>";
			$textMail .= "</tr>";
			$textMail .= "<tr>";
				$textMail .= "<td style='width:475px;color:#333;font-size:12px;text-align:right;'>Gastos de envio</td>";
				$textMail .= "<td style='width:175px;color:#333;font-size:12px;text-align:center;'>".$order->SHIPPING . " &euro;</td>";
			$textMail .= "</tr>";
			if($order->DISCOUNT > 0) {
				$textMail .= "<tr>";
					$textMail .= "<td style='width:475px;color:#333;font-size:12px;text-align:right;'>Descuento</td>";
					$textMail .= "<td style='width:175px;color:#333;font-size:12px;text-align:center;'>-".$order->DISCOUNT . " &euro;</td>";
				$textMail .= "</tr>";
			}
		$textMail .= "</tbody></table>";
		$textMail .= "<div style='width:100%;height:1px;display:block;clear:both;background-color:#666666;'>&nbsp;</div>";
		$textMail .= "<table cellspacing='0' cellpadding='1' align='left' border='0' style='min-width:620px;width:620px;margin:0 auto;'><tbody>";
			$textMail .= "<tr>";
				$textMail .= "<td style='width:475px;color:#333;font-size:13px;text-align:right;font-weight:bold;'>TOTAL</td>";
				$textMail .= "<td style='width:175px;color:#333;font-size:13px;text-align:center;'>".$order->COST . " &euro;</td>";
			$textMail .= "</tr>";
		$textMail .= "</tbody></table>";
		
		return $textMail;
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
		$thumb_url = $url.$ind."-".$imagename;
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
	function conversorSegundosHoras($tiempo_en_segundos) {
		$horas = floor($tiempo_en_segundos / 3600);
		$minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
		$segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);

		return $horas . ':' . $minutos . ":" . $segundos;
	}
	
	
?>