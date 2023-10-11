<section id="column-left-supplier">
	<div class="separator15"></div>
	<div id="box-list-cart">
		<h4 class="arial green">Dirección de envio</h4>
		<div class="separator5"></div>
		<div class="separator1 bgYellow"></div>
		<div class="separator5"></div>
		<div class="col-xs-12 grayStrong textBoxBold no-padding">
			<h5 class="textBox grayStrong">
				<?php echo $address->STREET ."<br/>". $address->CITY ."<br/>".$address->CP."-".$address->PROVINCE; ?>
			</h5>
		</div>
	</div>
	<div class="separator20"></div>
	<div id="box-order-comment">
		<div class="col-xs-12 no-padding">
			<h4 class="arial green">Observaciones</h4>
			<div class="separator5"></div>
			<div class="separator1 bgYellow"></div>
			<div class="separator5"></div>
		</div>
		<div class="col-xs-12 grayStrong textBoxBold no-padding">
			<h5 class="textBox grayStrong"><?php echo $order->COMMENT; ?></h5>
		</div>
	</div>
	<div class="separator20"></div>
	<div id="box-order-comment">
		<div class="col-xs-12 no-padding">
			<h4 class="arial green">Método de pago</h4>
			<div class="separator5"></div>
			<div class="separator1 bgYellow"></div>
			<div class="separator5"></div>
		</div>
		<div class="col-xs-12 grayStrong textBoxBold no-padding">
			<h5 class="textBox grayStrong"><?php echo $methodPay->TITLE; ?></h5>
		</div>
	</div>
	<div class="separator20"></div>
	<div id="box-order-time">
		<div class="col-xs-2 no-padding">
			<i class="fa fa-info-circle green icon-big"></i>
		</div>
		<div class="col-xs-10">
			<h5 class="grayStrong textBoxBold">
				Recibirá su pedido,
			</h5>
			<h5 class="grayStrong textBoxBoldItalic">
				<?php echo $ordObj->franjaInfo($order); ?>
			</h5>
		</div>
	</div>
</section>

