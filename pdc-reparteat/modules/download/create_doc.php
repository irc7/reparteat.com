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
	$connectBD = connectdb();
	if (allowed ("content")) { 	
		if(isset($_GET["id"]) && isset($_GET["filename1"])) {
			
			$error = 1;
			$idDown = abs(intval($_GET["id"]));
			
			$file = $_GET["filename1"];
			$origin = "../../../temp/".$file;
			$fileformat = formatNameFile($file);
			$destiny = "../../../files/download/doc/".$fileformat; 
			
			$auxSize = filesize($origin);
			
			if ($auxSize < 1024) {
			   $Size = $auxSize . " bytes";
			} else {
				$size_kb = $auxSize / 1024;
				if (intval($size_kb) < 1024){
					$Size = intval($size_kb) . " Kb";
				} else {
					$size_mb = intval($size_kb) / 1024;
					$Size = intval($size_mb) . " Mb";
				}
			}
			preg_match("|\.([a-z0-9]{2,4})$|i", $file, $ext);
			$Ext = $ext[1];
			
			if(copy($origin, $destiny)) {
				$qP = "update ".preBD."download_docs set POSITION = POSITION + 1 where IDDOWNLOAD = " . $idDown;
				checkingQuery($connectBD, $qP);
				
									
				$qD = "INSERT INTO ".preBD."download_docs (`IDDOWNLOAD`, `TITLE`, `URL`, `SIZE`, `EXTENSION`, `POSITION`) VALUES";
				$qD .=" ('".$idDown."','','".$fileformat."','".$Size."','".$Ext."',1)";
				checkingQuery($connectBD, $qD);
				
				if(file_exists($origin)) {
					unlink($origin);
				}
				
				$error = 0;
				$msg = "Documento subido correctamente.";
			} else {
				$msg = "Se ha producido un error en el proceso de subida, por favor vuelva a intentarlo.";
			}
			disconnectdb($connectBD);
		} else {
			$msg = "Se ha producido un error en el proceso de subida, por favor vuelva a intentarlo.";
		}
		$location = "Location: ../../index.php?mnu=content&com=download&tpl=edit&id=".$idDown."msg=".utf8_decode($msg)."&error=".$error;
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
?>