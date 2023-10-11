<?php
	$q = "select * from ".preBD."slider 
				where STATUS = 1
				and (DATE_START <= NOW() or (DATE_START <= NOW() and DATE_END <> DATE_START and DATE_END >= NOW()))
			order by DATE_START desc limit 0,1";
	
	$rS = checkingQuery($connectBD,$q);
	if($popup = mysqli_fetch_object($rS)) { 
?>

	<div id="wrap-popup" class="bgBlackOpacity transition">
		<div id="box-popup" class="bgGreen transition" style="background-image:url(<?php echo DOMAIN; ?>files/slide/image/<?php echo $popup->IMAGE; ?>)">
			<i id="popup-btn" class="fa fa-close yellow floatRight transition"></i>
			<div class="popup-text-all bgWhiteOpacity">
				<h2 class="popup-title arial textCenter green"><?php echo $popup->TITLE; ?></h2>
				<h4 class="popup-subtitle arial textCenter green"><?php echo $popup->SUBTITLE; ?></h4>
				<div class="popup-text arial textCenter">
					<?php echo $popup->TEXT; ?>
				</div>
				<div class="separator20"></div>
				<div class="textCenter">
				<?php if($popup->TARGET != "_none" && $popup->LINK != "") { ?>
					<a class="btn btn-primary arialBold btn-search" href="<?php echo $popup->LINK; ?>" title="<?php echo $popup->TITLE; ?>" alt="<?php echo $popup->TITLE; ?>" target="<?php echo $popup->TARGET; ?>">
						más información
					</a>
				<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>