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
	//pre($_POST);	pre($_FILES);	die();
	if ($_POST) {
		$error = NULL;
		$id = intval($_POST["id"]);
		
		$q = "select * from ".preBD."products where ID = " . $id;	
		$r = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($r);		
		
		
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
			
		
		$Cat = intval($_POST["Cat"]);
		$cambio_cat = 0;
		if(($row->IDCAT) != $Cat){
			$cambio_cat = 1;
		}
		$Status = intval($_POST["Status"]);
		$Title = mysqli_real_escape_string($connectBD, trim($_POST["Title"]));
		$Sumary = mysqli_real_escape_string($connectBD, trim($_POST["Sumary"]));
		$Text = mysqli_real_escape_string($connectBD, trim($_POST["Text"]));
		
		//Tamaño maximo de subida
			$maxSize = intval(ini_get('upload_max_filesize'));
			$maxSize = $maxSize * 1024 * 1024;
			
			$sizeUpload = 0;
			
		$q = "select * from ".preBD."products_images where IDPRODUCT = " . $id . " order by POSITION asc";
		$r = checkingQuery($connectBD, $q);
		$dataNew = array();
		$i = 0;
		while($imgBD = mysqli_fetch_object($r)) {
			$inputUrl = "image-".$imgBD->ID;
			$inputTitle = "title-img-".$imgBD->ID;
			$inputPosition = "position-".$imgBD->ID;
			$inputDelete = "delete-img-".$imgBD->ID;
			
			if($_FILES[$inputUrl]['error'] == 0){
				$sizeUpload = $sizeUpload + $_FILES[$inputUrl]['size'];
				$dataNew[$i]["action"] = "new";
			}elseif(isset($_POST[$inputDelete]) && $_POST[$inputDelete] != NULL) {
				$dataNew[$i]["action"] = "delete";
			}else {
				$dataNew[$i]["action"] = "none";
			}
			
			for($j=0;$j<count($_FILES["Image"]["error"]);$j++) {
				$sizeUpload = $sizeUpload + $_FILES['Image']['size'][$j];
			}
			$dataNew[$i]["title"] = mysqli_real_escape_string($connectBD, trim($_POST[$inputTitle]));
			$dataNew[$i]["position"] = intval($_POST[$inputPosition]);
			$dataNew[$i]["imgBD"] = $imgBD->URL;
			$dataNew[$i]["id"] = $imgBD->ID;
			$dataNew[$i]["positionBD"] = $imgBD->POSITION;
			$i++;
		} //fin del while
		
		if($maxSize > $sizeUpload) { //control en la subida de archivos en funcion del servidor
		
			$q = "select SIZEIMAGE, WIDTH, HEIGHT from ".preBD."products_cat where ID = " . $Cat;	
			$resultA = checkingQuery($connectBD, $q);
			$info = mysqli_fetch_object($resultA);
			
			for($i=0;$i<count($dataNew);$i++) {
				
				switch($dataNew[$i]["action"]) {
					case "delete":
						if($dataNew[$i]["imgBD"] != "") {
							$urlD = "../../../files/product/image/". $dataNew[$i]["imgBD"];
							deleteFile($urlD);
						}
						if($dataNew[$i]["imgBD"] != "") {
							$urlThumb = "../../../files/product/thumb/".$dataNew[$i]["imgBD"];
							deleteFile($urlThumb);
						}
						
					//actualizo posiciones
						
						for($j=0;$j<count($dataNew);$j++) {
							if($dataNew[$j]["position"] > $dataNew[$i]["position"]) {
								$dataNew[$j]["position"] = $dataNew[$j]["position"] - 1;
							}
						}
						$q = "DELETE FROM `".preBD."products_images` WHERE ID = " . $dataNew[$i]["id"];
						$r = checkingQuery($connectBD, $q);
						
					break;
					case "new":
						//Tratamiento de imagen
							$inputUrl = "image-".$dataNew[$i]["id"];
								
							if($dataNew[$i]["imgBD"] != "") {
								$urlD = "../../../files/product/image/". $dataNew[$i]["imgBD"];
								deleteFile($urlD);
							}
							if($dataNew[$i]["imgBD"] != "") {
								$urlThumb = "../../../files/product/thumb/".$dataNew[$i]["imgBD"];
								deleteFile($urlThumb);
							}
							
							preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES[$inputUrl]["name"], $ext);
						
							$file = checkingExtFile($ext[1]);
							
							if($file["upload"] == 1){
								if($_FILES[$inputUrl]["type"] == "image/jpeg" || $_FILES[$inputUrl]["type"] == "image/jpg" || $_FILES[$inputUrl]["type"] == "image/png" || $_FILES[$inputUrl]["type"] == "image/gif" || $_FILES[$inputUrl]["type"] == "image/pjpeg") {
									
									$dataNew[$i]["img"] = formatNameFile($_FILES[$inputUrl]["name"]);
									
									$temp_url = "../../../temp/";
									$temp_url_name = $temp_url .$dataNew[$i]["img"];
									$url = "../../../files/product/image/";
									
									move_uploaded_file($_FILES[$inputUrl]["tmp_name"],$temp_url_name);
									resizeImage($temp_url, $url, $dataNew[$i]["img"], $info->SIZEIMAGE, $file["EXT"], 0);
									
									$url_thumb = "../../../files/product/thumb/";
									customImage($temp_url, $url_thumb, $dataNew[$i]["img"], $file["EXT"], $info->WIDTH, $info->HEIGHT);	
								}else{
									$msg .= "La imagen ".$dataNew[$i]["title"]." debe de ser *JPG, *PNG o *GIF.<br/>";
								}
							}else{
								$msg .= $file["msg"]."<br/>";
							}
						$q = "UPDATE `".preBD."products_images` SET 
								`TITLE`= '".$dataNew[$i]["title"]."',
								`URL`='".$dataNew[$i]["img"]."',
								`POSITION`='".$dataNew[$i]["position"]."' 
							WHERE ID = ". $dataNew[$i]["id"];
						checkingQuery($connectBD, $q);
					
					
					break;
					case "none";
						$dataNew[$i]["img"] = $dataNew[$i]["imgBD"];
						$q = "UPDATE `".preBD."products_images` SET 
								`TITLE`= '".$dataNew[$i]["title"]."',
								`URL`='".$dataNew[$i]["img"]."',
								`POSITION`='".$dataNew[$i]["position"]."' 
							WHERE ID = ". $dataNew[$i]["id"];
							checkingQuery($connectBD, $q);
				
					break;
				}//fin del switch
			}	//fin del for
			
			
			$tot = count($_FILES["Image"]["name"]);
			
			for ($i = 0; $i < $tot; $i++){
				$error = NULL;
				if($_FILES["Image"]["error"][$i] == 0) {
					preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image"]["name"][$i], $ext);
					$file = checkingExtFile($ext[1]);
					if($file["upload"] == 1){									
						if (($_FILES["Image"]["type"][$i] == "image/gif" || $_FILES["Image"]["type"][$i] == "image/jpeg" || $_FILES["Image"]["type"][$i] == "image/pjpeg" || $_FILES["Image"]["type"][$i] == "image/png")) {
							
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
					$q_up = "UPDATE ".preBD."products_images SET POSITION = POSITION + 1 WHERE IDPRODUCT = ".$id;
					checkingQuery($connectBD, $q_up);
					
					$q = "INSERT INTO ".preBD."products_images (IDPRODUCT, TITLE, URL, POSITION) VALUES";
					$q .= " ('" .$id . "', '" . $image . "', '" . $image . "', '1')";
					checkingQuery($connectBD, $q);
				}
			}
		
		}else {
			$msg .= "El tamaño de los archivos de las imagenes supera los límites establecidos en el servidor.";
		}
				
		if ($error == NULL) {
			if($cambio_cat == 1){
				/*actualizamos la posicion de los product existentes*/
				$q_up = "UPDATE ".preBD."products SET POSITION = POSITION + 1 WHERE IDCAT = ".$Cat;
				checkingQuery($connectBD, $q_up);
				
				$q_up = "UPDATE ".preBD."products SET POSITION = POSITION - 1 WHERE POSITION > ".$row->POSITION." and IDCAT = ".$row->IDCAT;
				checkingQuery($connectBD, $q_up);
				
			}
			
			$q = "UPDATE ".preBD."products SET STATUS='".$Status."', 
				DATE_START='".$Date_start->format('Y-m-d H:i:s')."', 
				DATE_END='".$Date_end->format('Y-m-d H:i:s')."', 
				TITLE='".$Title."', 
				IDCAT='".$Cat."', 
				SUMARY='".$Sumary."', 
				TEXT='".$Text."'";
				if($cambio_cat == 1){
					$q .=", POSITION = '1'";
				}
				$q .= "WHERE ID='".$id."'";
				checkingQuery($connectBD, $q);
			
			
			$slug = formatNameUrl($Title);
			$che = true;
			while($che) {
				$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and ID_VIEW != " . $id . " and TYPE = 'product'";
				$r = checkingQuery($connectBD, $q);
				$t = mysqli_fetch_object($r);
				if($t->t == 0){
					$che = false;
				}else {
					$slug = $slug."-r";
				}
			}
			
			$q = "UPDATE `".preBD."url_web` SET 
				`SLUG`='".$slug."',
				`TITLE`='".mysqli_real_escape_string($connectBD,$Title)."' 
				WHERE ID_VIEW = '" . $id . "' and TYPE = 'product'";
			checkingQuery($connectBD, $q);
			
			
			
			
			
			
			
			disconnectdb($connectBD);
			
			$msg .= "Producto <em>".$Title."</em> modificado correctamente.";
			
			$location = "Location: ../../index.php?mnu=content&com=product&tpl=edit&record=".$id."&msg=".utf8_decode($msg);
			header($location);
		}
		else {
			echo $error;
		}
	}
?>