<div class="cp_box">
	<div class='cp_table650 cp_title'>Álbum</div>
<?php	
	$i=0;
	while($i<2) { ?>
		<div class='cp_table25 cp_title'>&nbsp;</div>
		<?php $i++;
	} ?>
	<br/>
	<br/>
	<?php
	$q = "SELECT * FROM ".preBD."images_gallery_sections ORDER BY TITLE ASC";
	$result = checkingQuery($connectBD, $q);
	while($row = mysqli_fetch_array($result)) { 
		$total_gallery = checkingSection("images_gallery","IDGALLERYSECTION", $row["ID"]);
	?>
		
		<div class='cp_table'>
			<div class='cp_table650'>
				<a href='index.php?mnu=content&com=gallery&tpl=option&opt=album&section=<?php echo $row['ID']; ?>&action=Editsection' title='<?php echo $row['TITLE']; ?>'>
					<?php echo $row['TITLE']; ?>
				</a>
			</div>
		<?php if (allowed($mnu) != 1) { ?>
			<div class='cp_table25'>
				<img class='image' src='images/edit_off.png' alt='' title='' />
			</div>
		<?php }	else { ?>
			<div class='cp_table25'>
				<a href='index.php?mnu=content&com=gallery&tpl=option&opt=album&section=<?php echo $row['ID']; ?>&action=Editsection'>
					<img class='image' src='images/edit.png' alt='Renombrar sección' title='Renombrar sección' />
				</a>
			</div>
		<?php }
			if (allowed($mnu) != 1) { ?>
			<div class='cp_table25'>
				<img class='image' src='images/delete_off.png' title='No tiene permisos para esta acción.' />
			</div>
		<?php }elseif($total_gallery>0){ ?>
			<div class='cp_table25'>
				<img class='image' src='images/delete_off.png' title='Debe vaciar el albúm de galerias para poder eliminarlo' />
			</div>
		<?php }else { 
			$msgAlert = "¡ATENCIÓN! Va a eliminar el álbum ".$row["TITLE"];
			$urlAlert = "modules/gallery/delete_album.php?mnu=".$mnu."&album=".$row['ID'];
		?>
			<div class='cp_table25'>
				<img class='image pointer' src='images/delete.png' alt='Eliminar sección' title='Eliminar sección' onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
			</div>
		<?php } ?>
		</div>
		<br/>
	<?php } ?>
		<br/>
	<div class='cp_table650'>&nbsp;</div>
	<div class='cp_table25'>&nbsp;</div>
	<?php 
	if (allowed($mnu) == 1) { ?>
		<div class='cp_table25 new_section_ie'><a href='index.php?mnu=content&com=gallery&tpl=option&opt=album&action=Createsection'><img class='image' src='images/add.png' alt='Crear sección' title='Crear sección' /></a></div>
	<?php }
	else { ?>
		<div class='cp_table25 new_section_ie'><img class='image' src='images/add_off.png' alt='' title='' /></div>
	<?php } ?>
	<br/>
	<br/>
</div>