<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	date_default_timezone_set("Europe/Madrid");
	$msg = "";

	if (!allowed("content")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if ($_POST) {
		
		$error = NULL;		
		
		/*fechas inicial y final*/
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
		
		//preForm(0);
		
		$Status = $_POST["Status"];
		$Author = trim($_POST["Author"]);
		$Gallery = intval($_POST["Gallery"]);
		$Title = addslashes(trim($_POST["Title"]));
		$Title_seo = addslashes(trim($_POST["Title_seo"]));
		if($Title_seo == ""){
			$Title_seo = $Title; 
		}
		$Text = addslashes(trim($_POST["Text"]));
		$TypeVideo = trim($_POST["typeVideo"]);
		$TypeImage = trim($_POST["menuImage"]);
		
		
//VIDEO
		switch($TypeVideo) {
			case "video":
				/*comprobamos error en el vídeo*/		
				if ($_FILES["Video"]["error"] == 0) {
					preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Video"]["name"], $ext);
					$ext[0] = str_replace(".", "", $ext[0]);
					$file = checkingExtFile($ext[0]);
					//pre($file);die();
					if($file["upload"] == 1){
						if($Gallery == 1 && ($_FILES["Video"]["type"] == "video/webm" || $_FILES["Video"]["type"] == "video/mp4" || $_FILES["Video"]["type"] == "video/ogg")) {
							
							$Video = formatNameFile($_FILES["Video"]["name"]);
							$url_f = "../../../files/videos/video/".$Video;
							move_uploaded_file($_FILES["Video"]["tmp_name"],$url_f);
							
						}else if ($Gallery != 1 && ($_FILES["Video"]["type"] == "video/x-flv" || $_FILES["Video"]["type"] == "video/mp4")) {
							
							$Video = formatNameFile($_FILES["Video"]["name"]);
							$url_f = "../../../files/videos/video/".$Video;
							move_uploaded_file($_FILES["Video"]["tmp_name"],$url_f);
							
						} else {
							$msg .= "Formato de video inválido.";
						}
					}else{
						$error = "Video";
						$msg .= $file["msg"];
					}
				} else {
					$error = "Video";
					$msg .= "No ha seleccionado ningún archivo de video.";
				}	
			break;
			case "youtube":
				$Video = trim($_POST["codeYoutube"]);
			break;
			default:
				$Video = "";
				$msg .= "Debe seleccionar un archivo de video o guardar un código de YouTube.";
				$error = "Video";
			break;
		}
	//IMAGEN		
		switch ($TypeImage){
			case "personal":	
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
						}else {
							$msg .= "Imágen no válida.";
						}
					}else{
						$error = "Image";
						$msg .= $file["msg"];
					}
				} else {
					$Image = "";
					$msg .= "No ha seleccionado ningúna imagen.";
				}
			break;
		
			case "youtube":
				$Image = $Video; 
			break;
			default:
				$Image = "";
				$msg .= "No se ha asociado ninguna imagen al video.";
			break;
		}
		
		if ($error == NULL) {
		/*actualizamos la posicion de las imágenes existentes*/
			$q_s_update = "SELECT * FROM ".preBD."videos WHERE IDGALLERY = ".$Gallery;
			$result_s_update = checkingQuery($connectBD, $q_s_update);
			
			while ($row_update = mysqli_fetch_object($result_s_update)) {
				
				$q_up = "UPDATE ".preBD."videos SET POSITION = '".($row_update->POSITION + 1)."' WHERE ID = ".$row_update->ID;
				checkingQuery($connectBD, $q_up);
				
			}			
			
			$q = "INSERT INTO ".preBD."videos (IDGALLERY, AUTHOR, DATE_START, DATE_END, TITLE, TITLE_SEO, TEXT, CODE, IMAGE, TYPEVIDEO, TYPEIMAGE, POSITION, STATUS) VALUES";
			$q .= " ('" .$Gallery . "', '" . $Author . "', '" . $Date_start->format('Y-m-d H:i:s'). "', '" . $Date_end->format('Y-m-d H:i:s') . "', '" . $Title . "', '" . $Title_seo . "', '" . $Text . "', '" . $Video . "', '" . $Image . "', '". $TypeVideo . "', '" . $TypeImage . "', '1', '" . $Status . "')";

			checkingQuery($connectBD, $q);
			
			$newId = mysqli_insert_id($connectBD);  

			/*/GESTION DE SITEMAP
			$q = "select TITLE from ".preBD."videos_gallery where ID = '" . $Gallery . "'";
			checkingQuery($connectBD, $q);
			
			$result = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_assoc($result);
			$sitemap = formatNameUrl(stripslashes($row["TITLE"])) . ".xml";
			
			$msg_alt = construcSitemapVideos($Gallery, $sitemap);			
			*/
			disconnectdb($connectBD);
			$msg = "Vídeo creado correctamente";
			$location = "Location: ../../index.php?mnu=content&com=videos&tpl=edit&record=".$newId."&msg=".utf8_decode($msg);
			header($location);
		} else {
			$location = "Location: ../../index.php?mnu=content&com=videos&tpl=create&msg=".utf8_decode($msg);
			header($location);
		}
	}
?>
