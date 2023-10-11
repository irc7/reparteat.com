<?php if($paragraph->IMAGE != ""): 
		$urlImg = "files/articles/image/".$paragraph->IMAGE; 
		$sizeImg = getimagesize($urlImg);
?>

    <div id="image-block<?php echo $paragraph->POSITION; ?>" class="image-block" align="<?php echo $float_box; ?>" style="<?php if($float_box == "center"): ?>text-align:center;<?php else: ?>width:auto;float:<?php echo $float_box; ?>;<?php endif;echo $margin.$styleAux; ?>">
       <?php $Link_img = stripslashes($paragraph->LINK);
                if($Link_img != "" && $Link_img != NULL && $Link_img != "http://"): ?>
                <a href="<?php echo $Link_img; ?>" target="<?php echo $paragraph->TARGET; ?>">
        <?php endif; ?>
    
        <img class="image-art img-responsive" id="image-art-<?php echo $paragraph->POSITION; ?>" src="<?php echo DOMAIN.$urlImg; ?>" alt="<?php echo $view_article->tA; ?>" title="<?php echo $view_article->tA; ?>" align="<?php echo $float_box; ?>" />
        
        <?php if($Link_img != "" && $Link_img != NULL && $Link_img != "http://"){echo "</a>";} ?>
        <?php if($paragraph->FOOT != ""): ?>
            <div id="foot-image<?php echo $paragraph->POSITION; ?>" class="foot-image">
				<div class="white <?php if($view_article->TYPE == "article"){echo "titleLight";}else{echo "textBox";} ?>"><?php echo stripslashes($paragraph->FOOT); ?></div>
            </div>
        <?php endif; ?>
		
    </div>
	<?php if($activeScript): ?>
		<script type="text/javascript">
			$(document).ready(function (){
					var WTotal = $("#wrap-column-center").width(); //ancho parcial
					
					var wImg = <?php echo $sizeImg[0]; ?>;
					var hImg = <?php echo $sizeImg[1]; ?>;
					
					var prop = hImg / wImg;
				<?php if($float_box == "none"): ?>
					$("#image-block<?php echo $paragraph->POSITION; ?>").removeAttr("width");
				
					var wPor = (wImg * 100) / WTotal;
					if(wPor > 100) {
						var wPor = 100;
					}
					var wBoxPX = WTotal * wPor / 100;
				<?php else:?>
					var pTotalAux = <?php echo $view_article->size_LR; ?> * 100 / maxWidth; //ancho en porcentaje de la alineación lateral
					
					var wBoxPX = WTotal * pTotalAux / 100;//ancho en px de la caja 
					if(wBoxPX > wImg) {
						wBoxPX = wImg;
					}
					
				<?php endif; ?>
				if((isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows()) && WTotal < 768) {
					WTotal = WTotal-20;
					var hBoxPX = WTotal * prop;
					$("#image-block<?php echo $paragraph->POSITION; ?>").width(WTotal+"px"); //damos ancho caja imagen
					$("#image-block<?php echo $paragraph->POSITION; ?>").height(hBoxPX+"px"); //altura caja imagen
					$("#image-art-<?php echo $paragraph->POSITION; ?>").width("100%"); //damos ancho imagen
					$("#text_block<?php echo $paragraph->POSITION; ?>").css("min-height", hBoxPX+"px"); //altura caja imagen
				}else{
					var hBoxPX = wBoxPX * prop;
					$("#image-block<?php echo $paragraph->POSITION; ?>").width(wBoxPX+"px"); //damos ancho caja imagen
					$("#image-block<?php echo $paragraph->POSITION; ?>").height(hBoxPX+"px"); //altura caja imagen
					$("#image-art-<?php echo $paragraph->POSITION; ?>").width("100%"); //damos ancho imagen
					$("#text_block<?php echo $paragraph->POSITION; ?>").css("min-height", hBoxPX+"px"); //altura caja imagen
				}
				
			});
		</script>
		
	<?php endif; ?>
<?php endif; ?>
