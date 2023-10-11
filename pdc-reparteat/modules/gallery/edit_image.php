<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	date_default_timezone_set("Europe/Madrid");
	
	$mnu = $_POST["mnu"];
	if (allowed($mnu)) {	
		if (isset($_GET['record'])) {
			$id = $_GET['record'];
		}

		if ($_POST) {
			$error = NULL;
				
			$msg = "";
			
			$q_image = "SELECT * FROM ".preBD."images WHERE ID = " . $id;
			checkingQuery($connectBD, $q_image);
			
			$result_image = checkingQuery($connectBD, $q_image);
			$row_image = mysqli_fetch_assoc($result_image);
			
			$change_image = 0;
			$change_gallery = 0;
			$change_author = 0;
			$change_title = 0;
			$change_text = 0;
			$change_status = 0;
			$change_Url = 0;
			
			$mnu = $_POST["mnu"];
			$Author = trim($_POST["Author"]);
			if($Author != $row_image['AUTHOR']) {
				$change_author = 1;
				$change_image = 1;
			}
			
			$Gallery = trim($_POST["Gallery"]);
			if($Gallery != $row_image['IDGALLERY']) {
				$q = "select max(POSITION) as last from ".preBD."images where IDGALLERY = " . $Gallery;
				$result_last = checkingQuery($connectBD, $q);
				$last = mysqli_fetch_assoc($result_last);
				if($last["last"] == "" || $last["last"] == NULL){
					$Position = 1;
				}else {
					$Position = $last["last"] + 1;
				}
				$change_gallery = 1;
				$change_image = 1;
			}
			
			$Title =   mysqli_real_escape_string($connectBD, trim($_POST["Title"]));
			if($Title!= $row_image['TITLE']) {
				$change_title = 1;
				$change_image = 1;
			}
			
			$Text =  mysqli_real_escape_string($connectBD, trim($_POST["Text"]));
			if($Text != $row_image['TEXT']) {
				$change_text = 1;
				$change_image = 1;
			}
			
			$Status = $_POST["Status"];
			if($Status != $row_image['STATUS']) {
				$change_status = 1;
				$change_image = 1;
			}		

				
			
	//IMAGEN
			if (($_POST["Select_Image"] == "on" || $_POST["Select_Min"] == "on") && $_FILES["Url"]["error"] == 0) {
				preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Url"]["name"], $ext);
				$ext[0] = str_replace(".", "", $ext[0]);
				$file = checkingExtFile($ext[0]);
				//pre($file);die();
				if($file["upload"] == 1){
				
					if (($_FILES["Url"]["type"] == "image/gif" || $_FILES["Url"]["type"] == "image/jpeg" || $_FILES["Url"]["type"] == "image/pjpeg" || $_FILES["Url"]["type"] == "image/png")) {
						if($_POST["Select_Min"] == "on") {
							$ext = explode("/", $_FILES["Url"]["type"]);
							$image = $row_image["URL"];
						} else { 
							$ext = explode("/", $_FILES["Url"]["type"]);
							$image = formatNameFile($_FILES["Url"]["name"]);
						}
						$temp_url = "../../../temp/";
						$temp_url_name = $temp_url .$image;
						move_uploaded_file($_FILES["Url"]["tmp_name"],$temp_url_name);
						
						$q = "SELECT * FROM ".preBD."images_gallery_style WHERE IDGALLERY = '" . $Gallery . "'";
						$result = checkingQuery($connectBD, $q);
						
						$style = mysqli_fetch_object($result);
						
						if(isset($_POST["Select_Image"]) && $_POST["Select_Image"] == "on") {
							$url = "../../../files/gallery/image/";
							resizeImage($temp_url, $url, $image, $style->WIDTH_IMAGE, $ext[1], 0);
						}
						$url_thumb = "../../../files/gallery/thumb/";
						if(isset($_POST["Select_Min"]) && $_POST["Select_Min"] == "on") {
							$thumb = $url_thumb . $image;
							if(file_exists($thumb) && $image != "") {
								unlink($thumb);	
							}
						} else {
							if($row_image["URL"] != "") {
								$urlDelete = "../../../files/gallery/image/" . $row_image["URL"];
								$thumbDelete = "../../../files/gallery/thumb/" . $row_image["URL"];
								deleteFile($urlDelete);
								deleteFile($thumbDelete);
							}
							$change_Url = 1;
							$change_image = 1;
						}
						$url_thumb = "../../../files/gallery/thumb/";
						customImage($temp_url, $url_thumb, $image, $ext[1], $style->WIDTH_THUMB, $style->HEIGHT_THUMB);
						
					} else {
						$error = "Url";
						$msg .= "Imágen no válida.";
					}
				}else{
					$error = "Image Up";
					$msg .= $file["msg"];
				}	
					
			} else {
				$Url_image = "";
			}
			
		
	//CONSULTAS
			if ($error == NULL) {
				if($change_image == 1){
					$q = "UPDATE ".preBD."images SET"; 
						$q .= " STATUS = '" . $Status;
						if($change_gallery == 1) {
							$q .= "', IDGALLERY = '" . $Gallery;
							$q .= "', POSITION = '" . $Position;
						}
						if($change_author == 1) {
							$q .= "', AUTHOR = '" . $Author;
						}
						if($change_title == 1) {
							$q .= "', TITLE = '" . $Title;
						}
						if($change_text == 1) {
							$q .= "', TEXT = '" . $Text;
						}
						if($change_Url == 1) {
							$q .= "', URL = '" . $image;
						}
					$q .= "' WHERE ID = " . $id;
							
					checkingQuery($connectBD, $q);
					
				}
				disconnectdb($connectBD);
				$msg .= "Imagen modificada correctamente";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=gallery&tpl=edit&record=".$id."&msg=".utf8_decode($msg);
				header($location);
			}else {
				echo $error;
			}

		}
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>