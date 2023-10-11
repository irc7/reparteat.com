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
//	pre($_GET);
//	pre($_FILES);
//	die();
	if($_GET) {
		$mnu = trim($_GET["mnu"]);
		$submnu = abs(intval($_GET["submnu"]));
		if(!isset($_GET["mnu"]) || !allowed($_GET["mnu"])) { 	
			disconnectdb($connectBD);
			$msg = "No tiene permisos para realizar esta acción";
			$location = "Location: ../../index.php?msg=".utf8_decode($msg);
			header($location);
		} else {
			$error= 1;
			$id = abs(intval($_GET["id"]));
			$filtersection = abs(intval($_GET["filtersection"]));
			$recodsperpage = abs(intval($_GET["recodsperpage"]));
			$page = abs(intval($_GET["page"]));
			$urlReturn = "mnu=".$mnu."&submnu=".$submnu."&filtersection=".$filtersection."&recodsperpage=".$recodsperpage."&page=".$page;
			if (isset($_GET["id"]) && $id > 0) {
				
				$q = "select * from ".preBD."downloads where ID = " . $id;
				$r = checkingQuery($connectBD, $q);
				$down = mysqli_fetch_object($r);
				
				//borrar la imagen si la tiene
				if($down->IMAGE != "") {
					$url = "../../../files/download/image/".$down->IMAGE;
					if(file_exists($url)) {
						unlink($url);
					}
				}
				//borrar los documentos asociados
				$q = "select * from ".preBD."download_docs where IDDOWNLOAD = '" . $id ."' order by POSITION desc";
				$res = checkingQuery($connectBD, $q);
				while($doc = mysqli_fetch_object($res)) {
					$url = "../../../files/download/doc/".$doc->URL;
					if($doc->URL != "") {
						deleteFile($url);
					}
					$qD = "delete FROM ".preBD."download_docs WHERE ID = " . $doc->ID;
					checkingQuery($connectBD, $qD);
				}
				
				
				$qD = "delete FROM ".preBD."downloads WHERE ID = " . $id;
				
				checkingQuery($connectBD, $qD); 
				
				$error = 0;
				$msg = "Descarga <em>".$down->TITLE."</em> eliminado correctamente.";
				
				disconnectdb($connectBD);
				$location = "Location: ../../index.php?".$urlReturn."&com=download&tpl=list&msg=".utf8_decode($msg)."&error=".$error;
				header($location);
			} else {
				disconnectdb($connectBD);
				$msg = "Descarga desconocida.";
				$location = "Location: ../../index.php?".$urlReturn."&com=download&tpl=list&msg=".utf8_decode($msg)."&error=".$error;
				header($location);
			}
		}
	}else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>