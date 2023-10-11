<?php 

	if(!isset($_SESSION[nameSessionZP]) || $_SESSION[nameSessionZP]->ID <= 0) {
		if(isset($_COOKIE[nameCookie])) {
			$userCookie = explode("%#-#%",$_COOKIE[nameCookie]);
			$q = "select * from ".preBD."user_web where ID = " . $userCookie[1] . " and PASS = '".$userCookie[0]."'";
			$r = checkingQuery($connectBD, $q);
			if($userC = mysqli_fetch_object($r)) {
			
				$q = "select MAX(DATE_LOG) as last from `".preBD."user_web_log` where IDUSER = " . $userC->ID;
				$r = checkingQuery($connectBD, $q);
				$Date = mysqli_fetch_object($r);
				
				$_SESSION[nameSessionZP] = $userC;
				$_SESSION[nameSessionZP]->startLog = 1;
				$_SESSION[nameSessionZP]->timelogin = new DateTime();
				$_SESSION[nameSessionZP]->timeline = new DateTime($Date->last);
			}
		}
	}
?>