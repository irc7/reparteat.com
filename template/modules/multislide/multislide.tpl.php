<div id="slider-multislide-pc" class="reparteat-slider-multislide slider-reparteat-home <?php echo $slider["hook"]->IDCHAR; ?>">
  <ul class="slides">
	<?php 
		$i = 1;
		foreach($slider["items"] as $item) { 
			$type="pc";		
			include("template/modules/multislide/multislide.partials.tpl.php");
		$i++;
		} 
	?>
</div>
<div id="slider-multislide-mobile" class="reparteat-slider-multislide slider-reparteat-home <?php echo $slider["hook"]->IDCHAR; ?>">
  <ul class="slides">
	<?php 
		$i = 1;
		foreach($slider["items"] as $item) { 
			$type="mobile";		
			include("template/modules/multislide/multislide.partials.tpl.php");
		$i++;
		} 
	?>
</div>
<div class="separator50"></div>
<style type="text/css">
	#slider-multislide-pc {
		max-width:<?php echo $slider["hook"]->WIDTH; ?>px;
		max-height:<?php echo $slider["hook"]->HEIGHT; ?>px;
	}
	#slider-multislide-mobile {
		max-width:<?php echo $slider["hook"]->WIDTH_MOBILE; ?>px;
		max-height:<?php echo $slider["hook"]->HEIGHT_MOBILE; ?>px;
	}
</style>
<script type="text/javascript" charset="utf-8">
	$(window).load(function() {
		$('.reparteat-slider-multislide').flexslider({
			animation: "fade",
			slideshow: true,
			easing: "swing",
			animationLoop: true,
			smoothHeight: false,
			slideshowSpeed: <?php echo $slider["hook"]->PAUSE_SECONDS; ?>,
			animationSpeed: <?php echo $slider["hook"]->SPEED*1000; ?>,
			randomize: false,
			pauseOnAction: false, 
			pauseOnHover: false, 
			touch: true, 
			video: false, 
			controlNav: false,
			directionNav: false, 
			prevText: "Anterior", 
			nextText: "Siguiente",
		});
		claculateHeightVideo();
	});
	$(window).resize(function() {
		claculateHeightVideo();
	});	
	function claculateHeightVideo() {
		var prop = 16/9;
		var hbox = parseInt($("header#home").css("height"));
		var wbox = parseInt($("header#home").css("width"));
		var propBox = wbox/hbox;
		if(prop > propBox) {
			//calcular width
			var h = hbox;
			var w = h * 1.777778;
		}else if(prop < propBox) {
			//calcular height
			var w = wbox;
			var h = w * 0.5625;
		}
		var t = hbox - h;
		if(t < 0) {
			var top = t/2;
		}else{
			var top = 0;
		}
		var l = wbox - w;
		if(l < 0) {
			var left = l/2;
		}else{
			var left = 0;
		}
		$(".item.video iframe").css("width", w+"px");
		$(".item.video iframe").css("height", h+"px");
		$(".item.video iframe").css("top", top+"px");
		$(".item.video iframe").css("left", left+"px");
		
	}
</script>