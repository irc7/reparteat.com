<?php
	session_start();
	if ($_SESSION["Login"] == NULL) {
		Header("Location: ../../login.php");
	}
	require_once ("../../includes/include.modules.php");
	
	$connectBD = connectdb();
	if (allowed("design")) {
		if ($_POST) {
			$item = $_POST["item"];
			$Menu = $_POST["Menu"];
			$q = "SELECT * FROM ".preBD."menu_item WHERE ID='".$item."'";
			$result = checkingQuery($connectBD, $q);
			
			$row = mysqli_fetch_array($result);
			
			$title = $row['TITLE'];
			$position = $row['POSITION'];
			$parent = $row["PARENT"];
			
			$q2 = "select * from ".preBD."menu_item where PARENT = " . $item;
			$result2 = checkingQuery($connectBD, $q2);
			
			$sons = mysqli_num_rows($result2);
			
			if($sons > 0) {
				$msg = "No puede eliminar el elemento de menú &quot;".$title."&quot; mientras contenga subopciones.";
			}else {
				$url = "../../../files/menus/image/" . $row["THUMBNAIL"];	
				if(file_exists($url)) {
					deleteFile($url);	
				}
			
				$q = "DELETE FROM ".preBD."menu_item WHERE ID='".$item."'";
				checkingQuery($connectBD, $q);
				
				$q_s_update = "SELECT * FROM ".preBD."menu_item WHERE POSITION > ".$position ." and IDMENU = ".$Menu." and PARENT = " . $parent;
				
				$result_s_update = checkingQuery($connectBD, $q_s_update);
				while ($row_update = mysqli_fetch_assoc($result_s_update)) {
					$q_up = "UPDATE ".preBD."menu_item SET POSITION = '".($row_update["POSITION"] - 1)."' WHERE ID = ".$row_update["ID"];
					checkingQuery($connectBD, $q_up);
				}
				$msg = "Elemento de menú ".$title." eliminado definitivamente.";
				
			}
			disconnectdb($connectBD);
		}
		$location = "Location: ../../index.php?mnu=design&com=menu&tpl=option&filtermenu=".$Menu."&msg=".$msg;
		header($location);
	} else {
		disconnectdb($connectBD);
		$msg = "No tiene permisos para realizar esta acción.";
		$location = "Location: ../../index.php?msg=".utf8_decode($msg);
		header($location);
	}
?>