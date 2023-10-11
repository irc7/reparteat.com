<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	if (phpversion() <> "4.3.9") date_default_timezone_set("Europe/Madrid");
	
	if (!allowed("configuration") && ($_SESSION[PDCLOG]['Login'] == "webmaster@ismaelrc.es") && ($_SESSION[PDCLOG]['Type'] == 4)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	//pre($_POST);pre($_FILES);die();
	if ($_POST) {
		$error = NULL;
		$id = $_POST["id"];
		$Title = $_POST["Title"];
		$Permission = $_POST["Permission"];

		$msg = "";
		$cambio_title = 0;
		$cambio_permission = 0;
		$change_image = 0;
		
		$q2 = "select * from ".preBD."configuration_modules where ID = " . $id;	
		
		$result2 = checkingQuery($connectBD, $q2);
		$aux = mysqli_fetch_object($result2);		
		
		$Image_name = $aux->IMAGE;
		
		if(($aux->MODULE) != $Title){
			$cambio_title = 1;
		}
		
		if(($aux->PERMISSION) != $Permission){
			$cambio_permission = 1;
		}		
		
		if ($_FILES["Image2"]["error"] == 0) {
			
			preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image2"]["name"], $ext);
			$ext[0] = str_replace(".", "", $ext[0]);
			$file = checkingExtFile($ext[0]);
			//pre($file);die();
			if($file["upload"] == 1){
				if ($_FILES["Image2"]["type"] == "image/gif" || $_FILES["Image2"]["type"] == "image/png" || $_FILES["Image2"]["type"] == "image/jpeg" || $_FILES["Image2"]["type"] == "image/pjpeg") {
					$Image_name2 = formatNameFile($_FILES["Image2"]["name"]);
					
					$url_delete = "../../images/modules/".$aux->IMAGE;
					if($aux->IMAGE != "") {
						deleteFile($url_delete);
					}
					
					$temp_url = "../../../temp/".$Image_name2;
					move_uploaded_file($_FILES["Image2"]["tmp_name"],$temp_url);
					
					$temp = "../../../temp/";
					$ext = explode("/", $_FILES['Image2']['type']);
					$url = "../../images/modules/";
					customImage($temp, $url, $Image_name2, $ext[1], 24, 24);
					$Image_name = $Image_name2;
					$change_image = 1;
				}else {
					$error = "Image";
					$msg .= "Imágen no válida.";
				}
			}else{
				$error = "Image";
				$msg .= $file["msg"];
			}
			
		}
		
		if ($error == NULL) {
			if(($cambio_title == 1) || ($cambio_permission == 1) || ($change_image == 1)){
				$q_up = "UPDATE ".preBD."configuration_modules SET MODULE = '".$Title. "', PERMISSION = " . $Permission . ", IMAGE = '" . $Image_name . "'";
				$q_up .= " WHERE ID = ".$id;
				checkingQuery($connectBD, $q_up);							
			}
			
			disconnectdb($connectBD);
			$msg .= "Módulo <em>".$Title."</em> modificado correctamente.";
			$location = "Location: ../../index.php?mnu=configuration&com=module&tpl=edit&module=".$id."&msg=".utf8_decode($msg);
			header($location);
		}
		else {
			echo $error;
		}
	}
?>