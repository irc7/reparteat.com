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
	connectdb();
	
	if($_GET) {
		$mnu = trim($_GET["mnu"]);
		$com = trim($_GET["com"]);
		if(!isset($_GET["mnu"]) || !allowed($_GET["mnu"])) { 	
			
			disconnectdb();
			$msg = "No tiene permisos para realizar esta acción";
			$location = "Location: ../../index.php?msg=".utf8_decode($msg);
			header($location);
		} else {
		
			$id = intval($_GET["record"]);
			$urlAux = "";
			if(isset($_GET["recodsperpage"]) && intval($_GET["recodsperpage"])>0){
				$recodsperpage = intval($_GET["recodsperpage"]);
				$urlAux .= "&recodsperpage=".$recodsperpage;
			}
			
			if(isset($_GET["page"]) && intval($_GET["page"])>0){
				$page = intval($_GET["page"]);
				$urlAux .= "&page=".$page;
			}
			
			if(isset($_GET["filtercat"]) && intval($_GET["filtercat"])>0){
				$filtercat = intval($_GET["filtercat"]);
				$urlAux .= "&filtercat=".$filtercat;
			}else{
				$filtercat = 0;
			}
			
			
			$action = trim($_GET["action"]);
			
			$q = "select * from ".preBD."products where ID = " . $id;
			$r = checkingQuery($connectBD, $q);
			$productBD = mysqli_fetch_object($r);
			
			if($id >= 0) {
				switch($action) {
					case "first":
						if($filtercat != 0) {
							$qf1 = "UPDATE `".preBD."products` 
									SET 
								POSITION = POSITION - 1 WHERE IDCAT = " . $filtercat." and POSITION > " . $productBD->POSITION;
							checkingQuery($connectBD, $qf1);
							
							$qf2 = "UPDATE `".preBD."products` 
									SET 
								POSITION = POSITION + 1 WHERE IDCAT = " . $filtercat." and ID != " . $productBD->ID;
							checkingQuery($connectBD, $qf2);
							$qf3 = "UPDATE `".preBD."products` 
									SET 
								POSITION = 1 WHERE ID = " . $productBD->ID;
							checkingQuery($connectBD, $qf3);
							
							$msgAlert = "Producto <em>".$productBD->TITLE."</em> colocado en primera posición.";							
						}
					break;
					case "last":
						if($filtercat != 0) {
							$lastPosition = checkingSection("products", "IDCAT", $filtercat);
							$ql1 = "UPDATE `".preBD."products` 
									SET 
								POSITION = POSITION - 1 WHERE IDCAT = " . $filtercat." and POSITION > " . $productBD->POSITION;
							checkingQuery($connectBD, $ql1);
							$ql2 = "UPDATE `".preBD."products` 
									SET 
								POSITION = ".$lastPosition." WHERE ID = " . $productBD->ID;
							checkingQuery($connectBD, $ql2);
							
							$msgAlert = "Producto <em>".$productBD->TITLE."</em> colocado en última posición.";							
						}
					break;
					case "moveup":
						if($filtercat != 0) {
							$qmu = "UPDATE `".preBD."products` 
									SET 
								POSITION = POSITION + 1 WHERE IDCAT = " . $filtercat." and POSITION = " . ($productBD->POSITION - 1);
							checkingQuery($connectBD, $qmu);
							
							$qmu2 = "UPDATE `".preBD."products` 
									SET 
								POSITION = POSITION - 1 WHERE ID = " . $productBD->ID;
							checkingQuery($connectBD, $qmu2);
							
							$msgAlert = "Producto <em>".$productBD->TITLE."</em> cambiado de posición correctamente.";							
						}
					break;
					case "movedown":
						if($filtercat != 0) {
							$qmd = "UPDATE `".preBD."products` 
									SET 
								POSITION = POSITION - 1 WHERE IDCAT = " . $filtercat." and POSITION = " . ($productBD->POSITION + 1);
							checkingQuery($connectBD, $qmd);
							
							$qmd2 = "UPDATE `".preBD."products` 
									SET 
								POSITION = POSITION + 1 WHERE ID = " . $productBD->ID;
							checkingQuery($connectBD, $qmd2);
							
							$msgAlert = "Producto <em>".$productBD->TITLE."</em> cambiado de posición correctamente.";							
						}
					break;
					case "home":
						$qs = "UPDATE `".preBD."products` 
								SET 
							`HOME`='1' WHERE ID = " . $id;
						checkingQuery($connectBD, $qs);
						$msgAlert = "Producto <em>".$productBD->TITLE."</em> mostrado en portada correctamente.";
						
					break;
					case "unhome":
						$qs = "UPDATE `".preBD."products` 
								SET 
							`HOME`='0' WHERE ID = " . $id;
						checkingQuery($connectBD, $qs);
						$msgAlert = "Producto <em>".$productBD->TITLE."</em> quitado de portada correctamente.";
						
					break;
					case "publish":
						$qs = "UPDATE `".preBD."products` 
								SET 
							`STATUS`='1' WHERE ID = " . $id;
						checkingQuery($connectBD, $qs);
						$msgAlert = "Producto <em>".$productBD->TITLE."</em> publicada correctamente.";
						
					break;
					case "unpublish":
						$qs = "UPDATE `".preBD."products` 
								SET 
							`STATUS`='0' WHERE ID = " . $id;
						checkingQuery($connectBD, $qs);
						$msgAlert = "Producto <em>".$productBD->TITLE."</em> pasado a borrador correctamente.";
						
					break;
					case "delete":
						
						$q = "select * from ".preBD."products_images where IDPRODUCT = " . $id;
						$r = checkingQuery($connectBD, $q);
						while($img = mysqli_fetch_object($r)) {
							if($img->URL != "") {
								$Thumb_url = "../../../files/product/image/".$img->URL;
								deleteFile($Thumb_url);
								$Image_url = "../../../files/product/image/".$img->URL;
								deleteFile($Image_url);
							}
						}
						$q = "DELETE FROM ".preBD."products_images WHERE IDPRODUCT='".$record."'";
						checkingQuery($connectBD, $q);
						
						//actualizo posiciones
						$qmu = "UPDATE `".preBD."products` 
								SET 
							POSITION = POSITION - 1 WHERE IDCAT = " . $productBD->IDCAT." and POSITION > " . $productBD->POSITION;
						checkingQuery($connectBD, $qmu);
						
						//borro url
						$qD1 = "DELETE FROM `".preBD."url_web` WHERE ID_VIEW = " . $id. " and VIEW = 'product' and TYPE = 'product'";
						checkingQuery($connectBD, $qD1);
						
						//borro registro
						$qD2 = "DELETE FROM `".preBD."products` WHERE ID = " . $id;
						checkingQuery($connectBD, $qD2);
						
						$msgAlert = "Producto <em>".$productBD->TITLE."</em> eliminado correctamente.";
					break;
				}
			}
		
			disconnectdb();
			$msg = $msgAlert;
			
			$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=option&msg=".utf8_decode($msg).$urlAux;
			header($location);
			
		}	
	}else {
		disconnectdb();
		$msg = "Se ha producido un error, si el problema persiste, póngase en contacto con el administrador.";
		$location = "Location: ../../index.php?mnu=".$mnu."&com=".$com."&tpl=option&msg=".utf8_decode($msg);
		header($location);
	}
?>