<?php
function calculateSizeDoc($s) {
	if ($s < 1024) {
	   $Size = $s . " bytes";
	} else {
		$size_kb = $s / 1024;
		if (intval($size_kb) < 1024){
			$Size = intval($size_kb) . " Kb";
		} else {
			$size_mb = intval($size_kb) / 1024;
			$Size = intval($size_mb) . " Mb";
		}
	}
	return $Size;
}