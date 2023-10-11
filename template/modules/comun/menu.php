	<nav id="mainNav" class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#reparteat-navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
                <a href="<?php echo DOMAIN; ?>" title="Inicio">
					<div class="logo-navbar"></div>
				</a>
            </div>
			<?php if(($view == "product" && $section > 0) || ($view == "supplier" && $id > 0)) { 
					if($view == "product") {
						$idCart = $section;
					}else if ($view == "supplier"){
						$idCart = $id;
					}else if ($view == "order"){
						$idCart = $_GET["supplier"];
					}
					$cartSupObj = new Supplier();
					$supplierCart = $cartSupObj->infoSupplierById($idCart);
					$timeSup = $cartSupObj->checkingOpen($supplierCart->ID,intval($_SESSION[sha1("zone")])); 
			?>
					 <div class="collapse navbar-collapse in" id="header-icon-cart">
						<ul class="nav navbar-nav navbar-right">
							<li id="mnu-cart">
								<div class="loading-cart">
									<img class="img-responsive" src="<?php echo DOMAIN; ?>template/images/loading.gif" />
								</div>
								<div id="btn-cart-open" class="sumary-cart-mnu pointer">
									<i class="fa fa-shopping-cart"></i>
									<span class="total-product-cart">
										<?php $totalProductCart = 0;
											if(isset($_SESSION[nameCartReparteat][$idCart]) && count($_SESSION[nameCartReparteat][$idCart]) > 0){
												foreach($_SESSION[nameCartReparteat][$idCart]["data"] as $itemCart) {
													$totalProductCart = $totalProductCart + $itemCart["ud"];
												} 
											}
											echo $totalProductCart;
										?>
									</span>
								</div>
								
							</li>
						</ul>
						
					</div>
			<?php } ?>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="reparteat-navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
					<li>
                        <a href="tel:+34681949316">Atención al cliente: <i class="fa fa-phone icon-phone"></i> 681 949 316</a>
                    </li>
					<li>
                        <a class="arial" href="<?php echo DOMAIN; ?>">Inicio</a>
                    </li>
				<?php /*	
                    <li>
                        <a class="arial" href="<?php echo DOMAIN.SLUGSUP; ?>">Restaurantes</a>
                    </li>
				*/ ?>	
				<?php if(isset($_SESSION[nameSessionZP]) && $_SESSION[nameSessionZP]->ID > 0) { ?>
					<li>
                        <a class="arial" href="<?php echo DOMAINZP; ?>"><i class="fa fa-user icon-header-profile"></i> Perfil</a>
                    </li>
				<?php }else { ?>	
                    <li>
                        <a class="arial" href="<?php echo DOMAINZP; ?>iniciar-sesion">Iniciar sesión</a>
                    </li>
                    <li>
                        <a class="arial" href="<?php echo DOMAINZP; ?>crear-cuenta">Registrarse</a>
                    </li>
				<?php } ?>
				
                </ul>
            </div>
           
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
	<div id="header-sumary-cart" class="header-sumary-cart bgWhite shadow close-cart">
		<i id="btn-cart-close" class="fa fa-close grayStrong pointer floatRight"></i>
		<div id="wrap-header-cart">
			<?php if(isset($_SESSION[nameCartReparteat][$id])  && count($_SESSION[nameCartReparteat][$id]["data"]) > 0){ 
					require("template/modules/cart/cart.tpl.php");
				}else{ 
			?>
					<h5 class="textCenter textBox green">
						<i class="fa fa-info-circle iconBig"></i>
						<div class="separator10"></div>
						No tiene ningún producto en este carrito
					</h5>
			<?php } ?>
		</div>
	</div>

