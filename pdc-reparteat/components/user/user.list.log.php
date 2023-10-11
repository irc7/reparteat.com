<?php if (allowed($mnu)) { ?>
		<div class='cp_mnu_title'>Registro de Logs</div>

<?php 	
//$_GET['recodsperpage']	///////////////////////////////////
	if (!isset($_GET['recodsperpage'])) {
		$recodsperpage = 25;
	}else {
		$recodsperpage = $_GET['recodsperpage'];
	}
	
	
//$_GET['page']	    ///////////////////////////////////////////
	if (!isset($_GET['page'])) {
		$page = 1;
		$firstrecord = 0;
	}else {
		$page = $_GET['page'];
		$firstrecord = ($page-1) * $recodsperpage;
	}
	
	
//$_GET['search']	//////////////////////////////////////////
	if (!isset($_GET['search']) || $_GET['search'] == "") {
		$search = NULL;
		$searchq = "";
	}else {
		$search = trim($_GET['search']);
		$searchq = " AND (MAIL LIKE '%".$search."%'";
		$searchq .= " OR IP LIKE '%".$search."%')";
	}
	
//$_GET['user']	///////////////////////////////////////////////
	if (!isset($_GET['user']) || $_GET['user'] == -1) {
		$user = -1;
		$userq = "";
	}else {
		$user = abs(intval($_GET['user']));
		$userq = " and IDUSER = " . $user;
	}
	
	
	
	$q = "select count(*) as total from ".preBD."users_log where true".$searchq.$userq;
	$r1 = checkingQuery($connectBD, $q);
	$logs = mysqli_fetch_object($r1);
	$totalpages = ceil($logs->total / $recodsperpage);	
		
?>		
		
	<div class='cp_box darkshaded cp_height30'>
		<form id='dropdown' name='dropdown' method="get" action="index.php">
			<div class='cp_table180 top'>
				
				<input type='hidden' name='mnu' value='<?php echo $mnu; ?>'>
				<input type='hidden' name='com' value='<?php echo $com; ?>'>
				<input type='hidden' name='tpl' value='<?php echo $tpl; ?>'>
				<input type='hidden' name='opt' value='<?php echo $opt; ?>'>
				<span class=' white'>Mostrar&nbsp;&nbsp;</span>
				<select name='recodsperpage' id='recodsperpage' width='20' onchange='dropdown.submit();'>
					<option value='5'<?php if($recodsperpage == 5){echo " selected='selected'";} ?>>5</option>
					<option value='10'<?php if($recodsperpage == 10){echo " selected='selected'";} ?>>10</option>
					<option value='25'<?php if($recodsperpage == 25){echo " selected='selected'";} ?>>25</option>
					<option value='50'<?php if($recodsperpage == 50){echo " selected='selected'";} ?>>50</option>
				</select>
				<span class='white'>&nbsp;de <?php echo $logs->total; ?></span>
			</div>
			<div class='cp_table240 top'>
				<input type='text' name='search' id='search' size='20' maxlength='150' value='<?php echo $search; ?>'>
				<input type='submit' value='Buscar'>
			</div>
			<div class='cp_table220 top right'>
				<span class='white'>Usuario: </span>
				<select name='user' id='user' onchange='dropdown.submit();'>
					<option value='-1'<?php if($user < 0){echo " selected='selected'";} ?>>Seleccione un usuario</option>
					<option value='0'<?php if($user == 0){echo " selected='selected'";} ?>>Usuario desconocido</option>
					<?php
					$q2 = "SELECT ID, Name FROM ".preBD."users ORDER BY Name ASC";
					$result2 = checkingQuery($connectBD, $q2);
					while($row2 = mysqli_fetch_object($result2)):
					?>
					<option value='<?php echo $row2->ID; ?>'<?php if ($user == $row2->ID) {echo " selected='selected'";} ?>>
						<?php echo $row2->Name; ?>
					</option>
					<?php endwhile; ?>
				</select>
			</div>
		</form>
	</div>
		
	<div class='cp_box cp_height30'>
		<div class='cp_table50 cp_title top'>
			<div class='cp_table50 bold'>&nbsp;</div>
			<div class='cp_table50 bold'>Id-user</div>
		</div>
		<div class='cp_table350 cp_title'>
			<div class='cp_table350 bold top'>Usuario</div>
			<div class='cp_table350 bold top'>Mail Log</div>
		</div>
		<div class='cp_table200 cp_title'>
			<div class='cp_table200 bold top'>Fecha/Hora</div>
			<div class='cp_table200 bold top'>IP</div>
		</div>
		<div class='cp_table100 cp_title'>
			<div class='cp_table100 bold top'>&nbsp;</div>
			<div class='cp_table100 bold top' style='margin-left: 10px;'>&nbsp;</div>
		</div>
	</div>	
<?php	
	$q = "select * from ".preBD."users_log where true".$searchq.$userq;
	$q .= " order by DATE_LOG desc LIMIT " . $firstrecord . ", " . $recodsperpage;
	
	$r = checkingQuery($connectBD, $q);
	$users = array();
	$i = 0;
	while($log = mysqli_fetch_object($r)) {
		$users[$i]["id_log"] = $log->ID;
		if($log->IDUSER != 0) {
			$users[$i]["id_user"] = $log->IDUSER;
			$qu = "select ID, Name from ".preBD."users where ID = " . $log->IDUSER;
			if($ru = checkingQuery($connectBD, $qu)) {
				$uBD = mysqli_fetch_object($ru);
				$users[$i]["name"] = $uBD->Name;
			}else {
				$users[$i]["name"] = "Usuario eliminado";
			}	
		}else {
			$users[$i]["id_user"] = "-";
			$users[$i]["name"] = "Usuario desconocido";
		
		}
		$users[$i]["login"] = $log->MAIL;
		$users[$i]["log"] = $log->LOG;
		$users[$i]["ip"] = $log->IP;
		
		$d = new DateTime($log->DATE_LOG);
		$users[$i]["dateLog"] = $d->format('d-m-Y H:i:s');

		$i++;
	}
?>	
	
<?php for($i=0;$i<count($users);$i++): ?>	
	<div class='cp_box shaded cp_height40'>
		<div class='cp_number bold center m1'><?php echo $users[$i]["id_user"]; ?></div>
		<div class='cp_table50'>&nbsp;</div>	
		<div class='cp_table350 top'>
			<div class='cp_table320 bold'>	
				<?php echo $users[$i]["name"]; ?>
			</div>
			<div style='height: 5px;clear:both;'>&nbsp;</div>
			<div class='cp_table320'>
				<?php echo $users[$i]["login"]; ?>
			</div>
		</div>	
		<div class='cp_table200 top'>
			<div class='cp_table190 bold'>	
				<?php echo $users[$i]["dateLog"]; ?>
			</div>
			<div style='height: 5px;clear:both;'>&nbsp;</div>
			<div class='cp_table190'>
				<?php echo $users[$i]["ip"]; ?>
			</div>
		</div>
		<div class='cp_table100 top'>
			<?php if($_SESSION[PDCLOG]["Type"] == 4): 
					$msgAlert = "¡ATENCIÓN! Va a eliminar el registro. ¿Desea continuar?";
					$urlAlert = "modules/user/delete_register_log.php?reg=".$users[$i]["id_log"];
			?>
			<div class='cp_table40 bold' style="float:right;">	
				<img class='image pointer' src='images/delete.png' title='Eliminar registro' onclick='alertConfirm("<?php echo $msgAlert; ?>", "<?php echo $urlAlert; ?>")' />
			</div>
			<?php endif; ?>
			<div class='cp_table40 bold'  style="float:right;">	
			<?php if($users[$i]["log"] == 0): ?>
				<img class='image' src='images/alert.png' title='Usuario desconocido' style="width:25px;" />
			<?php else: ?>
				&nbsp;
			<?php endif; ?>
			</div>
		</div>
	</div>
<?php endfor; ?>	
	
	
<?php
	$previouspage = $page - 1;
	$nextpage = $page + 1;
	$urlPag = "index.php?mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=".$opt;
	$urlPag .= "&user=".$user."&search=".$search."&recodsperpage=".$recodsperpage."&page=".$previouspage;
	if ($totalpages > 1):
	?>
	<div class='cp_box dotted cp_height25'>
		<?php if ($page > 1): ?>
			<div class='cp_table' style='margin-right:3px;'>
				<a href='<?php echo $urlPag; ?>'>
					<<
				</a>
			</div>
		<?php endif;
		if ($page > 9): ?>
			<div class='cp_table cp_pages center shaded'>
			<a href='<?php echo $urlPag; ?>&page=1'>1</a></div>
			<div class='cp_table' style='margin-left:3px;'>...</div>
		<?php endif;
		for ($i=1; $i < $totalpages + 1; $i++):
			if ($i > ($page - 9) && $i < ($page + 9)):
		?>
				<div style='margin-right:3px;' class='cp_table cp_pages center<?php if ($page == $i) {echo" darkshaded";}else{echo" shaded";} ?>'>
					<a href='<?php echo $urlPag . "&page=".$i; ?>'<?php if ($page == $i) {echo" style='color: white;'";} ?>><?php echo $i; ?></a>
				</div>
			<?php endif; 
			endfor; 
		endif;
		if ($page < ($totalpages - 9)): ?>
			<div class='cp_table' style='margin-left:3px;'>...</div>
			<div class='cp_table cp_pages center shaded' style='margin-right:3px;'>
				<a href='<?php echo $urlPag . "&page=".$totalpages; ?>'>
					<?php echo $totalpages; ?>
				</a>
			</div>
	<?php endif; ?>
	<?php if ($page < $totalpages): ?>
		<div class='cp_table' style='margin-left:3px;'>
			<a href='<?php echo $urlPag . "&page=".$nextpage; ?>'>
				>>
			</a>
		</div>
	<?php endif; ?>
	</div>	
<?php 
}else{
	echo "<p>No tiene permisos para acceder a esta sección.</p>";
}
?>	 
