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
		$msg = "";
		
		if(isset($_GET["option"])) {
			$optAct = trim($_GET["option"]);
		}else {
			$optAct = NULL;
		}
		
		$id = intval($_POST['id']);
		
		$q = "SELECT * FROM ".preBD."agenda WHERE ID = " . $id;
		$r = checkingQuery($connectBD, $q);
		$eventBD = mysqli_fetch_object($r);
		
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
		
		$Author = trim($_POST["Author"]);
		$Status = intval($_POST["Status"]);
		$Title = mysqli_real_escape_string($connectBD,trim($_POST["Title"]));
		$Text = mysqli_real_escape_string($connectBD,trim($_POST["Text"]));
		$Agenda = abs(intval($_POST["agenda"]));
		
		$optFile = intval($_POST["optFile"]);
		$url = "../../../files/agenda/doc/";
		switch($optFile) {
			case 1:
				if($eventBD->URL != "" && $eventBD->URL != NULL) {
					deleteFile($url.$eventBD->URL);
				}
				$Url_file = "";
				$Size = "";
			break;
			case 2:
				//Tamaño maximo de subida
				$maxSize = intval(ini_get('upload_max_filesize'));
				$maxSize = $maxSize * 1024 * 1024;
				if ($_FILES["Url_file"]["size"]<$maxSize) {
					if($eventBD->URL != "" && $eventBD->URL != NULL) {
						deleteFile($url.$eventBD->URL);
					}
					if ($_FILES["Url_file"]["error"]== 0) {
						preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Url_file"]["name"], $ext);
						$ext[0] = str_replace(".", "", $ext[0]);
						$file = checkingExtFile($ext[0]);
						
						if($file["upload"] == 1){
							$Url_file = formatNameFile($_FILES["Url_file"]["name"]);
							$url_c = "../../../files/agenda/doc/".$Url_file;
							move_uploaded_file($_FILES["Url_file"]["tmp_name"],$url_c);
							$Size = calculateSizeDoc($_FILES["Url_file"]["size"]);
						}else{
							$Url_file = "";
							$Size = "";
							$msg .= "El tipo de archivo seleccionado no esta permitido.<br/>";
						}
					} else {
						$Url_file = "";
						$Size = "";
					//	$msg .= "Error al subir el archivo<br/>";
					}
				}else{
					$msg .= "El tamaño del archivo seleccionado es mayor del permitido por el servidor.<br/>";
					$Url_file = $eventBD->URL;
					$Size = $eventBD->SIZE;
				}
			break;
			default:
				$Url_file = $eventBD->URL;
				$Size = $eventBD->SIZE;
			break;
		}
		
		
	//CONSULTAS
		if ($error == NULL) {
			
				$q = "UPDATE ".preBD."agenda SET
						STATUS = '" . $Status. "', 
						DATE_START = '" . $Date_start->format('Y-m-d H:i:s')."', 
						DATE_END = '" . $Date_end->format('Y-m-d H:i:s')."', 
						AUTHOR = '" . $Author . "', 
						TITLE = '" . $Title . "', 
						TEXT = '" . $Text . "', 
						URL = '" . $Url_file . "', 
						SIZE = '" . $Size . "', 
						ID_AGENDA_SECTION = '" . $Agenda ."'
						WHERE ID = " . $id;
						
				checkingQuery($connectBD, $q);
				$msg .= "Evento <em>".$Title."</em> modificado correctamente";
				
			if($optAct == "preview"){
				$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=edit&record=".$id."&msg=".utf8_decode($msg)."&preview=on";	
			}else{
				$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=edit&record=".$id."&msg=".utf8_decode($msg);
			}
			disconnectdb($connectBD);
			header($location);
			
		} else {
			disconnectdb($connectBD);
			$msg = "No se ha encontrado la festividad en la base de datos, vuelva a intentarlo gracias.";
			$location = "Location: ../../index.php?mnu=".$mnu."&com=agenda&tpl=option&msg=".utf8_decode($msg);
			header($location);
		}

	}
?>