<div id="slider-publicity-pc" class="reparteat-slider-publicity slider-reparteat <?php echo $publicity["hook"]->IDCHAR; ?>">
  <ul class="slides">
	<?php 
		$i = 1;
		foreach($publicity["items"] as $item) { 
			$type="pc";		
			include("template/modules/publicity/publicity.partials.tpl.php");
		$i++;
		} 
	?>
</div>
<div id="slider-publicity-mobile" class="reparteat-slider-publicity slider-reparteat <?php echo $publicity["hook"]->IDCHAR; ?>">
  <ul class="slides">
	<?php 
		$i = 1;
		foreach($publicity["items"] as $item) { 
			$type="mobile";		
			include("template/modules/publicity/publicity.partials.tpl.php");
		$i++;
		} 
	?>
</div>
<div class="separator50"></div>
<style type="text/css">
	#slider-publicity-pc {
		
		max-width:<?php echo $publicity["hook"]->WIDTH; ?>px;
		max-height:<?php echo $publicity["hook"]->HEIGHT; ?>px;
	}
	#slider-publicity-mobile {
		
		max-width:<?php echo $publicity["hook"]->WIDTH_MOBILE; ?>px;
		max-height:<?php echo $publicity["hook"]->HEIGHT_MOBILE; ?>px;
	}
</style>
<script type="text/javascript" charset="utf-8">
  $(window).load(function() {
    $('.reparteat-slider-publicity').flexslider({
		animation: "fade",
		slideshow: true,
		easing: "swing",
		animationLoop: true,
		smoothHeight: false,
		slideshowSpeed: <?php echo $publicity["hook"]->PAUSE_SECONDS; ?>,
		animationSpeed: <?php echo $publicity["hook"]->SPEED*1000; ?>,
		randomize: false,
		pauseOnAction: true, 
		pauseOnHover: true, 
		touch: true, 
		video: false, 
		controlNav: false,
		directionNav: false, 
		prevText: "Anterior", 
		nextText: "Siguiente",
	});
  });
</script>