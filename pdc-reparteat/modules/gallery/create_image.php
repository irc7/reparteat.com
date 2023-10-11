<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	
	date_default_timezone_set("Europe/Madrid");
	//pre($_POST);die();
	$mnu = $_POST["mnu"];
	if (allowed($mnu)) {
		if ($_POST) {		
			$error = NULL;
			
			$Status = $_POST["Status"];
			$Author = trim($_POST["Author"]);
			$Gallery = trim($_POST["Gallery"]);
			$Title =  mysqli_real_escape_string($connectBD, trim($_POST["Title"]));
			$Text = mysqli_real_escape_string($connectBD, trim($_POST["Text"]));
			
			$TypeUpload = trim($_POST["typeUpload"]);			
			
			/*Tamaño máximo de una única subida*/
			$max_size = return_bytes(ini_get('upload_max_filesize'));
			
			if($TypeUpload == "single"){
				if($_FILES["Url"]["size"] < $max_size){
					if ($_FILES["Url"]["error"] == 0) {
						preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Url"]["name"], $ext);
						$ext[0] = str_replace(".", "", $ext[0]);
						$file = checkingExtFile($ext[0]);
						if($file["upload"] == 1){
							if (($_FILES["Url"]["type"] == "image/gif" || $_FILES["Url"]["type"] == "image/jpeg" || $_FILES["Url"]["type"] == "image/pjpeg" || $_FILES["Url"]["type"] == "image/png")) {
								$ext = explode("/", $_FILES["Url"]["type"]);
								$image = formatNameFile($_FILES["Url"]["name"]);
								$temp_url = "../../../temp/";
								$temp_url_name = $temp_url .$image;
								move_uploaded_file($_FILES["Url"]["tmp_name"],$temp_url_name);							
								
								$q = "SELECT * FROM ".preBD."images_gallery_style WHERE IDGALLERY = '" . $Gallery . "'";
								$result = checkingQuery($connectBD, $q);
								$style = mysqli_fetch_object($result);
								
								$url = "../../../files/gallery/image/";
								resizeImage($temp_url, $url, $image, $style->WIDTH_IMAGE, $ext[1], 0);
								
								$widthThumb = $style->WIDTH_THUMB * ESCALE_THUMB;
								$heightThumb = $style->HEIGHT_THUMB * ESCALE_THUMB;
								
								$url_thumb = "../../../files/gallery/thumb/";
								customImage($temp_url, $url_thumb, $image, $ext[1], $widthThumb, $heightThumb);
							}else {
								$error = "Url";
								$msg .= "Imágen no válida.";
							}
						}else{
							$error = "Image";
							$msg .= $file["msg"];
						}
					} else {
						$Url_image = "";
						$msg .= "Error al subir la imagen";
					}
					
					if ($error == NULL) {
						/*actualizamos la posicion de las imágenes existentes*/					
						$q_up = "UPDATE ".preBD."images SET POSITION = POSITION + 1 WHERE IDGALLERY = ".$Gallery;
						checkingQuery($connectBD, $q_up);
						
						
						$q = "INSERT INTO ".preBD."images (IDGALLERY, STATUS, AUTHOR, TITLE, TEXT, URL, POSITION) VALUES";
						$q .= " ('" .$Gallery . "', '" . $Status . "', '" . $Author . "', '" . $Title . "', '" . $Text . "', '" . $image . "', '1')";
						checkingQuery($connectBD, $q);
						
						$idNew = mysqli_insert_id($connectBD); 						
						
						disconnectdb($connectBD);
						$msg = "Imagen <em>".$Title."</em> creada correctamente";
						$location = "Location: ../../index.php?mnu=".$mnu."&com=gallery&tpl=edit&record=".$idNew."&msg=".utf8_decode($msg);
						header($location);
					}else {
						echo $error;
					}
				}else{
					$error = "Tamaño de archivo mayor que el permitido por el servidor";
				}
			}else if($TypeUpload == "multiple"){
				$tot = count($_FILES["Multiple2"]["name"]);
				
				/*Máxima subida simultánea*/
				if(return_bytes(ini_get('upload_max_filesize')) > return_bytes(ini_get('post_max_size'))){
					$max_simultaneous_rise = ini_get('post_max_size');
				}else{
					$max_simultaneous_rise = ini_get('upload_max_filesize');
				}				
								
				/*Tamaño total que ocupan las imágenes a subir*/				
				$size_total = 0;
				for ($k = 0; $k < $tot; $k++){
					$size_total = $size_total + $_FILES["Multiple2"]["size"][$k];
				}
				
				if($size_total < return_bytes($max_simultaneous_rise)){
					for ($i = 0; $i < $tot; $i++){
						$error = NULL;
						if(($_FILES["Multiple2"]["error"][$i] == 0) && ($_FILES["Multiple2"]["size"][$i] < return_bytes($max_simultaneous_rise))) {
							preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Multiple2"]["name"][$i], $ext);
							$ext[0] = str_replace(".", "", $ext[0]);
							$file = checkingExtFile($ext[0]);
							if($file["upload"] == 1){									
								if (($_FILES["Multiple2"]["type"][$i] == "image/gif" || $_FILES["Multiple2"]["type"][$i] == "image/jpeg" || $_FILES["Multiple2"]["type"][$i] == "image/pjpeg" || $_FILES["Multiple2"]["type"][$i] == "image/png")) {
									$ext = explode("/", $_FILES["Multiple2"]["type"][$i]);
									
									$Title = $_FILES["Multiple2"]["name"][$i];
									
									$image = formatNameFile($_FILES["Multiple2"]["name"][$i]);
									$temp_url = "../../../temp/";
									$temp_url_name = $temp_url .$image;
									move_uploaded_file($_FILES["Multiple2"]["tmp_name"][$i],$temp_url_name);													
									
									$q = "SELECT * FROM ".preBD."images_gallery_style WHERE IDGALLERY = '" . $Gallery . "'";
									 
									$result = checkingQuery($connectBD, $q);
									$style = mysqli_fetch_object($result);
									
									$url = "../../../files/gallery/image/";
									resizeImage($temp_url, $url, $image, $style->WIDTH_IMAGE, $ext[1], 0);
									
									$widthThumb = $style->WIDTH_THUMB * ESCALE_THUMB;
									$heightThumb = $style->HEIGHT_THUMB * ESCALE_THUMB;
									
									$url_thumb = "../../../files/gallery/thumb/";
									customImage($temp_url, $url_thumb, $image, $ext[1], $widthThumb, $heightThumb);
									//pre("imagen copiada y cambiada ".$i);
								}else {
									$error = "Url";
									$msg .= "Imágen no válida.";
								}
							}else{
								$error = "Image";
								$msg .= $file["msg"];
							}
						} else {
							$Url_image = "";
							$error = "Tamaño imagen";
							$msg .= "Error al subir la imagen ".$_FILES["Multiple2"]["name"][$i].". El archivo está corrupto el tamaño supera el permitido por el servidor.";
						}
					
						if ($error == NULL) {
							
							$q_up = "UPDATE ".preBD."images SET POSITION = POSITION + 1 WHERE IDGALLERY = ".$Gallery;
							checkingQuery($connectBD, $q_up);
							
							$q = "INSERT INTO ".preBD."images (IDGALLERY, STATUS, AUTHOR, TITLE, TEXT, URL, POSITION) VALUES";
							$q .= " ('" .$Gallery . "', '" . $Status . "', '" . $Author . "', '" . $Title . "', '" . $Text . "', '" . $image . "', '1')";
							checkingQuery($connectBD, $q);
							
							$idNew = mysqli_insert_id($connectBD); 
											
						}else {
							echo $error;
						}
					}
				}else{
					$error = 1;
					$msg .= "Ha superado el máximo tamaño de subida simultánea que permite el servidor.";
				}
				
				disconnectdb($connectBD);
				if($error == NULL){
					$msg .= "Imágenes creadas correctamente";
				}
				$location = "Location: ../../index.php?mnu=".$mnu."&com=gallery&tpl=option&recodsperpage=25&search=&filtergallery=".$Gallery."&msg=".utf8_decode($msg);
				header($location);
			}
		}
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>
