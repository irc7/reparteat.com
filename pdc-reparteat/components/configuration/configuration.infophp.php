<?php 
	$V_PHP = explode(".", phpversion());
	if($V_PHP[0] >= 5){
		date_default_timezone_set("Europe/Paris");
	}
	phpInfo();

?>