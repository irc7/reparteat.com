<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	if (phpversion() <> "4.3.9") date_default_timezone_set("Europe/Madrid");
	
	if (!allowed("design")) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if ($_POST) {
		$error = NULL;
		$id = $_POST["id"];

		$msg = "";
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
			
		
		$Album = $_POST["Album"];
		
		$cambio_album = 0;
		
		$q2 = "select * from ".preBD."slider where ID = " . $id;	
		$result2 = checkingQuery($connectBD, $q2);
		$aux = mysqli_fetch_object($result2);		
		
		if(($aux->IDALBUM) != $Album){
			$cambio_album = 1;
		}
		
		$q = "select WIDTH, HEIGHT from ".preBD."slider_gallery where ID = " . $Album;	
		
		$resultA = checkingQuery($connectBD, $q);
		$info = mysqli_fetch_object($resultA);
		$Status = $_POST["Status"];
		$Title = mysqli_real_escape_string($connectBD,trim($_POST["Title"]));
		$Subtitle = mysqli_real_escape_string($connectBD,trim($_POST["Subtitle"]));
		$Text = mysqli_real_escape_string($connectBD,trim($_POST["Text"]));
		$Link = mysqli_real_escape_string($connectBD,trim($_POST["Link"]));
		$Target = $_POST["Target"];
		$Image_name = $_POST["Image1"];
		
		
		if ($_FILES["Image2"]["error"] == 0) {
			preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image2"]["name"], $ext);
			$ext[0] = str_replace(".", "", $ext[0]);
			$file = checkingExtFile($ext[0]);
			//pre($file);die();
			if($file["upload"] == 1){
				if ($_FILES["Image2"]["type"] == "image/gif" || $_FILES["Image2"]["type"] == "image/png" || $_FILES["Image2"]["type"] == "image/jpeg" || $_FILES["Image2"]["type"] == "image/pjpeg") {
					$Image_name2 = formatNameFile($_FILES["Image2"]["name"]);
					
					$url_delete = "../../../files/slide/image/".$Image_name;
					if($Image_name != "") {
						deleteFile($url_delete);
					}
					
					$temp_url = "../../../temp/".$Image_name2;
					move_uploaded_file($_FILES["Image2"]["tmp_name"],$temp_url);
					
					$temp = "../../../temp/";
					$ext = explode("/", $_FILES['Image2']['type']);
					$url = "../../../files/slide/image/";
					customImage($temp, $url, $Image_name2, $ext[1], $info->WIDTH, $info->HEIGHT);
					$Image_name = $Image_name2;
				}else {
					$error = "Image";
					$msg .= "Imágen no válida.";
				}
			}else{
				$error = "Image";
				$msg .= $file["msg"];
			}
		}
		if ($error == NULL) {
			if($cambio_album == 1){
				/*actualizamos la posicion de los banner existentes*/
				$q_up = "UPDATE ".preBD."slider SET POSITION = POSITION + 1 WHERE IDALBUM = ".$Album;
				checkingQuery($connectBD, $q_up);
				
			}
			
			$q = "UPDATE ".preBD."slider SET STATUS='".$Status."', 
				DATE_START='".$Date_start->format('Y-m-d H:i:s')."', 
				DATE_END='".$Date_end->format('Y-m-d H:i:s')."', 
				TITLE='".$Title."', 
				SUBTITLE='".$Subtitle."', 
				IDALBUM='".$Album."', 
				TEXT='".$Text."', 
				LINK='".$Link."', 
				TARGET='".$Target."', 
				IMAGE='".$Image_name."'";
				if($cambio_album == 1){
					$q .=", POSITION = '1'";
				}
				$q .= "WHERE ID='".$id."'";

				checkingQuery($connectBD, $q);
				
			
			if($cambio_album == 1){
				$q_up = "UPDATE ".preBD."slider SET POSITION = POSITION - 1 WHERE IDALBUM = ".$aux->IDALBUM." and POSITION > ".$aux->POSITION;
				checkingQuery($connectBD, $q_up);
				
			}
			
			
			disconnectdb($connectBD);
			$msg .= "Imagen <em>".$Title."</em> modificado correctamente.";
			$location = "Location: ../../index.php?mnu=design&com=slide&tpl=edit&record=".$id."&msg=".utf8_decode($msg);
			header($location);
		}
		else {
			echo $error;
		}
	}
?>