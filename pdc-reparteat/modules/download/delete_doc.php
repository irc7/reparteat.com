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
		if (isset($_GET["id"])) {
			$error = 1;
			$id = intval($_GET["id"]);
			$q = "select * from ".preBD."download_docs where ID = " . $id; 
			
			$r = checkingQuery($connectBD, $q);
			$doc = mysqli_fetch_object($r);
			$down = $doc->IDDOWNLOAD;
			
			if($doc->URL != "") {
				$url = "../../../files/download/doc/".$doc->URL;
				deleteFile($url);
			}
			$q = "DELETE FROM ".preBD."download_docs WHERE ID = " . $id;
			checkingQuery($connectBD, $q);
			
				$q = "UPDATE ".preBD."download_docs SET `POSITION` = `POSITION` - 1 WHERE POSITION > " . $doc->POSITION . " and IDDOWNLOAD = " . $down;
				checkingQuery($connectBD, $q);
			
				$msg = "Documento <em>".stripslashes($doc->TITLE)."</em> eliminado correctamente";
				$error=0;
			}
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=content&com=download&tpl=edit&id=".$down."msg=".utf8_decode($msg)."&error=".$error;
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}	
?>