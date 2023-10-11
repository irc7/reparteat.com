<?php require_once("template/modules/order/header_order.php"); ?>
<div class='separator5 bgYellow'>&nbsp;</div>
<!-- Start article section -->
<div class="container-fluid">
	<section id="ree-order">
		<div class="container">
			<div class="row">
				<form id="form-order" method="post" action="<?php echo DOMAIN; ?>template/modules/order/step1.php">
					<div class="supplier-area">
						<input type="hidden" name="idSupplier" id="idSupplier" value="<?php echo $idCart; ?>" />
						<div class="list-supplier col-sm-9 col-xs-12">
							<div class="step-order">
								<h4 class="title-step-order">1. Direcciones de envio para <em><?php echo $zoneInfo->CP . " - ". $zoneInfo->CITY; ?></em></h4>
								<div class="content-step">
									<div class="col-xs-7 no-padding">
										<h4 class="arial">Mis direcciones</h4>
									</div>
									<div class="col-xs-5 no-padding">
										<a href="<?php echo DOMAINZP; ?>?view=user&mod=user&tpl=address" title="Crear nueva dirección">
											<button type="button" class="btn transition bgGreen yellow floatRight">NUEVA DIRECCIÓN</button>
										</a>
									</div>
									<div class="separator5"></div>
									<div class="separator1 bgGrayNormal"></div>
									<div class="separator10"></div>
									<div id="wrap-my-directions">
									<?php 
										if(count($address)>0) {		
											foreach($address as $ind => $row) { 
												$zone = $zoneObj->infoZone($row->IDZONE);
												?>
											<div id="address-order-<?php echo $row->ID; ?>" class="transition address-order<?php if($row->FAV == 1){echo " bgGrayLight";} ?>">
												<div class="col-xs-2">
													<input type="radio" name="address" id="address-<?php echo $row->ID; ?>" value="<?php echo $row->ID; ?>" <?php if($row->FAV == 1){echo " checked";} ?> />
												</div>
												<div class="col-xs-10">
													<label for="address-<?php echo $row->ID; ?>">
														<div class="textBoxBold grayStrong">
															<?php echo $row->STREET; ?>
														</div>
														<div class="textBox grayStrong">
															<?php echo $zone->CITY . "<br />" . $zone->CP . " - " . $zone->PROVINCE; ?>
														</div>
													</label>
												</div>
												<div class="separator10"></div>
											</div>
									<?php 	} 											
										}else{
											?>
											<div class="row">
												<div class="col-md-12 col-xs-12">
													<h5 class="orange"><i class="fa fa-exclamation-triangle orange"></i>&nbsp;<em>No tiene ninguna dirección en la zona de reparto que ha seleccionado.</em></h5>
												</div>
											</div>
									<?php
										}
										?>
									</div>
									<div class="separator10"></div>
									<h4 id="title-points-deliver" class="arial transition no-checked"><input type="checkbox" class="" id="points-deliver" name="points-deliver" /> Seleccionar punto de recogida REPARTEAT</h4>
									<div id="wrap-points-deliver">
										<div class="separator5"></div>
										<div class="separator1 bgGrayNormal"></div>
										<div class="separator10"></div>
									<?php 
										if(count($points)>0) {		
											foreach($points as $ind => $row) { 
												$zone = $zoneObj->infoZone($row->IDZONE);
												$imagePoint = $row->IMAGE;
												if($imagePoint == "") {
													$imagePoint = "default.png";
												}
												?>
											<div id="address-order-<?php echo $row->ID; ?>" class="col-md-4 col-sm-6 col-xs-12 transition address-order">
												<div class="col-xs-2">
													<input type="radio" name="address" id="address-<?php echo $row->ID; ?>" value="<?php echo $row->ID; ?>" />
												</div>
												<div class="col-xs-10 textCenter">
													<label for="address-<?php echo $row->ID; ?>">
														<div class="textBox grayStrong textCenter">
															<img class="img-responsive icon-points" src="<?php echo DOMAIN; ?>files/points/icon/<?php echo $imagePoint; ?>" />
														</div>
														<div class="separator5"></div>
														<div class="textBoxBold grayStrong textCenter title-points">
															<?php echo $row->STREET; ?>
														</div>
													</label>
												</div>
											</div>
									<?php 	} 											
										}else{
											?>
											<div class="row">
												<div class="col-md-12 col-xs-12">
													<h5 class="orange"><i class="fa fa-exclamation-triangle orange"></i>&nbsp;<em>No existen puntos de recogida para esta zona de reparto.</em></h5>
												</div>
											</div>
									<?php
										}
										?>
									</div>
								</div>
							</div>
							<div class="step-order">
								<h4 class="title-step-order">2. Métodos de pago</h4>
								<div class="content-step">
								<?php foreach($methodPay as $method) { ?>
										
										<div id="methodpay-order-<?php echo $row->ID; ?>" class="methodpay-order bgGrayLight">
											<div class="col-xs-2">
												<input type="radio" name="methodpay" id="methodpay-<?php echo $method->ID; ?>" value="<?php echo $method->ID; ?>"<?php if($method->ID == 1) {echo " checked";} ?> />
											</div>
											<div class="col-xs-10">
												<label for="methodpay-1">
													<div class="textBoxBold grayStrong">
													<?php if($method->ID == 1) { ?>
														<h4 class="title-method-pay">
													<?php	echo "Paga en tu domicilio - " . $method->TITLE; ?>
														</h4>
														<ul class="list-method-pay">
															<li class="textBoxItalic grayNormal"><i class="fa fa-dot-circle-o green"></i> Pago en efectivo o con tarjeta de crédito/débito</li>
															<li class="textBoxItalic grayNormal"><i class="fa fa-dot-circle-o green"></i> Paga en efectivo o con tarjeta de crédito/débito mediante TPV móvil al recibir tu pedido.</li>
														</ul>
													<?php } else if($method->ID == 2) { ?>
															<h4 class="title-method-pay">
													<?php		echo "Paga ahora con tu tarjeta - " . $method->TITLE; ?>
															</h4>
															<ul class="list-method-pay">
																<li class="textBoxItalic grayNormal"><i class="fa fa-dot-circle-o green"></i> Plataforma RedSys BBVA</li>
																<li class="textBoxItalic grayNormal"><i class="fa fa-dot-circle-o green"></i> Paga con tu tarjeta de crédito/débito al efectuar su pedido directamente desde la web.</li>
															</ul>
														<?php } else if($method->ID == 3) { ?>
															<h4 class="title-method-pay">
														<?php	echo "Paga ahora con ";	?>
																<img src="<?php echo DOMAIN; ?>template/images/bizum.png" />
															</h4>
															<ul class="list-method-pay">
																<li class="textBoxItalic grayNormal"><i class="fa fa-dot-circle-o green"></i> Paga a través de bizum al efectuar su pedido directamente desde la web.</li>
															</ul>
														<?php } ?>
													</div>
												</label>
											</div>
											<div class="separator1"></div>
										</div>
										<div class="separator1 bgWhite"></div>
								<?php } ?>
								</div>
							</div>
							<div class="step-order">
							</div>
							<div class="step-order">
								<h4 class="title-step-order">Revisar productos</h4>
						<?php
								
								$subTotalOrder = 0;
								foreach($_SESSION[nameCartReparteat][$idCart]["data"] as $cont => $item) {
									
									$product = $cartProObj->infoProductByIdNoStatus($item["id"]);
									$imgDest = $cartProObj->productImageFav($item["id"]);
									$icons = $cartProObj->productIcon($item["id"]);
									
									$comps = $item["comp"]; 
									
									$subTotalOrder = $subTotalOrder + $item["cost"];
						?>
									<div id="item-order-<?php echo $cont; ?>" class="order-item">
										<div class="col-xs-3 order-item-img">
											<img class="img-responsive" src="<?php echo DOMAIN; ?>files/product/thumb/2-<?php echo $imgDest->URL; ?>" title="<?php echo $imgDest->TITLE; ?>" alt="<?php echo $imgDest->TITLE; ?>" />
										</div>
										<div class="col-xs-2 order-item-ud">
											<h4 class="textBoxBold grayStrong"><?php echo $item["ud"]; ?></h4>
										</div>
										<div class="col-xs-8 no-padding">
											<div class="order-item-name arial">
												<h4 class="textBoxBold grayStrong">
												<?php echo $item['title'];
													if($item['comp'] != "") {
														echo " <em>+ ".$item['comp']."</em>";
													}
												?>
												</h4>
											</div>
											<div class="order-item-sumary">
												<h5 class="textBox grayNormal">
													<?php echo $product->SUMARY; ?>
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
										<div class="col-xs-2 no-padding order-item-cost textCenter">
											<h4 class="textBoxBold grayStrong"><?php echo $item["cost"] . " €"; ?></h4>
										</div>
										
									</div>
									<div class="separator10"></div>
					<?php		} ?>
								
							</div>
							<div class="separator15"></div>
					
							<div class="mnu-btn-order no-mobile">
								<div class="col-xs-6">
									<div class="action-order textLeft">
										<a href="<?php echo DOMAIN.SLUGSUP."/".$supplierCart->SLUG; ?>" title="Volver a <?php echo $supplierCart->TITLE; ?>">
											<button type="button" class="btn transition bgGreen yellow">
												<i class="fa fa-arrow-circle-left" aria-hidden="true"></i> volver
											</button>
										</a>
									</div>
								</div>
								<div class="col-xs-6">
									<div class="action-order textRight">
										<button id="btn-action-order-<?php echo $idCart; ?>" class='btn btn-action-order transition bgGreen yellow<?php if($timeSup["status"] == 1 && $subTotalOrder >= $supplierCart->MIN && count($address)>0){echo " active-confirm' type='submit";}else{echo "' type='button";}?>'>
											Confirmar pedido
										</button>
									</div>
									<div class="separator15"></div>
									<div class="textBoxItalic textRight bgWhite <?php echo $classTimeSup; ?>">
										<i class="fa fa-<?php echo $iconTimeSup . " ".$classTimeSup; ?>" aria-hidden="true"></i>
										<span class="textBoxItalic <?php echo $classTimeSup; ?>">
											<?php echo $textTime; ?>
										</span>
									</div>
								</div>
							</div>
							<div class="separator10"></div>
						</div>  
						<div class="filter-supplier col-sm-3 col-xs-12 bgGrayLight">
							<?php require("template/modules/order/column.left.php"); ?>
						</div>
					</div>      
				</form>
			</div>
		</div>
	</section>
</div>

