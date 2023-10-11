<?php 
if($type=="mobile") {
	$img_slider = "mobile/1-".$item->IMAGE_MOBILE;
	$img_slider_bg = "mobile/bg-".$slider["hook"]->IDCHAR.".jpg";
	$widthSlider = $slider["hook"]->WIDTH_MOBILE;
	$heightSlider = $slider["hook"]->HEIGHT_MOBILE;
}else {
	$img_slider = "image/1-".$item->IMAGE;
	$img_slider_bg = "image/bg-".$slider["hook"]->IDCHAR.".jpg";
	$widthSlider = $slider["hook"]->WIDTH;
	$heightSlider = $slider["hook"]->HEIGHT;
}
?>
<li>
<?php if($item->TYPE == "image") { ?>
		<div class="item <?php echo $item->TYPE; ?> transition" style="background-image:url(<?php echo DOMAIN; ?>files/multislide/<?php echo $img_slider; ?>);">
			<img class="img-video hidden-block" src="<?php echo DOMAIN; ?>files/multislide/<?php echo $img_slider_bg; ?>" alt="<?php echo $item->TITLE; ?>" />
		</div>
<?php }else if($item->TYPE == "video" && $type == "pc") { ?>
		<div class="item <?php echo $item->TYPE; ?> transition" style="background-color:#009975;">
			<iframe class="embed-player slide-media" src="https://www.youtube.com/embed/<?php echo $item->VIDEO; ?>?&playlist=<?php echo $item->VIDEO; ?>&controls=0&showinfo=0&rel=0&autoplay=1&loop=1&mute=1" frameborder="0"></iframe>
		</div>
<?php }else if($item->TYPE == "video" && $type == "mobile") { ?>
		<div class="item image transition" style="background-image:url(<?php echo DOMAIN; ?>files/multislide/<?php echo $img_slider; ?>);">
			<img class="img-video hidden-block" src="<?php echo DOMAIN; ?>files/multislide/<?php echo $img_slider_bg; ?>" alt="<?php echo $item->TITLE; ?>" />
		</div>
<?php } ?>
</li>