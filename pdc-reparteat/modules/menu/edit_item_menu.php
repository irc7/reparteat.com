<?php
	session_start();
	if ($_SESSION["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	require_once ("../../includes/include.modules.php");
	date_default_timezone_set("Europe/Madrid");
	//pre($_POST);die();
	//pre($_FILES);die();
	
	$connectBD = connectdb();
	//pre($_POST);die();
	if (allowed("design")) {
		if ($_POST) {
			$error = NULL;
			$msg = "";
			$item = intval($_POST["id"]);
			$q = "select * from ".preBD."menu_item where ID = " . $item;
			$result = checkingQuery($connectBD, $q);
			$rowItem = mysqli_fetch_assoc($result);
			
			$change_item = 0;
			$change_title = 0;
			$change_parent = 0;
			$change_position = 0;
			$change_type = 0;
			$change_image = 0;
			$change_enlace_item = 0;
			
			$url = "../../../files/menus/image/";
			$old_image = $url .$rowItem["THUMBNAIL"];
			
			$Level = $rowItem["LEVEL"];
			
			$Menu = $_POST["Menu"];
			$delete_image_item_menu = $_POST["delete_image_item_menu"];
			
			$Title = trim($_POST["Title"]);	
			if ($Title != $rowItem["TITLE"]) {
				$change_item = 1;
				$change_title = 1;
			}
			
			if(isset($_POST["Parent"])){
				$Parent = $_POST["Parent"];
				if ($Parent != $rowItem["PARENT"]) {
					$change_item = 1;
					$change_parent = 1;
					
					$q = "select count(*) as total from ".preBD."menu_item where PARENT = " . $Parent;
					$result = checkingQuery($connectBD, $q);
					$row_total = mysqli_fetch_assoc($result);
					$Position = $row_total["total"] + 1;
					if($Position != $rowItem["POSITION"]) {
						$change_position = 1;
					}
					
					$q = "select LEVEL from ".preBD."menu_item where ID = " . $Parent;
					$result = checkingQuery($connectBD, $q);
					$row_p = mysqli_fetch_assoc($result);
					$Level = $row_p["LEVEL"] + 1;
					
				}
			} else {
				$Parent = 0;
			}
			
			$Type = $_POST["type_link"];
			if($Type != $rowItem["TYPE"]) {
				$change_item = 1;
				$change_type = 1;
			}
			
			/*comprobamos si se va a borrar la imagen*/
			if($delete_image_item_menu == "on"){
				$change_image = 1;
				
				if(file_exists($old_image)){
					deleteFile($old_image);
				}
				$Imagen_red_social = "";
				
				$change_enlace_item = 1;
				$enlace_item = 1;
				
			}else if ($_FILES["red_social"]["error"] == 0) {
				$Imagen_red_social = formatNameFile($_FILES["red_social"]['name']);
				if($Imagen_red_social != $rowItem["THUMBNAIL"]) {
					$change_item = 1;
					$change_image = 1;
				}
				
			}
			/*comprobamos que no cambie la forma de mostrar el enlace*/
			$enlace_item = $_POST["enlace_item"];
			if($enlace_item != $rowItem["DISPLAY"]) {
				$change_enlace_item = 1;
			}	
			
			switch($Type) {
				case 0:
					$Idview = NULL;
					$Target = "_self";
					$change_item = 1;
				break;
				case 1:
					$Idview = $_POST["Section"];
					$Target = "_self";
					$change_item = 1;
				break;
				case 2:
					$SectionArticle = $_POST["sectionArticle"];
					$aux = "Article" . $SectionArticle;
					$Idview = $_POST[$aux];
					$Target = "_self";
					$change_item = 1;
				break;
				case -1:
				$change_item = 1;
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
			
			if ($_FILES["red_social"]["error"] == 0) {
				preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["red_social"]["name"], $ext);
				$ext[0] = str_replace(".", "", $ext[0]);
				$file = checkingExtFile($ext[0]);
				//pre($file);die();
				if($file["upload"] == 1){
					if ($_FILES["red_social"]["type"] == "image/jpeg" || $_FILES["red_social"]["type"] == "image/pjpeg" || $_FILES["red_social"]["type"] == "image/gif" || $_FILES["red_social"]["type"] == "image/png") {
						$Image_red_social = formatNameFile($_FILES["red_social"]["name"]);					
						$temp_url_name = $url .$Image_red_social;
						
						if(file_exists($old_image)){
							deleteFile($old_image);
						}
						
						move_uploaded_file($_FILES["red_social"]["tmp_name"],$temp_url_name);
						
					} else {
						$msg .= "No es un archivo correcto. ";
						$error = "icono";
					}
				}else{
					$error = "red_social";
					$msg .= $file["msg"];
				}
			}		
			
			if($error == NULL) {
				if($change_item == 1){
					$q = "UPDATE ".preBD."menu_item SET"; 
						$q .= " LEVEL = '" . $Level;
					if($change_title == 1) {
						$q .= "', TITLE = '" . $Title;
					}
					if($change_parent == 1) {
						$q .= "', PARENT = '" . $Parent;
					}
					if($change_position == 1) {
						$q .= "', POSITION = '" . $Position;
					}
					if($change_type == 1) {
						$q .= "', TYPE = '" . $Type;
					}
					if($change_image == 1) {
						$q .= "', THUMBNAIL = '" . $Imagen_red_social;
					}	
					if($change_enlace_item == 1) {
						$q .= "', DISPLAY = '" . $enlace_item;
					}	
					$q .= "', IDVIEW = '" . $Idview;
					$q .= "', TARGET = '" . $Target;
					$q .= "' WHERE ID = " . $item;	
					
					checkingQuery($connectBD, $q);
					$msg .= "Elemento de menú modificado correctamente";
				}
			}
			disconnectdb($connectBD);
			$location = "Location: ../../index.php?mnu=design&com=menu&tpl=option&filtermenu=".$Menu."&item=".$item."&action=EditItem&msg=".$msg."#EditItem";
			header($location);
			
		}
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
	
?>