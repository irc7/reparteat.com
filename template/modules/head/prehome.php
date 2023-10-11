<style type="text/css">
#wrapperfull-hover {
	width:100%;
	position:absolute;
	height:100%;
	background-color:#fff;
	z-index:100000000;
	position:fixed;
}
#preloader {
    width: 100%;
    height: 100%;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background: #fff;
    z-index: 100000001;
    position: fixed;
}
#preloader img{
	position:relative;
	top:45%;
}
/*
.expand { 
	width:100%; 
	height:1px; 
	margin:25% 0; 
	background-color:#502a78; 
	position:absolute;
	box-shadow:0px 0px 10px 1px rgba(0,198,255,0.7);
    -moz-animation:fullexpand 2000ms cubic-bezier(1.000, 0.610, 0.970, 0.000); 
	-webkit-animation:fullexpand 2000ms cubic-bezier(1.000, 0.610, 0.970, 0.000);
}*/

@-moz-keyframes fullexpand {
	0%  { width:0px;}
	100%{ width:100%;}	
}
@-webkit-keyframes fullexpand {
	0%  { width:0px;}
	100%{ width:100%;}	
}
</style>
<script src="http://code.jquery.com/jquery-1.6.4.min.js" type="text/javascript"></script>
<script>
var $preLoad = jQuery.noConflict();
$preLoad(document).ready(function(){
	var ua = $preLoad.browser;
	//$preLoad('#wrapperFull').hide();
	//$preLoad('#test').hide();
	
	
	if (ua.mozilla && ua.version <='5.0' || ua.safari && (navigator.appVersion.indexOf('3.') != -1) || ua.opera || ua.msie){
	
		Resolution("<?php echo $moduleView; ?>");
		$eo(window).bind("resize", function() {
			Resolution("<?php echo $moduleView; ?>");
		});
		<?php if($view == "home"): //redimension segun el numero de banner fijos ?>
			var numBanners = $eo("#wrapper-info-banner2 .banner-info2").size();
			var heighItem = $eo("#wrapper-info-banner2 .banner-info2").height();
			var margin = $eo("#wrapper-info-banner2 .banner-info2").pixels("marginBottom");
			heighB = (heighItem*numBanners) + (margin * (numBanners - 1));
			$eo("#wrapper-info-banner2 .banner-info2").height(heighItem+"px");
			$eo("#wrapper-info-banner2").height(heighB+"px");
		<?php endif; ?>
		//$preLoad('#wrapperFull').show();
		$preLoad('#wrapperfull-hover').fadeOut('');    
	}else {
		$preLoad('body').append('<center><div id="preloader"><img src="<?php echo DOMAIN; ?>css/images/body/loading.gif" /></div></center>');
		$preLoad('#preloader').delay(2000).fadeOut('slow', function() {
		
		//$preLoad('#wrapperFull').css('visibility', 'visible');  
		//$preLoad('#wrapperFull').fadeIn('fast');  
		$preLoad('#wrapperfull-hover').fadeOut('');    
		
		
		Resolution("<?php echo $moduleView; ?>");
		$eo(window).bind("resize", function() {
			Resolution("<?php echo $moduleView; ?>");
		});
		<?php if($view == "home"): //redimension segun el numero de banner fijos ?>
			var numBanners = $eo("#wrapper-info-banner2 .banner-info2").size();
			var heighItem = $eo("#wrapper-info-banner2 .banner-info2").height();
			var margin = $eo("#wrapper-info-banner2 .banner-info2").pixels("marginBottom");
			heighB = (heighItem*numBanners) + (margin * (numBanners - 1));
			$eo("#wrapper-info-banner2 .banner-info2").height(heighItem+"px");
			$eo("#wrapper-info-banner2").height(heighB+"px");
		<?php endif; ?>
	});  	
	}
});
</script>