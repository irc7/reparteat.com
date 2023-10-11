<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}

	$mnu = trim($_POST["mnu"]);
	
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	if ($_POST) {
		
		$edituser = trim($_POST["user"]);
		$Name = mysqli_real_escape_string($connectBD,trim($_POST["Name"]));
		$Text = mysqli_real_escape_string($connectBD,trim($_POST["Text"]));
		
		$q = "SELECT * FROM ".preBD."users WHERE Login='".$edituser."'";
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_object($result);
		
	//Tratamiento de imagen			
		if(isset($_POST["optImg"]) && intval($_POST["optImg"]) === 1) {
			if($row->Image != "") {
				$urlD = "../../images/user/". $row->Image;
				deleteFile($urlD);
			}
			$Image = "";
		} else if(isset($_POST["optImg"]) && intval($_POST["optImg"]) === 2) {
			if ($_FILES["Image"]["error"]== 0) {
				if($member->IMAGE != "") {
					$urlD = "../../images/user/". $row->Image;
					deleteFile($urlD);
				}
				preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image"]["name"], $ext);
				$file = checkingExtFile($ext[1]);
				if($file["upload"] == 1){
					$Image = formatNameFile($Name.$ext[0]);
					$temp_url = "../../../temp/";
					$temp_url_name = $temp_url .$Image;
					$url = "../../images/user/";
					move_uploaded_file($_FILES["Image"]["tmp_name"],$temp_url_name);
					customImage($temp_url, $url, $Image, $ext[1], 90, 110);	
				}else{
					$msg .= $file["msg"]."<br/>";
				}
			} else {
				$Image = $row->Image;
				$msg .= "No ha seleccionado ninguna imagen.<br/>";
			}
		} else {
			$Image = $row->Image;
		}
		
		
		$q = "UPDATE ".preBD."users SET 
				Name = '".$Name."', 
				Text = '".$Text."', 
				Image = '".$Image."' 
				WHERE ID = '".$row->ID."'";
		
		checkingQuery($connectBD, $q);
		
		$msg = "Usuario <em>".$Name."</em> modificado correctamente.";
		
	}else {
		$msg = "Ha ocurrido un error inesperado, si el problema persiste, contacte con el administrador";	
	}
	disconnectdb($connectBD);
	$location = "Location: ../../index.php?mnu=".$mnu."&com=user&tpl=option&msg=".utf8_decode($msg);
	header($location);
?>