<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	date_default_timezone_set("Europe/Madrid");
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0]>=5){
		date_default_timezone_set("Europe/Paris");
	}	
	//pre($_POST);die();
	//pre($_FILES);
//	die();
	if (allowed("design")) {
		if ($_POST) {
			$error = NULL;
			$connectBD = connectdb();
			$msg = "";
			$Menu = $_POST["Menu"];		
			$enlace_item = $_POST["enlace_item"];
			$Title = addslashes(trim($_POST["Title"]));
			$Parent = $_POST["Parent"];
			
			if($Parent > 0){
				$q = "select LEVEL from ".preBD."menu_item where ID = " . $Parent;
				$result = checkingQuery($connectBD, $q);
				$row_p = mysqli_fetch_assoc($result);
				$Level = $row_p["LEVEL"] + 1;
			}else{
				$Level = 0;
			}
			
			$Type = $_POST["type_link"];
			
			$q = "select count(*) as total from ".preBD."menu_item where PARENT = " . $Parent ." and IDMENU = ".$Menu; 
			
			$result = checkingQuery($connectBD, $q);
			$row_total = mysqli_fetch_assoc($result);
			$Position = $row_total["total"] + 1;
			
			if(!isset($_POST["type_link"])) {
				$msg = "Debe seleccionar una opción para el elemento de menú.<br/>"; 		
			} else {
				switch($Type) {
					case 0:
						$Idview = NULL;
						$Target = "_self";
					break;
					case 1:
						$Idview = $_POST["Section"];
						$Target = "_self";
					break;
					case 2:
						$SectionArticle = $_POST["sectionArticle"];
						$aux = "Article" . $SectionArticle;
						$Idview = $_POST[$aux];
						$Target = "_self";
						 
					break;
					case 3:
						$Idview = $_POST["DescargaSection"];
						$Target = "_self";
					break;
					case 4:
						$SectionDescarga = $_POST["sectionDescarga"];
						$aux2 = "Descarga" . $SectionDescarga;
						$Idview = $_POST[$aux2];
						$Target = "_self";					 
					break;
					case 5:
						$Idview = $_POST["VideoSection"];
						$Target = "_self";
					break;
					case 6:
						$SectionVideo = $_POST["sectionVideo"];
						$aux3 = "Video" . $SectionVideo;
						$Idview = $_POST[$aux3];
						$Target = "_self";					 
					break;						
					case 7:
						$Idview = $_POST["GallerySection"];
						$Target = "_self";
					break;
					case 8:
						$SectionGallery = $_POST["sectionGallery"];
						$aux4 = "Gallery" . $SectionGallery;
						$Idview = $_POST[$aux4];
						$Target = "_self";					 
					break;						
					case -1:
						$Link = addslashes(trim($_POST["Link"]));
						if($Link == "" || $Link == NULL || $Link == "http://") {
							$error = "link";
							$msg .= "Debe escribir una url para el enlace.<br/>";
						} else {
							$Idview = $Link;	
						}
						$Target = $_POST["Target"];
					break;
				}
			}
			
			if ($_FILES["red_social"]["error"] == 0) {
				preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["red_social"]["name"], $ext);
				$ext[0] = str_replace(".", "", $ext[0]);
				$file = checkingExtFile($ext[0]);
				//pre($file);die();
				if($file["upload"] == 1){
					if ($_FILES["red_social"]["type"] == "image/jpeg" || $_FILES["red_social"]["type"] == "image/pjpeg" || $_FILES["red_social"]["type"] == "image/gif" || $_FILES["red_social"]["type"] == "image/png") {
						$Image_red_social = formatNameFile($_FILES["red_social"]["name"]);
						$url = "../../../files/menus/image/";
						$temp_url_name = $url .$Image_red_social;
						if(file_exists($temp_url_name)) {
							unlink($temp_url_name);	
						}
						//pre($temp_url_name);
						move_uploaded_file($_FILES["red_social"]["tmp_name"],$temp_url_name);
						//pre("pasa");
					} else {
						$msg .= "No es un archivo correcto. ";
						$error = "icono";
					}
				}else{
					$error = "Icono";
					$msg .= $file["msg"];
				}
			}
			
			if($error == NULL) {
				$q = "INSERT INTO ".preBD."menu_item (IDMENU, TITLE, LEVEL, PARENT, IDVIEW, TARGET, POSITION, TYPE, THUMBNAIL, DISPLAY) ";
				$q .= "VALUES ('".$Menu."', '".$Title."', '" . $Level. "', '" . $Parent. "', '".$Idview."', '".$Target."', '".$Position."', '".$Type."', '".$Image_red_social."', '".$enlace_item."')";
				
				checkingQuery($connectBD, $q);
				$msg .= "Elemento de menú creado correctamente.";
			} else {
				die($error);	
			}
			disconnectdb($connectBD);
			$location = "Location: ../../index.php?mnu=design&com=menu&tpl=option&filtermenu=".$Menu."&msg=".$msg;
			header($location);		
		}
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>