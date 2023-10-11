<?php require_once("template/modules/order/header_order.php"); ?>
<div class='separator5 bgYellow'>&nbsp;</div>
<!-- Start article section -->
<div class="container-fluid">
	<section id="ree-supplier">
		<div class="container">
			<div class="row">
				<div class="supplier-area">
					
						<div class="list-supplier col-sm-9 col-xs-12">
							<div class="alert-confirm-order bgGrayLight">
							<?php if($order->STATUS >=2 && $order->STATUS <=6) { ?>
								<h5 class="textBox greyStrong textCenter">
									<i class="fa fa-info-circle green"></i>
									<br/>
									<div class="btn bgWhite green">
										Ref.: <?php echo $order->REF; ?>
									</div>
									<div class="separator20"></div>
									Su pedido esta pendiente de confirmación por parte de la cocina de <?php echo $supplierCart->TITLE; ?>, se le enviará una alerta a su correo electrónico <em><?php echo $_SESSON[nameSessionZP]->LOGIN; ?></em> cuando sea aceptado.
									<div class="separator20"></div>
									<?php if($order->IDMETHODPAY == 2) { ?>
										<p class="bgGrayStrong white" style="padding:15px;">Si su pedido es cancelado, se le generará un saldo a su favor, por el importe total del pedido, a su disposición para cualquier pedido que quiera realizar en el futuro. Si por el contrario quiere la devolución del importe, diríjase a la página del pedido en el apartado de su perfil o pinche en "VER PEDIDO" y solicite la devolución en la parte inferior de la página.</p>
										<div class="separator20"></div>
									<?php } ?>
									El tiempo de entrega de su pedido es aproximado y podrá variar en función de la demanda, tanto en el restaurante como del repartidor y se irá actualizando en función de los mismos.
									<div class="separator20"></div>
									<b>Puede comprobar el estado de su pedido en el area de clientes en pedidos pendientes o pinchando en el siguiente botón.</b>
									<div class="separator20"></div>
									<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&ref=<?php echo $order->REF; ?>" class="btn bgGreen yellow">
										Ver pedido
									</a>
									<div class="separator20"></div>
									<span class="textBoxItalic">Ante cualquier incidencia en su pedido contacte con el establecimiento <b><?php echo $supplierCart->TITLE; ?></b> a través del teléfono <a style="display:inline;" href="tel:<?php echo $supplierCart->PHONE; ?>" title="Llamar a <?php echo $supplierCart->PHONE; ?>"><?php echo $supplierCart->PHONE; ?></a>.</span>
								</h5>
							<?php }else{ ?>
								<h5 class="textBox greyStrong textCenter">
									<i class="fa fa-exclamation-triangle orange"></i>
									<br/>
									<div class="btn bgWhite orange">
										Ref.: <?php echo $order->REF; ?>
									</div>
									<div class="separator20"></div>
									Ha ocurrido un error durante la gestión o pago del pedido.
									<div class="separator20"></div>
									Para más información acceda a su perfil de usuario y consulte el estado de su pedido
									<div class="separator20"></div>
									<a href="<?php echo DOMAINZP; ?>?view=order&mod=order&ref=<?php echo $order->REF; ?>" class="btn bgGreen yellow">
										Ver pedido
									</a>
									<div class="separator20"></div>
									<span class="textBoxItalic">Ante cualquier incidencia en su pedido contacte con el establecimiento <b><?php echo $supplierCart->TITLE; ?></b> a través del teléfono <a style="display:inline;" href="tel:<?php echo $supplierCart->PHONE; ?>" title="Llamar a <?php echo $supplierCart->PHONE; ?>"><?php echo $supplierCart->PHONE; ?></a>.</span>
								</h5>
							<?php } ?>
							</div>
							<div class="step-order">
								<h4 class="title-step-order">Resumen del pedido</h4>
						<?php
								
								$subTotalOrder = 0;
								foreach($products as $item) {
									
									$product = $proObj->infoProductByIdNoStatus($item->IDPRODUCT);
									$icons = $proObj->productIcon($item->IDPRODUCT);
									$comps = $item->IDCOM;
									$compsArray = explode("#-#", $comps);
								
									
									$subTotalOrder = $subTotalOrder + $item->COST;
					?>
									<div id="item-order-<?php echo $cont; ?>" class="order-item">
										<div class="col-xs-2 order-item-ud">
											<h4 class="textBoxBold grayStrong"><?php echo $item->UDS; ?></h4>
										</div>
										<div class="col-xs-7 no-padding">
											<div class="order-item-name arial">
												<h5 class="textBoxBold grayStrong">
												<?php echo $product->TITLE;
													for($i=0;$i<count($compsArray);$i++) {
														if($compsArray[$i]>0) {
															$com = $proObj->productComsByIdCom($compsArray[$i]);
															echo " <em>+ ".$com->TITLE."</em>";
														}
													}
												?>
												</h5>
											</div>
											<div class="order-item-icon">
												<ul class="list-product-icon">
												<?php foreach($icons as $icon) { ?>
													<li><img class="img-responsive" src="<?php echo DOMAIN; ?>files/product/icon/1-<?php echo $icon->ICON; ?>" title="<?php echo $icon->TITLE; ?>" /></li>
												<?php } ?>
												</ul>
											</div>
										</div>
										<div class="col-xs-3 no-padding order-item-cost textRight">
											<h4 class="textBoxBold grayStrong"><?php echo $item->COST . " €"; ?></h4>
										</div>
										
									</div>
									<div class="separator10"></div>
					<?php		} ?>
								
								<div class="separator5"></div>
								<div class="separator1 bgGrayStrong"></div>
								<div class="separator5"></div>
								<div class="cart-order-total">
									<div class="col-xs-4 no-padding">
										<h5 class="grayStrong textBox">Subtotal</h5>
									</div>
									<div class="col-xs-8 textRight grayStrong textBox no-padding">
										<?php echo $order->SUBTOTAL . " €"; ?>
									</div>
									<div class="col-xs-2 no-padding"></div>
								<div class="separator5"></div>
									<div class="col-xs-4 no-padding">
										<h5 class="grayStrong textBox">Gastos de envio</h5>
									</div>
									<div class="col-xs-8 textRight grayStrong textBox no-padding">
										<?php echo $order->SHIPPING . " €"; ?>
									</div>
								<?php if($order->DISCOUNT > 0) { ?>
								<div class="separator5"></div>
									<div class="col-xs-4 no-padding">
										<h5 class="grayStrong textBox">Descuento</h5>
									</div>
									<div class="col-xs-8 textRight grayStrong textBox no-padding">
										- <?php echo $order->DISCOUNT . " €"; ?>
									</div>
								<?php } ?>
								<div class="separator5"></div>
								<div class="separator1 bgGrayStrong"></div>
								<div class="separator5"></div>
									<div class="col-xs-4 no-padding">
										<h4 class="grayStrong textBoxBold">TOTAL</h4>
									</div>
									<div class="col-xs-8 textRight grayStrong textBoxBold no-padding">
										<h4 class="grayStrong textBoxBold">
										<?php 
											echo $order->COST . " €"; 
										?>
										</h4>
									</div>
								</div>
							</div>
							<div class="col-xs-12 no-padding">
								<div class="action-order textLeft">
									<a href="<?php echo DOMAIN.SLUGSUP."/".$supplierCart->SLUG; ?>" title="Volver a <?php echo $supplierCart->TITLE; ?>">
										<button id="btn-action-order-<?php echo $idCart; ?>" type="button" class="btn btn-action-order transition bgGreen yellow active-confirm">
											<i class="fa fa-arrow-circle-left" aria-hidden="true"></i> volver
										</button>
									</a>
								</div>
							</div>
							<div class="separator10"></div>
						</div>  
						<div class="filter-supplier col-sm-3 col-xs-12 bgGrayLight">
							<?php require("template/modules/order/column.confirm.left.php"); ?>
						</div>
					
				</div>      
			</div>
		</div>
	</section>
</div>

