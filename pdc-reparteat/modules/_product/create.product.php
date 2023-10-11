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
		
		$Cat = $_POST["Cat"];
		
		$q = "select SIZEIMAGE, WIDTH, HEIGHT from ".preBD."products_cat where ID = " . $Cat;		
		$resultA = checkingQuery($connectBD, $q);
		$info = mysqli_fetch_object($resultA);					   
		
		$Status = $_POST["Status"];
		
		$Title = mysqli_real_escape_string($connectBD, trim($_POST["Title"]));
		$Sumary = mysqli_real_escape_string($connectBD, trim($_POST["Sumary"]));
		$Text = mysqli_real_escape_string($connectBD, trim($_POST["Text"]));
		
		
		
		$tot = count($_FILES["Image"]["name"]);
				
		/*Máxima subida simultánea*/
		if(return_bytes(ini_get('upload_max_filesize')) > return_bytes(ini_get('post_max_size'))){
			$max_simultaneous_rise = ini_get('post_max_size');
		}else{
			$max_simultaneous_rise = ini_get('upload_max_filesize');
		}				
						
		/*Tamaño total que ocupan las imágenes a subir*/				
		$size_total = 0;
		for ($k = 0; $k < $tot; $k++){
			$size_total = $size_total + $_FILES["Image"]["size"][$k];
		}
		
		$imgs = array();
		$j = 0;
		if($size_total < return_bytes($max_simultaneous_rise)){
			for ($i = 0; $i < $tot; $i++){
				$error = NULL;
				if(($_FILES["Image"]["error"][$i] == 0) && ($_FILES["Image"]["size"][$i] < return_bytes($max_simultaneous_rise))) {
					preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image"]["name"][$i], $ext);
					$file = checkingExtFile($ext[1]);
					
					if($file["upload"] == 1){									
						if (($_FILES["Image"]["type"][$i] == "image/gif" || $_FILES["Image"]["type"][$i] == "image/jpeg" || $_FILES["Image"]["type"][$i] == "image/pjpeg" || $_FILES["Image"]["type"][$i] == "image/png")) {
							$ext = explode("/", $_FILES["Image"]["type"][$i]);
							
							$image = formatNameFile($_FILES["Image"]["name"][$i]);
							$temp_url = "../../../temp/";
							$temp_url_name = $temp_url .$image;
							move_uploaded_file($_FILES["Image"]["tmp_name"][$i],$temp_url_name);													
							
							
							$url = "../../../files/product/image/";
							resizeImage($temp_url, $url, $image, $info->SIZEIMAGE, $ext[1], 0);
							
							$widthThumb = $info->WIDTH * ESCALE_THUMB;
							$heightThumb = $info->HEIGHT * ESCALE_THUMB;
							
							$url_thumb = "../../../files/product/thumb/";
							customImage($temp_url, $url_thumb, $image, $ext[1], $widthThumb, $heightThumb);
							
						}else {
							$msg .= "Imágen ".$_FILES["Image"]["name"][$i]." no válida.";
						}
					}else{
						$msg .= $file["msg"];
					}
				} else {
					$msg .= "Error al subir la imagen ".$_FILES["Image"]["name"][$i].". El archivo está corrupto el tamaño supera el permitido por el servidor.";
				}
			
				if ($error == NULL) {
					$imgs[]= $image;
				}else {
					echo $error;
				}
			}
		}else{
			$msg .= "Ha superado el máximo tamaño de subida simultánea que permite el servidor.";
		}
	
		if ($error == NULL) {
			/*actualizamos la posicion de los product existentes*/
			$q_s_update = "SELECT * FROM ".preBD."products WHERE IDCAT = ".$Cat;
			$result_s_update = checkingQuery($connectBD, $q_s_update);
			
			while ($row_update = mysqli_fetch_object($result_s_update)) {
				$q_up = "UPDATE ".preBD."products SET POSITION = '".($row_update->POSITION + 1)."' WHERE ID = ".$row_update->ID;
				checkingQuery($connectBD, $q_up);
			}
			$q = "INSERT INTO ".preBD."products 
				(IDCAT, STATUS, DATE_START, DATE_END, TITLE, TEXT, SUMARY, POSITION) 
				VALUES 
				('".$Cat."', '".$Status."', '" . $Date_start->format('Y-m-d H:i:s'). "', '" . $Date_end->format('Y-m-d H:i:s') . "', '".$Title."', '".$Text."', '".$Sumary."', '1')";
			
			checkingQuery($connectBD, $q);
			$idNew = mysqli_insert_id($connectBD); 
			
			$slug = formatNameUrl($Title);
			$che = true;
			while($che) {
				$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and ID_VIEW != " . $idNew . " and TYPE = 'product'";
				$r = checkingQuery($connectBD, $q);
				$t = mysqli_fetch_object($r);
				if($t->t == 0){
					$che = false;
				}else {
					$slug = $slug."-r";
				}
			}
			$q = "INSERT INTO `".preBD."url_web` (`SLUG`, `VIEW`, `SEC_VIEW`, `ID_VIEW`, `TYPE`, `TITLE`) 
						VALUES ('".$slug."','product',0,'".$idNew."','product','".$Title."')";
			checkingQuery($connectBD, $q);
			
	//insertar imagenes
			for($i=0;$i<count($imgs);$i++) {
				$q_up = "UPDATE ".preBD."products_images SET POSITION = POSITION + 1 WHERE IDPRODUCT = ".$idNew;
				checkingQuery($connectBD, $q_up);
				
				$q = "INSERT INTO ".preBD."products_images (IDPRODUCT, TITLE, URL, POSITION) VALUES";
				$q .= " ('" .$idNew . "', '" . $imgs[$i] . "', '" . $imgs[$i] . "', '1')";
				checkingQuery($connectBD, $q);
							
				$idImgNew = mysqli_insert_id($connectBD); 
			}			
			
			disconnectdb($connectBD); 
			
			$msg .= "Producto <em>".$Title."</em> creado correctamente";
			$location = "Location: ../../index.php?mnu=content&com=product&tpl=edit&record=".$idNew."&msg=".utf8_decode($msg);
			header($location);
			
		}
		else {
			$error;
		}
	}
?>
