
	<iframe id="youtube-<?php echo $paragraph->ID; ?>" width="100%" height="auto" style="z-index:3;" src="//www.youtube.com/embed/<?php echo $paragraph->VIDEO; ?>?rel=0&autohide=1&showinfo=0&color=white&fs=1&autoplay=0" frameborder="0" allowfullscreen></iframe>
	<script type="text/javascript">
		$(document).ready(function(){
			var w = parseInt($("#youtube-<?php echo $paragraph->ID; ?>").width());
			var h = (w*9)/16;//formato 16:9
			$("#youtube-<?php echo $paragraph->ID; ?>").height(h+"px");
		});
		var resizeTimeryu<?php echo $paragraph->ID; ?>;
		$(window).resize(function() {
			clearTimeout(resizeTimeryu<?php echo $paragraph->ID; ?>);
			resizeTimeryu<?php echo $paragraph->ID; ?> = setTimeout(function() {
				var w = parseInt($("#youtube-<?php echo $paragraph->ID; ?>").width());
				var h = (w*9)/16;//formato 16:9
				$("#youtube-<?php echo $paragraph->ID; ?>").height(h+"px");
			});
		});
	</script>
	<?php if($paragraph->FOOT != "" && $paragraph->FOOT != null): ?>	
		<div class="foot-youtube-video bgRed white textBox">
			<div><?php echo stripslashes($paragraph->FOOT); ?></div>
		</div>
	<?php endif; ?>