<?php
	function getGallery($id) {
		global $connectBD;
		$q = "SELECT TITLE FROM ".preBD."videos_gallery WHERE ID = '" . $id . "'";
		
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