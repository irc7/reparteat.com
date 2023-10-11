<section id="column-left-supplier">
	<div class="col-sm-2 col-xs-12">
		<div class="box-logo-supplier">
			<img class="img-responsive" src="<?php echo DOMAIN; ?>files/supplier/logo/<?php echo $supView->LOGO; ?>" />
		</div>
	</div>
	<div class="col-sm-1 col-xs-12"></div>
	<div class="col-sm-9 col-xs-12">
	<?php if(trim(strip_tags($supView->TEXT)) != "" && trim(strip_tags($supView->TEXT)) != "&nbsp;") { ?>
		<div class="more-info-supplier">
			<div class="description-supplier textBox">
				<?php echo $supView->TEXT; ?>
			</div>
			<div class="separator10">&nbsp;</div>
		</div>
		<div class="separator20">&nbsp;</div>
	<?php } ?>
		<ul class="list-contact-supplier-product">
		<?php if($address) { ?>
			<li>
				<div class="col-xs-1 no-padding">
					<i class="fa fa-map-marker green"></i>
				</div>
				<div class="col-xs-11">
					<span class="data-contact-sup"><?php echo $address->STREET; ?> - <?php echo $address->CP; ?> - <?php echo $address->CITY; ?> - <?php echo $address->PROVINCE; ?></span>
				</div>
				<div class="separator1"></div>
			</li>
		<?php } ?>
		<?php if($supView->PHONE != "") { ?>
			<li>
				<div class="col-xs-1 no-padding">
					<i class="fa fa-phone-square green"></i>
				</div>
				<div class="col-xs-11">
					<span class="data-contact-sup"><?php echo $supView->PHONE; ?></span>
				</div>
				<div class="separator1"></div>
			</li>
		<?php } ?>
		<?php if($supView->MOVIL != "") { ?>
			<li>
				<div class="col-xs-1 no-padding">
					<i class="fa fa-phone-square green"></i>
				</div>
				<div class="col-xs-11">
					<span class="data-contact-sup"><?php echo $supView->MOVIL; ?></span>
				</div>
				<div class="separator1"></div>
			</li>
		<?php } ?>
		</ul>
		<div class="col-sm-10 col-xs-12">	
			<div class="suplier-product-time-mobile">
				<div class="separator20"></div>
				<?php include("template/modules/cart/cart.time.tpl.php"); ?>	
			</div>
		</div>
	</div>
</section>
<div class="separator15"></div>
<div class="separator1 bgYellow"></div>
