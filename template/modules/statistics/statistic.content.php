<?php

//ESTADISTICAS CONTENIDO
if($id != 0) {
	
	$q = "select * from ".preBD."statistics_content where IDCONTENT = " . $id;
	$q .= " and TYPE = '" . $view;
	$q .= "' and MONTH = " . $Smonth;
	$q .= " and YEAR = " . $Syear;
	
	$result_c = checkingQuery($connectBD,$q);
	$content = mysqli_fetch_object($result_c);
	 
	if($content) {
		$q = "update ".preBD."statistics_content SET VISITS = VISITS + 1 where ID = " . $content->ID;
		checkingQuery($connectBD,$q);
	} else {
		$q = "insert into ".preBD."statistics_content (TYPE, IDCONTENT, MONTH, YEAR, VISITS)";
		$q .= " values ('".$view."', '".$id."', '".$Smonth."', '".$Syear."', '1')";
		checkingQuery($connectBD,$q);
	}
	
}else if($section != 0){
	
	$q = "select * from ".preBD."statistics_content where IDCONTENT = " . $section;
	$q .= " and TYPE = 'section' and MONTH = " . $Smonth;
	$q .= " and YEAR = " . $Syear;
	
	$result_c = checkingQuery($connectBD,$q);
	$content = mysqli_fetch_object($result_c);
	 
	if($content) {
		$q = "update ".preBD."statistics_content SET VISITS = VISITS + 1 where ID = " . $content->ID;
		checkingQuery($connectBD,$q);
	} else {
		$q = "insert into ".preBD."statistics_content (TYPE, IDCONTENT, MONTH, YEAR, VISITS)";
		$q .= " values ('section', '".$section."', '".$Smonth."', '".$Syear."', '1')";
		checkingQuery($connectBD,$q);
	}
	
}else if($view != ""){
	
	$q = "select * from ".preBD."statistics_content where IDCONTENT = 0";
	$q .= " and TYPE = '".$view."' and MONTH = " . $Smonth;
	$q .= " and YEAR = " . $Syear;
	
	$result_c = checkingQuery($connectBD,$q);
	$content = mysqli_fetch_object($result_c);
	 
	if($content) {
		$q = "update ".preBD."statistics_content SET VISITS = VISITS + 1 where ID = " . $content->ID;
		checkingQuery($connectBD,$q);
	} else {
		$q = "insert into ".preBD."statistics_content (TYPE, IDCONTENT, MONTH, YEAR, VISITS)";
		$q .= " values ('".$view."', '0', '".$Smonth."', '".$Syear."', '1')";
		checkingQuery($connectBD,$q);
	}

}
?>