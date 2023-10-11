	<ul id="social-networks">
		<?php require_once ("includes/class/goo.gl.php"); 
		
			if($view == "article") {
				$url = DOMAIN.$view_article->slug;	
				$class = "orange";
			}elseif($view == "blog") {
				$url = DOMAIN.slugBlog.$view_article->slug;
				$class = "grayNormal";
			}
			
		?>
		<li id="share-whatsapp" class="icon-redes transition">
			<?php $urlt = goo_gl_short_url($url."?&rs=whatsapp"); ?>
			<a class="transition" href="whatsapp://send?text=<?php echo urlencode($view_article->tA); ?>%20<?php echo $urlt; ?>" alt="Compartir en WhatsApp" title="Compartir en WhatsApp">
				<i class="fa fa-whatsapp <?php echo $class; ?> transitionSlow" aria-hidden="true"></i>
			</a>
		</li>
		<li class="icon-redes transition">
			<?php $urlt = goo_gl_short_url($url."?&rs=linkedin"); ?>
			<a class="transition" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $urlt; ?>&title=<?php echo $view_article->tA; ?>&summary=<?php echo strip_tags($view_article->sumaryA); ?>&source=Blog_IHP_Pediatria" alt="Compartir en Linkedin" title="Compartir en Linkedin" target="_blank">
				<i class="fa fa-linkedin-square <?php echo $class; ?> transitionSlow" aria-hidden="true"></i>
			</a>
		</li>
		<?php /*
		<li class="icon-redes transition">
			<?php $urlt = goo_gl_short_url($url."?&rs=googleplus"); ?>
			<a class="transition" href="https://plus.google.com/share?url=<?php echo $urlt; ?>" target="_blank" alt="Compartir en Google+" title="Compartir en Google+">
				<i class="fa fa-google-plus-square <?php echo $class; ?> transitionSlow" aria-hidden="true"></i>
			</a>
		</li>
		*/ ?>
		<li class="icon-redes transition">
			<?php $urlt = goo_gl_short_url($url."?&rs=twitter"); ?>
			
			<a class="transition" href="https://twitter.com/intent/tweet?original_referer=<?php echo $url; ?>&tw_p=tweetbutton&url=<?php echo $urlt; ?>&via=IHPpediatria&text=<?php echo urlencode($view_article->tA); ?>" alt="Compartir en Twitter" title="Compartir en Twitter" target="_blank">
				<i class="fa fa-twitter-square <?php echo $class; ?> transitionSlow" aria-hidden="true"></i>
			</a>
		</li>	
		<li class="icon-redes transition">
			<?php $urlt = $url."?&rs=facebook"; ?>
			<a href="https://www.facebook.com/sharer.php?u=<?php echo $url; ?>" target="_blank">
				<i class="fa fa-facebook-square <?php echo $class; ?> transitionSlow" aria-hidden="true"></i>
			</a>
		</li>
	</ul>