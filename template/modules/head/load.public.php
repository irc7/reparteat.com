<?php
	if (isset($_GET['code']) && !isset($_GET['view'])) {
		$code = trim($_GET['code']);
		$param = paramDirect($code);
		
		$view = $param["view"];
		$id = $param["id"];
		$section = $param["section"];
	} elseif (isset($_GET['code']) && isset($_GET['view'])) {//articulos dentro de congresos
		$code = trim($_GET['code']);
		$param = paramDirect($code);
		$view = $_GET['view'];
		$id = $param["id"];
		
		$section = $param["section"];
	}elseif(isset($_GET["slugbd"]) && trim($_GET["slugbd"]) != ""){ 
	
		$slugbd = trim($_GET["slugbd"]);
		/*caso de q la url venga con mensaje*/
		$pos = strpos($slugbd, "msg");
		if(($pos != "") && (isset($pos))){
			$slug_aux = substr($slugbd, 0, $pos);
			$pos2 = strpos($slugbd, "=");
			$mensaje = substr($slugbd, $pos2+1, strlen($slugbd));
			$slugbd = $slug_aux;
		}

		$q = "select * from ".preBD."url_web where SLUG = '" . $slugbd . "'";
		$r=checkingQuery($connectBD,$q);
		$viewBD = mysqli_fetch_object($r);
		$slug = $viewBD->SLUG; 
		$view = $viewBD->VIEW;
		$section = $viewBD->SEC_VIEW;
		$id = $viewBD->ID_VIEW;
		$titulo = $viewBD->TITLE;
		if(isset($_GET["aux"]) && intval($_GET["aux"]) != 0){
			$aux = intval($_GET["aux"]);
			if($aux < STARTYEAR) {
				$_GET["page"] = $aux;
			}else {
				$_GET["year"] = $aux;
			}
		}
	}else {
		if(isset($_GET["view"]) && $_GET["view"] != NULL && $_GET["view"] != ""){
			$view = $_GET["view"];
		}else{
			$view = "home";
		}
		if($view == "blog") {
			$sgb = trim($_GET["slugb"]);
			$q = "select * from ".preBD."blog where SLUG = '".$sgb."'";
			$r = checkingQuery($connectBD, $q);
			if($blogView = mysqli_fetch_object($r)) {
				$id = 0;
				$section = $blogView->IDSECTION;
			}else{
				$q = "select * from ".preBD."url_web where SLUG = '" . $sgb . "'";
				$r=checkingQuery($connectBD,$q);
				$viewBD = mysqli_fetch_object($r);
				$slug = $viewBD->SLUG; 
				$view = $viewBD->VIEW;
				$section = $viewBD->SEC_VIEW;
				$id = $viewBD->ID_VIEW;
				$titulo = $viewBD->TITLE;
			}
		}else{
			
			if(isset($_GET["id"])){
				$id = $_GET["id"];
			}else{
				$id = 0;
			}
			
			if(isset($_GET["section"])){
				$section = $_GET["section"];
			}else{
				$section = 0;
			}
		}
	}
	$now = date('Y').date('m').date('d').date('H').date('i').date('s');
	$nowObj = new DateTime();
	$date_joker = "0000-00-00 00:00:00";

	$V_PHP = explode(".", phpversion());
	if($V_PHP>=5){
		date_default_timezone_set("Europe/Paris");
	}

	if($section == 0 && $id!= 0) {
		if($view == "article") {
			$q = "select IDSECTION from ".preBD."articles where ID = " . $id;
			
			$result_section = checkingQuery($connectBD,$q);
			$sec = mysqli_fetch_assoc($result_section);
			$section = $sec["IDSECTION"];
		}
	}
	$navigation = ObtenerNavegador();
	$S_O = ObtenerSistemaOperativo();
		
	if($view != "preview") {
		//require_once("template/modules/statistics/statistic.php");
	}
	/*
	if($navigation == "Unknown") {
		$ip = getRealIP();
		$referer = $_SERVER["HTTP_REFERER"];
		$agent = $_SERVER["HTTP_USER_AGENT"];
		
		$q = "select ID from ".preBD."control_visit where IP = '".$ip."' and REFERER = '".$referer."' and AGENT = '".$agent."'";
		$r = checkingQuery($connectBD,$q);
		if($vi = mysqli_fetch_object($r)) {
			$q = "update ".preBD."control_visit set CONT = CONT + 1, DATE_L = NOW()";
			checkingQuery($connectBD,$q);
		}else{
			$q = "INSERT INTO `".preBD."control_visit`(`IP`, `REFERER`, `AGENT`, `CONT`, `DATE_L`) VALUES ('".$ip."','".$referer."','".$agent."',1,NOW())";
			checkingQuery($connectBD,$q);
		}
	}
	*/
//	pre($navigation);
//	pre($S_O);
?>



















