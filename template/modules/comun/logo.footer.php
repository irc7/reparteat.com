<!-- Home Page
    ==========================================-->
<?php
	$q = "select * from ".preBD."slider where IDALBUM = 6 and STATUS = 1 order by POSITION asc";
	$r = checkingQuery($connectBD, $q);
	$totalImg = mysqli_num_rows($r);
	$widthBox = ceil(12/$totalImg);
?>	

    <div id="wrap-logos-home">
	<?php while($slide = mysqli_fetch_object($r)) { ?>
			<div class="col-xs-<?php echo $widthBox; ?> no-padding">
			<?php if($slide->TARGET != "_none") { ?>
					<a class="transitionSlow" href="<?php echo $slide->LINK; ?>" target="<?php echo $slide->TARGET; ?>" title="Ir a <?php echo $slide->TITLE; ?>">
			<?php } ?>
					<img class="img-responsive transitionSlow" src="<?php echo DOMAIN; ?>files/slide/image/<?php echo $slide->IMAGE; ?>" />
			<?php if($slide->TARGET != "_none") {	?>
					</a>
			<?php } ?>
			</div>
	<?php } ?>
    </div>
