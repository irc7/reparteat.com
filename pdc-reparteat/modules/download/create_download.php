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
	$connectBD = connectdb();
//	pre($_GET);
//	pre($_FILES);
//	die();
	if($_POST) {
		$mnu = trim($_POST["mnu"]);
		$submnu = abs(intval($_POST["submnu"]));
		if(!isset($_POST["mnu"]) || !allowed($_POST["mnu"])) { 	
			disconnectdb($connectBD);
			$msg = "No tiene permisos para realizar esta acción";
			$location = "Location: ../../index.php?msg=".utf8_decode($msg);
			header($location);
		} else {
		
		
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
			$Section = intval($_POST["Section"]);
			
			$Title = addslashes(trim($_POST["Title"]));
			$Text = addslashes(trim($_POST["Text"]));
			
	//Tamaño maximo de subida
			$maxSize = intval(ini_get('upload_max_filesize'));
			$maxSize = $maxSize * 1024 * 1024;
			
			$sizeUpload = 0;
			if($_FILES['Image']['error'] == 0){
				$sizeUpload = $sizeUpload + $_FILES['Image']['size'];
			}
//			if($_FILES['Doc']["error"] == 0) {
//				$sizeUpload = $sizeUpload + $_FILES['Doc']['size'];
//			}
			
			if($maxSize > $sizeUpload) { //control en la subida de archivos en funcion del servidor
				$uploadImg = FALSE;
				if ($_FILES['Image']['error'] == 0) {
					preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image"]["name"], $ext);
					$ext[0] = str_replace(".", "", $ext[0]);
					$file = checkingExtFile($ext[0]);
					//pre($file);die();
					if($file["upload"] == 1){
						if ($_FILES['Image']['type'] == "image/gif" || $_FILES['Image']['type'] == 'image/png' || $_FILES['Image']['type'] == 'image/jpeg' || $_FILES['Image']['type'] == 'image/pjpeg') {
							
							$extension = explode("/", $_FILES['Image']['type']);
							$imgExt = $extension[1];
						
							$Image = formatNameFile($_FILES['Image']['name']);
							
							$temp_url = "../../../temp/".$Image;
							$temp = "../../../temp/";
							
							move_uploaded_file($_FILES['Image']['tmp_name'],$temp_url);
							
							$q = "select WIDTH, HEIGHT from ".preBD."download_sections where ID = " . $Section;		
							$resultA = checkingQuery($connectBD, $q);
							$info = mysqli_fetch_object($resultA);
							
							$url = "../../../files/download/image/";
							customImage($temp, $url, $Image, $imgExt, $info->WIDTH, $info->HEIGHT);
							$uploadImg = TRUE;
						}else {
							$error = "Image";
							$msg .= "Imágen no válida.";
							$Image = "";
						}
					}else{
						$error = "Image Download";
						$msg .= $file["msg"];
					}	
				}else {
					$uploadImg = FALSE;
					$Image = "";
				}
			
			//Documento
/*				$Title_doc = addslashes(trim($_POST["Title_doc"]));
				if ($_FILES['Doc']['error'] == 0) {
					$extension = explode("/", $_FILES['Doc']['type']);
					$Ext = $extension[1];
					
					if ($_FILES["Doc"]["size"] < 1024) {
					   $Size = $_FILES["Doc"]["size"] . " bytes";
					} else {
						$size_kb = $_FILES["Doc"]["size"] / 1024;
						if (intval($size_kb) < 1024){
							$Size = intval($size_kb) . " Kb";
						} else {
							$size_mb = intval($size_kb) / 1024;
							$Size = intval($size_mb) . " Mb";
						}
					}
					$Doc = formatNameFile($_FILES['Doc']['name']);
					
					$url = "../../../files/download/doc/".$Doc;
					
					move_uploaded_file($_FILES['Doc']['tmp_name'],$url);
					
				}else{
					$error = "No ha seleccionado ningun documento.";
					$msg .= "Error al guardar el informe, no ha seleccionado ningún documento adjunto";
					if($uploadImg) {
						if($Image != "") {
							$urlImg = "../../../files/download/image/".$Image;
							deleteFile($urlImg);
						}
					}
				}
*/
			} else {//la suma de la imagen y el documento es mayor que el limite de subida del servidor
				$error = "Tamaño de subida excedido";
				$msg .= "Error al guardar el informe, los archivos seleccionados exceden el tamaño límite de subida al servidor";
			}
			
//Grupos de usuarios con acceso			
			$groups = array();
			$groups = $_POST["userWebGroup"];
			
			if ($error == NULL) {
				
				$q = "INSERT INTO ".preBD."downloads (`IDSECTION`, `STATUS`, `DATE_START`, `DATE_END`, `AUTHOR`, `TITLE`, `TEXT`, `IMAGE`) VALUES";
				$q .= " ('" .$Section . "', '" . $Status . "', '" . $Date_start->format('Y-m-d H:i:s'). "', '" . $Date_end->format('Y-m-d H:i:s') . "', '" . $Author . "', '" . $Title . "', '" . $Text . "', '" . $Image . "')";
				checkingQuery($connectBD, $q);
				
				$idNew = mysqli_insert_id($connectBD);
//Asociamos los grupos de usuarios a la descarga
				for($i=0;$i<count($groups);$i++) {
					$q = "insert INTO ".preBD."download_userweb (`IDGROUP`, `IDDOWNLOAD`) VALUES ('".$groups[$i]."','".$idNew."')";
					checkingQuery($connectBD, $q);
					
				}

				$msg = "Descarga <em>".$Title.".</em> creada correctamente.";
				
				disconnectdb($connectBD);
				$location = "Location: ../../index.php?mnu=".$mnu."&submnu=".$submnu."&com=download&tpl=edit&idnew=".$idNew."&msg=".utf8_decode($msg)."&error=0";
				header($location);
			}else {
				disconnectdb($connectBD);
				$location = "Location: ../../index.php?mnu=".$mnu."&submnu=".$submnu."&com=download&tpl=create&msg=".utf8_decode($msg)."&error=1";
				header($location);
			}
		}
	} else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}		
?>