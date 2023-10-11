<?php if (allowed($mnu)) {
		$q = "Select * from ".preBD."configuration_modules where IDMENU = 3 and PERMISSION >= '" . $_SESSION[PDCLOG]["Type"] ."' order by POSITION asc";
		
		$result = checkingQuery($connectBD, $q);
		while($row = mysqli_fetch_object($result)){
			$codigo = build_menu($row->ID, $row->MODULE, $row->PERMISSION, $row->MENU, $row->IMAGE);
			echo $codigo;
		}  ?>
<?php } ?>

