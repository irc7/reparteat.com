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
		
	if (isset($_GET['record'])) {
		$id = $_GET['record'];
	}
	
	
	if (allowed("content")) {
		//pre($_POST);die();
		if ($_POST) {
			$error = NULL;
			connectdb();
			$msg = "";
			$q_article = "SELECT * FROM ".preBD."articles_temp WHERE ID = " . $id;
			$result_article = checkingQuery($connectBD, $q_article);
			$row_article = mysqli_fetch_assoc($result_article);
			$change_article = 0;
			$change_status = 0;
			$change_section = 0;
			$change_author = 0;
			$change_date_start = 0;
			$change_date_end = 0;
			$change_title = 0;
			$change_title_seo = 0;
			$change_subtitle = 0;
			$change_sumary = 0;
			$change_intro = 0;
			$change_image = 0;
			
			$Author = trim($_POST["Author"]);
			if($Author != $row_article['AUTHOR']) {
				$change_author = 1;
				$change_article = 1;
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
			
			$Status = trim($_POST["Status"]);
			if($Status != $row_article['STATUS']) {
				$change_status = 1;
				$change_article = 1;
			}
			
			$Section = abs(intval($_POST["Section"]));
			if($Section != $row_article['IDSECTION']) {
				$change_section = 1;
				$change_article = 1;
			}
			
			if(trim($_POST["Firm"]) == "") {
				$Firm = "Redacción";
			}else{
				$Firm = mysqli_real_escape_string($connectBD,trim($_POST["Firm"]));
			}
			$Appointment = addslashes(trim($_POST["Appointment"]));
				
			$Title = addslashes(trim($_POST["Title"]));
			if($Title!= $row_article['TITLE']) {
				$change_title = 1;
				$change_article = 1;
			}
			
			$Title_seo = addslashes(trim($_POST["Title_seo"]));
			if($Title_seo == ""){
				$Title_seo = $Title; 
			}
			if($Title_seo!= $row_article['TITLE_SEO']) {
				$change_title_seo = 1;
				$change_article = 1;
			}
			
			$Subtitle = addslashes(trim($_POST["Subtitle"]));
			if($Subtitle != $row_article['SUBTITLE']) {
				$change_subtitle = 1;
				$change_article = 1;
			}
			
			$Sumary = addslashes(trim($_POST["Sumary"]));
			if($Sumary != $row_article['SUMARY']) {
				$change_sumary = 1;
				$change_article = 1;
			}
			
			$Intro = addslashes(trim($_POST["Intro"]));
			if($Intro != $row_article['INTRO']) {
				$change_intro = 1;
				$change_article = 1;
			}
			
	//PARRAFOS
			$q_paragraphs = "SELECT * FROM ".preBD."paragraphs_temp WHERE IDARTICLE = '" . $id . "' ORDER BY POSITION";
			
			$result_paragraphs = checkingQuery($connectBD, $q_paragraphs);
			$paragraphs = mysqli_num_rows($result_paragraphs);
			$new_paragraphs = FALSE;
			$option_act = trim($_GET["option"]);
			$test = 0;
			if($option_act == "add"){
				$paragraphs++;
				$new_paragraphs = TRUE;
				$q2 = "INSERT INTO ".preBD."paragraphs_temp (`IDARTICLE`, `POSITION`) VALUES ('" . $id . "', '" . $paragraphs . "')";
				checkingQuery($connectBD, $q2);
			}else if($option_act == "go_up"){
				/*subir posición adjunto*/
				$file = $_GET["file"];
				
				$q = "SELECT * FROM ".preBD."paragraphs_file_temp WHERE ID='".$file."'";
				
				$result = checkingQuery($connectBD, $q);
				$row = mysqli_fetch_array($result);
				
				$title = $row['TITLE'];
				$position = $row['POSITION'];
				$parent = $row['IDPARAGRAPH'];
				
				//pre($row);die();
				$q1 = "UPDATE ".preBD."paragraphs_file_temp SET POSITION = '".$position."' WHERE POSITION = '".($position - 1)."' and IDPARAGRAPH = " . $parent;
				checkingQuery($connectBD, $q1);
				
				$q2 = "UPDATE ".preBD."paragraphs_file_temp SET POSITION='".($position - 1)."' WHERE ID='".$file."' and IDPARAGRAPH = " . $parent;
				checkingQuery($connectBD, $q2);
				
				$msg = "Posición del adjunto de párrafo ".$title." modificado.<br/>";
			}else if($option_act == "lower"){
				/*bajar posición adjunto*/				
				$file = $_GET["file"];
				
				$q = "SELECT * FROM ".preBD."paragraphs_file_temp WHERE ID='".$file."'";
				
				$result = checkingQuery($connectBD, $q);
				$row = mysqli_fetch_array($result);
				
				$title = $row['TITLE'];
				$position = $row['POSITION'];
				$parent = $row['IDPARAGRAPH'];
				
				//pre($row);die();
				$q1 = "UPDATE ".preBD."paragraphs_file_temp SET POSITION = '".$position."' WHERE POSITION = '".($position + 1)."' and IDPARAGRAPH = " . $parent;
				checkingQuery($connectBD, $q1);
				
				$q2 = "UPDATE ".preBD."paragraphs_file_temp SET POSITION='".($position + 1)."' WHERE ID = '".$file."' and IDPARAGRAPH = " . $parent;
				checkingQuery($connectBD, $q2);
				
				$msg = "Posición del adjunto de párrafo ".$title." modificado.<br/>";
			} else {
				if($option_act != "save" && $option_act != "preview") {
					if(isset($_GET["file"])) {
						$delete_file = $_GET["file"];
						deleteParagraphFile($delete_file);
					} else {
						$test_paragraphs = $_GET["block"];
						$action = $option_act;
						$test = 1;
					}
				}
			}
			$q2_paragraphs = "SELECT * FROM ".preBD."paragraphs_temp WHERE IDARTICLE = '" . $id . "' ORDER BY POSITION";
			$result2_paragraphs = checkingQuery($connectBD, $q2_paragraphs);
			$i = 1;
			while ($row_paragraphs = mysqli_fetch_assoc($result_paragraphs)) {
				
				$p_title = "Block" . $i . "_title";
				$p_text = "Block" . $i . "_text";
				$p_align = "Block" . $i . "_align";
				$p_foot = "Block" . $i . "_foot";
				$p_file = "Block" . $i . "_file";
				$p_title_file = "Block" . $i . "_title_file";
				$p_album = "Block" . $i . "_album";
				
				$Blocks[$i]["change_paragraphs"] = 0;
				$Blocks[$i]["change_title"] = 0;
				$Blocks[$i]["change_text"] = 0;
				$Blocks[$i]["change_align"] = 0;
				$Blocks[$i]["change_foot"] = 0;
				$Blocks[$i]["change_album"] = 0;

				$Blocks[$i]["delete"] = FALSE;
			
				//TITULO
				if ($_POST[$p_title] != NULL){
					$Blocks[$i]["title"] = addslashes(trim($_POST[$p_title]));
					if ($Blocks[$i]["title"] != trim($row_paragraphs["TITLE"])) {
						$Blocks[$i]["change_title"] = 1;
						$Blocks[$i]["change_paragraphs"] = 1;
					}
				} else {
					$Blocks[$i]["title"] = "";
					$Blocks[$i]["change_title"] = 1;
					$Blocks[$i]["change_paragraphs"] = 1;
				}
				//TEXTO
				$Blocks[$i]["text"] = addslashes(trim($_POST[$p_text]));
				if ($Blocks[$i]["text"] != $row_paragraphs["TEXT"]) {
					$Blocks[$i]["change_text"] = 1;
					$Blocks[$i]["change_paragraphs"] = 1;
				} 
							
				//PIE DE FOTO
		
				$Blocks[$i]["foot"] = addslashes(trim($_POST[$p_foot]));
				if ($Blocks[$i]["foot"] != $row_paragraphs["FOOT"]) {
					$Blocks[$i]["change_foot"] = 1;
					$Blocks[$i]["change_paragraphs"] = 1;
				} 

				//ALIGN
				if ($_POST[$p_align] != NULL) {
					$Blocks[$i]["align"] = trim($_POST[$p_align]);
					if ($Blocks[$i]["align"] != $row_paragraphs["ALIGN"]) {
						if (($row_paragraphs["ALIGN"] == "center") && $row_paragraphs["TYPE"] != "youtube" && $row_paragraphs["TYPE"] != "gallery"){
							$image_url = "../../../files/articles_temp/image/".$row_paragraphs["IMAGE"];
							$source_url = "../../../temp/".$row_paragraphs["IMAGE"];
							copy($image_url, $source_url);						
							$ext = explode(".", $row_paragraphs["IMAGE"]);
							$temp_url = "../../../temp/";
							$url = "../../../files/articles_temp/image/";
							$sizeImage = sizeImgForSection($Section, $Block1_align);
							resizeImage($temp_url, $url, $row_paragraphs["IMAGE"], $sizeImage["big"], $ext[1], 1);
						}
						$Blocks[$i]["change_align"] = 1;
						$Blocks[$i]["change_paragraphs"] = 1;
					}
				} else {
					if($i != $paragraphs) {
						$Blocks[$i]["align"] = $row_paragraphs["ALIGN"];
					} else {
						$Blocks[$i]["align"] = "center";
						$Blocks[$i]["change_align"] = 1;
						$Blocks[$i]["change_paragraphs"] = 1;
					}
				}
				//POSITION
					
				$Blocks[$i]["position"] = $i;
							
		//IMAGE
		$p_o_p = "o_p_" . $i;
		$Opt_par = $_POST[$p_o_p];
		//pre($Opt_par);die();
		$p_image = "Image" . $i;
		$p_video = "Video" . $i;
		
		$Blocks[$i]["change_image"] = 0;
		$Blocks[$i]["change_video"] = 0;
		
		$p_opt_link = "opt_link".$i;
		$p_link_img = "Link_img".$i;
		$p_target = "Target_link".$i;
		if($_POST[$p_opt_link] == "on") {
			$Blocks[$i]["link_img"] = addslashes(trim($_POST[$p_link_img]));
			$Blocks[$i]["target_link"] = $_POST[$p_target];
			if($Blocks[$i]["link_img"] != $row_paragraphs["LINK"] || $Blocks[$i]["target_link"] != $row_paragraphs["LINK"]) {
				$Blocks[$i]["change_link_img"] = 1;
				$Blocks[$i]["change_target_link"] = 1;
				$Blocks[$i]["change_paragraphs"] = 1;
			}
		} else {
			$Blocks[$i]["link_img"] = "";
			$Blocks[$i]["change_link_img"] = 1;
			$Blocks[$i]["change_paragraphs"] = 1;
		}
	//TYPE	
		if($Opt_par == 0) {
			$Blocks[$i]["type"] = "image";
			if($Blocks[$i]["type"] != $row_paragraphs["TYPE"]) {
				if($row_paragraphs["TYPE"] == "video") {
					$Blocks[$i]["video"] = "";
					$Blocks[$i]["change_video"] = 1;
					$Blocks[$i]["change_paragraphs"] = 1;
					if($row_paragraphs["VIDEO"] != "") {
						$urlDelete = "../../../files/articles_temp/video/".$row_paragraphs["VIDEO"];
						deleteFile($urlDelete);
					}
				} elseif($row_paragraphs["TYPE"] == "youtube") {
					$Blocks[$i]["video"] = "";
					$Blocks[$i]["change_video"] = 1;
					$Blocks[$i]["change_paragraphs"] = 1;
				}
				if($row_article['THUMBNAIL'] != $row_paragraphs["IMAGE"] && $row_paragraphs["IMAGE"] != "") {
					$urlDelete = "../../../files/articles_temp/image/" . $row_paragraphs["IMAGE"];
					deleteFile($urlDelete);
					$urlDelete = "../../../files/articles_temp/thumb/" . $row_paragraphs["IMAGE"];
					deleteFile($urlDelete);
				}
			} 
			$p_mnu_img = "mnu_img".$i;
			$mnu_img = $_POST[$p_mnu_img];
			
			switch($mnu_img) {
				case 0:
					$Blocks[$i]["image"] = "";
					if($row_article['THUMBNAIL'] != $row_paragraphs["IMAGE"]) {
						if($row_paragraphs["IMAGE"] != "") {
							$urlDelete = "../../../files/articles_temp/image/" . $row_paragraphs["IMAGE"];
							deleteFile($urlDelete);
							$urlDelete = "../../../files/articles_temp/thumb/" . $row_paragraphs["IMAGE"];
							deleteFile($urlDelete);
						}
					}
					$Blocks[$i]["change_image"] = 1;
					$Blocks[$i]["change_paragraphs"] = 1;
					
					$Blocks[$i]["link_img"] = "";
					$Blocks[$i]["target_link"] = "_blank";
					$Blocks[$i]["change_link_img"] = 1;
					$Blocks[$i]["change_target_link"] = 1;
				break;
				case 1:
					if ($_FILES[$p_image]["error"] == 0) {
						preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES[$p_image]["name"], $ext);
						$ext[0] = str_replace(".", "", $ext[0]);
						$file = checkingExtFile($ext[0]);
						//pre($file);die();
						if($file["upload"] == 1){
							if (($_FILES[$p_image]["type"] == "image/gif" || $_FILES[$p_image]["type"] == "image/jpeg" || $_FILES[$p_image]["type"] == "image/pjpeg" || $_FILES[$p_image]["type"] == "image/png") && $_FILES[$p_image]["size"] < 10048000) {
								//pre("entra");
								$ext = explode("/", $_FILES[$p_image]["type"]);
								$Blocks[$i]["image"] = formatNameFile($_FILES[$p_image]["name"]);
								$temp_url = "../../../temp/";
								$temp_url_name = $temp_url .$Blocks[$i]["image"];
								$url = "../../../files/articles_temp/image/";
								move_uploaded_file($_FILES[$p_image]["tmp_name"],$temp_url_name);
								
								$sizeImage = sizeImgForSection($Section, $Blocks[$i]["align"]);
								resizeImage($temp_url, $url, $Blocks[$i]["image"], $sizeImage["big"], $ext[1], 0);
								$url_thumb = "../../../files/articles_temp/thumb/";
								customImage($temp_url, $url_thumb, $Blocks[$i]["image"], $ext[1], $sizeImage["w-thumb"], $sizeImage["h-thumb"]);
								
								$Blocks[$i]["change_image"] = 1;
								$Blocks[$i]["change_paragraphs"] = 1;
								
								if($row_paragraphs["IMAGE"] != "") {
									$urlDelete = "../../../files/articles_temp/image/" . $row_paragraphs["IMAGE"];
									deleteFile($urlDelete);
									$urlDelete = "../../../files/articles_temp/thumb/" . $row_paragraphs["IMAGE"];
									deleteFile($urlDelete);
								}
								
							} else {
								$error = "Imagen Bloque ".$i." options";
								$msg .= "Imágen del Bloque ".$i." no válida.<br/>";
							}
						}else{
							$error = "Download";
							$msg .= $file["msg"]. "<br/>";
						}
					} else {
							$msg .= "No ha seleccionado ninguna imagen para el Bloque ".$i.".<br/>";
					}
					if ($Blocks[$i]["link_img"] != $row_paragraphs["LINK"]) {
						$Blocks[$i]["change_link_img"] = 1;
						$Blocks[$i]["change_paragraphs"] = 1;
					} 
					if ($Blocks[$i]["target_link"] != $row_paragraphs["TARGET"]) {
						$Blocks[$i]["change_target_link"] = 1;
						$Blocks[$i]["change_paragraphs"] = 1;
					} 
				break;
				case 2:
					$url = "../../../files/articles_temp/image/";
					$url_thumb = "../../../files/articles_temp/thumb/";
					$source_url = "../../images/ruler.gif";
					createruler($Section, $url, $url_thumb, $source_url);
					$Blocks[$i]["image"] = "ruler.gif";
					$Blocks[$i]["align"] = "left";
					$Blocks[$i]["change_align"] = 1;
					$Blocks[$i]["change_image"] = 1;
					$Blocks[$i]["change_paragraphs"] = 1;
					if(($row_article['THUMBNAIL'] != $row_paragraphs["IMAGE"]) && $row_paragraphs["IMAGE"] != NULL && $row_paragraphs["IMAGE"] != "") {
							$urlDelete = "../../../files/articles_temp/image/" . $row_paragraphs["IMAGE"];
							deleteFile($urlDelete);
							$urlDelete = "../../../files/articles_temp/thumb/" . $row_paragraphs["IMAGE"];
							deleteFile($urlDelete);
					}
					$Blocks[$i]["link_img"] = "";
					$Blocks[$i]["target_link"] = "_blank";
					$Blocks[$i]["change_link_img"] = 1;
					$Blocks[$i]["change_target_link"] = 1;
				break;
				case -1:
					$Blocks[$i]["image"] = $row_paragraphs["IMAGE"];
				break;
			}
			

			
	// TYPE VIDEO // YOUTUBE
		} else if($Opt_par == 1 || $Opt_par == 2) {

			$Blocks[$i]["link_img"] = "";
			$Blocks[$i]["target_link"] = "_blank";
			$Blocks[$i]["change_link_img"] = 1;
			$Blocks[$i]["change_target_link"] = 1;

			if($Opt_par == 1){
				$Blocks[$i]["type"] = "video";	
			} elseif($Opt_par == 2) {
				$Blocks[$i]["type"] = "youtube";
			}
			
			$p_mnu_video = "mnu_video".$i;
			$mnu_video = $_POST[$p_mnu_video];
			
			$p_mnu_img = "mnu_img".$i;
			$mnu_img = $_POST[$p_mnu_img];
			
			$Blocks[$i]["change_paragraphs"] = 1;
			switch($mnu_video) {
				case 0:
					$Blocks[$i]["video"] = "";
					$Blocks[$i]["change_video"] = 1;
					if($row_paragraphs["TYPE"] == "youtube") {
						if(($row_paragraphs["IMAGE"] != $row_paragraphs["VIDEO"]) && $row_paragraphs["IMAGE"] != NULL && $row_paragraphs["IMAGE"] != "") {	
							if(($row_article['THUMBNAIL'] != $row_paragraphs["IMAGE"]) && $row_paragraphs["IMAGE"] != NULL && $row_paragraphs["IMAGE"] != "") {
									$urlDelete = "../../../files/articles_temp/image/" . $row_paragraphs["IMAGE"];
									deleteFile($urlDelete);
									$urlDelete = "../../../files/articles_temp/thumb/" . $row_paragraphs["IMAGE"];
									deleteFile($urlDelete);
							}
						}
						$Blocks[$i]["image"] = "";
						$Blocks[$i]["change_image"] = 1;
					} else {
						if(($row_article['THUMBNAIL'] != $row_paragraphs["IMAGE"]) && $row_paragraphs["IMAGE"] != NULL && $row_paragraphs["IMAGE"] != "") {
							$urlDelete = "../../../files/articles_temp/image/" . $row_paragraphs["IMAGE"];
							deleteFile($urlDelete);
							$urlDelete = "../../../files/articles_temp/thumb/" . $row_paragraphs["IMAGE"];
							deleteFile($urlDelete);
						}
						$Blocks[$i]["image"] = "";
						$Blocks[$i]["change_image"] = 1;
					}
				break;
				case 1:
					if ($_FILES[$p_video]["error"]== 0) { 
						preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES[$p_video]["name"], $ext);
						$ext[0] = str_replace(".", "", $ext[0]);
						$file = checkingExtFile($ext[0]);
						//pre($file);die();
						if($file["upload"] == 1){
							if (($_FILES[$p_video]["type"] == "video/x-flv" || $_FILES[$p_video]["type"] == "video/mp4" || $_FILES[$p_video]["type"] == "video/3gpp" || $_FILES[$p_video]["type"] == "video/mpeg4" || $_FILES[$p_video]["type"] == "video/webm")) {
								$Blocks[$i]["video"] = formatNameFile($_FILES[$p_video]["name"]);
								$url_f = "../../../files/articles_temp/video/".$Blocks[$i]["video"];
								move_uploaded_file($_FILES[$p_video]["tmp_name"],$url_f);
								$Blocks[$i]["change_video"] = 1;
								$Blocks[$i]["change_paragraphs"] = 1;
								if($row_paragraphs["TYPE"] == "video") {
									if($row_paragraphs["VIDEO"] != "") {
										$urlDelete = "../../../files/articles_temp/video/" . $row_paragraphs["VIDEO"];
										deleteFile($urlDelete);
									}
								}
							} else {
								$msg .= "Formato de video inválido.<br/>";
							}
						}else{
							$error = $p_video;
							$msg .= $file["msg"]. "<br/>";
						}	
					} else {
						$error = "Video";
						$msg .= "No ha seleccionado ningun video.<br/>";
					}
				break;
				case 2:
					$p_youtube = "Youtube". $i;
					if($row_paragraphs["TYPE"] == "video") {
						if($row_paragraphs["VIDEO"] != "") {
							$urlDelete = "../../../files/articles_temp/video/" . $row_paragraphs["VIDEO"];
							deleteFile($urlDelete);
						}
					}
					$Blocks[$i]["video"] = addslashes(trim($_POST[$p_youtube])); 
					$Blocks[$i]["image"] = $Blocks[$i]["video"];
					if ($Blocks[$i]["video"] != $row_paragraphs["VIDEO"]) {
						$Blocks[$i]["change_video"] = 1;
						$Blocks[$i]["change_image"] = 1;
						$Blocks[$i]["change_paragraphs"] = 1;
					} 
				break;
			}
			
			switch($mnu_img) {
				case 0:
					if(($row_article['THUMBNAIL'] != $row_paragraphs["IMAGE"]) && ($row_paragraphs["IMAGE"] != $row_paragraphs["VIDEO"])) {
						$urlDelete = "../../../files/articles_temp/image/" . $row_paragraphs["IMAGE"];
						deleteFile($urlDelete);
						$urlDelete = "../../../files/articles_temp/thumb/" . $row_paragraphs["IMAGE"];
						deleteFile($urlDelete);
					}
					$Blocks[$i]["image"] = "";
					$Blocks[$i]["change_image"] = 1;
					$Blocks[$i]["change_paragraphs"] = 1;
				break;
				case 1:
					if ($_FILES[$p_image]["error"] == 0) {
						preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES[$p_image]["name"], $ext);
						$ext[0] = str_replace(".", "", $ext[0]);
						$file = checkingExtFile($ext[0]);
						//pre($file);die();
						if($file["upload"] == 1) {
							if (($_FILES[$p_image]["type"] == "image/gif" || $_FILES[$p_image]["type"] == "image/jpeg" || $_FILES[$p_image]["type"] == "image/pjpeg" || $_FILES[$p_image]["type"] == "image/png") && $_FILES[$p_image]["size"] < 10048000) {
								$ext = explode("/", $_FILES[$p_image]["type"]);
								$Blocks[$i]["image"] = formatNameFile($_FILES[$p_image]["name"]);
								$temp_url = "../../../temp/";
								$temp_url_name = $temp_url .$Blocks[$i]["image"];
								$url = "../../../files/articles_temp/image/";
								move_uploaded_file($_FILES[$p_image]["tmp_name"],$temp_url_name);
								
								$sizeImage = sizeImgForSection($Section, $Blocks[$i]["align"]);
								resizeImage($temp_url, $url, $Blocks[$i]["image"], $sizeImage["big"], $ext[1], 0);
								$url_thumb = "../../../files/articles_temp/thumb/";
								customImage($temp_url, $url_thumb, $Blocks[$i]["image"], $ext[1], $sizeImage["w-thumb"], $sizeImage["h-thumb"]);
								
								$Blocks[$i]["change_image"] = 1;
								$Blocks[$i]["change_paragraphs"] = 1;
								if(($row_article['THUMBNAIL'] != $row_paragraphs["IMAGE"]) && ($row_paragraphs["IMAGE"] != $row_paragraphs["VIDEO"])) {
									$urlDelete = "../../../files/articles_temp/image/" . $row_paragraphs["IMAGE"];
									deleteFile($urlDelete);
									$urlDelete = "../../../files/articles_temp/thumb/" . $row_paragraphs["IMAGE"];
									deleteFile($urlDelete);
								}
							} else {
								$error = "Imagen Bloque ".$i." options";
								$msg .= "Imágen del Bloque ".$i." no válida.<br/>";
							}
						}else{
							$error = $p_image;
							$msg .= $file["msg"]. "<br/>";
						}	
					} else {
						$msg .= "No ha seleccionado ninguna imagen para el Bloque ".$i.".<br/>";
					}
				break;
				case 2:
					if ($Blocks[$i]["video"] != $row_paragraphs["IMAGE"]) {
						$Blocks[$i]["image"] = $Blocks[$i]["video"];
						$Blocks[$i]["change_image"] = 1;
						$Blocks[$i]["change_paragraphs"] = 1;
					} 
				break;
				case -1:
					$Blocks[$i]["image"] = $row_paragraphs["IMAGE"];
				break;
			}
			
		}elseif($Opt_par == 5) {
			//GALERIAS
			$Blocks[$i]["album"] = $_POST[$p_album];
			$Blocks[$i]["type"] = "gallery";

			
			if ($Blocks[$i]["album"] != $row_paragraphs["IDALBUM"]) {
				$Blocks[$i]["change_album"] = 1;
				$Blocks[$i]["change_paragraphs"] = 1;
			}
			//pre($row_paragraphs["IDALBUM"]." - ".$album);
		}
			
			
		//DOWNLOADS
		$insert_file = 0;
			if($i < 8) {
				if($_FILES[$p_file]["error"] == 0) {
					preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES[$p_file]["name"], $ext);
					$ext[0] = str_replace(".", "", $ext[0]);
					$file = checkingExtFile($ext[0]);
					//pre($file);die();
					if($file["upload"] == 1) {
						$title_file = trim($_POST[$p_title_file]);
						if ($title_file != "") {
							$Url_file = formatNameFile($title_file);
							$extension = explode(".", $_FILES[$p_file]["name"]); 
							$num_extension = count($extension);
							$Url_file .= "." . $extension[$num_extension-1];
						} else{
							$Url_file = formatNameFile($_FILES[$p_file]["name"]);
						}
						if ($_FILES[$p_file]["size"] < 1024) {
						   $Size = $_FILES[$p_file]["size"] . "bytes";
						} else {
							$size_kb = $_FILES[$p_file]["size"] / 1024;
							if (intval($size_kb) < 1024){
								$Size = intval($size_kb) . "Kb";
							} else {
								$size_mb = intval($size_kb) / 1024;
								$Size = intval($size_mb) . "Mb";
							}
						}
						$url_f = "../../../files/articles/doc/".$Url_file;
						move_uploaded_file($_FILES[$p_file]["tmp_name"],$url_f);
						
						$insert_file = 1;
					}else{
						$error = $p_file;
						$msg .= $file["msg"]. "<br/>";
					}
				} else {
					$insert_file = 0;
				}
			}	
				if($insert_file == 1) {
					$q_last = "select MAX(POSITION) as lastPosition from ".preBD."paragraphs_file_temp where IDPARAGRAPH = " . $row_paragraphs["ID"]; 
					$result_last = checkingQuery($connectBD, $q_last) ;
					$last = mysqli_fetch_assoc($result_last);
					$lastPosition = $last["lastPosition"];
					
					if(($lastPosition != "") && (isset($lastPosition))){
						$posicion = $lastPosition + 1;
					}else{
						$posicion = 1;
					}
					
					$q_file = "insert into ".preBD."paragraphs_file_temp (IDCOPY, IDPARAGRAPH, TITLE, URL, SIZE, POSITION) VALUES";
					$q_file .= "('0', '".$row_paragraphs["ID"]."', '".$title_file."', '".$Url_file."', '".$Size."', '".$posicion."')";
					checkingQuery($connectBD, $q_file);
					
				}
				$i++;
				
			
			$var = $_POST["total_adjuntos"];			
			if($var != ""){
				for($k=0; $k<=$var; $k++){		
					$aux = "title_adjunto" . $k;
					$aux2 = "num_adjunto" . $k;
					$nombre = $_POST[$aux];
					$identidad = $_POST[$aux2];
				
					$q = "select * from ".preBD."paragraphs_file_temp where ID = " . $identidad; 

					$result = checkingQuery($connectBD, $q);
					$row = mysqli_fetch_assoc($result);

					$q = "update ".preBD."paragraphs_file_temp set TITLE = '" . $nombre ."' WHERE ID = '" . $identidad . "'";

					checkingQuery($connectBD, $q);
					
					$q1 = "update ".preBD."paragraphs_file set TITLE = '" . $nombre ."' WHERE ID = '" . $row["IDCOPY"] . "'";
					checkingQuery($connectBD, $q1);
				}
			}
		}//END WHILE
	//	pre($_POST);
	//	pre($_FILES);
	//	pre($Blocks);
	//	die();
	//MINIATURA DEL ARTICULO
			$new_thumb = $_POST["thumbimage"];

			switch($new_thumb) {
				case 0:
					if (($_FILES["Thumb_image"]["error"] == 0) && ($_FILES["Thumb_image"]["name"] != $row_article['THUMBNAIL'])) {
						preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Thumb_image"]["name"], $ext);
						$ext[0] = str_replace(".", "", $ext[0]);
						$file = checkingExtFile($ext[0]);
						//pre($file);die();
						if($file["upload"] == 1) {
							if (($_FILES["Thumb_image"]["type"] == "image/gif" || $_FILES["Thumb_image"]["type"] == "image/jpeg" || $_FILES["Thumb_image"]["type"] == "image/pjpeg" || $_FILES[$p_image]["type"] == "image/png") && $_FILES["Thumb_image"]["size"] < 10048000) {
								$ext = explode("/", $_FILES["Thumb_image"]["type"]);
								$Thumb_image = formatNameFile($_FILES["Thumb_image"]["name"]);
								$temp_url = "../../../temp/";
								$temp_url_name = $temp_url .$Thumb_image;
								$url = "../../../files/articles_temp/image/";
								move_uploaded_file($_FILES["Thumb_image"]["tmp_name"],$temp_url_name);
								
								$sizeImage = sizeImgForSection($Section, "center");
								resizeImage($temp_url, $url, $Thumb_image, $sizeImage["big"], $ext[1], 0);
								$url_thumb = "../../../files/articles_temp/thumb/";
								customImage($temp_url, $url_thumb, $Thumb_image, $ext[1], $sizeImage["w-thumb"], $sizeImage["h-thumb"]);
								
								$Thumbnail_url = $Thumb_image;
								$change_image = 1;
								$change_article = 1;
								$search = false;
								for($j=1;$j<=count($Blocks);$j++) {
									if($Blocks[$j]["image"] == $row_article["THUMBNAIL"]) {
										$search = true;
										break;
									}
								}
								if(!$search) {
									if($row_article["THUMBNAIL"] != "") {
										$urlDelete = "../../../files/articles_temp/image/" . $row_article["THUMBNAIL"];
										deleteFile($urlDelete);
										$urlDelete = "../../../files/articles_temp/thumb/" . $row_article["THUMBNAIL"];
										deleteFile($urlDelete);
									}
								}
							}else {
								$error = "Article options";
								$msg .= "Imágen no válida.<br/>";
							}
						}else{
							$error = $p_file;
							$msg .= $file["msg"]. "<br/>";
						}	
					}
					else {
						if ($new_thumb == 0){
							$msg .= "Selecciona una miniatura para el post.<br/>";
						}
					}
				break;
				case 1000:
					$search = false;
					for($j=1;$j<=count($Blocks);$j++) {
						if($Blocks[$j]["image"] == $row_article["THUMBNAIL"]) {
							$search = true;
							break;
						}
					}
					if(!$search) {
						if($row_article["THUMBNAIL"] != "") {
							$urlDelete = "../../../files/articles_temp/image/" . $row_article["THUMBNAIL"];
							deleteFile($urlDelete);
							$urlDelete = "../../../files/articles_temp/thumb/" . $row_article["THUMBNAIL"];
							deleteFile($urlDelete);
						}
					}
					$Thumbnail_url = "";
					$change_image = 1;
					$change_article = 1;
				break;
				case -1:
					$Thumbnail_url = $row_article["THUMBNAIL"];
				break;
				default:
					if($Blocks[$new_thumb]["type"] == "youtube") {
						$search3 = false;				
						for($j=1;$j<=count($Blocks);$j++) {
							if($Blocks[$j]["image"] == $row_article["THUMBNAIL"]) {
								$search3 = true;
								break;
							}
						}

						if(!$search3) {
							if($row_article["THUMBNAIL"] != "") {
								$urlDelete = "../../../files/articles_temp/image/" . $row_article["THUMBNAIL"];
								deleteFile($urlDelete);
								$urlDelete = "../../../files/articles_temp/thumb/" . $row_article["THUMBNAIL"];
								deleteFile($urlDelete);
							}
						}
						
						$url_aux = "../../../files/articles_temp/image/". $Blocks[$new_thumb]["image"];
						if($Blocks[$new_thumb]["image"] != "" && file_exists($url_aux)) {
							$Thumbnail_url = $Blocks[$new_thumb]["image"];
						}else{
							$Thumbnail_url = "v=".$Blocks[$new_thumb]["image"];
						}
						$change_image = 1;
						
						
					}else if($Blocks[$new_thumb]["type"] == "gallery"){
						/*miramos si la antigua miniatura existe en algun parrafo para borrarlo*/
						$search2 = false;			
						for($j=1;$j<=count($Blocks);$j++) {
							if($Blocks[$j]["image"] == $row_article["THUMBNAIL"]) {
								$search2 = true;
								break;
							}
						}

						if(!$search2) {
							if($row_article["THUMBNAIL"] != "") {
								$urlDelete = "../../../files/articles_temp/image/" . $row_article["THUMBNAIL"];
								deleteFile($urlDelete);
								$urlDelete = "../../../files/articles_temp/thumb/" . $row_article["THUMBNAIL"];
								deleteFile($urlDelete);
							}
						}
						
						/*seleccionamos la primera imagen de la galería*/
						$q = "select URL from ".preBD."images where STATUS = 1 and IDGALLERY = ".$Blocks[$new_thumb]["album"]." order by POSITION asc limit 0, 1";

						$resultThumb = checkingQuery($connectBD, $q);
						$total_img = mysqli_num_rows($resultThumb);
						if($total_img == 0) {
							$msg .= "No se ha podido generar miniatura, el albúm seleccionado no contiene imagenes.";
						}else {
							$img = mysqli_fetch_object($resultThumb);
							
							/*si es la misma imagen, no hacemos nada, en caso contrario creamos la miniatura*/
							if($row_article["THUMBNAIL"] == $img->URL){
								$Thumbnail_url = $Blocks[$new_thumb]["image"];
							}else{						
								$ext = explode(".", $img->URL);	
								$origen_img = "../../../files/gallery/image/".$img->URL;
								$destino_img = "../../../temp/".$img->URL;
								if(!file_exists($destino_img)) {
									$copy = copy($origen_img, $destino_img);
								}
							
								$temp_url = "../../../temp/";
								$url = "../../../files/articles_temp/image/";
								
								$sizeImage = sizeImgForSection($Section, "center");
								resizeImage($temp_url, $url, $img->URL, $sizeImage["big"], $ext[1], 0);
								if(!file_exists($destino_img)) {
									$copy = copy($origen_img, $destino_img);
								}
								
								$url_thumb = "../../../files/articles_temp/thumb/";
								customImage($temp_url, $url_thumb, $img->URL, $ext[1], $sizeImage["w-thumb"], $sizeImage["h-thumb"]);
								
								$Thumbnail_url = $img->URL;
								$Blocks[$new_thumb]["image"] = $img->URL;
								$Blocks[$new_thumb]["change_image"] = 1;
								$Blocks[$new_thumb]["change_paragraphs"] = 1;
								$change_image = 1;
							}
						}					
					}else{
						$Thumbnail_url = $Blocks[$new_thumb]["image"];
						$change_image = 1;
					}
					$change_article = 1;
				break;
			}
			//CONSULTAS
			if ($error == NULL) {
				//if($change_article == 1){
					$q = "UPDATE ".preBD."articles_temp SET"; 
						$q .= " STATUS = '" . $Status;
						$q .= "', FIRM = '" . $Firm;
						$q .= "', APPOINTMENT = '" . $Appointment;
						$q .= "', DATE_START = '" . $Date_start->format('Y-m-d H:i:s');
						$q .= "', DATE_END = '". $Date_end->format('Y-m-d H:i:s');
						
						if($change_section == 1) {
							$q .= "', IDSECTION = '" . $Section;
						}
						if($change_author == 1) {
							$q .= "', AUTHOR = '" . $Author;
						}
						if($change_title == 1) {
							$q .= "', TITLE = '" . mysqli_real_escape_string($connectBD,$Title);
						}
						if($change_title_seo == 1) {
							$q .= "', TITLE_SEO = '" . mysqli_real_escape_string($connectBD,$Title_seo);
						}
						if($change_subtitle == 1) {
							$q .= "', SUBTITLE = '" . mysqli_real_escape_string($connectBD,$Subtitle);
						}
						if($change_sumary == 1) {
							$q .= "', SUMARY = '" . mysqli_real_escape_string($connectBD,$Sumary);
						}
						if($change_intro == 1) {
							$q .= "', INTRO = '" . mysqli_real_escape_string($connectBD,$Intro);
						}
						if($change_image == 1) {
							$q .= "', THUMBNAIL = '" . mysqli_real_escape_string($connectBD,$Thumbnail_url);
						}
					$q .= "' WHERE ID = " . $id;

					checkingQuery($connectBD, $q);
				//}
				for ($i=1;$i<=$paragraphs;$i++) {
					if ($Blocks[$i]["change_paragraphs"] == 1){
						$q_up = "UPDATE ".preBD."paragraphs_temp SET"; 
						$q_up .= " TYPE = '" . $Blocks[$i]["type"];
						if ($Blocks[$i]["change_title"] == 1){
							$q_up .= "', TITLE = '" . mysqli_real_escape_string($connectBD,$Blocks[$i]["title"]);
						}
						if ($Blocks[$i]["change_align"] == 1){
							$q_up .= "', ALIGN = '". $Blocks[$i]["align"];
						}
						if ($Blocks[$i]["change_foot"] == 1){
							$q_up .= "', FOOT = '" . mysqli_real_escape_string($connectBD,$Blocks[$i]["foot"]);
						}
						if ($Blocks[$i]["change_text"] == 1){
							$q_up .= "', TEXT = '" . mysqli_real_escape_string($connectBD,$Blocks[$i]["text"]);
						}
						if ($Blocks[$i]["change_image"] == 1){
							$q_up .= "', IMAGE = '" . mysqli_real_escape_string($connectBD,$Blocks[$i]["image"]);
						}
						if ($Blocks[$i]["change_link_img"] == 1){
							$q_up .= "', LINK = '" . mysqli_real_escape_string($connectBD,$Blocks[$i]["link_img"]);
						}
						if ($Blocks[$i]["change_target_link"] == 1){
							$q_up .= "', TARGET = '" . $Blocks[$i]["target_link"];
						}
						if ($Blocks[$i]["change_video"] == 1){
							$q_up .= "', VIDEO = '" . $Blocks[$i]["video"];
						}
						if ($Blocks[$i]["change_album"] == 1){
							$q_up .= "', IDALBUM = '" . $Blocks[$i]["album"];
						}
						$q_up .= "' WHERE IDARTICLE = " . $id . " AND POSITION = " . $i;
						
						checkingQuery($connectBD, $q_up);
					}
				}
				//GESTION DE RSS
					$qI = "select ID from ".preBD."blog where IDSECTION = '" . $Section . "'";
					$r = checkingQuery($connectBD, $qI);
					$blog = mysqli_fetch_object($r);
					constructBlogRSS($blog->ID);
					if($Section != $row_article['IDSECTION']) {
						$qI2 = "select ID from ".preBD."blog where IDSECTION = '" . $row_article['IDSECTION'] . "'";
						$r2 = checkingQuery($connectBD, $qI2);
						$blogOld = mysqli_fetch_object($r2);
						constructBlogRSS($blogOld->ID);
					}
				//FIN
				
//Gestion de las etiquetas (no se relaciona con el articulo temporal se guarda y se asocia con el original)
				
				$q = "DELETE FROM `".preBD."blog_post_tags` WHERE ID_POST = " . $row_article["IDARTICLE"];
				checkingQuery($connectBD, $q);
				
				$qE = "select * from ".preBD."blog_tags order by TITLE asc";
				$rE = checkingQuery($connectBD, $qE);
				while($tag = mysqli_fetch_object($rE)) {
					$label = "Tags_".$tag->ID;
					if(isset($_POST[$label])) {
						$q = "insert into ".preBD."blog_post_tags (ID_POST, ID_TAG)
								VALUES
								('".$row_article["IDARTICLE"]."', '".$tag->ID."')";
						checkingQuery($connectBD, $q);
					}
				}
				$tnews = array();
				if(isset($_POST["NewTags"]) && trim($_POST["NewTags"]) != "") {
					$tnews = explode(",", $_POST["NewTags"]);
				
					$errorTag = false;
					for($t=0;$t<count($tnews);$t++) {
						if(trim($tnews[$t]) != "") {
							$titleTag = strtoupper(trim($tnews[$t]));
							$q = "select count(*) as tags from ".preBD."blog_tags where TITLE = '".$titleTag."'";
							$rS = checkingQuery($connectBD, $q);
							$total = mysqli_fetch_object($rS);
							if($total->tags == 0) {
								$q = "insert into ".preBD."blog_tags (TITLE, SLUG)
										VALUES
										('".mysqli_real_escape_string($connectBD,trim($tnews[$t]))."', '".formatNameUrl(trim($tnews[$t]))."')";
								checkingQuery($connectBD, $q);
								$tn = mysqli_insert_id($connectBD);
								$q = "insert into ".preBD."blog_post_tags (ID_POST, ID_TAG)
										VALUES
										('".$row_article["IDARTICLE"]."', '".$tn."')";
								checkingQuery($connectBD, $q);
							}else {
								$msg .= "La etiqueta <em>".trim($tnews[$t])."</em> ya existe.<br/>";
							}
						}
					}
				}
				
				if ($test == 1) {
					$location = "Location: change_position_blocks.php?&recordTemp=".$id."&msg=".utf8_decode($msg)."&block=".$test_paragraphs."&action=".$action;
					header($location);
				} else if($option_act == "preview"){
					$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=edit&recordTemp=".$id."&msg=".utf8_decode($msg)."&preview=on";
					header($location);
				} else if($option_act == "save"){
					
					
					
					if(trim($Title) != "") {
						
						if(isset($_POST["destino"]) && count($_POST["destino"]) > 0){
							foreach($_POST["destino"] as $new) {
								$newA = explode("_", $new);
								$_SESSION["relatedNews"][] = $newA[1];
							}
						}
						if(isset($_POST["UrlArt"]) && trim($_POST["UrlArt"]) != "") {
							$slug = formatNameUrl(trim($_POST["UrlArt"]));
						}else {
							$slug = formatNameUrl($Title_seo);
						}
						$location = "Location: upload_article.php?mnu=blog&com=blog&record=".$row_article["IDARTICLE"]."&recordTemp=".$id."&urlart=".$slug."&msg=".utf8_decode($msg);
						header($location);
					}else{
						$msg = "Título es obligatorio.";
						$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=edit&recordTemp=".$id."&msg=".utf8_decode($msg);
						header($location);
					}
				} else {
					$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=edit&recordTemp=".$id."&msg=".utf8_decode($msg);
					header($location);
				}
			}
			else {
				$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=edit&recordTemp=".$id."&msg=".utf8_decode($msg);
				header($location);
			}
		
		}
	} else {
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
	}
	disconnectdb($connectBD);
	header($location);
?>