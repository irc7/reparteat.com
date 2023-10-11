<?php require_once("template/modules/supplier/header_supplier_product.php"); ?>
<div class='separator5 bgYellow'>&nbsp;</div>
<!-- Start article section -->
<div class="container-fluid">
	<section id="ree-supplier-product">
		<div class="container">
			<div class="row">
				<div class="supplier-area-list">
				<?php if($supView->STATUS != 0) { ?>
					<?php require("template/modules/cart/cart.time.php"); ?>
					<?php require("template/modules/supplier/supplier.info.tpl.php"); ?>
					
					<div class="separator20">&nbsp;</div>
					<div class="list-product-supplier">
						<div id="list-product-supplier-left" class="col-sm-8 col-xs-12 no-padding">
							<div class="list-supplier">
							<h3>Nuestro menú</h3>
							<div class="separator20">&nbsp;</div>
							<div class="filter-category bgGreen">
								<div class="form-group">
									<div class="col-xs-12">
										<h3 class="white">¿Qué quieres pedir?</h3>
										<h6 class="white">Encuentra lo que buscas más facilmente, filtrando tus productos seleccionando las categorias que deseas.</h6>
									</div>
									<div class="separator1 bgYellow"></div>
									<div class="separator10"></div>
									<div class="col-xs-12">
										<div class="col-md-6 col-sm-6 col-xs-12">
											<div class="col-sm-1 col-xs-2 no-padding">
												<input type="checkbox" class="form-control" id="filter-cat-all" name="filter-cat-all" value="all" checked="checked" />
											</div>
											<div class="col-sm-11 col-xs-10">
												<label for="filter-cat-all" class="white">Todos los productos</label>
											</div>
										</div>
										<div class="separator10">&nbsp;</div>
										<?php /*
										<select class="form-control" name="filter-cat" id="filter-cat">
											<option value="all">Todas las categorías</option>
										<?php foreach($catFilter as $filter){ ?>
											<option value="<?php echo formatNameUrl($filter->TITLE); ?>"><?php echo $filter->TITLE; ?></option>
										<?php }	?>
										</select>
										<?php 
									*/
											$cont = 0;
											foreach($catFilter as $filter){ 
										?>
											<div class="col-md-3 col-sm-6 col-xs-12">
												<div class="col-sm-1 col-xs-2 no-padding">
													<input type="checkbox" class="form-control filter-cat-checkbox" name="filter-cat-<?php echo $cont; ?>" value="<?php echo formatNameUrl($filter->TITLE); ?>" />
												</div>
												<div class="col-sm-11 col-xs-10">
													<label for="filter-cat-<?php echo $cont; ?>" class="white"><?php echo $filter->TITLE; ?></label>
												</div>
											</div>
										<?php 
												$cont++;
											}	
										?>
									</div>
									<div class="separator10">&nbsp;</div>
									<div class="col-xs-12">
										<button id="btn-filter-cat" class="btn btn-primary arial floatRight bgYellow">Filtrar</button>
									</div>
								</div>
							</div>
							<div id="start-list-product" class="separator20">&nbsp;</div>
						<?php 
							foreach($products as $pro) {
								$catPro = $proObj->infoCategories($pro->ID);
								$classCat = "";
								foreach($catPro as $row){
									$classCat .= formatNameUrl($row->TITLE)." ";									
								}
								$imgDest = $proObj->productImageFav($pro->ID);
								
								$icons = $proObj->productIcon($pro->ID);
								$coms = $proObj->productComs($pro->ID);
						?>
							<div class="product-item all <?php echo $classCat; ?>">
								<a href="<?php echo DOMAIN.SLUGSUP."/".$supView->SLUG."/".$pro->SLUG; ?>" alt="<?php echo $pro->TITLE; ?>" title="<?php echo $pro->TITLE; ?>">
								<?php if($supView->VIEW_IMG == 1){ ?>
									<div class="product-thumb col-sm-3 col-xs-12 no-padding">
									<?php if(!$imgDest) { ?>
										<img class="img-responsive" src="<?php echo DOMAIN; ?>files/product/thumb/1-default.jpg" alt="<?php echo $pro->TITLE; ?>" title="<?php echo $pro->TITLE; ?>" />
									<?php }else{ ?>
										<img class="img-responsive" src="<?php echo DOMAIN; ?>files/product/thumb/1-<?php echo $imgDest->URL; ?>" title="<?php echo $imgDest->TITLE; ?>" alt="<?php echo $imgDest->TITLE; ?>" />
									<?php } ?>
									</div>
									<div class="product-info col-md-8 col-sm-7 col-xs-12">
								<?php }else{ ?>
									<div class="product-info col-md-10 col-sm-10 col-xs-10">
								<?php } ?>
										<div class="product-title">
											<h4 class="arial green"><?php echo $pro->TITLE; ?></h4>
										</div>
										<?php 
											$comBasic = array();
											$comOpt = array();
											for($i=0;$i<count($coms);$i++) { 
												if($coms[$i]->TYPE == "basic") {
													$comBasic[] = $coms[$i];
												}else if($coms[$i]->TYPE == "optional") {
													$comOpt[] = $coms[$i];
												}
											}
											if(trim($pro->SUMARY) != "") { ?>
												<div class="product-sumary">
													<h5 class="textBox grayNormal"><?php echo $pro->SUMARY; ?></h5>
												</div>
										<?php }else{ 
												if(count($comBasic)>0) {
										?>
													<div class="product-coms">
														<ul class="list-product-coms">
														<?php 
															for($i=0;$i<count($comBasic);$i++) { 
																if($comBasic[$i]->TYPE == "basic") {
																	echo "<li>".$comBasic[$i]->TITLE;
																	if($i < count($comBasic)-1) {
																		echo " • ";
																	}
																	echo "</li>";
																}
															}
														?>
														</ul>
													</div>
										<?php 	}
											} 
											if(count($comOpt)>0) { ?>
											<div class="product-coms">
												<ul class="list-product-coms">
													<li><strong>Extras: </strong></li>
													<?php
														for($i=0;$i<count($comOpt);$i++) { 
															echo "<li><em>";
															echo $comOpt[$i]->TITLE;
															if($i < count($comOpt)-1) {
																echo " <span class='green'>•</span> ";
															}
															echo "</em></li>";
														}	
													?>
													<li><strong class="green arial">Editar</strong></li>
												</ul>
											</div>
										<?php } ?>
										<div class="separator1"></div>
										<div class="product-icon">
											<ul class="list-product-icon">
											<?php foreach($icons as $icon) { ?>
												<li><img class="img-responsive" src="<?php echo DOMAIN; ?>files/product/icon/1-<?php echo $icon->ICON; ?>" title="<?php echo $icon->TITLE; ?>" /></li>
											<?php } ?>
											</ul>
										</div>
									</div>
								</a>
							<?php if($supView->VIEW_IMG == 1){ ?>
								<div class="product-info col-md-1 col-sm-2 col-xs-12 no-padding">
							<?php }else{ ?>
								<div class="product-info col-md-2 col-sm-2 col-xs-2 no-padding">
							<?php } ?>
									<div class="product-cost grayStrong textBoxBold textRight">
										<?php echo $pro->COST; ?> &euro;
									</div>
							<?php	if((!isset($_SESSION[nameSessionZP]) && $supView->STATUS == 1 && $timeSup["status"] == 1) || (isset($_SESSION[nameSessionZP]) && $_SESSION[nameSessionZP]->IDTYPE == 4 && $supView->STATUS == 1 && $timeSup["status"] == 1)) { ?>
									<div class="box-add-cart">
									<form method="post" id="form-add-to-cart">
										<input type="hidden" name="idProduct" id="idProduct-<?php echo $pro->ID; ?>" value="<?php echo $pro->ID; ?>" />
										<input type="hidden" name="idSupplier" id="idSupplier-<?php echo $pro->ID; ?>" value="<?php echo $supView->ID; ?>" />
										<input type="hidden" class="form-control" name="totalPro" id="totalPro-<?php echo $pro->ID; ?>" value="1" />
										<input type="hidden" class="form-control" name="inTime" id="inTime-<?php echo $proView->ID; ?>" value="<?php if($timeSup["status"] == 1){echo "1";}else{echo "0";} ?>" />
										<button type="button" id="add-to-cart-<?php echo $pro->ID; ?>" class="btn add-to-cart-list transition bgGreen yellow floatRight">
											<i class="fa fa-plus"></i>
										</button>
									</form>
									</div>
							<?php 	} ?>
								</div>
								<div class="separator5"></div>
								<div class="separator1 bgGrayLight"></div>
								
								<div class="separator15">&nbsp;</div>
							</div>
						<?php } ?>
							</div>
						</div>
						<div id="list-product-supplier-right" class="filter-supplier col-sm-4 col-xs-12 bgGrayLight">
							<div class="info-supplier-order">
								<h4 class="arial green">Realiza tu pedido</h4>
								<div class="text-info">
									<h5>Algunos de nuestros platos pueden tener ingredientes adicionales a su elección pinche sobre ellos para agregarlos de manera más personalizada.</h5>
								</div>
								<div class="cost-supplier">
									<!--<i class="fa fa-money" aria-hidden="true"></i>-->
									<img class="img-responsive icon-list-supplier" src="<?php echo DOMAIN; ?>template/images/icon-money.png" />
									<span class="textBoxBold grayNormal">
										Gastos de envío: <?php echo $supView->COST; ?> &euro;
									</span>
								</div>
								<div class="separator15">&nbsp;</div>
								<div class="min-supplier">
									<!--<i class="fa fa-motorcycle" aria-hidden="true"></i>-->
									<img class="img-responsive icon-list-supplier" src="<?php echo DOMAIN; ?>template/images/icon-flaw.png" />
									<span class="textBoxBold grayNormal">
										Pedido mínimo: <?php echo $supView->MIN; ?> &euro;
									</span>
								</div>
								<div class="separator15">&nbsp;</div>
								<div class="min-supplier">
									<!--<i class="fa fa-clock-o" aria-hidden="true"></i>-->
									<img class="img-responsive icon-list-supplier" src="<?php echo DOMAIN; ?>template/images/icon-supplier.png" />
									<span class="textBoxBold grayNormal">
										Tiempo de entrega estimado: <?php echo $supView->TIME + timeRe; ?> min
									</span>
								</div>
								<div class="separator20 no-pc"></div>
							</div>
							<div class="open-wrap-cart-mobile bgWhite textRight">
							<?php if(isset($_SESSION[nameCartReparteat][$id])  && count($_SESSION[nameCartReparteat][$id]["data"]) > 0){ ?>
								<h4 class="arial green open-cart">Ver resumen de la compra <i class="fa fa-shopping-basket icon-view-cart"></i></h4>
							<?php }else{ ?>
								<h4 class="arial green">Ningún producto en la cesta <i class="fa fa-shopping-basket icon-view-cart"></i></h4>
							<?php } ?>	
							</div>
							<div id="wrap-cart">
						<?php if(isset($_SESSION[nameCartReparteat][$id])  && count($_SESSION[nameCartReparteat][$id]["data"]) > 0){ ?>
							<?php require("template/modules/cart/cart.tpl.php"); ?>
						<?php }else{ ?>
						<?php } ?>	
							</div>
							<div class="separator20 no-mobile"></div>
							<div class="suplier-product-time-pc">
								<?php include("template/modules/cart/cart.time.tpl.php"); ?>	
							</div>
						</div>
						
					</div>
				<?php }else{ ?>
					<div class="page-not-found">
						<h3 class="danger arial" ><i class="fa fa-exclamation-triangle"></i> Restaurante no disponible en estos momentos.</h3>
						<div class="separator50">&nbsp;</div>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
	</section>
</div>