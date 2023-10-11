<div class="cp_box">
	<div class='cp_table250 cp_title'>Galer&iacute;a</div>
	<div class='cp_table250 cp_title'>Álbum</div>
	<div class='cp_table150 cp_title'>Miniatura</div>
<?php	
	$i=0;
	while($i<2) { ?>
		<div class='cp_table25 cp_title'>&nbsp;</div>
		<?php $i++;
	} ?>
	<br/>
	<br/>
	<?php
	$q = "SELECT * FROM ".preBD."images_gallery ORDER BY TITLE ASC";
	$result = checkingQuery($connectBD, $q);
	while($row = mysqli_fetch_array($result)) {
		
		$gallery = $row['ID'];
		$seccion = $row['IDGALLERYSECTION'];
		
		$qS = "select * from ".preBD."images_gallery_style where IDGALLERY = " . $gallery;
		$resultS = checkingQuery($connectBD, $qS);
		$styles = mysqli_fetch_assoc($resultS); 
		
		$q3 = "select * from ".preBD."images_gallery_sections where ID = " . $seccion;
		$result3 = checkingQuery($connectBD, $q3);
		$section = mysqli_fetch_assoc($result3); 		
		?>
		
		<div class='cp_table'>
			<div class='cp_table250'>
				<a href='index.php?mnu=content&com=gallery&tpl=option&opt=gallery&gallery=<?php echo $gallery; ?>&action=Editgallery' title='<?php echo $row['DESCRIPTION']; ?>'>
					<?php echo $row['TITLE']; ?>
				</a>
			</div>
		
			<div class='cp_table250'>
				<a href='index.php?mnu=content&com=gallery&tpl=option&opt=gallery&gallery=<?php echo $gallery; ?>&action=Editgallery' title='<?php echo $row['DESCRIPTION']; ?>'>
					<?php echo $section['TITLE']; ?>
				</a>
			</div>
			
			<div class='cp_table150'>
				<a href='index.php?mnu=content&com=gallery&tpl=option&opt=gallery&gallery=<?php echo $gallery; ?>&action=Editstyles' title='Editar estilos'>
					<?php echo $styles['WIDTH_THUMB']; ?> x <?php echo  $styles["HEIGHT_THUMB"]; ?> px
				</a>
			</div>
			<?php 
			if (allowed($mnu) != 1) { ?>
				<div class='cp_table25'>
					<img class='image' src='images/edit_off.png' title='Renombrar galería' />
				</div>
			<?php }
			else { ?>
				<div class='cp_table25'>
					<a href='index.php?mnu=content&com=gallery&tpl=option&opt=gallery&gallery=<?php echo $gallery; ?>&action=Editgallery'>
						<img class='image' src='images/edit.png' alt='Renombrar galería' title='Renombrar galería' />
					</a>
				</div>
			<?php }
			$numImage = checkingSection("images", "IDGALLERY", $gallery);
			if (allowed($mnu) != 1 || $numImage > 0) { ?>
				<div class='cp_table25'>
					<img class='image' src='images/delete_off.png' title='<?php if($numImage->total > 0){echo "Debe vaciar la galería para poder borrarla.";}if(allowed($mnu) != 1){echo "No tiene permisos para realizar esta acción.";} ?>' />
				</div>
			<?php }else { 
				$urlAlert = "modules/gallery/delete_gallery.php?mnu=".$mnu."&gallery=". $gallery;
				$msgAlert = "¡ATENCIÓN! Va a eliminar la galería ".stripslashes($row['TITLE']);
			?>
				<div class='cp_table25'>
					<img class='image pointer' src='images/delete.png' alt='Eliminar galería' title='Eliminar galería' onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
				</div>
			<?php } ?>
		</div>
		<br/>
	<?php } ?>
		<br/>
	<div class='cp_table250'>&nbsp;</div>
	<div class='cp_table250'>&nbsp;</div>
	<div class='cp_table150'>&nbsp;</div>
	<div class='cp_table25'>&nbsp;</div>
	<?php 
	if (allowed($mnu) == 1) { ?>
		<div class='cp_table25 new_section_ie'>
			<a href='index.php?mnu=content&com=gallery&tpl=option&opt=gallery&action=Creategallery'>
				<img class='image' src='images/add.png' alt='Crear galería' title='Crear galería' />
			</a>
		</div>
	<?php }
	else { ?>
		<div class='cp_table25 new_section_ie'>
			<img class='image' src='images/add_off.png' alt='' title='' />
		</div>
	<?php } ?>
	<br/>
	<br/>
</div>