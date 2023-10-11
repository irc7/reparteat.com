<?php $file = extFile($paragraph->VIDEO); ?> 

	<video id="video-<?php echo $paragraph->ID; ?>" class="video-article" controls poster="<?php  DOMAIN; ?>files/articles/thumb/<?php echo $paragraph->IMAGE;?>">
		 <source src="<?php echo DOMAIN; ?>files/articles/video/<?php echo $paragraph->VIDEO;?>" type="<?php echo $file->TYPE; ?>">
	</video>
	<script type="text/javascript">
		$(document).ready(function(){
			var w = parseInt($("#video-<?php echo $paragraph->ID; ?>").width());
			var h = (w*9)/16;//formato 16:9
			$("#video-<?php echo $paragraph->ID; ?>").height(h+"px");
		});
		var resizeTimervp<?php echo $paragraph->ID; ?>;
		$(window).resize(function() {
			clearTimeout(resizeTimervp<?php echo $paragraph->ID; ?>);
			resizeTimervp<?php echo $paragraph->ID; ?> = setTimeout(function() {
				var w = parseInt($("#video-<?php echo $paragraph->ID; ?>").width());
				var h = (w*9)/16;//formato 16:9
				$("#video-<?php echo $paragraph->ID; ?>").height(h+"px");
			});
		});
	</script>
<?php if($paragraph->FOOT != "" && $paragraph->FOOT != null): ?>	
	<div class="foot-youtube-video bgRed white textBox">
		<div><?php echo stripslashes($paragraph->FOOT); ?></div>
	</div>
<?php endif; ?>