<?php if (allowed($mnu)) {		
	if (isset($_GET['filtersection'])){
		$filtersection=$_GET['filtersection'];
	}
	
	$q = "Select * from ".preBD."configuration_modules where IDMENU = 1 and PERMISSION >= '" . $_SESSION[PDCLOG]["Type"] ."' order by POSITION asc";
	
	$result = checkingQuery($connectBD, $q);
	while($row = mysqli_fetch_object($result)){
		$codigo = build_menu($row->ID, $row->MODULE, $row->PERMISSION, $row->MENU, $row->IMAGE);
		echo $codigo;
	} 
} ?>