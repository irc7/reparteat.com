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
	
	if (allowed("configuration")) {	
		if ($_POST) {
			$error = NULL;
			connectdb();
			
			$msg = "";		
			$rss_elements =  trim($_POST["rss_elements"]);
			$caesura =  trim($_POST["caesura"]);
			$size = array();
			
			$size[0] =  abs(intval($_POST["maxwidth"]));
			$size[1] =  abs(intval($_POST["maxheight"]));
			
			$option = $_POST["optImg"];
			if($option != 1) {	
				$q = "select TEXT from ".preBD."configuration where ID = 12";
				$result = checkingQuery($connectBD, $q);
				$imageRSS = mysqli_fetch_object($result);	
			}
			switch($option) {
				case 0:
					$url = "../../../files/rss/image/".$imageRSS->TEXT;
					deleteFile($url);
					$Image = "";
					$updateImg = true;
				break;
				case 1:
					$updateImg = false;
				break;
				case 2:
					if ($_FILES["imageRSS"]["error"]== 0) { 
						preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["imageRSS"]["name"], $ext);
						$ext[0] = str_replace(".", "", $ext[0]);
						$file = checkingExtFile($ext[0]);
						//pre($file);die();
						if($file["upload"] == 1){
							if ($_FILES["imageRSS"]["type"] == "image/jpeg" || $_FILES["imageRSS"]["type"] == "image/pjpeg" || $_FILES["imageRSS"]["type"] == "image/gif" || $_FILES["imageRSS"]["type"] == "image/png") {
								$ext = explode("/", $_FILES["imageRSS"]["type"]);
								$Image = formatNameFile($_FILES["imageRSS"]["name"]);
								$temp_url = "../../../temp/";
								$temp_url_name = $temp_url .$Image;
								$url = "../../../files/rss/image/";
								move_uploaded_file($_FILES["imageRSS"]["tmp_name"],$temp_url_name);
								$sizeImg = getimagesize($temp_url_name);
								if($sizeImg[0]>$size[0] || $sizeImg[1]>$size[1]) {
									$p = $sizeImg[0] / $sizeImg[1];
									$newWidth = $size[0];
									$newHeight = $size[0] / $p;
									if($newHeight > $size[1]) {
										$newHeight = $size[1];
										$newWidth = $newHeight * $p;
									}
									customImage($temp_url, $url, $Image, $ext[1], $newWidth, $newHeight);
								} else {
									$url .= $Image;
									rename($temp_url_name, $url);
								}
								if($imageRSS->TEXT != "") {
									$url = "../../../files/rss/image/".$imageRSS->TEXT;
									deleteFile($url);
								}
								$updateImg = true;
							} else {
								$error = "imageRSS";
								$msg .= "El formato de la imagen debe de ser: *.jpg, *.png o *.gif).--";
								$updateImg = false;
							}
						}else{
							$error = "Image RSS";
							$msg .= $file["msg"];
						}	
					}else {
						$updateImg = false;
					}
				break;
			}
			
			//CONSULTAS
			if ($error == NULL) {
				
				$q = "UPDATE ".preBD."configuration SET"; 
					$q .= " VALUE = '" . $rss_elements;
					$q .= "', AUXILIARY = '" . $caesura;
				$q .= "' WHERE ID = 9";

				checkingQuery($connectBD, $q);
				if($updateImg) {
					$q = "UPDATE ".preBD."configuration SET"; 
						$q .= " TEXT = '" . $Image;
						$q .= "' WHERE ID = 12";

					checkingQuery($connectBD, $q);
					
				}
				
				disconnectdb($connectBD);
				$msg .= "Configuración RSS modificado correctamente.";
				$location = "Location: ../../index.php?mnu=configuration&com=configuration&tpl=rss&msg=".utf8_decode($msg);
				header($location);		
			}else {
				disconnectdb($connectBD);
				echo $error;
			}
		}
	}else{
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>