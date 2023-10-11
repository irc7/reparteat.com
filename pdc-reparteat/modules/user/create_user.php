<?php
	session_start();
	require_once ("../../includes/include.modules.php");
	if ($_SESSION[PDCLOG]["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	require_once "../../includes/classes/Password/class.Password.php";
	require_once "../../includes/classes/User/class.User.php";
	$mnu= $_POST["mnu"];
	
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
	
	if ($_POST) {
	
		$user = new User(); 
		
		$user->login = mysqli_real_escape_string($connectBD,trim($_POST["Login"]));
		$user->name = mysqli_real_escape_string($connectBD,trim($_POST["Name"]));
		$user->type = mysqli_real_escape_string($connectBD,trim($_POST["Type"]));
		$user->text = mysqli_real_escape_string($connectBD,trim($_POST["Text"]));
		$user->pwd = trim($_POST["Pwd"]);
		
		if ($_FILES["Image"]["error"] == 0) {
			preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image"]["name"], $ext);
			$file = checkingExtFile($ext[1]);
			if($file["upload"] == 1){
				$user->image = formatNameFile($name.$ext[0]);
				$temp_url = "../../../temp/";
				$temp_url_name = $temp_url .$user->image;
				move_uploaded_file($_FILES["Image"]["tmp_name"],$temp_url_name);
				$url = "../../images/user/";
				customImage($temp_url, $url, $user->image, $ext[1], 90, 110);
			}else{
				$user->image = "";
				$msg = $file["msg"];
			}
		} else {
			$user->image = "";
		}
	
		if($user->add()) {
			$msg .= "Usuario </em>".$user->login."</em> creado";			
		}else {
			$msg .= "Error al crear el usuario";			
		}
		
		
	}else {
		$msg = "Ha ocurrido un error inesperado, si el problema persiste, contacte con el administrador";	
	}
	
	$location = "Location: ../../index.php?mnu=".$mnu."&com=user&tpl=option&msg=".utf8_decode($msg);
	header($location);
?>