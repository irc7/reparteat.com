<section id="column-left-supplier">
	<div class="col-sm-3 col-xs-12 no-padding">
		<div class="box-logo-supplier">
			<img class="img-responsive" src="<?php echo DOMAIN; ?>files/supplier/logo/<?php echo $supView->LOGO; ?>" />
		</div>
	</div>
	<div class="col-sm-9 col-xs-12">
		<div class="more-info-supplier">
			<div class="description-supplier textBox">
				<?php echo $supView->TEXT; ?>
			</div>
			
			<div class="separator10">&nbsp;</div>
		</div>
		<div class="row">
			<div class="col-sm-4 col-xs-12">
				<h3>Datos de contacto</h3>
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
			</div>
			<div class="col-sm-4 col-xs-12">
				<h3>Tipos de cocina</h3>
				<ul>	
					<?php 
						for($i=0;$i<count($catSup);$i++) { 
							echo "<li>".$catSup[$i]->TITLE."</li>";
						} 
					?>
				</ul>
			</div>
			<div class="col-sm-4 col-xs-12">
				<h3>Horario de reparto</h3>
				<ul>
					<?php foreach($timeControl as $time) { ?>
					<li>
						<?php 
							echo $days[$time->DAY - 1] . " de " . $time->START_H.":";
							if(strlen($time->START_M) == 1) {echo "0";}
							echo $time->START_M;
							echo " a " . $time->FINISH_H.":";
							if(strlen($time->FINISH_M) == 1) {echo "0";}
							echo $time->FINISH_M;
						?>
					</li>
					<?php } ?>
				</ul>
				<div class="suplier-product-time-mobile">
					<div class="separator20"></div>
					<?php include("template/modules/cart/cart.time.tpl.php"); ?>	
				</div>
			</div>
		</div>
	</div>
</section>
<div class="separator15"></div>
<div class="separator1 bgYellow"></div>
