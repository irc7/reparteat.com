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
//pre($_FILES);pre($_POST);die();
	if($_POST) {
	//Tamaño maximo de subida
		$maxSize = intval(ini_get('upload_max_filesize'));
		$maxSize = $maxSize * 1024 * 1024;
		
		$sizeUpload = 0;
		
		$mnu = trim($_POST["mnu"]);
		$submnu = abs(intval($_POST["submnu"]));
		if(!isset($_POST["mnu"]) || !allowed($_POST["mnu"])) { 	
			disconnectdb($connectBD);
			$msg = "No tiene permisos para realizar esta acción";
			$location = "Location: ../../index.php?msg=".utf8_decode($msg);
			header($location);
		} else {
		
			$id = intval($_POST["id"]);
			$q = "select * from ".preBD."downloads where ID = " . $id;
			$r = checkingQuery($connectBD, $q);
			$downBD = mysqli_fetch_object($r);
			
			
			$Section = intval($_POST["Section"]);
			$qS = "select * from ".preBD."download_sections where ID = " . $Section;
			$rS = checkingQuery($connectBD, $qS);
			$secBD = mysqli_fetch_object($rS);
			
			if(isset($_POST["Action"])) {
				$Action = trim($_POST["Action"]);
				switch($Action) {
					case "deleteImage":
						if($downBD->IMAGE != "") {
							$url = "../../../files/download/image/".$downBD->IMAGE;
							deleteFile($url);
							$q = "UPDATE ".preBD."downloads SET `IMAGE`= '' WHERE ID = " . $id;
							checkingQuery($connectBD, $q);
							$downBD->IMAGE = "";
							$msg .= "Imagen eliminada correctamente";							
						} else {
							$msg .= "No existe la imagen que desea borrar.";
						}
					break;
					case "deleteDoc":
						$idDoc = intval($_POST["idAction"]);
						
						if($idDoc > 0) {
							$q = "select TITLE as t, URL as url, POSITION as p from ".preBD."download_docs where ID = " . $idDoc;
							$r = checkingQuery($connectBD, $q);
							$d = mysqli_fetch_object($r);
							
							$qU = "update ".preBD."download_docs set POSITION = POSITION - 1 where POSITION > ". $d->p . " and IDDOWNLOAD = " . $id;
							checkingQuery($connectBD, $qU);
							
							$url = "../../../files/download/doc/".$d->url;
							deleteFile($url);
							
							$qD = "DELETE FROM `".preBD."download_docs` WHERE ID = ". $idDoc;
							checkingQuery($connectBD, $qD);
							
							$msg .= "Documento <em>".$d->t."</em> eliminado correctamente.";
						}
					break;
					case "upDoc":
						$idDoc = intval($_POST["idAction"]);
						if($idDoc > 0) {
							$q = "select TITLE as t, POSITION as p from ".preBD."download_docs where ID = " . $idDoc;
							$r = checkingQuery($connectBD, $q);
							$d = mysqli_fetch_object($r);
							
							$qU = "update ".preBD."download_docs set POSITION = POSITION + 1 where POSITION = ". ($d->p-1) . " and IDDOWNLOAD = " . $id;
							checkingQuery($connectBD, $qU);
							$qU = "update ".preBD."download_docs set POSITION = POSITION - 1 where ID = " . $idDoc;
							
							checkingQuery($connectBD, $qU);
							$msg .= "Posición del documento <em>".$d->t."</em> modificada correctamente.";
						}
					break;
					case "downDoc":
						$idDoc = intval($_POST["idAction"]);
						if($idDoc > 0) {
							$q = "select TITLE as t, POSITION as p from ".preBD."download_docs where ID = " . $idDoc;
							$r = checkingQuery($connectBD, $q);
							$d = mysqli_fetch_object($r);
							
							$qU = "update ".preBD."download_docs set POSITION = POSITION - 1 where POSITION = ". ($d->p+1) . " and IDDOWNLOAD = " . $id;
							checkingQuery($connectBD, $qU);
							$qU = "update ".preBD."download_docs set POSITION = POSITION + 1 where ID = " . $idDoc;
							checkingQuery($connectBD, $qU);
							$msg .= "Posición del documento <em>".$d->t."</em> modificada correctamente.";
						}
					break;
					case "changeDoc":
						$idDoc = intval($_POST["id_document"]);
						/*seleccionamos documento*/
						$q = "select * from ".preBD."download_docs where ID = " . $idDoc;
						$r = checkingQuery($connectBD, $q);
						$d = mysqli_fetch_object($r);
						if($idDoc > 0) {
							$aux = 'Documento_'.$idDoc;
							if($_FILES[$aux]["error"] == 0) {
								$sizeUpload2 = $sizeUpload + $_FILES[$aux]['size'];
								if($maxSize > $sizeUpload2) {
									/*subimos nuevo documento*/
									preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES[$aux]["name"], $ext);
									$ext[0] = str_replace(".", "", $ext[0]);
									$file = checkingExtFile($ext[0]);
									if($file["upload"] == 1){
										$UrlFile = formatNameFile($_FILES[$aux]['name']);
										$ext[0] = str_replace(".", "", $ext[0]);
										$temp_url = "../../../files/download/doc/".$UrlFile;
										move_uploaded_file($_FILES[$aux]['tmp_name'],$temp_url);
										$Size = calculateSizeDoc($_FILES[$aux]["size"]);
										
										/*borramos documento antiguo*/
										if($d->URL != "") {
											$url = "../../../files/download/doc/";
											$urlOld = $url . $d->URL;
											deleteFile($urlOld);
										}		

										/*actualizamos en la base de datos*/
										$qU = "update ".preBD."download_docs set URL = '".$UrlFile."', SIZE = '".$Size."', EXTENSION = '".$ext[0]."' where ID = " . $idDoc;
										//pre($qU);die();
										checkingQuery($connectBD, $qU);
										$msg .= "Documento modificado <em>".$d->TITLE."</em> modificada correctamente.";
									} else {
										$msg .= "El formato del documento no está permitido.<br/>";
									}							
								} else {//la suma de la imagen y el documento es mayor que el limite de subida del servidor
									$msg .= "Ha excedido el tamaño límite de subida al servidor.";
								}
								
							}else{
								$msg .= "No ha seleccionado ningún documento.<br/>";							
							}
						}
					break;
				}
			}
			
			
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
			$Title = addslashes(trim($_POST["Title"]));
			$Text = addslashes(trim($_POST["Text"]));
			
			if(isset($_POST["newImage"]) && trim($_POST["newImage"]) == "ok") {
				$newImage = true;
			}else{
				$newImage = false;
			}
			
			
			if($_FILES['Image']['error'] == 0 && $newImage){
				$sizeUpload = $sizeUpload + $_FILES['Image']['size'];
			}
			
			
			$insertNewDoc = 0;
			if($secBD->MULTIDOWNLOAD == 0) {
				if($_FILES['UrlSimple']["error"] == 0) {
					$sizeUpload = $sizeUpload + $_FILES['UrlSimple']['size'];
					$insertNewDoc = 1;
				}
			}else{
				for($i=0;$i<count($_FILES['UrlMultiple']["size"]);$i++) {
					if($_FILES['UrlMultiple']["error"][$i] == 0) {
						$sizeUpload = $sizeUpload + $_FILES['UrlMultiple']["size"][$i];
					}
					$insertNewDoc++;
				}
			}
			
			
			if($maxSize > $sizeUpload) { //control en la subida de archivos en funcion del servidor
				if ($_FILES['Image']['error'] == 0 && $newImage) {
					preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image"]["name"], $ext);
					$ext[0] = str_replace(".", "", $ext[0]);
					$file = checkingExtFile($ext[0]);
					//pre($file);die();
					$newImg = trim($_POST["newImage"]);
					if($file["upload"] == 1 && $newImg == "ok"){
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
							if($downBD->IMAGE != "") {
								$urlOld = $url . $downBD->IMAGE;
								deleteFile($urlOld);
							}
						}else {
							$msg .= "Imágen no válida.<br/>";
							$Image = $downBD->IMAGE;
						}
					}else{
						$error = "Image Section";
						$msg .= $file["msg"]."<br/>";
					}	
				}else {
					$Image = $downBD->IMAGE;
				}
			
			
				if($insertNewDoc > 0) {
					$Files = array();
					$j=0;
					if($secBD->MULTIDOWNLOAD == 0) {
						if(isset($_FILES["UrlSimple"]) && $_FILES["UrlSimple"]["error"] == 0) {
							preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["UrlSimple"]["name"], $ext);
							$ext[0] = str_replace(".", "", $ext[0]);
							$file = checkingExtFile($ext[0]);
							//pre($file);die();
							if($file["upload"] == 1){
								$UrlFile = formatNameFile($_FILES['UrlSimple']['name']);
								$temp_url = "../../../files/download/doc/".$UrlFile;
								move_uploaded_file($_FILES['UrlSimple']['tmp_name'],$temp_url);
								$Size = calculateSizeDoc($_FILES["UrlSimple"]["size"]);
							} else {
								$msg .= "El formato del documento no está permitido.<br/>";
							}
							$Files[$j]["title"] = $_FILES['UrlMultiple']['name']; 
							$Files[$j]["name"] = $UrlFile; 
							$Files[$j]["size"] = $Size; 
							$Files[$j]["ext"] = $ext[0]; 
						}else {
							$msg .= "No ha seleccionado ningún documento.<br/>";
						}
					}else{
						$totalDocs = count($_FILES["UrlMultiple"]["name"]);
						for($i=0;$i < $totalDocs;$i++) {
							if(isset($_FILES["UrlMultiple"]) && $_FILES["UrlMultiple"]["error"][$i] == 0) {
								preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["UrlMultiple"]["name"][$i], $ext);
								$ext[0] = str_replace(".", "", $ext[0]);
								$file = checkingExtFile($ext[0]);
								//pre($file);die();
								if($file["upload"] == 1){
									$UrlFile = formatNameFile($_FILES['UrlMultiple']['name'][$i]);
									$temp_url = "../../../files/download/doc/".$UrlFile;
									
									move_uploaded_file($_FILES['UrlMultiple']['tmp_name'][$i],$temp_url);
									$Size = calculateSizeDoc($_FILES["UrlMultiple"]["size"][$i]);
									$Files[$j]["title"] = $_FILES['UrlMultiple']['name'][$i]; 
									$Files[$j]["name"] = $UrlFile; 
									$Files[$j]["size"] = $Size; 
									$Files[$j]["ext"] = $ext[0]; 
									$j++;
								} else {
									$msg .= "El formato del documento <em>".$_FILES["UrlMultiple"]["name"][$i]."</em> no está permitido.<br/>";
								}
							}
						}
					}
				}	
			} else {//la suma de la imagen y el documento es mayor que el limite de subida del servidor
				$msg .= "Ha excedido el tamaño límite de subida al servidor.";
			}
			
