<?php


function constructDateComplete($date, $months) {
		
	$d = explode("-", $date);
	$i = intval($d[1]);
	$day = explode(" ", $d[2]);
	
	$m = $months[$i-1];
	$t = $day[0] . " " . $m . " " . $d[0];
	
	return $t;
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

	function construct_date_newsletter($date, $months, $days) {
		$d = explode(" ", $date);
		$day = explode("-", $d[0]);
		
		$i = intval($day[1]);
		$m = $months[$i-1];
		$i2 = date(w, strtotime($d[0]));
		$name_day = $days[$i2-1];
		
		$t = $name_day . ", " .$day[2] . " de " . $m . " de " . $day[0];
		return $t;
}
	
//IMAGENES PARA SLIDE
	function createSlide($imagename, $ext) {
		
		$thumb_width = 540;
		$thumb_height = 275;
		
		$image_url = "../../../temp/".$imagename;
		$thumb_proportion = $thumb_width / $thumb_height;
		$image_info = getimagesize($image_url);
		$width = $image_info[0];
		$height = $image_info[1];
		if (($width / $thumb_width) >= ($height / $thumb_height)) {
			$crop_width = $height * $thumb_proportion;
			$crop_height = $height;
			$crop_x = ceil(($width - $crop_width) / 2);
			$crop_y = 0;
		}
		else {
			$crop_width = $width;
			$crop_height = $width / $thumb_proportion;
			$crop_x = 0;
			$crop_y = ceil(($height - $crop_height) / 2);
		}
		$thumb_url = "../../../files/images/slide/".$imagename;
		if($ext == "jpeg" || $ext == "pjpeg") {	
			$source = imagecreatefromjpeg($image_url);
			$thumb = imagecreatetruecolor($thumb_width, $thumb_height);
			imagecopyresized($thumb, $source, 0, 0, $crop_x, $crop_y, $thumb_width, $thumb_height, $crop_width, $crop_height);
			if($width != $thumb_width) {
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
		imagedestroy($thumb);
		unlink($image_url);
	}

	function deleteTempPast() {
	
	
		$dir1 = "../files/articles_temp/image/";
		$dir2 = "../files/articles_temp/thumb/";
		$dir3 = "../temp/";
		$dir4 = "../files/articles_temp/video/";

		
		$images_article = opendir($dir1);
		while ($file_art = readdir($images_article))  {   
			if (is_file($dir1.$file_art)) { 
				unlink($dir1.$file_art); 
			}
		}
		
		$images_thumb = opendir($dir2);
		while ($file_thumb = readdir($images_thumb))  {   
			if (is_file($dir2.$file_thumb)) { 
				unlink($dir2.$file_thumb); 
			}
		}
		
		$dir_temp = opendir($dir3);
		while ($file_temp = readdir($dir_temp))  {   
			if (is_file($dir3.$file_temp)) { 
				unlink($dir3.$file_temp); 
			}
		}
		
		$videos_temp = opendir($dir4);
		while ($files = readdir($videos_temp))  {   
			if (is_file($dir4.$files)) { 
				unlink($dir4.$files); 
			}
		}
		
		$q1 = "TRUNCATE TABLE `".preBD."articles_temp`";
		checkingQuery($connectBD, $q1);		
		
		$q2 = "TRUNCATE TABLE `".preBD."paragraphs_temp`";
		checkingQuery($connectBD, $q2);
		
		$q2 = "TRUNCATE TABLE `".preBD."paragraphs_file_temp`";
		checkingQuery($connectBD, $q2);
		
	}










?>