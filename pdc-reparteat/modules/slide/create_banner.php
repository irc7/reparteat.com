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
		
		$q = "select WIDTH, HEIGHT from ".preBD."slider_gallery where ID = " . $Album;		
		$resultA = checkingQuery($connectBD, $q);
		$info = mysqli_fetch_object($resultA);					   
		
		$Status = $_POST["Status"];
		
		$Title = mysqli_real_escape_string($connectBD, trim($_POST["Title"]));
		$Subtitle = mysqli_real_escape_string($connectBD, trim($_POST["Subtitle"]));
		$Text = mysqli_real_escape_string($connectBD, trim($_POST["Text"]));
		$Link = mysqli_real_escape_string($connectBD, trim($_POST["Link"]));
		$Target = $_POST["Target"];
 		if ($_FILES['Image']['error'] == 0) {
			preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image"]["name"], $ext);
			$ext[0] = str_replace(".", "", $ext[0]);
			$file = checkingExtFile($ext[0]);
			if($file["upload"] == 1){
				if ($_FILES['Image']['type'] == "image/gif" || $_FILES['Image']['type'] == 'image/png' || $_FILES['Image']['type'] == 'image/jpeg' || $_FILES['Image']['type'] == 'image/pjpeg') {
					$ext = explode("/", $_FILES["Image"]["type"]);
					$Image_url = formatNameFile($_FILES['Image']['name']);
					$temp_url = "../../../temp/".$Image_url;
					$temp = "../../../temp/";
					move_uploaded_file($_FILES['Image']['tmp_name'],$temp_url);
					$url = "../../../files/slide/image/";
					customImage($temp, $url, $Image_url, $ext[1], $info->WIDTH, $info->HEIGHT);	
				}else {
					$error = "Image";
					$msg .= "Imágen no válida.";
				}
			}else{
				$error = "Image";
				$msg .= $file["msg"];
			}
		}
		else {
			$Image_url = "";
		}
		if ($error == NULL) {
			/*actualizamos la posicion de los banner existentes*/
				$q_up = "UPDATE ".preBD."slider SET POSITION = POSITION + 1 WHERE IDALBUM = ".$Album;
				checkingQuery($connectBD, $q_up);
				
			$q = "INSERT INTO `".preBD."slider`(`IDALBUM`, `DATE_START`, `DATE_END`, `TITLE`, `SUBTITLE`, `TEXT`, `IMAGE`, `LINK`, `TARGET`, `POSITION`, `STATUS`) 
					VALUES 
				('".$Album."','" . $Date_start->format('Y-m-d H:i:s'). "','" . $Date_end->format('Y-m-d H:i:s') . "','".$Title."', '".$Subtitle."', '".$Text."', '".$Image_url."', '".$Link."', '".$Target."',1,'".$Status."')";
			
			checkingQuery($connectBD, $q);
			$idNew = mysqli_insert_id($connectBD); 
			disconnectdb($connectBD);
			$msg .= "Imagen <em>".$Title."</em> creado";
			$location = "Location: ../../index.php?mnu=design&com=slide&tpl=edit&record=".$idNew."&msg=".utf8_decode($msg);
			header($location);
			
		}
		else {
			$error;
		}
	}
?>
