<div class="container">
<div class="row">
	<div class='cp_table300 cp_title'>Banner</div>
	<div class='cp_table100 cp_title'>Pausa</div>
	<div class='cp_table100 cp_title'>Velocidad</div>
	<div class='cp_table150 cp_title'>Tamaño de imagen</div>
<?php 	
	$i=0;
	while($i<2) { ?>
		<div class='cp_table25 cp_title'>&nbsp;</div>
		<?php
		$i++;
	} ?>
	<br/>
	<br/>
	<?php
	$q = "SELECT * FROM ".preBD."slider_gallery ORDER BY TITLE ASC";
	$result = checkingQuery($connectBD, $q);
	$com_url = "mnu=design&com=slide&tpl=option&opt=section";
	while($row = mysqli_fetch_object($result)) {
		$album = $row->ID;
		
		?>
		<div class='cp_table'>
			<div class='cp_table300'>
				<a href='index.php?<?php echo $com_url; ?>&album=<?php echo $album; ?>&action=Editbanner' title='Renombrar banner'>
					<?php echo $row->TITLE; ?>
				</a>
			</div>
			<div class='cp_table100'>
				<a href='index.php?<?php echo $com_url; ?>&album=<?php echo $album; ?>&action=Editpause' title='Cambiar tiempo pausa entre imágenes'>
					<?php echo $row->PAUSE_SECONDS; ?> segundos
				</a>
			</div>

			<div class='cp_table100'>
				<a href='index.php?<?php echo $com_url; ?>&album=<?php echo $album; ?>&action=Editspeed' title='Cambiar tiempo pausa entre imágenes'>
					<?php echo $row->SPEED; ?> segundos
				</a>
			</div>	
		
			<div class='cp_table75'>
				<a href='index.php?<?php echo $com_url; ?>&album=<?php echo $album; ?>&action=Changesize' title='Cambiar tamaño del banner'>
					a: <?php echo $row->WIDTH; ?>
				</a>
			</div>
			<div class='cp_table75'>
				<a href='index.php?<?php echo $com_url; ?>&album=<?php echo $album; ?>&action=Changesize' title='Cambiar tamaño del banner'>
					h: <?php echo $row->HEIGHT; ?>
				</a>
			</div>
		<?php 
		if (allowed($mnu) != 1) { ?>
			<div class='cp_table25'>
				<img class='image' src='images/edit_off.png' alt='' title='No tiene permisos para realizar esta acción' />
			</div>
		<?php } else { ?>
			<div class='cp_table25'>
				<a href='index.php?<?php echo $com_url; ?>&album=<?php echo $album; ?>&action=Editbanner'>
					<img class='image' src='images/edit.png' alt='Renombrar banner' title='Renombrar banner' />
				</a>
			</div>
		<?php }
		$images = checkingSection("slider", "IDALBUM", $album); 
		
		if($images == 0) { 
			$msgAlert = "¡ATENCI&Oacute;N! Va a eliminar el banner ".stripslashes($row->TITLE)." &iquest;Desea continuar?";
			$urlAlert = "modules/slide/delete_album.php?&album=".$album;
		?>
			<div class='cp_table25'>
				<img class='image pointer' src='images/delete.png' alt='Eliminar banner' title='Eliminar banner' onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
			</div>
		<?php }else { ?>
			<div class='cp_table25'>
				<img class='image pointer' src='images/delete_off.png' alt='Banner bloqueado' title='Banner bloqueado' onclick='alert("¡ATENCI&Oacute;N! No puede eliminar el banner <?php echo stripslashes($row->TITLE); ?> forma parte del diseño de la web.");' />
			</div>	
		<?php }?>		
		</div>
<?php } ?>
	<br/>
	<div class='cp_table500'>&nbsp;</div>
	<div class='cp_table150'>&nbsp;</div>
	<div class='cp_table25'>&nbsp;</div>
	<?php 
	if (allowed($mnu) == 1) { ?>
		<div class='cp_table25 new_section_ie'>
			<a href='index.php?<?php echo $com_url; ?>&action=Createbanner'>
				<img class='image' src='images/add.png' alt='Crear banner' title='Crear banner' />
			</a>
		</div>
	<?php }
	else { ?>
		<div class='cp_table25 new_section_ie'>
			<img class='image' src='images/add_off.png' alt='' title='' />
		</div>
	<?php } ?>
	</div>
</div>
	