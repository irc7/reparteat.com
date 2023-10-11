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
	if (!allowed($mnu)) {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
//	pre($_POST);
//	pre($_FILES);
//	die();
	if($_POST) {
		
		$com = trim($_POST["com"]);
		
		$Status = intval($_POST["Status"]);
		
		$Name = mysqli_real_escape_string($connectBD, trim($_POST["Name"]));
		$Surname = mysqli_real_escape_string($connectBD, trim($_POST["Surname"]));
		
		$Email = trim($_POST["Email"]);
		
		$Sp = array();
		$Sp = $_POST["Specialities"];
		
		$Center = array();
		$Center = $_POST["Center"];
		
		$Email = mysqli_real_escape_string($connectBD, trim($_POST["Email"]));
		$Text = mysqli_real_escape_string($connectBD, trim($_POST["Text"]));
		$Sex = trim($_POST["Sex"]);
		
		$Lat = floatval($_POST["Lat"]);
		$Lng = floatval($_POST["Lng"]);
		
		if ($_FILES["Image"]["error"] == 0) {
			preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image"]["name"], $ext);
			
			$file = checkingExtFile($ext[1]);
			//pre($file);die();
			if($file["upload"] == 1){
				if (($_FILES["Image"]["type"] == "image/gif" || $_FILES["Image"]["type"] == "image/jpeg" || $_FILES["Image"]["type"] == "image/pjpeg" || $_FILES["Image"]["type"] == "image/png")) {
					$Image = formatNameFile($_FILES["Image"]["name"]);
					$temp_url = "../../../temp/";
					$temp_url_name = $temp_url .$Image;
					move_uploaded_file($_FILES["Image"]["tmp_name"],$temp_url_name);
					$url = "../../../files/cm/doctor/image/";
					
					$width = 300;
					$height = 300;
					
					customImage($temp_url, $url, $Image, $ext[1], $width, $height);
				}else {
					$Image = "";
					$msg .= "Imágen no válida.";
				}
			}else{
				$Image = "";
				$msg .= $file["msg"];
			}
		} else {
			$Image = "";
			$msg .= "No ha seleccionado ningúna imagen.";
		}
		
		$q = "INSERT INTO `".preBD."cm_doctors`(`NAME`, `SURNAME`, `EMAIL`, `SEX`, `IMAGE`, `TEXT`, `STATUS`) 
				VALUES 
				('".$Name."','".$Surname."','".$Email."','".$Sex."','".$Image."','".$Text."','".$Status."')";
		
		checkingQuery($connectBD, $q);
		$idNew = mysqli_insert_id($connectBD); 
		
		$q = "INSERT INTO `".preBD."cm_ds`(`IDDOCTOR`, `IDSPECIALITY`) VALUES ";
		for($i=0;$i<count($Sp);$i++){
			if($i == (count($Sp)-1)) { 
				$q .= "('".$idNew."','".$Sp[$i]."')";
			}else {
				$q .= "('".$idNew."','".$Sp[$i]."'),";
			}
		}
		checkingQuery($connectBD, $q);
		
		$q = "INSERT INTO `".preBD."cm_dc`(`IDDOCTOR`, `IDCENTER`) VALUES ";
		for($i=0;$i<count($Center);$i++){
			if($i == (count($Center)-1)) { 
				$q .= "('".$idNew."','".$Center[$i]."')";
			}else {
				$q .= "('".$idNew."','".$Center[$i]."'),";
			}
		}
		checkingQuery($connectBD, $q);
		$msg = "Doctor/a <em>".trim($_POST["Name"])." ".trim($_POST["Surname"])."</em> creado correctamente.";
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&id=".$idNew."&msg=".utf8_decode($msg);
	header($location);
?>