<?php if (allowed($mnu)) { ?>
	<div class='cp_mnu_title'>Gestión de permisos</div>
	<?php	
		$mydegree = getdegree($_SESSION[PDCLOG]['Type']);	
	
		include ("components/user/user.list.permission.php");
		
		if (isset($_GET['permission'])) {
			$permission = $_GET['permission'];
			$action = $_GET['action'];
			$user = $_GET['user']; 
			
			$q = "Select * from ".preBD."users_permissions where Id_user = ".$user;
			
			$result = checkingQuery($connectBD, $q);
			$row = mysqli_fetch_assoc($result);
			
			?>
			<br/>&nbsp;
			<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡ATENCIÓN!' />¡ATENCIÓN! Va a cambiar los permisos de "<?php echo $permission; ?>" para el usuario "<?php echo $row['Type']; ?>"</div>
			<br/>
			<form method='post' action='modules/user/change_permissions.php'>
			<div class='cp_formfield'><label for='pwd'>Inserta tu contrase&ntilde;a:</label></div>
			<input type='password' name='pwd' id='pwd' size='30' />
			<input type='hidden' name='permission' value='<?php echo $permission; ?>' />
			<input type='hidden' name='action' value='<?php echo $action; ?>' />
			<input type='hidden' name='user' value='<?php echo $user; ?>' />
			<input type='hidden' name='type' value='<?php echo $row['Type']; ?>' />
			<input type='submit' value='Cambiar permisos' />
			</form>
		<?php }
		else {
			$msg = NULL;
		}
		
		if (isset($_GET['msg'])) {
			$msg = utf8_encode($_GET['msg']); ?>
			<br/>&nbsp;
			<div class='cp_info'><img class='cp_msgicon' src='images/info.png' alt='¡INFORMACIÓN!' /><?php echo $msg; ?></div>
		<?php }
		else {
			$msg = NULL;
		}
		if (strpos($msg,"desconectado")) {
			session_destroy();
		}
}else{
	echo "<p>No tiene permisos para acceder a esta sección.</p>";
}?>	