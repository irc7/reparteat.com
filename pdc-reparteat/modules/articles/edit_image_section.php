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
	
	//pre($_POST);die();
	if (allowed("content")) {
		$item = intval($_POST["section"]);
		$q = "select * from ".preBD."articles_sections where ID = " . $item;
		$result = checkingQuery($connectBD, $q);
		$rowItem = mysqli_fetch_assoc($result);
		$url = "../../../files/section/image/";
		
		$msg = "No ha escogido ninguna opción";		
	
		/*comprobamos si queremos borrar o actualizar la imagen*/
		if($_POST['delete_image_section'] == 1){			
			$old_image = $url .$rowItem["THUMBNAIL"];

			if(file_exists($old_image)){
				deleteFile($old_image);
			}
			/*actualizamos la tabla base de datos*/
			$q = "UPDATE ".preBD."articles_sections SET"; 
			$q .= " THUMBNAIL = ''";
			$q .= " WHERE ID = " . $item;	
			checkingQuery($connectBD, $q);
					
			$msg = "Sección modificada correctamente";
		
		}else{
			if($_FILES) {
				if ($_FILES["Doc"]["error"] == 0) {
					preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Doc"]["name"], $ext);
					$ext[0] = str_replace(".", "", $ext[0]);
					$file = checkingExtFile($ext[0]);
					//pre($file);die();
					if($file["upload"] == 1){
						if ($_FILES["Doc"]["type"] == "image/jpeg" || $_FILES["Doc"]["type"] == "image/pjpeg" || $_FILES["Doc"]["type"] == "image/gif" || $_FILES["Doc"]["type"] == "image/png") {
							$nombre_imagen = formatNameFile($_FILES["Doc"]["name"]);
							$ext = explode("/", $_FILES["Doc"]["type"]);
							
							/*borramos imagen antigua*/
							$old_image = $url .$rowItem["THUMBNAIL"];
							if(file_exists($old_image)){
								deleteFile($old_image);
							}
							
							/*movemos la imagen nueva*/
							$temp_url = "../../../temp/".$nombre_imagen;
							$temp = "../../../temp/";
							move_uploaded_file($_FILES['Doc']['tmp_name'],$temp_url);
							customImage($temp, $url, $nombre_imagen, $ext[1], $rowItem['WIDTH_IMAGE'], $rowItem['HEIGHT_IMAGE']);	
							
							/*actualizamos la tabla base de datos*/
							$q = "UPDATE ".preBD."articles_sections SET"; 
							$q .= " THUMBNAIL = '".$nombre_imagen."'";				
							$q .= " WHERE ID = " . $item;	
							checkingQuery($connectBD, $q);
							
							$msg = "Sección modificada correctamente";						
						} else {
							$msg = "No es un archivo correcto. ";
							$error = "imagen";
						}
					}else{
						$error = "Image Section";
						$msg .= $file["msg"];
					}
				}			
			}else{
				$msg = "No se ha adjuntado ninguna imagen";				
			}
		}
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=content&com=articles&tpl=option&opt=section&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>