//actualizamos todos los titulos de las descargas
			$q = "select ID from ".preBD."download_docs where IDDOWNLOAD = " . $id;
			$q .= " order by POSITION asc";
			$result=checkingQuery($connectBD, $q);
			while($doc = mysqli_fetch_object($result)) {
				$name = "Title_doc-".$doc->ID;
				if(isset($_POST[$name])) {	
					$title_doc = trim($_POST[$name]);
						
					$qU = "UPDATE ".preBD."download_docs SET `TITLE`='".$title_doc."' WHERE ID = " . $doc->ID;
					checkingQuery($connectBD, $qU);
				}
			}
			if ($error == NULL) {
				
				$q = "UPDATE ".preBD."downloads SET";
				$q.= " `IDSECTION` = '" . $Section;
				$q.= "', `STATUS` = '" . $Status;
				$q.= "', `DATE_START` = '" . $Date_start->format('Y-m-d H:i:s');
				$q.= "', `DATE_END` = '" . $Date_end->format('Y-m-d H:i:s');
				$q.= "', `AUTHOR` = '" . $Author;
				$q.= "', `TITLE` = '" . $Title;
				$q.= "', `TEXT` = '" . $Text;
				$q.= "', `IMAGE` = '" . $Image;
				$q.= "' where ID = " . $id;
				checkingQuery($connectBD, $q);			
				
				if(count($Files) > 0) {
					$qP = "update `".preBD."download_docs` set POSITION = POSITION + ".count($Files)." where IDDOWNLOAD = " . $id;
					checkingQuery($connectBD, $qP);
					for($i=0;$i<count($Files);$i++) {										
						$qD = "INSERT INTO `".preBD."download_docs`(`IDDOWNLOAD`, `TITLE`, `URL`, `SIZE`, `EXTENSION`, `POSITION`) VALUES";
						$qD .=" ('".$id."','".$Files[$i]["title"]."','".$Files[$i]["name"]."','".$Files[$i]["size"]."','".$Files[$i]["ext"]."',".($i + 1).")";
						checkingQuery($connectBD, $qD);
					}
				}
		
				$msg = "Descarga <em>".$Title.".</em> modificada correctamente.";
				disconnectdb($connectBD);
				$location = "Location: ../../index.php?mnu=".$mnu."&submnu=".$submnu."&com=download&tpl=edit&id=".$id."&msg=".utf8_decode($msg);				
				header($location);
			} else {
				disconnectdb($connectBD);
				$location = "Location: ../../index.php?mnu=".$mnu."&submnu=".$submnu."&com=download&tpl=create&id=".$id."&msg=".utf8_decode($msg);					
				header($location);
			}
		}
	}else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>