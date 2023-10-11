<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	date_default_timezone_set("Europe/Madrid");

	if (!allowed("content")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	

	
	if ($_POST) {
		$error = NULL;
		$msg = "";
		
		$id = intval($_POST['idVideo']);
		 
		$q = "SELECT * FROM ".preBD."videos WHERE ID = " . $id;
		$result = checkingQuery($connectBD, $q);
		
		$videoBD = mysqli_fetch_object($result);


		$Date_start_hh = abs(intval($_POST["Date_start_hh"]));
		if ($Date_start_hh == NULL) {
			$Date_start_hh = "00";
		} elseif(strlen($Date_start_hh) == 1) {
			$Date_start_hh = '0' . $Date_start_hh;
		} elseif($Date_start_hh > 23) {
			$Date_start_hh = 23;
			$msg .= "El número de horas no puede ser mayor a 23.<br/>";
		}
		
		$Date_start_ii = abs(intval($_POST["Date_start_ii"]));
		if ($Date_start_ii == NULL) {
			$Date_start_ii = "00";
		} elseif(strlen($Date_start_ii) == 1) {
			$Date_start_ii = '0' . $Date_start_ii;
		} elseif($Date_start_ii > 59) {
			$Date_start_ii = 59;
			$msg .= "El número de minutos no puede ser mayor a 59.<br/>";
		}
		
		$DateAux = $_POST["date_day"]." ".$Date_start_hh.":".$Date_start_ii.":00";
		$Date_start = new DateTime($DateAux);
		
		if(isset($_POST["controlDateEnd"])) {//si viene activado el checkbox
			$Date_end_hh = abs(intval($_POST["Date_end_hh"]));
			if ($Date_end_hh == NULL) {
				$Date_end_hh = "00";
			} elseif(strlen($Date_end_hh) == 1) {
				$Date_end_hh = '0' . $Date_end_hh;
			} elseif($Date_end_hh > 23) {
				$Date_end_hh = 23;
				$msg .= "El número de horas no puede ser mayor a 23.<br/>";
			}
			
			$Date_end_ii = abs(intval($_POST["Date_end_ii"]));
			if ($Date_end_ii == NULL) {
				$Date_end_ii = "00";
			} elseif(strlen($Date_end_ii) == 1) {
				$Date_end_ii = '0' . $Date_end_ii;
			} elseif($Date_end_ii > 59) {
				$Date_end_ii = 59;
				$msg .= "El número de minutos no puede ser mayor a 59.<br/>";
			}
			
			$DateAux = $_POST["date_day_finish"]." ".$Date_end_hh.":".$Date_end_ii.":00";
			$Date_end = new DateTime($DateAux);
			
			if($Date_start->getTimestamp() > $Date_end->getTimestamp()) {
				$Date_end = $Date_start;
				$msg .= "La fecha de fin del evento no puede ser anterior a la del comienzo. Su valor ha sido modificado.<br/>";
			}
		}else{
			$Date_end = new DateTime($DateAux);
		}
			
		
		
		$Status = $_POST["Status"];
		$Author = trim($_POST["Author"]);
		$Gallery = intval($_POST["Gallery"]);
		$Title = addslashes(trim($_POST["Title"]));
		$Title_seo = addslashes(trim($_POST["Title_seo"]));
		if($Title_seo == ""){
			$Title_seo = $Title; 
		}
		$Text = addslashes(trim($_POST["Text"]));
		$typeVideo = trim($_POST["typeVideo"]);
		
		
		switch($typeVideo) {
			case "video":
			//TRATAR VIDEO
				if(isset($_FILES["Video"]) && $_FILES["Video"]["error"] == 0) {
					preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Video"]["name"], $ext);
					$ext[0] = str_replace(".", "", $ext[0]);
					$file = checkingExtFile($ext[0]);
					//pre($file);die();
					if($file["upload"] == 1){
						if($Gallery == 1 && ($_FILES["Video"]["type"] == "video/webm" || $_FILES["Video"]["type"] == "video/mp4" || $_FILES["Video"]["type"] == "video/ogg")) {
							$Video = formatNameFile($_FILES["Video"]["name"]);
							$url_f = "../../../files/videos/video/".$Video;
							move_uploaded_file($_FILES["Video"]["tmp_name"],$url_f);
							if($videoBD->TYPEVIDEO == "video" && $videoBD->CODE != "") {
							//borramos en antiguo	
								$urlOld = "../../../files/videos/video/".$videoBD->CODE;
								deleteFile($urlOld);
							}
						}else if ($Gallery != 1 && ($_FILES["Video"]["type"] == "video/x-flv" || $_FILES["Video"]["type"] == "video/mp4")) {
							$Video = formatNameFile($_FILES["Video"]["name"]);
							$url_f = "../../../files/videos/video/".$Video;
							move_uploaded_file($_FILES["Video"]["tmp_name"],$url_f);
							if($videoBD->TYPEVIDEO == "video" && $videoBD->CODE != "") {
							//borramos en antiguo	
								$urlOld = "../../../files/videos/video/".$videoBD->CODE;
								deleteFile($urlOld);
							}
						} else {
							$msg .= "Formato de video inválido.";
						}
					}else{
						$error = "Video";
						$msg .= $file["msg"];
					}
				}else {
					$Video = $videoBD->CODE;
				} 			

			//TRATAR IMAGE
				if(isset($_POST["changeImg"]) && trim($_POST["changeImg"]) == "personal") {
					$optImg = "upload";
				} else {
					$optImg = "nothing";
				}
			break;
			case "youtube":
				$Video = trim($_POST["codeYoutube"]);
				if($videoBD->TYPEVIDEO == "video" && $videoBD->CODE != "") {
				//borramos en antiguo	
					$urlOld = "../../../files/videos/video/".$videoBD->CODE;
					deleteFile($urlOld);
				}
				//TRATAR IMAGE
				if(isset($_POST["changeImg"]) && trim($_POST["changeImg"]) == "personal") {
					$optImg = "upload";
				} elseif(isset($_POST["changeImg"]) && trim($_POST["changeImg"]) == "youtube") {
					$optImg = "youtube";
				}else{
					$optImg = "nothing";
				}
			break;
		}
		
		
		switch($optImg) {
			case "upload":
				if ($_FILES["Image"]["error"] == 0) {
					preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image"]["name"], $ext);
					$ext[0] = str_replace(".", "", $ext[0]);
					$file = checkingExtFile($ext[0]);
					//pre($file);die();
					if($file["upload"] == 1){

						if (($_FILES["Image"]["type"] == "image/gif" || $_FILES["Image"]["type"] == "image/jpeg" || $_FILES["Image"]["type"] == "image/pjpeg" || $_FILES["Image"]["type"] == "image/png")) {
							$ext = explode("/", $_FILES["Image"]["type"]);
							$Image = formatNameFile($_FILES["Image"]["name"]);
							$temp_url = "../../../temp/";
							$temp_url_name = $temp_url .$Image;
							move_uploaded_file($_FILES["Image"]["tmp_name"],$temp_url_name);
							$url = "../../../files/videos/image/";
						
						//consultar tamaños	
							
							$q = "SELECT WIDTH_IMAGE, WIDTH_THUMB, HEIGHT_THUMB FROM ".preBD."videos_gallery_style WHERE IDGALLERY = '" . $Gallery . "'";
							$result = checkingQuery($connectBD, $q);
							
							$row = mysqli_fetch_object($result);
							$image_width = $row->WIDTH_IMAGE;
							resizeImage($temp_url, $url, $Image, $image_width, $ext[1], 0);//a 0 no borra la imagen de TEMP pq se necesita para miniatura
							
							$thumb_width = $row->WIDTH_THUMB;
							$thumb_height = $row->HEIGHT_THUMB;
							
							$url_thumb = "../../../files/videos/thumb/";
							customImage($temp_url, $url_thumb, $Image, $ext[1], $thumb_width, $thumb_height);
							if($videoBD->IMAGE != "") {
								//borramos en antiguo	
								$urlImgOld = "../../../files/videos/image/".$videoBD->IMAGE;
								deleteFile($urlImgOld);
								$urlThumbOld = "../../../files/videos/thumb/".$videoBD->IMAGE;
								deleteFile($urlThumbOld);
							}
						}else {
							$msg .= "Imágen no válida.";
						}
					}else{
						$error = "Image";
						$msg .= $file["msg"];
					}
				} else {
					$msg .= "No ha seleccionado ningúna imagen.";
				}
				
				
			break;
			case "youtube":
				$Image = $Video;
				//borramos en antiguo	
				if($videoBD->IMAGE != "") {	
					$urlImgOld = "../../../files/videos/image/".$videoBD->IMAGE;
					deleteFile($urlImgOld);
					
					$urlThumbOld = "../../../files/videos/thumb/".$videoBD->IMAGE;
					deleteFile($urlThumbOld);
				}
			break;
			case "nothing":
				$Image = $videoBD->IMAGE;
			break;
		}
		
		if($Image == $Video && $Image != "") {
			$typeImage = "youtube";
		}elseif($Image != $Video && $Image != "") {
			$typeImage = "personal";
		}else {
			$typeImage = "";
		}
		
		
//CONSULTAS
		if ($error == NULL) {

			/*cambio de sección de galería*/		
			if($videoBD->IDGALLERY != $Gallery){
				$change_gallery = TRUE;
			}else{
				$change_gallery = FALSE;
			}

			/*cambio de estado*/			
			if($videoBD->STATUS != $Status){
				$change_status = TRUE;
			}else{
				$change_status = FALSE;
			}		

			/*cambio de título*/
			if($videoBD->TITLE != $Title){
				$change_title = TRUE;
			}else{
				$change_title = FALSE;
			}	
			
			$q = "UPDATE ".preBD."videos SET"; 
				$q .= " STATUS = '" . $Status;
				$q .= "', IDGALLERY = '" . $Gallery;
				$q .= "', AUTHOR = '" . $Author;
				$q .= "', DATE_START = '" . $Date_start->format('Y-m-d H:i:s');
				$q .= "', DATE_END = '" . $Date_end->format('Y-m-d H:i:s');				
				$q .= "', TITLE = '" . $Title;
				$q .= "', TITLE_SEO = '" . $Title_seo;
				$q .= "', TEXT = '" . $Text;
				$q .= "', CODE = '" . $Video;
				$q .= "', IMAGE = '" . $Image;
				$q .= "', TYPEVIDEO = '" . $typeVideo;
				$q .= "', TYPEIMAGE = '" . $typeImage;
				$q .= "' WHERE ID = " . $id;
					
			checkingQuery($connectBD, $q);
			
			
			/*/GESTION DE SITEMAP
			if($change_gallery) {
				
				$q_sec_new = "select TITLE from ".preBD."videos_gallery where ID = '" . $Gallery . "'";
				$result_sec_new = checkingQuery($connectBD, $q_sec_new);
				
				$row_sec_new = mysqli_fetch_assoc($result_sec_new);
				$sitemap_new = formatNameUrl(stripslashes($row_sec_new["TITLE"])) . ".xml";
				
				$msg_alt = construcSitemapVideos($Gallery, $sitemap_new);
				
				$q_sec_old = "select TITLE from ".preBD."videos_gallery where ID = '" . $videoBD->IDGALLERY . "'";
				$result_sec_old = checkingQuery($connectBD, $q_sec_old);
				
				$row_sec_old = mysqli_fetch_assoc($result_sec_old);
				$sitemap_old = formatNameUrl(stripslashes($row_sec_old["TITLE"])) . ".xml";
				
				$msg_alt = construcSitemapVideos($videoBD->IDGALLERY, $sitemap_old);
				
			} elseif(!$change_gallery && ($change_title || $change_status)) {
				
				$q_sec = "select TITLE from ".preBD."videos_gallery where ID = '" . $Gallery . "'";
				$result_sec = checkingQuery($connectBD, $q_sec);
				
				$row_sec = mysqli_fetch_assoc($result_sec);
				$sitemap = formatNameUrl(stripslashes($row_sec["TITLE"])) . ".xml";
				
				$msg_alt = construcSitemapVideos($Gallery, $sitemap);
			}	
*/
			disconnectdb($connectBD);
			$msg .= "Vídeo modificado correctamente";
			
		}
		$location = "Location: ../../index.php?mnu=content&com=videos&tpl=edit&record=".$id."&msg=".utf8_decode($msg);
		header($location);

	}
?>