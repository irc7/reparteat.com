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
if (allowed("blog")) {
	$msg = "";
	$dir1 = "../../../files/articles_temp/image/";
	$dir2 = "../../../files/articles_temp/thumb/";
	$dir3 = "../../../temp/";
	$dir4 = "../../../files/articles_temp/video/";

	
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

//	$download_img = opendir($dir5);
//	while ($images = readdir($download_img))  {   
//		if (is_file($dir5.$images)) { 
//			unlink($dir5.$images); 
//		}
//	}


	$q1 = "TRUNCATE TABLE `".preBD."articles_temp`";
	checkingQuery($connectBD, $q1);
	
	$q2 = "TRUNCATE TABLE `".preBD."paragraphs_temp`";
	checkingQuery($connectBD, $q2);
	
	$q2 = "TRUNCATE TABLE `".preBD."paragraphs_file_temp`";
	checkingQuery($connectBD, $q2);
	

	disconnectdb($connectBD);
	$msg .= "Carpetas temporales vaciadas correctamente";
	if(!isset($_GET["return"])) {
		$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=trash&msg=".utf8_decode($msg);
	}else{
		$location = "Location: ../../index.php?mnu=default&com=home&tpl=default&msg=".utf8_decode($msg);
	}
	header($location);
} else {
	disconnectdb($connectBD);
	$msg = "No tiene permisos para realizar esta acción.";
	$location = "Location: ../../index.php?msg=".utf8_decode($msg);
	header($location);
}
?>