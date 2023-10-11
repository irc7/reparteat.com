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
	if(allowed($mnu)) {
	
		if ($_POST) {
			
			$error = NULL;
			$msg= "";
			$typeArticle = trim($_POST["type"]);
			if(isset($_GET["add_p"]) && $_GET["add_p"] == "ON"){
				$add_p = 1;	
			}else{
				$add_p = 0;
			}
			$Author = $_POST["Author"];
			
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
			$Section = trim($_POST["Section"]);
			
			$q = "select TYPE from ".preBD."articles_sections where ID = " . $Section;
			$r = checkingQuery($connectBD, $q);
			$typeSection = mysqli_fetch_object($r);
			
			if(trim($_POST["Firm"]) == "") {
				$Firm = "Redacción";
			}else{
				$Firm = mysqli_real_escape_string($connectBD,trim($_POST["Firm"]));
			}
			
			$Title = mysqli_real_escape_string($connectBD,trim($_POST["Title"]));
			$Title_seo = mysqli_real_escape_string($connectBD,trim($_POST["Title_seo"]));
			if($Title_seo == ""){
				$Title_seo = $Title;
			}
			$Subtitle = mysqli_real_escape_string($connectBD,trim($_POST["Subtitle"]));
			$Sumary = mysqli_real_escape_string($connectBD,trim($_POST["Sumary"]));
			$Intro = mysqli_real_escape_string($connectBD,trim($_POST["Intro"]));
			//PARAGRAPHS

			$Block1_title = mysqli_real_escape_string($connectBD,trim($_POST["Block1_title"]));
			$Block1_text = addslashes(trim($_POST["Block1_text"]));
			$Block1_align = $_POST["Block1_align"];
			$Block1_foot = mysqli_real_escape_string($connectBD,$_POST["Block1_foot"]);	
	
			if($_POST["o_p_1"] == 0) {
				if (isset($_POST["mnu_img1"])) {
					$opt_img = $_POST["mnu_img1"];
				}else {
					$opt_img = 2;	
				}
				
				$Block1_video = "";
				$Block1_type = "image";
				if($_POST["opt_link1"] == "on") {
					$Block1_target = $_POST["Target_link1"];
					$Block1_link = addslashes($_POST["Link_img1"]);
				} else {
					$Block1_target = NULL;
					$Block1_link = NULL;
				}
				switch($opt_img){
					case 0:
						$url = "../../../files/articles/image/";
						$url_thumb = "../../../files/articles/thumb/";
						$source_url = "../../images/ruler.gif";
						createruler($Section, $url, $url_thumb, $source_url);
						$Block1_image = "ruler.gif";
						$Block1_align = "left";
						$Thumbnail = "";
					break;
					case 1:
						if ($_FILES["Image1"]["error"]== 0) { 
							preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image1"]["name"], $ext);
							
							$file = checkingExtFile($ext[1]);
							//pre($file);die();
							if($file["upload"] == 1){
								if ($_FILES["Image1"]["type"] == "image/jpeg" || $_FILES["Image1"]["type"] == "image/pjpeg" || $_FILES["Image1"]["type"] == "image/gif" || $_FILES["Image1"]["type"] == "image/png") {
									
									$Block1_image = formatNameFile($_FILES["Image1"]["name"]);
									$temp_url = "../../../temp/";
									$temp_url_name = $temp_url .$Block1_image;
									$url = "../../../files/articles/image/";
									move_uploaded_file($_FILES["Image1"]["tmp_name"],$temp_url_name);
									//consulto tamaños
									
									$sizeImage = sizeImgForSection($Section, $Block1_align);
									//array con los tamaños en la seccion
									resizeImage($temp_url, $url, $Block1_image, $sizeImage["big"], $file["EXT"], 0);
									$url_newsletter = "../../../files/articles/newsletter/";
									customImageNewsletter($temp_url, $url_newsletter, $Block1_image, $file["EXT"], 360, 210);
									$url_thumb = "../../../files/articles/thumb/";
									customImage($temp_url, $url_thumb, $Block1_image, $file["EXT"], $sizeImage["w-thumb"], $sizeImage["h-thumb"]);
									$Thumbnail = $Block1_image;
								} else {
									$error = "Image1";
									$msg .= "El formato de la imagen debe de ser: *.jpg, *.png o *.gif).--";
								}
							}else{
								$error = "Image1";
								$msg .= $file["msg"];
							}
						}
					break;
					case 2:
						$Block1_image = "";
						$Block1_align = "right";
						$Thumbnail = "";
					break;
				} 	
			} elseif($_POST["o_p_1"] == 1) {
				$Block1_target = "_blank";
				$Block1_link = "";
				$opt_video = $_POST["mnu_video1"];
				switch($opt_video){
					case 0:
						$Block1_video = addslashes(trim($_POST["Youtube1"]));
						$Block1_type = "youtube";
						$Block1_image = $Block1_video;
						
						$Thumbnail = "v=".$Block1_image;						
					break;
					case 1:
						$Block1_type = "video";
						if ($_FILES["Video1"]["error"]== 0) {
							preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Video1"]["name"], $ext);
							$ext[0] = str_replace(".", "", $ext[0]);
							$file = checkingExtFile($ext[0]);
							//pre($file);die();
							if($file["upload"] == 1){
								if (($_FILES["Video1"]["type"] == "video/x-flv" || $_FILES["Video1"]["type"] == "video/mp4" || $_FILES["Video1"]["type"] == "video/3gpp" || $_FILES["Video1"]["type"] == "video/mpeg4" || $_FILES["Video1"]["type"] == "video/webm")) {
									$Block1_video = formatNameFile($_FILES["Video1"]["name"]);
									$url_f = "../../../files/articles/video/".$Block1_video;
									move_uploaded_file($_FILES["Video1"]["tmp_name"],$url_f);
									$Block1_type = "video";
								} else {
									$msg .= "Formato de video inválido.--";
								}
							}else{
								$error = "Video";
								$msg .= $file["msg"];
							}
						} else {
							$error = "Video";
							$msg .= "No ha seleccionado ningun video.--";
						}
						if ($_FILES["Video1_img"]["error"] == 0) {
							preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Video1_img"]["name"], $ext);
							
							$file = checkingExtFile($ext[1]);
							//pre($file);die();
							if($file["upload"] == 1){
								if ($_FILES["Video1_img"]["type"] == "image/jpeg" || $_FILES["Video1_img"]["type"] == "image/pjpeg" || $_FILES["Video1_img"]["type"] == "image/gif" || $_FILES["Video1_img"]["type"] == "image/png") {
									$ext = explode("/", $_FILES["Video1_img"]["type"]);
									$Block1_image = formatNameFile($_FILES["Video1_img"]["name"]);
									$temp_url = "../../../temp/";
									$temp_url_name = $temp_url .$Block1_image;
									$url = "../../../files/articles/image/";
									move_uploaded_file($_FILES["Video1_img"]["tmp_name"],$temp_url_name);
									
									$sizeImage = sizeImgForSection($Section, $Block1_align);
									resizeImage($temp_url, $url, $Block1_image, $sizeImage["big"], $file["EXT"], 0);
									$url_newsletter = "../../../files/articles/newsletter/";
									customImageNewsletter($temp_url, $url_newsletter, $Block1_image, $file["EXT"], 215, 120);
									$url_thumb = "../../../files/articles/thumb/";
									customImage($temp_url, $url_thumb, $Block1_image, $file["EXT"], $sizeImage["w-thumb"], $sizeImage["h-thumb"]);
									
									$Thumbnail = $Block1_image;
								} else {
									$error = "Video1_img";
									$msg .= "El formato de la imagen debe de ser: *.jpg, *.png o *.gif).--";
								}
							}else{
								$error = "Image video";
								$msg .= $file["msg"];
							}
						}
					break;
				}
			} elseif($_POST["o_p_1"] == 5) {
				$q = "select URL from ".preBD."images where STATUS = 1 and IDGALLERY = ".$_POST['Block1_album']." order by POSITION asc limit 0, 1";

				$resultThumb = checkingQuery($connectBD, $q);
				$total_img = mysqli_num_rows($resultThumb);
				
				
				if($total_img == 0) {
					$msg .= "No se ha podido generar miniatura, el albúm seleccionado no contiene imagenes.";
				}else {
					$img = mysqli_fetch_object($resultThumb);
					
					$Block1_type = "gallery";
					$Block1_image = $img->URL;

				
					$ext = explode(".", $img->URL);	
					$origen_img = "../../../files/gallery/image/".$img->URL;
					$destino_img = "../../../temp/".$img->URL;
					if(!file_exists($destino_img)) {
						$copy = copy($origen_img, $destino_img);
					}
				
					$temp_url = "../../../temp/";
					$url = "../../../files/articles/image/";
					
					$sizeImage = sizeImgForSection($Section, $Block1_align);
					resizeImage($temp_url, $url, $Block1_image, $sizeImage["big"], $ext[1], 0);
					if(!file_exists($destino_img)) {
						$copy = copy($origen_img, $destino_img);
					}
					$url_newsletter = "../../../files/articles/newsletter/";
					customImageNewsletter($temp_url, $url_newsletter, $Block1_image, $ext[1], 215, 120);
					$url_thumb = "../../../files/articles/thumb/";
					customImage($temp_url, $url_thumb, $Block1_image, $ext[1], $sizeImage["w-thumb"], $sizeImage["h-thumb"]);
					$Thumbnail = $img->URL;
				}
			}else{
				$msg .= "Error al tratar la imagen o video del parrafo";	
			}
						
			if($_FILES["Block1_file"]["error"] == 0 && isset($_FILES["Block1_file"])) {
				preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Block1_file"]["name"], $ext);
				$ext[0] = str_replace(".", "", $ext[0]);
				$file = checkingExtFile($ext[0]);
				//pre($file);die();
				if($file["upload"] == 1){
					$title_file = trim($_POST["Block1_title_file"]);
					if ($title_file != "") {
						$Url_file = formatNameFile($title_file);
						$extension = explode(".", $_FILES["Block1_file"]["name"]); 
						$num_extension = count($extension);
						$Url_file .= "." . $extension[$num_extension-1];
					} else{
						$Url_file = formatNameFile($_FILES["Block1_file"]["name"]);
					}
					if ($_FILES["Block1_file"]["size"] < 1024) {
					   $Size = $_FILES["Block1_file"]["size"] . "bytes";
					} else {
						$size_kb = $_FILES["Block1_file"]["size"] / 1024;
						if (intval($size_kb) < 1024){
							$Size = intval($size_kb) . "Kb";
						} else {
							$size_mb = intval($size_kb) / 1024;
							$Size = intval($size_mb) . "Mb";
						}
					}
					$url_f = "../../../files/articles/doc/".$Url_file;
					move_uploaded_file($_FILES["Block1_file"]["tmp_name"],$url_f);
					
					$insert_file = TRUE;
				}else{
					$error = "Download";
					$msg .= $file["msg"];
					$insert_file = FALSE;
				}
			} else {
				$insert_file = FALSE;
			}

			$Block1_album = $_POST["Block1_album"];

			if ($error == NULL) {
				
				$q = "INSERT INTO ".preBD."articles (IDSECTION, FIRM, TYPE, STATUS, DATE_START, DATE_END, AUTHOR, TITLE, TITLE_SEO, SUBTITLE, SUMARY, INTRO, THUMBNAIL) VALUES";
				$q .= " ('" .$Section . "', '" .$Firm . "', '".$typeSection->TYPE."', '" . $Status . "', '" . $Date_start->format('Y-m-d H:i:s'). "', '" . $Date_end->format('Y-m-d H:i:s') . "', '" . $Author . "', '" . $Title . "', '" . $Title_seo . "', '" . $Subtitle . "', '" . $Sumary . "', '" . $Intro . "', '" . $Thumbnail . "')";
				checkingQuery($connectBD, $q);
				
				$record_number = mysqli_insert_id($connectBD); 
				
				if(isset($_POST["UrlArt"]) && trim($_POST["UrlArt"]) != "") {
					$slug = formatNameUrl(trim($_POST["UrlArt"]));
				}else {
					$slug = formatNameUrl(trim($_POST["Title_seo"]));
				}
				$che = true;
				while($che) {
					$q = "select count(*) as t from ".preBD."url_web where SLUG = '".$slug."' and ID_VIEW != " . $record_number;
					$r = checkingQuery($connectBD, $q);
					$t = mysqli_fetch_object($r);
					if($t->t == 0){
						$che = false;
					}else {
						$slug = $slug."-r";
					}
				}
				
				$q = "INSERT INTO `".preBD."url_web` (`SLUG`, `VIEW`, `SEC_VIEW`, `ID_VIEW`, `TYPE`, `TITLE`) 
							VALUES ('".$slug."','article',0,'".$record_number."','article','".$Title_seo."')";
				checkingQuery($connectBD, $q);
				
				//GESTION DE RSS
				//if($Section == 2) {
				//	constructRSS();	
				//}
				//FIN
				
				//GESTION DE SITEMAP
				$q = "select TITLE from ".preBD."articles_sections where ID = '" . $Section . "'";
				
				$result = checkingQuery($connectBD, $q);
				$row = mysqli_fetch_assoc($result);
				$sitemap = formatNameUrl(stripslashes($row["TITLE"])) . ".xml";
				
				$msg_alt = construcSitemapArticles($Section, $sitemap);
				
				//FIN
				
				$q2 = "INSERT INTO ".preBD."paragraphs (`TITLE`, `TEXT`, `IMAGE`, `VIDEO`, `FOOT`, `ALIGN`, `IDARTICLE`, `POSITION`, `TYPE`, `LINK`, `TARGET`, `IDALBUM`) VALUES";
				$q2 .= "('" . $Block1_title . "','" . $Block1_text . "','" . $Block1_image . "','" . $Block1_video . "','" . $Block1_foot . "','" . $Block1_align . "','" . $record_number . "','1','".$Block1_type."','".$Block1_link."','".$Block1_target."','".$Block1_album."')";
				checkingQuery($connectBD, $q2);
				
				$paragraphs = mysqli_insert_id($connectBD);
				if($insert_file) {
					$q_file = "insert into ".preBD."paragraphs_file (IDPARAGRAPH, TITLE, URL, SIZE) VALUES";
					$q_file .= "('".$paragraphs."', '".$title_file."', '".$Url_file."', '".$Size."')";
					checkingQuery($connectBD, $q_file);
					
				}
				if ($add_p == 1){
					$q3 = "INSERT INTO ".preBD."paragraphs (`IDARTICLE`, `POSITION`, `TITLE`, `IMAGE`, `VIDEO`, `TEXT`, `FOOT`, `ALIGN`, `TYPE`, `LINK`, `TARGET`, `IDALBUM`) VALUES";
					$q3 .= " ('" . $record_number . "', '2', '', '', '', '', '', 'right', '', '', '_blank','0')";
					checkingQuery($connectBD, $q3);
					
				}
			//Noticias relacionadas
				if(isset($_POST["destino"]) && count($_POST["destino"]) > 0){
					$q = "INSERT INTO `".preBD."articles_related`(`ID1`, `ID2`) VALUES ";
					$i = 1;
					foreach($_POST["destino"] as $new) {
						$newA = explode("_", $new);
						$q .= "(".$record_number.",".$newA[1].")";
						if($i < count($_POST["destino"])){
							$q .= ", ";
						}
						$i++;
					}
					checkingQuery($connectBD, $q);
				}
				
				
				disconnectdb($connectBD);
				$msg .= "Artículo creado correctamente";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=articles&tpl=edit&record=".$record_number."&type=".$typeArticle."&preview=new&msg=".utf8_decode($msg);
				header($location);
			}
			else {
				$msg .= "Error al crear el artículo.";
				$location = "Location: ../../index.php?mnu=".$mnu."&com=articles&tpl=create&type=".$typeArticle."&msg=".utf8_decode($msg);
				header($location);
			}
		}
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	
?>