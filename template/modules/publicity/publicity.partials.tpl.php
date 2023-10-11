<?php 
if($type=="mobile") {
	$img_slider = "mobile/1-".$item->IMAGE_MOBILE;
	$img_slider_bg = "mobile/bg-".$publicity["hook"]->IDCHAR.".jpg";
	$widthSlider = $publicity["hook"]->WIDTH_MOBILE;
	$heightSlider = $publicity["hook"]->HEIGHT_MOBILE;
}else {
	$img_slider = "image/1-".$item->IMAGE;
	$img_slider_bg = "image/bg-".$publicity["hook"]->IDCHAR.".jpg";
	$widthSlider = $publicity["hook"]->WIDTH;
	$heightSlider = $publicity["hook"]->HEIGHT;
}
?>
<li>
<?php if($item->LINK != "") { ?>
	<a href="<?php echo $item->LINK; ?>" target="<?php echo $item->TARGET; ?>">
<?php } ?>
<?php if($item->TYPE == "all") { ?>
		<div class="item <?php echo $item->TYPE; ?> transition">
			<img class="img-responsive transitionVerySlow" src="<?php echo DOMAIN; ?>files/publicity/<?php echo $img_slider; ?>" alt="<?php echo $item->TITLE; ?>" />
			<section class="text-caption">
				<h3><?php echo $item->TITLE; ?></h3>
				<p><?php echo $item->TEXT; ?></p>
			</section>
		</div>
<?php }else if($item->TYPE == "image") { ?>
		<div class="item <?php echo $item->TYPE; ?> transition">
			<img class="img-responsive transitionVerySlow" src="<?php echo DOMAIN; ?>files/publicity/<?php echo $img_slider; ?>" alt="<?php echo $item->TITLE; ?>">
		</div>
		<?php }else if($item->TYPE == "text") { ?>
		<div class="item <?php echo $item->TYPE; ?> transition" style="background-color:#009975;">
			<img class="img-responsive hidden-block" src="<?php echo DOMAIN; ?>files/publicity/<?php echo $img_slider_bg; ?>" alt="<?php echo $item->TITLE; ?>" />
			<section class="text-caption">
				<h3><?php echo $item->TITLE; ?></h3>
				<p><?php echo $item->TEXT; ?></p>
			</section>
		</div>
<?php } ?>
<?php if($item->LINK != "") { ?>
	</a>
<?php } ?>
</li>