<?php //DESCARGAS
	$q_d = "select * from ".preBD."paragraphs_file";
	if($view=="preview") {
		$q_d .= "_temp";	
	}
	$q_d .= " where IDPARAGRAPH = '" . $paragraph->ID;
	$q_d .= "' order by TITLE asc";
	
	if(!checkingQuery($connectBD,$q_d)){
		die("Error(file paragraphs): " . mysqli_error());	
	}
	$result_d = checkingQuery($connectBD,$q_d);
	while($row_down = mysqli_fetch_object($result_d)):
		preg_match("|\.([a-z0-9]{2,4})$|i", $row_down->URL, $ext);
?>
	<div class="title-download col-md-12 no-padding">
	<?php 
		$name_icon = "icon_".$ext[1].".png";
		$url_icon="files/download/icon/".$name_icon;
		if(!file_exists($url_icon)){
			$name_icon = "icon_unk.gif";
		}
		echo "<div class='col-md-3 no-padding'><img src='".DOMAIN.$url_icon."' style='border: none;max-width:100%;' /></div>\r\n";
	?>
		<div class='col-md-9 no-padding'>
			<div class='col-md-12 no-padding'>
				<strong class="orange">
				<?php if($row_down->TITLE != ""){
					echo $row_down->TITLE;
				}else{ 
					$var = explode("-", $row_down->URL);
					for($i=1;$i<count($var);$i++){
						echo $var[$i];
					}
				} ?>
				</strong>&nbsp;<span class="size_file">(<?php echo $row_down->SIZE; ?>)</span>
				
			</div>
				<a class="link-download-article transition" href="<?php echo DOMAIN; ?>template/modules/article/downloader.php?file=<?php echo $row_down->ID; ?>" title="Descargar <?php echo $row_down->TITLE; ?>">
					<i class="fa fa-cloud-download" aria-hidden="true"></i>
				</a>
			<?php if($row_down->ID != 2){ ?>
				<a class="link-download-article transition" href="<?php echo DOMAIN; ?>files/articles/doc/<?php echo $row_down->URL; ?>" title="Ver <?php echo $row_down->TITLE; ?>" target="_blank">
					<i class="fa fa-eye" aria-hidden="true"></i>
				</a>
			<?php } ?>
		</div>
		
	</div>
<?php endwhile;//FIN DESCARGAS ?>  