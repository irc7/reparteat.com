<?php 
	if (allowed($mnu)) { 
			
	?>
				<div class="cp_mnu_title title_header_mod">Comentarios</div>
			<?php	if (isset($_GET['msg'])) {
					$msg = utf8_encode($_GET['msg']); ?>
					<div class="cp_info"><img class="cp_msgicon" src="images/info.png" alt="¡INFORMACIÓN!" /><?php echo $msg ?></div>
					<br/>
				<?php }
				include ("components/articles/articles.list.comment.php");
				if (isset($_GET["action"])) {
					$action = trim($_GET["action"]);
					
					// VIEW COMMENT
					if ($action == "Viewcomment") { ?>
						<br/>
						<div style="color:#333;clear:both;margin-bottom:10px;">
							<div class="cp_table" style="width:100px;color:#666;">Autor:</div>
							<?php echo $Name ?>
							<br />
							<div class="cp_table" style="color:#666;width:100px;">Fecha:</div> 
							<?php echo $Date ?>
							<br />
							<div class="cp_table" style="width:100px;color:#666;">IP del autor:</div> <?php echo $Ip ?>
							<br />
						</div>
						<div class="cp_box dotted">
							<div style="color:#333;">
								<div class="cp_table" style="width:100px;color:#666">Comentario:</div>
								<div style="color:#333;display:block;clear:both;">
									<?php echo $Text ?>
								</div>
							</div>
						</div>
						
							<div class="cp_table">
								<?php if($Status == 1) { 
										$msgAlert = "Va a despublicar el comentario para el artículo " . $postBD->TITLE . "¿Está seguro?";
										$urlAlert = "modules/articles/publish_comment.php?record=".$id_comment."&status=0";
								?>
									<input class="pointer" type="submit" value="Despublicar" onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
								<?php }else{ 
									$msgAlert = "Va a publicar el comentario para el artículo " . $postBD->TITLE . "¿Está seguro?";
									$urlAlert = "modules/articles/publish_comment.php?record=".$id_comment."&status=1";
								?>
									<input class="pointer" type="submit" value="Publicar" onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
								<?php } ?>
							</div>
						
							<div class="cp_table" style="float:right !important;">
								<?php 
									$msgAlert = "Va a eliminar el comentario para el artículo " . $postBD->TITLE . "¿Está seguro?";
									$urlAlert = "modules/articles/delete_comment.php?record=".$id_comment;
								?>
								<input class="pointer" type="submit" value="Eliminar" onclick="alertConfirm('<?php echo $msgAlert; ?>', '<?php echo $urlAlert; ?>');" />
							</div>			
					<?php }
				}
			
	}else{
		echo "<p>No tiene permiso para acceder a esta sección.</p>";
	}
?>	