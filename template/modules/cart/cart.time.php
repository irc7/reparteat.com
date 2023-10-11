<?php 
	$timeSup = $supObj->checkingOpen($supView->ID,intval($_SESSION[sha1("zone")])); 
	if($supView->STATUS == 1) {
		if($timeSup["status"] == 1) {
			$classTimeSup = "green";
			$iconTimeSup = "unlock";
			$textTime = "Disponible hasta las " . $timeSup["time"]->FINISH_H .":";
			if(strlen($timeSup["time"]->FINISH_M) == 1) {
				$textTime .= "0";
			}
			$textTime .= $timeSup["time"]->FINISH_M;
			$textInfoTime = "Disponible para pedidos";
		} else {
			if($timeSup["time"] == null) {
				$textTime = "No disponible";
				$classTimeSup = "danger";
				$iconTimeSup = "lock";
			}else {
				$classTimeSup = "orange";
				$iconTimeSup = "clock-o";
				$textTime = "Disponible a partir de " . $timeSup["time"]->START_H .":";
				if(strlen($timeSup["time"]->START_M) == 1) {
					$textTime .= "0";
				}
				$textTime .= $timeSup["time"]->START_M;
			}
			$textInfoTime = "No se pueden tramitar pedidos hasta que el restaurante esté disponible para pedidos.";
		}
	}else{
		$textTime = "No disponible";
		$classTimeSup = "danger";
		$iconTimeSup = "lock";
		$textInfoTime = "El restaurante no se encuentra disponible para pedidos.";
	}
?>
	