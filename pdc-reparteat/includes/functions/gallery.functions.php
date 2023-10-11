<?php
	function getgallery($id) {
		global $connectBD;
		$q = "SELECT TITLE FROM ".preBD."images_gallery WHERE ID = '" . $id . "'";
		
		$result = checkingQuery($connectBD, $q);
		$row = mysqli_fetch_array($result);
		$section = $row['TITLE'];
		if ($section != NULL) {
			return $section;
		}
		else {
			return "Ninguna";
		}
	}
?>