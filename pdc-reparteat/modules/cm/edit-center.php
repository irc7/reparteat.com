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
		
		$id = intval($_POST["id"]);
		$q = "select * from ".preBD."cm_centers where ID = " . $id;
		$r = checkingQuery($connectBD, $q);
		$center = mysqli_fetch_object($r);
		
		
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
		
		//Tamaño maximo de subida
			$maxSize = intval(ini_get('upload_max_filesize'));
			$maxSize = $maxSize * 1024 * 1024;
			
			$sizeUpload = 0;
			if($_FILES['Image']['error'] == 0){
				$sizeUpload = $_FILES['Image']['size'];
			}
			
			if($maxSize > $sizeUpload) { //control en la subida de archivos en funcion del servidor
		//Tratamiento de imagen			
				if(isset($_POST["optImg"]) && intval($_POST["optImg"]) === 1) {
					if($center->IMAGE != "") {
						$urlD = "../../../files/cm/center/image/". $center->IMAGE;
						deleteFile($urlD);
					}
					$Image = "";
				} else if(isset($_POST["optImg"]) && intval($_POST["optImg"]) === 2) {
					if ($_FILES["Image"]["error"]== 0) {
						if($center->IMAGE != "") {
							$urlD = "../../../files/cm/center/image/". $center->IMAGE;
							deleteFile($urlD);
						}
						preg_match("|\.([a-z0-9]{2,4})$|i", $_FILES["Image"]["name"], $ext);
					
						$file = checkingExtFile($ext[1]);
						if($file["upload"] == 1){
							if($_FILES["Image"]["error"] == "image/jpeg" && $_FILES["Image"]["error"] == "image/jpg" && $_FILES["Image"]["error"] == "image/png" && $_FILES["Image"]["error"] == "image/gif" && $_FILES["Image"]["error"] == "image/pjpeg") {
								$Image = formatNameFile($_FILES["Image"]["name"]);
								$temp_url = "../../../temp/";
								$temp_url_name = $temp_url .$Image;
								move_uploaded_file($_FILES["Image"]["tmp_name"],$temp_url_name);
								$width = 300;
								$height = 300;
								$url = "../../../files/cm/center/image/";
								customImage($temp_url, $url, $Image, $ext[1], $width, $height);	
							}else{
								$msg .= "La imagen debe de ser JPG, *PNG o *GIF.<br/>";
							}
						}else{
							$msg .= $file["msg"]."<br/>";
						}
					} else {
						$Image = $center->IMAGE;
						$msg .= "No ha seleccionado ninguna imagen.<br/>";
					}
				} else {
					$Image = $center->IMAGE;
				}
			
			}else {
				$msg .= "El tamaño del archivo de la imagen supera los límites establecidos en el servidor.";
			}
	
			
		$q = "UPDATE `".preBD."cm_centers` SET 
			`NAME`='".$Name."',
			`PHONE`='".$Phone."',
			`FAX`='".$Fax."',
			`EMAIL`='".$Mail."',
			`ADDRESS`='".$Address."',
			`CITY`='".$City."',
			`CP`='".$CP."',
			`PROVINCE`='".$Province."',
			`COUNTRY`='".$Country."',
			`TEXT`='".$Text."',
			`IMAGE`='".$Image."',
			`LAT`='".$Lat."',
			`LNG`='".$Lng."',
			`TYPE`='".$Type."',
			`STATUS`='".$Status."'
			WHERE ID = " . $id;
			
		checkingQuery($connectBD, $q);
		
		$q = "DELETE FROM `".preBD."cm_cs` WHERE IDCENTER = " . $id;
		checkingQuery($connectBD, $q);
		if(count($Sp)) {
			$q = "INSERT INTO `".preBD."cm_cs`(`IDCENTER`, `IDSPECIALITY`) VALUES ";
			for($i=0;$i<count($Sp);$i++){
				if($i == (count($Sp)-1)) { 
					$q .= "('".$id."','".$Sp[$i]."')";
				}else {
					$q .= "('".$id."','".$Sp[$i]."'),";
				}
			}
			checkingQuery($connectBD, $q);
		}		
		$msg = "Centro <em>".trim($_POST["Name"])."</em> creado correctamente.";
		
		disconnectdb($connectBD);
	}
	$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=edit&opt=".$opt."&id=".$id."&msg=".utf8_decode($msg);
	header($location);
?>