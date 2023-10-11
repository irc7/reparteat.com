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

	$mnu = trim($_GET["mnu"]);
	if(allowed($mnu)) {
		$typeArticle = trim($_GET["type"]);
		
		if (isset($_GET['msg'])) {
			$msg = $_GET['msg'];
		}	
		if (isset($_GET['recordTemp'])) {
			$id = $_GET['recordTemp'];
		}
		if (isset($_GET['block'])) {
			$block = $_GET['block'];
		}
		if (isset($_GET['action'])) {
			$action = $_GET['action'];
		}
		
		if ($block != NULL && $action != NULL) {
			//ARTICULO
			$q_art = "SELECT * FROM ".preBD."articles_temp WHERE ID = '" . $id . "'";
			
			$result_art = checkingQuery($connectBD, $q_art);
			$row_article = mysqli_fetch_assoc($result_art);
			
			
			switch ($action){
				case "up": 
					//BLOQUE
					$q_block = "SELECT * FROM ".preBD."paragraphs_temp WHERE IDARTICLE = ". $id . " AND POSITION =" . $block;
					
					$result_block = checkingQuery($connectBD, $q_block);
					$edit_block = mysqli_fetch_array($result_block);
					$id_paragraphs = $edit_block["ID"];


					$q_block2 = "SELECT * FROM ".preBD."paragraphs_temp WHERE IDARTICLE = " . $id . " AND POSITION =" . ($block - 1);
					
					$result_block2 = checkingQuery($connectBD, $q_block2);
					$row_block_up = mysqli_fetch_array($result_block2);

					$id_block_up = $row_block_up["ID"];
					
					$q_up1 = "UPDATE ".preBD."paragraphs_temp SET POSITION =  POSITION - 1 WHERE ID = " . $id_paragraphs ;
					checkingQuery($connectBD, $q_up1);
					
					$q_up2 = "UPDATE ".preBD."paragraphs_temp SET POSITION =  POSITION + 1 WHERE ID = " . $id_block_up;
					checkingQuery($connectBD, $q_up2);
					
				break;
				case "down": 
					//BLOQUE
					$q_block = "SELECT * FROM ".preBD."paragraphs_temp WHERE IDARTICLE = ". $id . " AND POSITION =" . $block;
					
					$result_block = checkingQuery($connectBD, $q_block);
					$edit_block = mysqli_fetch_array($result_block);
					$id_paragraphs = $edit_block["ID"];
				
					$q_block3 = "SELECT * FROM ".preBD."paragraphs_temp WHERE IDARTICLE = " . $id . " AND POSITION =" . ($block + 1);
					$result_block3 = checkingQuery($connectBD, $q_block3);
					$row_block_up = mysqli_fetch_array($result_block3);

					$id_block_up = $row_block_up["ID"];
					
					$q_up1 = "UPDATE ".preBD."paragraphs_temp SET POSITION =  POSITION + 1 WHERE ID = " . $id_paragraphs ;
					checkingQuery($connectBD, $q_up1);
					
					$q_up2 = "UPDATE ".preBD."paragraphs_temp SET POSITION =  POSITION - 1 WHERE ID = " . $id_block_up;
					checkingQuery($connectBD, $q_up2);
					
				break;
				case "delete":
					//BLOQUES
					$q_update = "SELECT * FROM ".preBD."paragraphs_temp WHERE POSITION >= " . $block . " AND IDARTICLE = ".$id." ORDER BY POSITION"; 
					
					$result_update = checkingQuery($connectBD, $q_update);
					$t = 1;
					while($blocks_update = mysqli_fetch_assoc($result_update)){
						$id_block_update = $blocks_update["ID"];
						if ($block == $blocks_update["POSITION"]) {
							if ($row_article["URL_THUMBNAIL"] != $blocks_update["URL_IMAGE"] && $blocks_update["URL_IMAGE"] != "") {
								deleteImageParagraph ($blocks_update["URL_IMAGE"]);
							}
							$q_delete = "DELETE FROM ".preBD."paragraphs_temp WHERE ID = " . $id_block_update;
							checkingQuery($connectBD, $q_delete);
							
							$q_file = "select * from ".preBD."paragraphs_file_temp where IDPARAGRAPH = " . $id_block_update;
							$result_file = checkingQuery($connectBD, $q_file);
							
							while($files = mysqli_fetch_assoc($result_file)){
								 deleteParagraphFile($files["ID"]);	
							}
						} else {
							$q_up2 = "UPDATE ".preBD."paragraphs_temp SET POSITION =  POSITION - 1 WHERE ID = '" . $id_block_update . "'";
							checkingQuery($connectBD, $q_up2);
							
						}	
					}
					$msg .= ".<br/>Bloque numero ".$block." borrado";
				break;
			}
				
		}
		 
		disconnectdb($connectBD);
		
		$location = "Location: ../../index.php?mnu=".$mnu."&com=articles&tpl=edit&recordTemp=".$id."&type=".$typeArticle."&preview=pasive&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>