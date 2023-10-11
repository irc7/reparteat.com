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
	$mnu = trim($_POST["mnu"]);
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	

	if ($_POST) {
		$error = NULL;
		
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
		
		$Author = $_POST["Author"];
		$Status = $_POST["Status"];
		$Title = mysqli_real_escape_string($connectBD,trim($_POST["Title"]));
 		$Text = mysqli_real_escape_string($connectBD,trim($_POST["Text"]));
		$Agenda = $_POST["agenda"];
		
		
		//Tamaño maximo de subida
		$maxSize = intval(ini_get('upload_max_filesize'));
		$maxSize = $maxSize * 1024 * 1024;
		if ($_FILES["Url_file"]["error"]== 0) {
			if ($_FILES["Url_file"]["size"]<$maxSize) {
				preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Url_file"]["name"], $ext);
				$ext[0] = str_replace(".", "", $ext[0]);
				$file = checkingExtFile($ext[0]);
				if($file["upload"] == 1){
					$Url_file = formatNameFile($_FILES["Url_file"]["name"]);
					$url_c = "../../../files/agenda/doc/".$Url_file;
					move_uploaded_file($_FILES["Url_file"]["tmp_name"],$url_c);
					$Size = calculateSizeDoc(intval($_FILES["Url_file"]["size"]));
				}else{
					$msg .= "El tipo de archivo seleccionado no esta permitido.<br/>";
				}
			}else{
				$msg .= "El tamaño del archivo seleccionado es mayor del permitido por el servidor.<br/>";
			}
		} else {
			$Url_file = "";
		//	$msg .= "Error al subir el archivo<br/>";
		}
		
		if ($error == NULL) {
			
			
			$q = "INSERT INTO ".preBD."agenda (DATE_START, DATE_END, ID_AGENDA_SECTION, STATUS, AUTHOR, TITLE, TEXT, URL, SIZE) 
					VALUES 
				('".$Date_start->format('Y-m-d H:i:s')."', '".$Date_end->format('Y-m-d H:i:s')."', '".$Agenda."', '".$Status."', '".$Author."', '".$Title."', '".$Text."', '".$Url_file."', '".$Size."')";
			checkingQuery($connectBD, $q);
			$record_id = mysqli_insert_id($connectBD); 
			$msg .= "Evento <em>".$Title."</em> creado correctamente.";
			disconnectdb($connectBD);
			$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=edit&&record=".$record_id."&msg=".utf8_decode($msg);
			header($location);
		}else {
			disconnectdb($connectBD);
			$msg = "Se ha producido un error inesperado, vuelva a intertarlo.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=create&opt=days&msg=".utf8_decode($msg);
			header($location);
		}
	}else{
		disconnectdb($connectBD);
		$msg = "Se ha producido un error inesperado, vuelva a intertarlo.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=create&msg=".utf8_decode($msg);
		header($location);
	}
?>