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
	
	if($_GET) {
		$mnu = trim($_GET["mnu"]);
		$com = trim($_GET["com"]);
		$opt = trim($_GET["opt"]);
		if(!isset($_GET["mnu"]) || !allowed($_GET["mnu"])) { 	
			disconnectdb($connectBD);
			$msg = "No tiene permisos para realizar esta acción";
			$location = "Location: ../../index.php?msg=".utf8_decode($msg);
			header($location);
		} else {
			$id = intval($_GET["id"]);
			$urlAux = "";
			if(isset($_GET["recodsperpage"]) && intval($_GET["recodsperpage"])>0){
				$recodsperpage = intval($_GET["recodsperpage"]);
				$urlAux .= "&recodsperpage=".$recodsperpage;
			}
			if(isset($_GET["page"]) && intval($_GET["page"])>0){
				$page = intval($_GET["page"]);
				$urlAux .= "&page=".$page;
			}
			if(isset($_GET["filterType"]) && intval($_GET["filterType"])>0){
				$filtertype = intval($_GET["filterType"]);
				$urlAux .= "&filterType=".$filterType;
			}else{
				$filterType = 0;
			}
			$action = trim($_GET["action"]);
			$q = "select * from ".preBD."cm_centers where ID = " . $id;
			$r = checkingQuery($connectBD, $q);
			$center = mysqli_fetch_object($r);
			if($id >= 0) {
				switch($action) {
					case "publish":
						$qs = "UPDATE `".preBD."cm_centers` 
								SET 
							`STATUS`='1' WHERE ID = " . $id;
						checkingQuery($connectBD, $qs);
						$msgAlert = "Centro <em>".$center->NAME."</em> publicad correctamente.";
					break;
					case "unpublish":
						$qs = "UPDATE `".preBD."cm_centers` 
								SET 
							`STATUS`='0' WHERE ID = " . $id;
						checkingQuery($connectBD, $qs);
						$msgAlert = "Centro <em>".$center->NAME."</em> pasado a borrador correctamente.";
					break;
					case "delete":
						if($center->IMAGE != "") {
							$Thumb_url = "../../../files/cm/center/image/".$center->IMAGE;
							deleteFile($Thumb_url);
						}
						$q = "DELETE FROM ".preBD."cm_cs WHERE IDCENTER = '".$id."'";
						checkingQuery($connectBD, $q);
						
						$q = "DELETE FROM ".preBD."cm_dc WHERE IDCENTER = '".$id."'";
						checkingQuery($connectBD, $q);
						
						//borro registro
						$qD2 = "DELETE FROM `".preBD."cm_centers` WHERE ID = " . $id;
						checkingQuery($connectBD, $qD2);
						
						$msgAlert = "Centro <em>".$center->NAME."</em> eliminado correctamente.";
					break;
				}
			}
			disconnectdb($connectBD);
			$msg = $msgAlert;
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&opt=".$opt."&tpl=option&msg=".utf8_decode($msg).$urlAux;
			header($location);
		}	
	}else {
		disconnectdb($connectBD);
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&opt=".$opt."&tpl=option&msg=".utf8_decode($msg);
		header($location);
	}
?>