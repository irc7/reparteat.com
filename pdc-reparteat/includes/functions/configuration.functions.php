<?php

//esta funcion se usa desde la carpeta Rss de la parte publica de ahi las url's relativas	
function selectThumbnail($img) {
	$image = array();
	$url = "../files/articles/thumb/";
	$urlAbs = DOMAIN."files/articles/thumb/";
	$urlImg = DOMAIN."files/articles/image/";
	$urlImgA = "../files/articles/image/";
	$type = "image";
	if($img == "") {
		$urlAbs.= "default.jpg";
		$urlImg .= "default.jpg";
		$type = "default";
	} else {
		$urlAbs.= $img;
		$urlImg.= $img;
		$url .= $img;
		$urlImgA.= $img;
		if(!file_exists($url)) {
			$p = explode("=", $img);
			$urlImg = "http://img.youtube.com/vi/".$p[1]."/0.jpg";
			$urlAbs = "http://img.youtube.com/vi/".$p[1]."/1.jpg";
			$type = "youtube";
			$image["size"][0] = 480;
			$image["size"][1] = 360;
		}else {
			$image["size"] = getimagesize($urlImgA);
		}
		
	}
	
	$image["thumb"] = $urlAbs;
	$image["url"] = $urlImg;
	$image["type"] = $type;
	
	return $image;

}

?>