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
	
	if ($_POST) {
		
		$com = trim($_POST["com"]);
		$tpl = trim($_POST["tpl"]);
		$opt = trim($_POST["opt"]);
		
		$Status = intval($_POST["Status"]);
		$Name = mysqli_real_escape_string($connectBD, trim($_POST["Name"]));
		$Type = trim($_POST["Type"]);
		
		$Sp = array();
		$Sp = $_POST["Specialities"];
		
		$Address = mysqli_real_escape_string($connectBD, trim($_POST["Address"]));
		$City = mysqli_real_escape_string($connectBD, trim($_POST["City"]));
		$CP = mysqli_real_escape_string($connectBD, trim($_POST["CP"]));
		$Province = intval($_POST["Province"]);
		$Country = mysqli_real_escape_string($connectBD, trim($_POST["Country"]));
		$Phone = mysqli_real_escape_string($connectBD, trim($_POST["Phone"]));
		$Fax = mysqli_real_escape_string($connectBD, trim($_POST["Fax"]));
		$Mail = mysqli_real_escape_string($connectBD, trim($_POST["Mail"]));
		
		$Text = mysqli_real_escape_string($connectBD, trim($_POST["Text"]));
		
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
					$url = "../../../files/cm/center/image/";
					
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
		
		
		
		$q = "INSERT INTO `".preBD."cm_centers`(`NAME`, `PHONE`, `FAX`, `EMAIL`, `ADDRESS`, `CITY`, `CP`, `PROVINCE`, `COUNTRY`, `TEXT`, `IMAGE`, `LAT`, `LNG`, `TYPE`, `STATUS`) 
				VALUES 
			('".$Name."','".$Phone."','".$Fax."','".$Mail."','".$Address."','".$City."','".$CP."','".$Province."','".$Country."','".$Text."','".$Image."','".$Lat."','".$Lng."','".$Type."','".$Status."')";
		
		checkingQuery($connectBD, $q);
		$idNew = mysqli_insert_id($connectBD); 
		if(count($Sp)>0) {
			$q = "INSERT INTO `".preBD."cm_cs`(`IDCENTER`, `IDSPECIALITY`) VALUES ";
			for($i=0;$i<count($Sp);$i++){
				if($i == (count($Sp)-1)) { 
					$q .= "('".$idNew."','".$Sp[$i]."')";
				}else {
					$q .= "('".$idNew."','".$Sp[$i]."'),";
				}
			}
			checkingQuery($connectBD, $q);
		}
		$msg = "Centro <em>".trim($_POST["Name"])."</em> creado correctamente.";
		
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&id=".$idNew."&msg=".utf8_decode($msg);
	header($location);
?>