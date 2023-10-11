<?php

	$url_com = "mnu=".$mnu."&com=".$com."&tpl=".$tpl."&opt=permission";

	$q = "SELECT * FROM ".preBD."users_permissions ORDER BY Degree";
	
	$r2 = checkingQuery($connectBD, $q);
?>
	<div class='cp_permission_column'>
		<div class='cp_permission_index cp_title'>Área</div>
		<div class='cp_permission_index bold'>Contenidos</div>
		<div class='cp_permission_index bold'>Diseño</div>
		<div class='cp_permission_index bold'>mailing</div>
		<div class='cp_permission_index bold'>Estadísticas</div>
		<div class='cp_permission_index bold'>SEO</div>
		<div class='cp_permission_index bold'>Configuración</div>
		<div class='cp_permission_index bold'>Blog</div>
		<div class='cp_permission_index bold'>Zona privada</div>
	</div>
	
<?php
	while($row2 = mysqli_fetch_array($r2)) {

		$id_user = $row2['Id_user'];
		
		//si no es webmaster, imprime por pantalla los usuarios que va recorriendo con sus permisos
		if($id_user != "4"){
?>
		<div class='cp_permission_column'>
			<div class='cp_permission_item cp_title'><?php echo $row2['Type']; ?></div>
			<div class='cp_permission_item'>
<?php		/*permisos de contenidos*/
			if ($row2['content'] != 1) {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/unchecked_off.png' alt='' title='' />
			<?php } else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=Content&action=on&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/unchecked.png' alt='Permitir' title='Permitir' />
					</a>
			<?php } 
			} else {
				if ($mydegree >= getdegree($row2['Id_user'])) {
			?>
					<img class='image' src='images/checked_off.png' alt='' title='' />
				<?php }else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=Content&action=off&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/checked.png' alt='Denegar' title='Denegar' />
					</a>
			<?php }
			}
			?>
			</div>
		
			<div class='cp_permission_item'>
<?php			/*permisos de diseño*/
			if ($row2['design'] != 1) {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/unchecked_off.png' alt='' title='' />
				<?php } else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=Design&action=on&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/unchecked.png' alt='Permitir' title='Permitir' />
					</a>
				<?php }
			}else {
				if ($mydegree >= getdegree($row2['Id_user'])) {
			?>
					<img class='image' src='images/checked_off.png' alt='' title='' />
				<?php }	else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=Design&action=off&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/checked.png' alt='Denegar' title='Denegar' />
					</a>
				<?php }
			} ?>
			</div>
			<div class='cp_permission_item'>
<?php		/*permisos de Newsletter*/
			if ($row2['mailing'] != 1) {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/unchecked_off.png' alt='' title='' />
				<?php }else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=mailing&action=on&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/unchecked.png' alt='Permitir' title='Permitir' />
					</a>
<?php 			}
			}else {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/checked_off.png' alt='' title='' />
				<?php }	else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=mailing&action=off&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/checked.png' alt='Denegar' title='Denegar' />
					</a>
				<?php }
			} ?>
			</div>
			<div class='cp_permission_item'>
<?php 		/*permisos de estadísticas*/
			if ($row2['statistics'] != 1) {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/unchecked_off.png' alt='' title='' />
				<?php }	else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=Statistics&action=on&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/unchecked.png' alt='Permitir' title='Permitir' />
					</a>
<?php			 }
			}else {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/checked_off.png' alt='' title='' />
				<?php } else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=Statistics&action=off&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/checked.png' alt='Denegar' title='Denegar' />
					</a>
				<?php }
			}
?>			</div>
			<div class='cp_permission_item'>
<?php			/*permisos de SEO*/
			if ($row2['seo'] != 1) {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/unchecked_off.png' alt='' title='' />
				<?php }	else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=SEO&action=on&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/unchecked.png' alt='Permitir' title='Permitir' />
					</a>
				<?php }
			}else {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/checked_off.png' alt='' title='' />
				<?php }else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=SEO&action=off&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/checked.png' alt='Denegar' title='Denegar' />
					</a>
				<?php }
			}
?>			</div>
			<div class='cp_permission_item'>
<?php 		/*permisos de estadísticas*/
			if ($row2['configuration'] != 1) {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/unchecked_off.png' alt='' title='' />
		<?php	}	else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=Configuration&action=on&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/unchecked.png' alt='Permitir' title='Permitir' />
					</a>
		<?php	}
			}else {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/checked_off.png' alt='' title='' />
		<?php 	}else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=Configuration&action=off&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/checked.png' alt='Denegar' title='Denegar' />
					</a>
		<?php 	}
			}			
		?>
			</div>
			<div class='cp_permission_item'>
<?php 		/*permisos de estadísticas*/
			if ($row2['blog'] != 1) {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/unchecked_off.png' alt='' title='' />
		<?php	}else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=Blog&action=on&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/unchecked.png' alt='Permitir' title='Permitir' />
					</a>
		<?php	}
			}else {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/checked_off.png' alt='' title='' />
		<?php 	}else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=Blog&action=off&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/checked.png' alt='Denegar' title='Denegar' />
					</a>
		<?php 	}
			}			
		
		?>
			</div>
			<div class='cp_permission_item'>
<?php 		/*permisos de estadísticas*/
			if ($row2['privatezone'] != 1) {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/unchecked_off.png' alt='' title='' />
		<?php	}else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=Privatezone&action=on&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/unchecked.png' alt='Permitir' title='Permitir' />
					</a>
		<?php	}
			}else {
				if ($mydegree >= getdegree($row2['Id_user'])) {
?>
					<img class='image' src='images/checked_off.png' alt='' title='' />
		<?php 	}else { ?>
					<a href='index.php?<?php echo $url_com; ?>&permission=Privatezone&action=off&user=<?php echo $row2['Id_user']; ?>'>
						<img class='image' src='images/checked.png' alt='Denegar' title='Denegar' />
					</a>
		<?php 	}
			}
?>			
			</div>
		</div>
<?php	}
		?>
<?php } ?>