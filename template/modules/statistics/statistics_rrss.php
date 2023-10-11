<?php
	if(isset($_SERVER["HTTP_REFERER"]) && trim($_SERVER["HTTP_REFERER"])!="" && (strpos(trim($_SERVER["HTTP_REFERER"]), "ihppediatria") == false || strpos(trim($_SERVER["HTTP_REFERER"]), "ihpediatria") == false) && (strpos(DOMAIN, "ihppediatria") == false || strpos(DOMAIN, "ihpediatria") == false)) {
		$urlVisit = trim($_SERVER["HTTP_REFERER"]);
		$urlVisitA = explode("//", $urlVisit, 2);
		$urlVisitAA = explode("/",$urlVisitA[1], 2);
		$urlVisit = $urlVisitAA[0]; 
		if($view == "article" && $id > 0) {
			$typecontent = "article";
			$idst = $id;
		}else if($view == "article" && $id == 0 && $section != 0) {
			$typecontent = "section";
			$idst = $section;
		}else if($view == "blog" && $id > 0) {
			$typecontent = "blog";
			$idst = $id;
		}else if($view == "blog" && $id == 0 && $section != 0) {
			$typecontent = "section";
			$idst = $section;
		}else {
			$typecontent = $view;
			$idst = 0;
		}
		
		if(strpos($urlVisit, "facebook") == true) {
			$type = "facebook";
		}else if(strpos($urlVisit, "t.co") == true) {//twitter
			$type = "twitter";
		}else if(strpos($urlVisit, "linkedin") == true) {//linkedin
			$type = "linkedin";
		}else if(strpos($urlVisit, "plus.google") == true) {
			$type = "googleplus";
		}else {
			$type = "other";
		}
		$q = "select ID from ".preBD."statistics_rrss 
				where IDCONTENT = ".$idst."
				and TYPECONTENT = '" . $typecontent ."' 
				and TYPE = '" . $type ."' 
				and MONTH = " . $Smonth ." 
				and YEAR = " . $Syear;
				
		$r = checkingQuery($connectBD,$q);
		
		if($ex = mysqli_fetch_object($r)){
			$q = "UPDATE `".preBD."statistics_rrss` SET CONT=CONT+1 WHERE ID = " . $ex->ID;
		}else{		
			$q = "INSERT INTO `".preBD."statistics_rrss`
					(`HTTP_REFERER`, `IDCONTENT`, `YEAR`, `MONTH`, `TYPECONTENT`, `TYPE`, `CONT`) 
					VALUES 
					('".$urlVisit."','".$idst."','".$Syear."','".$Smonth."','".$typecontent."','".$type."',1)";
		}
		checkingQuery($connectBD,$q);
		
	} else if(isset($_GET["rs"]) && trim($_GET["rs"]) != ""){
		$type = trim($_GET["rs"]);
		if($view == "article" && $id > 0) {
			$typecontent = "article";
			$idst = $id;
		}else if($view == "article" && $id == 0 && $section != 0) {
			$typecontent = "section";
			$idst = $section;
		}
		
		$q = "select ID from ".preBD."statistics_rrss 
				where IDCONTENT = ".$idst."
				and TYPECONTENT = '" . $typecontent ."' 
				and TYPE = '" . $type ."' 
				and MONTH = " . $Smonth ." 
				and YEAR = " . $Syear;
		$r = checkingQuery($connectBD,$q);
				
		if($ex = mysqli_fetch_object($r)){
			$q = "UPDATE `".preBD."statistics_rrss` SET CONT=CONT+1 WHERE ID = " . $ex->ID;
		}else{		
			$q = "INSERT INTO `".preBD."statistics_rrss`
					(`HTTP_REFERER`, `IDCONTENT`, `YEAR`, `MONTH`, `TYPECONTENT`, `TYPE`, `CONT`) 
					VALUES 
					('','".$idst."','".$Syear."','".$Smonth."','".$typecontent."','".$type."',1)";
		}
		checkingQuery($connectBD,$q);
		
	}
?>