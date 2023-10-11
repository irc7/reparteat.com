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
	$msg = NULL;
	
	if(allowed("blog")) {
		$q = "SELECT * FROM ".preBD."articles WHERE TRASH='1' and TYPE='blog'";
		
		$result = checkingQuery($connectBD, $q);
		$i= 1;
		while ($row_article = mysqli_fetch_array($result)) {
			$Thumbnail = $row_article['THUMBNAIL'];
			$id_article = $row_article['ID'];
			
			$q1 = "SELECT * FROM ".preBD."paragraphs WHERE IDARTICLE='" . $id_article . "' ORDER BY POSITION";
			
			$result1 = checkingQuery($connectBD, $q1);
			$j=1;
			while($row_block = mysqli_fetch_array($result1)) {
				$q2 = "select ID, URL from ".preBD."paragraphs_file where IDPARAGRAPH = " . $row_block["ID"];
				
				$result2 = checkingQuery($connectBD, $q2);
				while($row_files = mysqli_fetch_assoc($result2)) {
					$url_file = "../../../files/articles/doc/ " . $row_files["URL"];
					if(file_exists($url_file)) {
						unlink($url_file);	
					}
					$q3 = "DELETE FROM ".preBD."paragraphs_file WHERE ID='".$row_files["ID"]."'";
					checkingQuery($connectBD, $q3);
					
				}
				$blocks = array();
				$blocks[$i]["type"] = $row_blocks["TYPE"];
				$blocks[$i]["image"] = $row_blocks["IMAGE"];
				$blocks[$i]["video"] = $row_blocks["VIDEO"];
				$j++;
			}
			$q2 = "DELETE FROM ".preBD."paragraphs WHERE IDARTICLE = '" .$id_article . "'";
			checkingQuery($connectBD, $q2);
			
			
			//BORRADO DE IMÁGENES
				$delete_thumb_article = FALSE;
				for($j=1;$j<=count($blocks);$j++) {
					if ($blocks[$j]["image"] != "" && $blocks[$j]["video"] != $blocks[$j]["image"]){
						if ($Thumbnail != $blocks[$j]["image"]){
							deleteImageParagraph($blocks[$j]["image"]);
						} else {
							$delete_thumb_article = TRUE;	
						}
					}
					if ($blocks[$j]["type"] == video){
						deleteVideoParagraph($blocks[$j]["image"]);
					}
				}
				if ($delete_thumb_article) {
					deleteImageParagraph($Thumbnail);
				}
			
			$q3 = "DELETE FROM ".preBD."articles WHERE ID='" . $id_article . "'";
			checkingQuery($connectBD, $q3);
			
			$i++;
		}
		
		
		$msg = "Papelera vaciada";
	
		disconnectdb($connectBD);
		$location = "Location: ../../index.php?mnu=blog&com=blog&tpl=trash&msg=".utf8_decode($msg);
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>